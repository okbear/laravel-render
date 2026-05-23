<div>
    <form wire:submit="save" class="space-y-6">

        {{-- タイトル --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="title">
                タイトル <span class="text-red-400">*</span>
            </label>
            <input wire:model="title"
                   type="text"
                   id="title"
                   placeholder="投稿のタイトルを入力..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('title') border-red-400 bg-red-50 @enderror">
            @error('title')
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- カテゴリ --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="category">
                カテゴリ <span class="text-red-400">*</span>
            </label>
            <input wire:model="category"
                   type="text"
                   id="category"
                   placeholder="例: テクノロジー、ライフスタイル、日記"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('category') border-red-400 bg-red-50 @enderror">
            @error('category')
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- 本文 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="body">
                本文 <span class="text-red-400">*</span>
            </label>
            <textarea wire:model="body"
                      id="body"
                      rows="12"
                      placeholder="投稿の内容を入力..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition resize-none @error('body') border-red-400 bg-red-50 @enderror"></textarea>
            @error('body')
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- 公開設定 --}}
        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
            <button type="button" wire:click="$toggle('published')"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 {{ $published ? 'bg-indigo-600' : 'bg-gray-300' }}">
                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $published ? 'translate-x-6' : 'translate-x-1' }}"></span>
            </button>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ $published ? '公開する' : '下書きとして保存' }}</p>
                <p class="text-xs text-gray-400">{{ $published ? '投稿が一覧に表示されます' : '後から公開できます' }}</p>
            </div>
        </div>

        {{-- 送信ボタン --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-sm font-medium hover:bg-indigo-700 transition flex items-center gap-2 disabled:opacity-50">
                <span wire:loading wire:target="save">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </span>
                {{ isset($post) && $post?->exists ? '更新する' : '投稿する' }}
            </button>
            <a href="{{ isset($post) && $post?->exists ? route('posts.show', $post) : route('posts.index') }}"
               wire:navigate
               class="text-sm text-gray-500 hover:text-gray-700 transition">キャンセル</a>
        </div>
    </form>
</div>
