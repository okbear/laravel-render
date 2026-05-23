# Laravel ブログ CRUD アプリ — 開発ドキュメント

Laravel 初心者向けに、このプロジェクトで何をやっているかをまとめたドキュメントです。

---

## 目次

1. [プロジェクト構成](#1-プロジェクト構成)
2. [開発環境のしくみ](#2-開発環境のしくみ)
3. [Laravel の基本概念](#3-laravel-の基本概念)
4. [このアプリの実装解説](#4-このアプリの実装解説)
5. [Livewire とは](#5-livewire-とは)
6. [よく使うコマンド](#6-よく使うコマンド)

---

## 1. プロジェクト構成

```
dev-test/
├── app/
│   ├── Http/Controllers/
│   │   └── PostController.php     # 投稿のCRUD処理（詳細ページ・削除）
│   ├── Livewire/
│   │   ├── PostList.php           # 一覧・検索・フィルター・削除確認
│   │   └── PostForm.php           # 投稿作成・編集フォーム
│   └── Models/
│       └── Post.php               # 投稿モデル（DBとのやりとり）
├── database/
│   └── migrations/
│       └── ..._create_posts_table.php  # postsテーブルの定義
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php          # 全ページ共通のHTMLレイアウト
│   ├── livewire/
│   │   ├── post-list.blade.php    # 一覧コンポーネントのHTML
│   │   └── post-form.blade.php    # フォームコンポーネントのHTML
│   └── posts/
│       ├── index.blade.php        # 一覧ページ
│       ├── show.blade.php         # 詳細ページ
│       ├── create.blade.php       # 作成ページ
│       └── edit.blade.php         # 編集ページ
├── routes/
│   └── web.php                    # URLとコントローラーの対応表
├── compose.yaml                   # Docker Compose の設定（Sail が生成）
└── .devcontainer/
    └── devcontainer.json          # Dev Container の設定
```

---

## 2. 開発環境のしくみ

### Docker + Laravel Sail

このプロジェクトは **Docker** を使って開発環境を構築しています。
ホストマシン（あなたのMac）に PHP や MySQL をインストールする必要はありません。

```
あなたのMac
└── Docker
    ├── laravel.test コンテナ  ← PHP 8.5 が動いている
    ├── mysql コンテナ         ← MySQL 8.4 が動いている
    └── redis コンテナ         ← Redis が動いている
```

**Laravel Sail** は `docker compose` コマンドのラッパーです。

```bash
# Sail なし（長い）
docker compose exec laravel.test php artisan migrate

# Sail あり（短い）
./vendor/bin/sail artisan migrate
```

### 起動・停止

```bash
# 起動（バックグラウンド）
./vendor/bin/sail up -d

# 停止
./vendor/bin/sail down
```

---

## 3. Laravel の基本概念

### MVC パターン

Laravel は **MVC（Model-View-Controller）** という設計パターンを使っています。

| 役割 | ファイル | 説明 |
|------|---------|------|
| **Model** | `app/Models/Post.php` | データベースとのやりとりを担当 |
| **View** | `resources/views/` | HTMLの表示を担当 |
| **Controller** | `app/Http/Controllers/PostController.php` | リクエストを受け取り、ModelとViewをつなぐ |

### ルーティング

`routes/web.php` に「このURLにアクセスしたらこの処理を実行する」という対応を書きます。

```php
// Route::resource は CRUD に必要な7つのルートを一括で定義する
Route::resource('posts', PostController::class);
```

これだけで以下のルートが自動生成されます：

| メソッド | URL | アクション | 説明 |
|---------|-----|-----------|------|
| GET | /posts | index | 一覧 |
| GET | /posts/create | create | 作成フォーム表示 |
| POST | /posts | store | 作成処理 |
| GET | /posts/{id} | show | 詳細 |
| GET | /posts/{id}/edit | edit | 編集フォーム表示 |
| PUT | /posts/{id} | update | 更新処理 |
| DELETE | /posts/{id} | destroy | 削除処理 |

### Eloquent ORM

**Eloquent** は Laravel のデータベース操作ライブラリです。
SQLを直接書かずに、PHPのコードでDBを操作できます。

```php
// 全件取得
Post::all();

// 条件付き取得
Post::where('published', true)->get();

// 作成
Post::create(['title' => 'タイトル', 'body' => '本文', ...]);

// 更新
$post->update(['title' => '新しいタイトル']);

// 削除
$post->delete();
```

### Blade テンプレート

**Blade** は Laravel のテンプレートエンジンです。
HTMLの中に `{{ }}` や `@if` などの記法でPHPの値を埋め込めます。

```blade
{{-- 変数の表示（XSS対策済み） --}}
{{ $post->title }}

{{-- 条件分岐 --}}
@if ($post->published)
    <span>公開中</span>
@else
    <span>下書き</span>
@endif

{{-- ループ --}}
@foreach ($posts as $post)
    <p>{{ $post->title }}</p>
@endforeach

{{-- 別のビューを読み込む --}}
@extends('layouts.app')
@section('content')
    ここにコンテンツ
@endsection
```

### マイグレーション

**マイグレーション** はデータベースのテーブル定義をコードで管理する仕組みです。

```php
// database/migrations/..._create_posts_table.php
Schema::create('posts', function (Blueprint $table) {
    $table->id();                              // id カラム（自動採番）
    $table->string('title');                   // VARCHAR
    $table->text('body');                      // TEXT
    $table->string('category')->default('uncategorized');
    $table->boolean('published')->default(false);
    $table->timestamps();                      // created_at, updated_at
});
```

実行コマンド：
```bash
./vendor/bin/sail artisan migrate
```

---

## 4. このアプリの実装解説

### Post モデル

```php
// app/Models/Post.php
#[Fillable(['title', 'body', 'category', 'published'])]
class Post extends Model
{
    // ...
}
```

`#[Fillable]` は **マスアサインメント保護** のための設定です。
`Post::create($request->all())` のように一括でデータを保存するとき、
ここに書いたカラムだけが保存対象になります（セキュリティ対策）。

### バリデーション

フォームの入力値を検証する仕組みです。

```php
// Livewire の場合（PostForm.php）
#[Validate('required|string|max:255')]
public string $title = '';
```

`required` = 必須、`string` = 文字列、`max:255` = 255文字以内。
バリデーションに失敗すると自動でエラーメッセージが表示されます。

---

## 5. Livewire とは

**Livewire** は、JavaScript をほとんど書かずにリアルタイムなUIを作れる Laravel のライブラリです。

### 通常の Laravel との違い

```
通常の Laravel:
ユーザー操作 → ページ全体をリロード → サーバーで処理 → 新しいHTMLを返す

Livewire:
ユーザー操作 → Livewire が差分だけをAjaxで送受信 → 該当部分だけ更新
```

### このアプリでの使い方

**PostList コンポーネント**（`app/Livewire/PostList.php`）

```php
// wire:model.live.debounce.300ms="search" と書くと
// 入力するたびに（300ms待って）自動でこのプロパティが更新される
public string $search = '';

// プロパティが変わると render() が自動で再実行される
public function render()
{
    $posts = Post::query()
        ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
        ->paginate(9);

    return view('livewire.post-list', compact('posts'));
}
```

**Blade での対応**（`resources/views/livewire/post-list.blade.php`）

```blade
{{-- wire:model.live でリアルタイムに PHP のプロパティと同期 --}}
<input wire:model.live.debounce.300ms="search" type="text" placeholder="検索...">

{{-- wire:click でPHPのメソッドを呼び出す --}}
<button wire:click="confirmDelete({{ $post->id }})">削除</button>
```

### Livewire の主なディレクティブ

| ディレクティブ | 説明 |
|--------------|------|
| `wire:model` | 入力値とPHPプロパティを同期 |
| `wire:model.live` | リアルタイムで同期（入力のたびに） |
| `wire:click="method"` | クリックでPHPメソッドを呼び出す |
| `wire:submit="method"` | フォーム送信でPHPメソッドを呼び出す |
| `wire:loading` | 通信中に表示/非表示を切り替える |
| `wire:navigate` | ページ遷移をSPA風にする |

---

## 6. よく使うコマンド

```bash
# コンテナ起動
./vendor/bin/sail up -d

# コンテナ停止
./vendor/bin/sail down

# マイグレーション実行
./vendor/bin/sail artisan migrate

# マイグレーションをリセットして再実行（データも消える）
./vendor/bin/sail artisan migrate:fresh

# Tinker（対話型コンソール）
./vendor/bin/sail artisan tinker

# ルート一覧を確認
./vendor/bin/sail artisan route:list

# キャッシュクリア
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan view:clear

# Composer パッケージのインストール
./vendor/bin/sail composer require パッケージ名

# Livewire コンポーネントの作成
./vendor/bin/sail artisan make:livewire コンポーネント名

# モデルの作成
./vendor/bin/sail artisan make:model モデル名

# コントローラーの作成
./vendor/bin/sail artisan make:controller コントローラー名
```
