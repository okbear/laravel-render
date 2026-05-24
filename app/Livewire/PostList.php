<?php

namespace App\Livewire;

use App\Models\Post;
use App\Support\AdminAccess;
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

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Post::query()
            ->when($this->search, fn ($q) => $q->where(function ($query): void {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('body', 'like', "%{$this->search}%");
            }))
            ->when($this->category, fn ($q) => $q->where('category', $this->category));

        if (AdminAccess::allows()) {
            if ($this->status === 'published') {
                $query->published();
            } elseif ($this->status === 'draft') {
                $query->where('published', false);
            }
        } else {
            $query->published();
        }

        $posts = $query->latest()->paginate(9);

        $categoriesQuery = Post::query()->select('category')->distinct();
        if (! AdminAccess::allows()) {
            $categoriesQuery->published();
        }
        $categories = $categoriesQuery->pluck('category');

        return view('livewire.post-list', compact('posts', 'categories'));
    }
}
