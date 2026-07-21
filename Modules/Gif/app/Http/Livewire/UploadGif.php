<?php

namespace Modules\Gif\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Gif\Models\Gif;

#[Layout('admin::layouts.admin')]
#[Title('Upload GIF')]
class UploadGif extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
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

    public function updatedFile(): void
    {
        $this->validateOnly('file');
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

        // --- Determine extension and mime from actual content ---
        $ext      = $isGif ? 'gif' : 'mp4';
        $mimeType = $isGif ? 'image/gif' : 'video/mp4';

        // --- Store with UUID filename ---
        $filename = Str::uuid()->toString() . '.' . $ext;
        $path     = $this->file->storeAs('gifs', $filename, 'public');

        // --- Persist to database ---
        Gif::create([
            'title'             => trim($this->title),
            'file_path'         => $path,
            'file_size'         => $this->file->getSize(),
            'mime_type'         => $mimeType,
            'original_filename' => $this->file->getClientOriginalName(),
            'uploaded_by'       => auth()->id(),
        ]);

        $this->reset('title', 'file');
        $this->uploaded = true;
    }

    public function render()
    {
        return view('gif::livewire.upload-gif');
    }
}
