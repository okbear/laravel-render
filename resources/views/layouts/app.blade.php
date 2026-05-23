<!DOCTYPE html>
<html lang="ja" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ブログ') — MyBlog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-full">

{{-- ナビゲーション --}}
<header class="bg-white border-b border-gray-100 sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
        <a href="{{ route('posts.index') }}" wire:navigate class="flex items-center gap-2 font-bold text-gray-900 text-lg hover:text-indigo-600 transition-colors">
            <span class="text-2xl">✍️</span>
            MyBlog
        </a>
        <a href="{{ route('posts.create') }}" wire:navigate
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            新規投稿
        </a>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    @yield('content')
</main>

@livewireScripts
</body>
</html>
