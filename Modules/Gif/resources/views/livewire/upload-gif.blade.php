{{-- Upload GIF/MP4 — Gentelella admin panel, Livewire 4 --}}
<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <p class="page-pretitle">GIF Manager</p>
                <h1 class="page-title">Upload File</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.gifs.index') }}" class="btn btn-outline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Back to list
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-1">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">New GIF / MP4</h3>
                </div>
                <div class="card-body">

                    @if ($uploaded)
                        <div class="alert alert-success" role="alert" aria-live="polite">
                            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <div class="alert-body">File uploaded successfully. <a href="{{ route('admin.gifs.index') }}">View all →</a></div>
                        </div>
                    @endif

                    <form wire:submit="save" enctype="multipart/form-data" novalidate>

                        {{-- Title --}}
                        <div class="form-group">
                            <label for="title" class="form-label">
                                Title <span class="required">*</span>
                            </label>
                            <input
                                id="title"
                                type="text"
                                wire:model="title"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Enter a descriptive title"
                                maxlength="255"
                                required
                            >
                            @error('title')
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- File picker --}}
                        <div class="form-group">
                            <label for="file-input" class="form-label">
                                File <span class="required">*</span>
                                <span class="form-hint" style="font-weight:400">— GIF or MP4, max 10 MB</span>
                            </label>

                            <input
                                id="file-input"
                                type="file"
                                wire:model="file"
                                accept="image/gif,video/mp4"
                                class="form-control @error('file') is-invalid @enderror"
                                required
                            >

                            <div wire:loading wire:target="file" style="margin-top:6px;">
                                <div class="loading-bar" role="progressbar" aria-label="Uploading…"></div>
                            </div>

                            @error('file')
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Preview --}}
                        @if ($file && !$errors->has('file'))
                            <div class="form-group" aria-live="polite">
                                <p class="form-label">Preview</p>

                                @php
                                    $origName = $file->getClientOriginalName();
                                    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                                @endphp

                                @if ($ext === 'mp4')
                                    <video
                                        src="{{ $file->temporaryUrl() }}"
                                        controls
                                        muted
                                        playsinline
                                        style="max-width:320px; max-height:240px; border-radius:var(--radius); border:1px solid var(--border-color); display:block;"
                                        aria-label="MP4 preview"
                                    ></video>
                                @else
                                    <img
                                        src="{{ $file->temporaryUrl() }}"
                                        alt="GIF preview"
                                        style="max-width:320px; max-height:240px; border-radius:var(--radius); border:1px solid var(--border-color);"
                                    >
                                @endif

                                <p class="form-hint" style="margin-top:4px;">
                                    {{ $origName }}
                                    — {{ round($file->getSize() / 1024, 1) }} KB
                                </p>
                            </div>
                        @endif

                        {{-- Submit --}}
                        <div class="form-actions right" style="border-top:none; margin-top:8px; padding-top:0;">
                            <button
                                type="submit"
                                class="btn btn-primary"
                                wire:loading.attr="disabled"
                                wire:target="save"
                            >
                                <span wire:loading.remove wire:target="save">Upload</span>
                                <span wire:loading wire:target="save">
                                    <span class="btn-spinner" aria-hidden="true"></span>
                                    Uploading…
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
