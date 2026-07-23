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

                    {{-- ── Duplicate warning banner ── --}}
                    @if ($duplicateWarning)
                        <div class="alert alert-warning" role="alert" aria-live="assertive" style="margin-bottom:20px;">
                            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            <div class="alert-body">
                                <strong>Possible duplicate detected.</strong><br>
                                This file looks very similar to <em>{{ e($duplicateWarning) }}</em>.
                                <div style="margin-top:10px; display:flex; gap:8px;">
                                    <button
                                        wire:click="confirmDuplicate"
                                        class="btn btn-warning"
                                        style="height:32px; padding:0 14px; font-size:13px;"
                                        aria-label="Upload anyway"
                                    >Upload anyway</button>
                                    <button
                                        wire:click="$set('file', null)"
                                        class="btn btn-outline"
                                        style="height:32px; padding:0 14px; font-size:13px;"
                                        aria-label="Choose different file"
                                    >Choose different file</button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- ── Success banner ── --}}
                    @if ($uploaded)
                        <div class="alert alert-success" role="alert" aria-live="polite">
                            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            <div class="alert-body">
                                File uploaded successfully.
                                <a href="{{ route('admin.gifs.index') }}">View all →</a>
                            </div>
                        </div>
                    @endif

                    <form wire:submit="save" enctype="multipart/form-data" novalidate>

                        {{-- ── Title field ── --}}
                        <div class="form-group">
                            <label for="title" class="form-label">
                                Title <span class="required">*</span>
                            </label>
                            <input
                                id="title"
                                type="text"
                                wire:model="title"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Enter a descriptive title (any language)"
                                maxlength="255"
                                required
                                autocomplete="off"
                            >
                            @error('title')
                                <p class="form-error" role="alert">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline;vertical-align:-1px;margin-right:4px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- ── Dropzone ── --}}
                        <div class="form-group">
                            <label class="form-label">
                                File <span class="required">*</span>
                                <span class="form-hint" style="font-weight:400"> — GIF or MP4, max 10 MB</span>
                            </label>

                            <div
                                x-data="{
                                    dragging: false,
                                    triggerPicker() { this.$refs.fileInput.click(); },
                                    handleDrop(e) {
                                        this.dragging = false;
                                        const file = e.dataTransfer.files[0];
                                        if (!file) return;
                                        // Feed dropped file into Livewire's file input
                                        const dt = new DataTransfer();
                                        dt.items.add(file);
                                        this.$refs.fileInput.files = dt.files;
                                        this.$refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                                    }
                                }"
                                @dragover.prevent="dragging = true"
                                @dragleave.prevent="dragging = false"
                                @drop.prevent="handleDrop($event)"
                                @click="triggerPicker()"
                                :class="dragging ? 'dropzone--active' : ''"
                                class="dropzone"
                                role="button"
                                tabindex="0"
                                @keydown.enter.prevent="triggerPicker()"
                                @keydown.space.prevent="triggerPicker()"
                                aria-label="Click or drag a GIF / MP4 file here"
                            >
                                {{-- Hidden real input wired to Livewire --}}
                                <input
                                    x-ref="fileInput"
                                    id="file-input"
                                    type="file"
                                    wire:model="file"
                                    accept="image/gif,video/mp4"
                                    class="sr-only"
                                    aria-hidden="true"
                                    tabindex="-1"
                                >

                                <div class="dropzone__body" wire:loading.remove wire:target="file">
                                    <div class="dropzone__icon" aria-hidden="true">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                            <polyline points="17 8 12 3 7 8"/>
                                            <line x1="12" y1="3" x2="12" y2="15"/>
                                        </svg>
                                    </div>
                                    <p class="dropzone__label">
                                        <strong>Click to browse</strong> or drag &amp; drop
                                    </p>
                                    <p class="dropzone__hint">GIF · MP4 · max 10 MB</p>
                                </div>

                                {{-- Upload progress indicator --}}
                                <div class="dropzone__loading" wire:loading wire:target="file" aria-live="polite">
                                    <div class="loading-bar" role="progressbar" aria-label="Uploading…"></div>
                                    <p class="dropzone__hint" style="margin-top:8px">Uploading…</p>
                                </div>

                            </div>

                            @error('file')
                                <p class="form-error" role="alert">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline;vertical-align:-1px;margin-right:4px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- ── Preview ── --}}
                        @if ($file && !$errors->has('file'))
                            <div class="form-group" aria-live="polite">
                                <p class="form-label">Preview</p>
                                @php
                                    $origName = $file->getClientOriginalName();
                                    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                                @endphp

                                <div style="display:inline-block; border-radius:var(--radius); overflow:hidden; border:1px solid var(--border-color); background:var(--card-bg);">
                                    @if ($ext === 'mp4')
                                        <video
                                            src="{{ $file->temporaryUrl() }}"
                                            controls muted playsinline
                                            style="max-width:320px; max-height:240px; display:block;"
                                            aria-label="MP4 preview"
                                        ></video>
                                    @else
                                        <img
                                            src="{{ $file->temporaryUrl() }}"
                                            alt="GIF preview"
                                            style="max-width:320px; max-height:240px; display:block;"
                                        >
                                    @endif
                                </div>
                                <p class="form-hint" style="margin-top:6px;">
                                    {{ $origName }} — {{ round($file->getSize() / 1024, 1) }} KB
                                </p>
                            </div>
                        @endif

                        {{-- ── Submit row ── --}}
                        <div class="form-actions right" style="border-top:none; margin-top:8px; padding-top:0;">
                            <button
                                type="submit"
                                class="btn btn-brand"
                                wire:loading.attr="disabled"
                                wire:target="save"
                            >
                                <span wire:loading.remove wire:target="save">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline;vertical-align:-1px;margin-right:6px"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    Upload
                                </span>
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

{{-- ── Upload form styles ── --}}
<style>
/* Dropzone */
.dropzone {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 160px;
    border: 2px dashed var(--border-color, rgba(255,255,255,.12));
    border-radius: var(--radius, 10px);
    background: var(--card-bg-alt, rgba(255,255,255,.03));
    cursor: pointer;
    transition: border-color .2s, background .2s;
    user-select: none;
    padding: 24px;
    text-align: center;
}
.dropzone:hover,
.dropzone:focus-visible,
.dropzone--active {
    border-color: var(--primary, #7c3aed);
    background: rgba(124,58,237,.06);
    outline: none;
}
.dropzone__icon {
    color: var(--text-muted, #94a3b8);
    margin-bottom: 10px;
}
.dropzone__label {
    font-size: .875rem;
    color: var(--text-secondary, #cbd5e1);
    margin: 0 0 4px;
}
.dropzone__hint {
    font-size: .75rem;
    color: var(--text-muted, #64748b);
    margin: 0;
}
.dropzone__loading {
    text-align: center;
}

/* Brand button — indigo→violet gradient matching Landing palette */
.btn-brand {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 22px;
    border: none;
    border-radius: var(--radius, 8px);
    background: linear-gradient(135deg, #6366f1, #7c3aed);
    color: #fff;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(99,102,241,.35);
    transition: box-shadow .2s, opacity .2s, transform .15s;
}
.btn-brand:hover {
    box-shadow: 0 6px 20px rgba(99,102,241,.50);
    transform: translateY(-1px);
}
.btn-brand:active {
    transform: translateY(0);
}
.btn-brand:disabled {
    opacity: .6;
    cursor: not-allowed;
    transform: none;
}
</style>
