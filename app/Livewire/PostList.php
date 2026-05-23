<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $status = '';

    public ?int $deletingId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Post::findOrFail($this->deletingId)->delete();
            $this->deletingId = null;
            session()->flash('success', '投稿を削除しました。');
        }
    }

    public function render()
    {
        $posts = Post::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%")
                ->orWhere('body', 'like', "%{$this->search}%"))
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->when($this->status === 'published', fn($q) => $q->where('published', true))
            ->when($this->status === 'draft', fn($q) => $q->where('published', false))
            ->latest()
            ->paginate(9);

        $categories = Post::select('category')->distinct()->pluck('category');

        return view('livewire.post-list', compact('posts', 'categories'));
    }
}
