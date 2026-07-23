{{-- GIF Library index — Gentelella admin panel, Livewire 4 --}}
<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <p class="page-pretitle">GIF Manager</p>
                <h1 class="page-title">GIF Library</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.gifs.upload') }}" class="btn btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Upload GIF
                </a>
            </div>
        </div>
    </div>

    {{-- ── Empty state ── --}}
    @if ($gifs->isEmpty())
        <div class="card">
            <div class="card-body empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <p class="empty-state-title">No GIFs yet</p>
                <p class="empty-state-desc">Upload your first GIF to get started.</p>
                <div class="empty-state-actions">
                    <a href="{{ route('admin.gifs.upload') }}" class="btn btn-primary">Upload GIF</a>
                </div>
            </div>
        </div>

    @else
        {{-- ── GIF grid ── --}}
        <div class="row">
            <div class="col-1">
                <div class="media-grid">
                    @foreach ($gifs as $gif)
                        <div
                            class="media-tile"
                            wire:key="gif-{{ $gif->id }}"
                        >
                            {{-- Thumbnail: GIF → img, MP4 → video --}}
                            @if ($gif->mime_type === 'video/mp4')
                                <video
                                    src="{{ $gif->url }}"
                                    muted
                                    autoplay
                                    loop
                                    playsinline
                                    style="width:100%; height:100%; object-fit:cover; display:block;"
                                    aria-label="{{ e($gif->title) }}"
                                ></video>
                            @else
                                <img
                                    src="{{ $gif->url }}"
                                    alt="{{ e($gif->title) }}"
                                    loading="lazy"
                                    style="width:100%; height:100%; object-fit:cover; display:block;"
                                >
                            @endif

                            {{-- Meta overlay --}}
                            <div class="meta">
                                <span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:120px;">
                                    {{ $gif->title }}
                                </span>
                                <span>{{ $gif->formatted_size }}</span>
                            </div>

                            {{-- AI status badge --}}
                            @if ($gif->isPendingReview())
                                <div
                                    style="position:absolute; top:6px; left:6px; background:rgba(251,191,36,0.9); color:#000; border-radius:4px; font-size:10px; font-weight:600; padding:2px 6px; display:flex; align-items:center; gap:3px;"
                                    title="AI analysis in progress"
                                >
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Analysing…
                                </div>
                            @elseif ($gif->isFlagged())
                                <div
                                    style="position:absolute; top:6px; left:6px; background:rgba(239,68,68,0.9); color:#fff; border-radius:4px; font-size:10px; font-weight:600; padding:2px 6px; display:flex; align-items:center; gap:3px;"
                                    title="Flagged by AI — pending manual review"
                                >
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    Flagged
                                </div>
                            @elseif ($gif->aiMetadata?->suggested_title)
                                {{-- AI suggestion available — show action button --}}
                                <button
                                    wire:click="showAiSuggestion({{ $gif->id }})"
                                    style="position:absolute; top:6px; left:6px; background:rgba(99,102,241,0.9); color:#fff; border-radius:4px; font-size:10px; font-weight:600; padding:2px 6px; border:none; cursor:pointer; display:flex; align-items:center; gap:3px;"
                                    title="View AI suggestion"
                                    aria-label="View AI suggestion for {{ e($gif->title) }}"
                                >
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 2a5 5 0 015 5c0 5.25-5 10-5 10S7 12.25 7 7a5 5 0 015-5z"/><circle cx="12" cy="7" r="2"/></svg>
                                    AI
                                </button>
                            @endif

                            {{-- AI suggestion panel (shown when aiSuggestionId matches) --}}
                            @if ($aiSuggestionId === $gif->id && $gif->aiMetadata?->suggested_title)
                                <div
                                    style="position:absolute; inset:0; background:rgba(10,12,28,0.93); display:flex; flex-direction:column; gap:8px; padding:12px; overflow:auto;"
                                    role="dialog"
                                    aria-modal="true"
                                    aria-label="AI suggestion for {{ e($gif->title) }}"
                                >
                                    <p style="color:#a5b4fc; font-size:10px; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:.5px;">AI Suggestion</p>
                                    <p style="color:#fff; font-size:12px; margin:0;">{{ e($gif->aiMetadata->suggested_title) }}</p>
                                    @if ($gif->aiMetadata->suggested_tags)
                                        <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                            @foreach ($gif->aiMetadata->suggested_tags as $tag)
                                                <span style="background:rgba(99,102,241,0.3); color:#c7d2fe; border-radius:3px; font-size:10px; padding:1px 5px;">#{{ e($tag) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if ($gif->aiMetadata->description)
                                        <p style="color:#94a3b8; font-size:11px; margin:0; line-height:1.4;">{{ e($gif->aiMetadata->description) }}</p>
                                    @endif
                                    <div style="display:flex; gap:6px; margin-top:4px;">
                                        <button
                                            wire:click="applyAiTitle({{ $gif->id }})"
                                            class="btn btn-primary"
                                            style="height:26px; padding:0 10px; font-size:11px;"
                                            aria-label="Apply AI title"
                                        >Apply title</button>
                                        <button
                                            wire:click="showAiSuggestion({{ $gif->id }})"
                                            class="btn btn-outline"
                                            style="height:26px; padding:0 10px; font-size:11px;"
                                            aria-label="Close AI suggestion"
                                        >Close</button>
                                    </div>
                                </div>
                            @endif

                            {{-- Delete: confirm overlay --}}
                            @if ($confirmDeleteId === $gif->id)
                                <div
                                    style="position:absolute; inset:0; background:rgba(15,23,42,0.85); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; padding:12px;"
                                    role="dialog"
                                    aria-modal="true"
                                    aria-label="Confirm delete {{ e($gif->title) }}"
                                >
                                    <p style="color:#fff; font-size:12px; text-align:center; margin:0;">
                                        Delete this GIF?<br>
                                        <span style="opacity:.7;">This cannot be undone.</span>
                                    </p>
                                    <div style="display:flex; gap:6px;">
                                        <button
                                            wire:click="delete({{ $gif->id }})"
                                            wire:loading.attr="disabled"
                                            class="btn btn-danger"
                                            style="height:28px; padding:0 10px; font-size:12px;"
                                            aria-label="Confirm delete"
                                        >Delete</button>
                                        <button
                                            wire:click="cancelDelete"
                                            class="btn btn-outline"
                                            style="height:28px; padding:0 10px; font-size:12px;"
                                            aria-label="Cancel delete"
                                        >Cancel</button>
                                    </div>
                                </div>
                            @else
                                {{-- Delete trigger button --}}
                                <button
                                    wire:click="confirmDelete({{ $gif->id }})"
                                    class="fm-item-menu"
                                    style="opacity:1; position:absolute; top:6px; right:6px; background:rgba(15,23,42,0.6); border-radius:50%; border:none; cursor:pointer; width:28px; height:28px; display:flex; align-items:center; justify-content:center;"
                                    aria-label="Delete {{ e($gif->title) }}"
                                    title="Delete"
                                >
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                </button>
                            @endif

                            {{-- Upload date (bottom-left) --}}
                            <div style="position:absolute; bottom:4px; left:8px; font-size:10px; color:rgba(255,255,255,0.55);">
                                {{ $gif->created_at->format('M j, Y') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Pagination ── --}}
        @if ($gifs->hasPages())
            <div style="margin-top:16px;">
                {{ $gifs->links() }}
            </div>
        @endif
    @endif
</div>
