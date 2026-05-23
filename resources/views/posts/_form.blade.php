@php $isEdit = isset($post); @endphp

<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-1" for="title">タイトル</label>
    <input type="text" id="title" name="title"
           value="{{ old('title', $isEdit ? $post->title : '') }}"
           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('title') border-red-400 @enderror">
    @error('title')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-1" for="category">カテゴリ</label>
    <input type="text" id="category" name="category"
           value="{{ old('category', $isEdit ? $post->category : '') }}"
           placeholder="例: テクノロジー、ライフスタイル"
           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('category') border-red-400 @enderror">
    @error('category')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-1" for="body">本文</label>
    <textarea id="body" name="body" rows="10"
              class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('body') border-red-400 @enderror">{{ old('body', $isEdit ? $post->body : '') }}</textarea>
    @error('body')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-6 flex items-center gap-2">
    <input type="checkbox" id="published" name="published" value="1"
           {{ old('published', $isEdit ? $post->published : false) ? 'checked' : '' }}
           class="w-4 h-4 text-indigo-600">
    <label for="published" class="text-sm text-gray-700">公開する</label>
</div>
