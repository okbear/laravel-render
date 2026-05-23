<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PostForm extends Component
{
    public ?Post $post = null;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string')]
    public string $body = '';

    #[Validate('required|string|max:100')]
    public string $category = '';

    public bool $published = false;

    public function mount(?Post $post = null): void
    {
        if ($post && $post->exists) {
            $this->post      = $post;
            $this->title     = $post->title;
            $this->body      = $post->body;
            $this->category  = $post->category;
            $this->published = $post->published;
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'     => $this->title,
            'body'      => $this->body,
            'category'  => $this->category,
            'published' => $this->published,
        ];

        if ($this->post && $this->post->exists) {
            $this->post->update($data);
            session()->flash('success', '投稿を更新しました。');
            $this->redirect(route('posts.show', $this->post), navigate: true);
        } else {
            $post = Post::create($data);
            session()->flash('success', '投稿を作成しました。');
            $this->redirect(route('posts.show', $post), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.post-form');
    }
}
