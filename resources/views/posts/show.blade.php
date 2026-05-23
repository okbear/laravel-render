@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- フラッシュメッセージ --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- パンくず --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('posts.index') }}" wire:navigate class="hover:text-indigo-600 transition-colors">一覧</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 truncate max-w-xs">{{ $post->title }}</span>
    </nav>

    {{-- 記事本体 --}}
    <article class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8 sm:p-10">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full">{{ $post->category }}</span>
                @if ($post->published)
                    <span class="text-xs font-medium bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">公開</span>
                @else
                    <span class="text-xs font-medium bg-amber-50 text-amber-600 px-3 py-1 rounded-full">下書き</span>
                @endif
                <span class="text-xs text-gray-400 ml-auto">{{ $post->created_at->format('Y年m月d日 H:i') }}</span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-8 leading-tight">{{ $post->title }}</h1>

            <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed whitespace-pre-wrap text-base">{{ $post->body }}</div>
        </div>

        {{-- アクションバー --}}
        <div class="px-8 sm:px-10 py-5 bg-gray-50 border-t border-gray-100 flex items-center gap-3">
            <a href="{{ route('posts.edit', $post) }}" wire:navigate
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                編集
            </a>
            <form action="{{ route('posts.destroy', $post) }}" method="POST"
                  x-data
                  @submit.prevent="if(confirm('本当に削除しますか？')) $el.submit()">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-2 border border-red-200 text-red-500 hover:bg-red-50 text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    削除
                </button>
            </form>
        </div>
    </article>
</div>
@endsection
