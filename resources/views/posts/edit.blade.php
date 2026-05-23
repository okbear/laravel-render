@extends('layouts.app')

@section('title', '投稿を編集')

@section('content')
<div class="max-w-2xl mx-auto">
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('posts.index') }}" wire:navigate class="hover:text-indigo-600 transition-colors">一覧</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('posts.show', $post) }}" wire:navigate class="hover:text-indigo-600 transition-colors truncate max-w-xs">{{ $post->title }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600">編集</span>
    </nav>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 sm:p-10">
        <h1 class="text-xl font-bold text-gray-900 mb-8">投稿を編集</h1>
        <livewire:post-form :post="$post" />
    </div>
</div>
@endsection
