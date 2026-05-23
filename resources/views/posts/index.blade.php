@extends('layouts.app')

@section('title', '投稿一覧')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">投稿一覧</h1>
        <p class="text-sm text-gray-500 mt-1">すべての投稿を管理できます</p>
    </div>
</div>

<livewire:post-list />
@endsection
