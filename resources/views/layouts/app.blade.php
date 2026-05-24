<!DOCTYPE html>
<html lang="ja" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ブログ') — MyBlog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- EasyMDE: Markdown エディタ --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

        /* EasyMDE のスタイル調整 */
        .EasyMDEContainer .CodeMirror {
            border-radius: 0 0 0.75rem 0.75rem;
            border-color: #e5e7eb;
            font-size: 0.9rem;
            font-family: 'Menlo', 'Monaco', 'Courier New', monospace;
        }
        .EasyMDEContainer .editor-toolbar {
            border-radius: 0.75rem 0.75rem 0 0;
            border-color: #e5e7eb;
            background: #f9fafb;
        }
        .EasyMDEContainer .editor-toolbar button:hover,
        .EasyMDEContainer .editor-toolbar button.active {
            background: #e0e7ff;
            border-color: #c7d2fe;
        }

        /* Markdown レンダリング用 prose スタイル */
        .prose h1 { font-size: 1.75rem; font-weight: 700; margin: 1.5rem 0 0.75rem; }
        .prose h2 { font-size: 1.4rem; font-weight: 700; margin: 1.25rem 0 0.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.25rem; }
        .prose h3 { font-size: 1.15rem; font-weight: 600; margin: 1rem 0 0.5rem; }
        .prose p { margin: 0.75rem 0; line-height: 1.8; }
        .prose ul { list-style: disc; padding-left: 1.5rem; margin: 0.75rem 0; }
        .prose ol { list-style: decimal; padding-left: 1.5rem; margin: 0.75rem 0; }
        .prose li { margin: 0.25rem 0; line-height: 1.7; }
        .prose blockquote { border-left: 4px solid #6366f1; padding-left: 1rem; color: #6b7280; margin: 1rem 0; font-style: italic; }
        .prose code { background: #f3f4f6; padding: 0.15rem 0.4rem; border-radius: 0.25rem; font-size: 0.85em; font-family: 'Menlo', monospace; color: #4f46e5; }
        .prose pre { background: #1e1e2e; color: #cdd6f4; padding: 1.25rem; border-radius: 0.75rem; overflow-x: auto; margin: 1rem 0; }
        .prose pre code { background: none; color: inherit; padding: 0; font-size: 0.875rem; }
        .prose a { color: #4f46e5; text-decoration: underline; }
        .prose a:hover { color: #3730a3; }
        .prose table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        .prose th { background: #f3f4f6; padding: 0.5rem 0.75rem; text-align: left; font-weight: 600; border: 1px solid #e5e7eb; }
        .prose td { padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; }
        .prose tr:nth-child(even) td { background: #f9fafb; }
        .prose hr { border-color: #e5e7eb; margin: 1.5rem 0; }
        .prose img { max-width: 100%; border-radius: 0.5rem; margin: 1rem 0; }
        .prose strong { font-weight: 700; }
        .prose em { font-style: italic; }
        .prose del { text-decoration: line-through; color: #9ca3af; }
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
        <div class="flex items-center gap-3">
            @if (\App\Support\AdminAccess::allows())
                <a href="{{ route('posts.create') }}" wire:navigate
                   class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    新規投稿
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition">
                        ログアウト
                    </button>
                </form>
            @else
                <a href="{{ route('login.google') }}"
                   class="text-sm text-gray-500 hover:text-indigo-600 transition">
                    管理ログイン
                </a>
            @endif
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    @yield('content')
</main>

@livewireScripts
</body>
</html>
