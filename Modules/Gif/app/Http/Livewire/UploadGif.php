<?php

namespace Modules\Gif\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Gif\Events\GifUploaded;
use Modules\Gif\Models\Gif;

#[Layout('admin::layouts.admin')]
#[Title('Upload GIF')]
class UploadGif extends Component
{
    use WithFileUploads;

    #[Validate('required|string|min:2|max:255')]
    public string $title = '';

    /**
     * Accepted formats: GIF and MP4.
     *
     * mimes:gif,mp4   — PHP finfo detects real MIME type from magic bytes,
     *                   not just the file extension.
     * max:10240       — 10 MB in KB (Livewire unit).
     *
     * Magic-byte double-check in save() adds a second layer of security:
     *   GIF: starts with GIF87a or GIF89a
     *   MP4: starts with ftyp box (bytes 4-8 = "ftyp") or common MP4 signatures
     */
    #[Validate([
        'file' => ['required', 'mimes:gif,mp4', 'max:10240'],
    ])]
    public $file = null;

    public bool $uploaded = false;

    /**
     * Duplicate warning: set by checkDuplicate() before finalising the upload.
     * Holds the title of the similar GIF found, or null when no duplicate exists.
     */
    public ?string $duplicateWarning = null;

    /**
     * When true the admin has explicitly acknowledged the duplicate warning
     * and wants to proceed anyway.
     */
    public bool $duplicateConfirmed = false;

    public function updatedFile(): void
    {
        $this->validateOnly('file');

        // Reset duplicate state whenever a new file is chosen.
        $this->duplicateWarning  = null;
        $this->duplicateConfirmed = false;
    }

    /** Admin clicked "Upload anyway" after seeing the duplicate warning. */
    public function confirmDuplicate(): void
    {
        $this->duplicateConfirmed = true;
    }

    public function save(): void
    {
        $this->validate();

        // --- Magic-byte verification ---
        $tmpPath  = $this->file->getRealPath();
        $handle   = fopen($tmpPath, 'rb');
        $header   = fread($handle, 12);
        fclose($handle);

        $isGif = str_starts_with($header, 'GIF87a') || str_starts_with($header, 'GIF89a');
        // MP4/MOV ftyp box: bytes 4–7 are "ftyp"
        $isMp4 = substr($header, 4, 4) === 'ftyp';

        if (! $isGif && ! $isMp4) {
            $this->addError('file', 'The file is not a valid GIF or MP4 (magic bytes mismatch).');
            return;
        }

        // --- Duplicate detection (requires Ai module) ---
        if (! $this->duplicateConfirmed) {
            $warning = $this->checkDuplicate($tmpPath);
            if ($warning !== null) {
                $this->duplicateWarning = $warning;
                return; // Pause upload — show warning to admin
            }
        }

        // --- Determine extension and mime from actual content ---
        $ext      = $isGif ? 'gif' : 'mp4';
        $mimeType = $isGif ? 'image/gif' : 'video/mp4';

        // --- Store with UUID filename ---
        $filename = Str::uuid()->toString() . '.' . $ext;
        $path     = $this->file->storeAs('gifs', $filename, 'public');

        // --- Persist to database ---
        $gif = Gif::create([
            'title'             => trim($this->title),
            'file_path'         => $path,
            'file_size'         => $this->file->getSize(),
            'mime_type'         => $mimeType,
            'original_filename' => $this->file->getClientOriginalName(),
            'uploaded_by'       => auth()->id(),
            'status'            => 'pending_review', // will be set to 'approved' by AI or admin
        ]);

        // --- Fire event — Ai module listens async (graceful if Ai is disabled) ---
        GifUploaded::dispatch($gif);

        $this->reset('title', 'file');
        $this->duplicateWarning   = null;
        $this->duplicateConfirmed = false;
        $this->uploaded = true;
    }

    /**
     * Check whether the uploaded file is visually similar to an existing GIF.
     *
     * Returns the matching GIF title when similarity ≥ 92 %, null otherwise.
     * If the Ai module is not bound (disabled / removed), returns null silently.
     */
    private function checkDuplicate(string $tmpPath): ?string
    {
        if (! app()->bound(\Modules\Core\Contracts\MediaIntelligenceInterface::class)) {
            return null;
        }

        try {
            /** @var \Modules\Core\Contracts\MediaIntelligenceInterface $intelligence */
            $intelligence = app(\Modules\Core\Contracts\MediaIntelligenceInterface::class);
            $queryEmbedding = $intelligence->generateEmbedding($tmpPath);

            if (empty($queryEmbedding)) {
                return null;
            }

            // Compare against all stored embeddings
            $metadata = \DB::table('gif_ai_metadata')
                ->whereNotNull('embedding')
                ->join('gifs', 'gifs.id', '=', 'gif_ai_metadata.gif_id')
                ->select('gifs.title', 'gif_ai_metadata.embedding')
                ->get();

            foreach ($metadata as $row) {
                $stored = json_decode($row->embedding, true);
                if (! is_array($stored) || empty($stored)) {
                    continue;
                }

                $similarity = $this->cosineSimilarity($queryEmbedding, $stored);

                if ($similarity >= (float) config('ai.duplicate_threshold', 0.92)) {
                    return $row->title;
                }
            }
        } catch (\Throwable) {
            // AI service down — allow upload to proceed without duplicate check.
        }

        return null;
    }

    /**
     * Cosine similarity between two float vectors (range: −1 to 1).
     *
     * @param  float[]  $a
     * @param  float[]  $b
     */
    private function cosineSimilarity(array $a, array $b): float
    {
        $dot  = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        $len = min(count($a), count($b));
        for ($i = 0; $i < $len; $i++) {
            $dot   += $a[$i] * $b[$i];
            $normA += $a[$i] ** 2;
            $normB += $b[$i] ** 2;
        }

        if ($normA === 0.0 || $normB === 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    public function render()
    {
        return view('gif::livewire.upload-gif');
    }
}
