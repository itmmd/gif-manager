<?php

namespace Modules\Ai\Http\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Gif\Models\Gif;

/**
 * Admin page for reviewing GIFs flagged by AI content moderation.
 *
 * Shows all GIFs with status = 'flagged' or 'pending_review'.
 * Admin can:
 *   - Approve: set status to 'approved' (GIF becomes publicly visible)
 *   - Reject:  delete the GIF from disk and database
 */
#[Layout('admin::layouts.admin')]
#[Title('Content Moderation')]
class ModerationQueue extends Component
{
    use WithPagination;

    /** Filter: 'flagged' | 'pending_review' | 'all' */
    public string $filter = 'flagged';

    /** ID pending confirmation for rejection (delete) */
    public ?int $confirmRejectId = null;

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
        $this->confirmRejectId = null;
    }

    public function approve(int $id): void
    {
        $gif = Gif::find($id);
        if (! $gif) {
            return;
        }

        $gif->update(['status' => 'approved']);

        // Update AI metadata moderation status to match
        \DB::table('gif_ai_metadata')
            ->where('gif_id', $id)
            ->update(['moderation_status' => 'approved']);
    }

    public function confirmReject(int $id): void
    {
        $this->confirmRejectId = $id;
    }

    public function cancelReject(): void
    {
        $this->confirmRejectId = null;
    }

    public function reject(int $id): void
    {
        $gif = Gif::find($id);
        if (! $gif) {
            $this->confirmRejectId = null;
            return;
        }

        Storage::disk('public')->delete($gif->file_path);
        $gif->delete();

        $this->confirmRejectId = null;

        if ($this->page > 1 && $this->buildQuery()->count() === 0) {
            $this->previousPage();
        }
    }

    public function render()
    {
        return view('ai::livewire.moderation-queue', [
            'gifs'         => $this->buildQuery()->with(['uploader', 'aiMetadata'])->latest()->paginate(12),
            'flaggedCount' => Gif::flagged()->count(),
            'pendingCount' => Gif::pendingReview()->count(),
        ]);
    }

    private function buildQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return match ($this->filter) {
            'pending_review' => Gif::pendingReview(),
            'all'            => Gif::whereIn('status', ['flagged', 'pending_review']),
            default          => Gif::flagged(),
        };
    }
}
