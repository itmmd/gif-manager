{{-- AI Content Moderation Queue — Gentelella admin panel, Livewire 4 --}}
<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <p class="page-pretitle">AI Module</p>
                <h1 class="page-title">Content Moderation</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.gifs.index') }}" class="btn btn-outline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    GIF Library
                </a>
            </div>
        </div>
    </div>

    {{-- ── Filter tabs ── --}}
    <div style="display:flex; gap:8px; margin-bottom:20px;">
        <button
            wire:click="setFilter('flagged')"
            class="btn {{ $filter === 'flagged' ? 'btn-danger' : 'btn-outline' }}"
            style="height:32px; padding:0 14px; font-size:13px; display:flex; align-items:center; gap:6px;"
            aria-pressed="{{ $filter === 'flagged' ? 'true' : 'false' }}"
        >
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Flagged
            @if ($flaggedCount > 0)
                <span style="background:#ef4444; color:#fff; border-radius:10px; font-size:10px; padding:1px 6px; font-weight:700;">{{ $flaggedCount }}</span>
            @endif
        </button>
        <button
            wire:click="setFilter('pending_review')"
            class="btn {{ $filter === 'pending_review' ? 'btn-warning' : 'btn-outline' }}"
            style="height:32px; padding:0 14px; font-size:13px; display:flex; align-items:center; gap:6px;"
            aria-pressed="{{ $filter === 'pending_review' ? 'true' : 'false' }}"
        >
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Pending
            @if ($pendingCount > 0)
                <span style="background:#f59e0b; color:#000; border-radius:10px; font-size:10px; padding:1px 6px; font-weight:700;">{{ $pendingCount }}</span>
            @endif
        </button>
        <button
            wire:click="setFilter('all')"
            class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-outline' }}"
            style="height:32px; padding:0 14px; font-size:13px;"
            aria-pressed="{{ $filter === 'all' ? 'true' : 'false' }}"
        >All</button>
    </div>

    {{-- ── Empty state ── --}}
    @if ($gifs->isEmpty())
        <div class="card">
            <div class="card-body empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <p class="empty-state-title">
                    @if ($filter === 'flagged') No flagged GIFs
                    @elseif ($filter === 'pending_review') No pending GIFs
                    @else Queue is empty
                    @endif
                </p>
                <p class="empty-state-desc">All content is moderated.</p>
            </div>
        </div>

    @else

        <div class="row">
            <div class="col-1">
                <div class="media-grid">
                    @foreach ($gifs as $gif)
                        <div class="media-tile" wire:key="mod-{{ $gif->id }}" style="position:relative;">

                            {{-- Thumbnail --}}
                            @if ($gif->mime_type === 'video/mp4')
                                <video src="{{ $gif->url }}" muted autoplay loop playsinline
                                       style="width:100%;height:100%;object-fit:cover;display:block;"
                                       aria-label="{{ e($gif->title) }}"></video>
                            @else
                                <img src="{{ $gif->url }}" alt="{{ e($gif->title) }}"
                                     loading="lazy"
                                     style="width:100%;height:100%;object-fit:cover;display:block;">
                            @endif

                            {{-- Status badge --}}
                            @if ($gif->isFlagged())
                                <div style="position:absolute;top:6px;left:6px;background:rgba(239,68,68,0.9);color:#fff;border-radius:4px;font-size:10px;font-weight:600;padding:2px 6px;">
                                    Flagged
                                </div>
                            @elseif ($gif->isPendingReview())
                                <div style="position:absolute;top:6px;left:6px;background:rgba(251,191,36,0.9);color:#000;border-radius:4px;font-size:10px;font-weight:600;padding:2px 6px;">
                                    Pending
                                </div>
                            @endif

                            {{-- AI reason (if flagged) --}}
                            @if ($gif->isFlagged() && $gif->aiMetadata?->moderation_reason)
                                <div style="position:absolute;bottom:28px;left:0;right:0;background:rgba(15,23,42,0.85);padding:4px 8px;font-size:10px;color:#fca5a5;line-height:1.3;">
                                    {{ e($gif->aiMetadata->moderation_reason) }}
                                </div>
                            @endif

                            {{-- Meta bar --}}
                            <div class="meta">
                                <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100px;">{{ $gif->title }}</span>
                                <span style="font-size:10px;color:rgba(255,255,255,0.45);">{{ $gif->uploader?->name ?? '—' }}</span>
                            </div>

                            {{-- Confirm reject overlay --}}
                            @if ($confirmRejectId === $gif->id)
                                <div style="position:absolute;inset:0;background:rgba(15,23,42,0.9);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;padding:12px;"
                                     role="dialog" aria-modal="true" aria-label="Confirm rejection">
                                    <p style="color:#fff;font-size:12px;text-align:center;margin:0;">
                                        Delete this GIF permanently?<br>
                                        <span style="opacity:.7;font-size:11px;">This cannot be undone.</span>
                                    </p>
                                    <div style="display:flex;gap:6px;">
                                        <button wire:click="reject({{ $gif->id }})"
                                                class="btn btn-danger"
                                                style="height:28px;padding:0 10px;font-size:12px;"
                                                aria-label="Confirm rejection">Delete</button>
                                        <button wire:click="cancelReject"
                                                class="btn btn-outline"
                                                style="height:28px;padding:0 10px;font-size:12px;"
                                                aria-label="Cancel">Cancel</button>
                                    </div>
                                </div>
                            @else
                                {{-- Action buttons --}}
                                <div style="position:absolute;top:6px;right:6px;display:flex;gap:4px;">
                                    {{-- Approve --}}
                                    <button
                                        wire:click="approve({{ $gif->id }})"
                                        style="width:28px;height:28px;border-radius:50%;border:none;cursor:pointer;background:rgba(16,185,129,0.85);display:flex;align-items:center;justify-content:center;"
                                        title="Approve — make public"
                                        aria-label="Approve {{ e($gif->title) }}"
                                    >
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                    </button>
                                    {{-- Reject / Delete --}}
                                    <button
                                        wire:click="confirmReject({{ $gif->id }})"
                                        style="width:28px;height:28px;border-radius:50%;border:none;cursor:pointer;background:rgba(239,68,68,0.85);display:flex;align-items:center;justify-content:center;"
                                        title="Reject and delete"
                                        aria-label="Reject {{ e($gif->title) }}"
                                    >
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>
                            @endif

                            {{-- Upload date --}}
                            <div style="position:absolute;bottom:4px;left:8px;font-size:10px;color:rgba(255,255,255,0.45);">
                                {{ $gif->created_at->format('M j, Y') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if ($gifs->hasPages())
            <div style="margin-top:16px;">
                {{ $gifs->links() }}
            </div>
        @endif
    @endif
</div>
