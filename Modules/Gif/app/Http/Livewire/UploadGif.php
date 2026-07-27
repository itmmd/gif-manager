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

    #[Validate([
        'file' => ['required', 'mimes:gif,mp4', 'max:10240'],
    ])]
    public $file = null;

    public bool $uploaded = false;

    public ?string $duplicateWarning = null;

    public bool $duplicateConfirmed = false;

    public function updatedFile(): void
    {
        $this->validateOnly('file');

        $this->duplicateWarning   = null;
        $this->duplicateConfirmed = false;
    }

    public function confirmDuplicate(): void
    {
        $this->duplicateConfirmed = true;
    }

    public function save(): void
    {
        $this->validate();

        $tmpPath = $this->file->getRealPath();
        $handle  = fopen($tmpPath, 'rb');
        $header  = fread($handle, 12);
        fclose($handle);

        $isGif = str_starts_with($header, 'GIF87a') || str_starts_with($header, 'GIF89a');
        $isMp4 = substr($header, 4, 4) === 'ftyp';

        if (! $isGif && ! $isMp4) {
            $this->addError('file', 'The file is not a valid GIF or MP4 (magic bytes mismatch).');
            return;
        }

        if (! $this->duplicateConfirmed) {
            $warning = $this->checkDuplicate($tmpPath);
            if ($warning !== null) {
                $this->duplicateWarning = $warning;
                return;
            }
        }

        $ext      = $isGif ? 'gif' : 'mp4';
        $mimeType = $isGif ? 'image/gif' : 'video/mp4';
        $filename = Str::uuid()->toString() . '.' . $ext;
        $path     = $this->file->storeAs('gifs', $filename, 'public');

        $gif = Gif::create([
            'title'             => trim($this->title),
            'file_path'         => $path,
            'file_size'         => $this->file->getSize(),
            'mime_type'         => $mimeType,
            'original_filename' => $this->file->getClientOriginalName(),
            'uploaded_by'       => auth()->id(),
            'status'            => 'pending_review',
        ]);

        GifUploaded::dispatch($gif);

        $this->reset('title', 'file');
        $this->duplicateWarning   = null;
        $this->duplicateConfirmed = false;
        $this->uploaded = true;
    }

    private function checkDuplicate(string $tmpPath): ?string
    {
        if (! app()->bound(\Modules\Core\Contracts\MediaIntelligenceInterface::class)) {
            return null;
        }

        try {
            $intelligence   = app(\Modules\Core\Contracts\MediaIntelligenceInterface::class);
            $queryEmbedding = $intelligence->generateEmbedding($tmpPath);

            if (empty($queryEmbedding)) {
                return null;
            }

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
            // AI service unavailable — allow upload to proceed.
        }

        return null;
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        $dot   = 0.0;
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
