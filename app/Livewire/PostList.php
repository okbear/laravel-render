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

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $posts = Post::query()
            ->published()
            ->when($this->search, fn($q) => $q->where(function ($query): void {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('body', 'like', "%{$this->search}%");
            }))
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->latest()
            ->paginate(9);

        $categories = Post::published()->select('category')->distinct()->pluck('category');

        return view('livewire.post-list', compact('posts', 'categories'));
    }
}
