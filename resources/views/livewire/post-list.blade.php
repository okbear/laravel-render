<div>
    {{-- フラッシュメッセージ --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- 検索・フィルターバー --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-8">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="タイトル・本文で検索..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition">
            </div>
            <select wire:model.live="category"
                    class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition bg-white">
                <option value="">すべてのカテゴリ</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
            <select wire:model.live="status"
                    class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition bg-white">
                <option value="">すべてのステータス</option>
                <option value="published">公開</option>
                <option value="draft">下書き</option>
            </select>
        </div>
    </div>

    {{-- 投稿グリッド --}}
    <div wire:loading.class="opacity-50 transition-opacity" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        @forelse ($posts as $post)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                {{-- カードヘッダー --}}
                <div class="p-5 flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full">
                            {{ $post->category }}
                        </span>
                        @if ($post->published)
                            <span class="text-xs font-medium bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full">公開</span>
                        @else
                            <span class="text-xs font-medium bg-amber-50 text-amber-600 px-2.5 py-1 rounded-full">下書き</span>
                        @endif
                    </div>
                    <h2 class="font-bold text-gray-900 mb-2 line-clamp-2 leading-snug">
                        <a href="{{ route('posts.show', $post) }}" wire:navigate class="hover:text-indigo-600 transition-colors">
                            {{ $post->title }}
                        </a>
                    </h2>
                    <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed">{{ $post->body }}</p>
                </div>
                {{-- カードフッター --}}
                <div class="px-5 py-3 border-t border-gray-50 flex items-center justify-between">
                    <span class="text-xs text-gray-400">{{ $post->created_at->format('Y/m/d') }}</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('posts.edit', $post) }}" wire:navigate
                           class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition-colors">編集</a>
                        <span class="text-gray-200">|</span>
                        <button wire:click="confirmDelete({{ $post->id }})"
                                class="text-xs text-red-400 hover:text-red-600 font-medium transition-colors">削除</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-20 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-medium">投稿が見つかりません</p>
                <p class="text-sm mt-1">検索条件を変えるか、新しい投稿を作成してください。</p>
            </div>
        @endforelse
    </div>

    {{-- ページネーション --}}
    <div>{{ $posts->links() }}</div>

    {{-- 削除確認モーダル --}}
    @if ($deletingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data x-init="$el.querySelector('[data-modal]').focus()">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="cancelDelete"></div>
            <div data-modal tabindex="-1"
                 class="relative bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm text-center"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">投稿を削除しますか？</h3>
                <p class="text-sm text-gray-500 mb-6">この操作は取り消せません。</p>
                <div class="flex gap-3">
                    <button wire:click="cancelDelete"
                            class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        キャンセル
                    </button>
                    <button wire:click="delete"
                            class="flex-1 px-4 py-2.5 bg-red-500 rounded-xl text-sm font-medium text-white hover:bg-red-600 transition">
                        削除する
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
