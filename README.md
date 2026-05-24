# プロジェクト起動・開発ガイド

## 0. 初回セットアップ（GitHubからのクローン）
GitHubからプロジェクトを初めて取得して動かす場合の手順です。

```bash
# 1. リポジトリをクローンして移動
git clone <repository-url>
cd <repository-name>

# 2. 環境変数ファイルの作成
cp .env.example .env

# 3. パッケージのインストール
# ※ホスト側にPHP/Composerがある場合。無い場合は後述の「A」でコンテナを開いてから実行してください
composer install
npm install

# 4. アプリケーションキーの生成とマイグレーション
# 後述の「A」または「B」の手順でコンテナを立ち上げた後、以下を実行します

# 【A. Dev Containerの場合】
php artisan key:generate
php artisan migrate

# 【B. Macローカル（Sail）の場合】
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

---

このプロジェクトは、**Dev Container（VS Code等でのコンテナ開発）**と**Macローカル（ホスト側）での軽量起動**の2つの方法で開発できます。

マシンのメモリ（RAM）が8GB等の場合、VS CodeのDev Container機能はメモリを大きく消費し、フリーズの原因となることがあります。その場合は**「B. Macローカルから軽量起動する方法」**を推奨します。

---

## A. Dev Containerで開発する場合（フルコンテナ環境）
VS Code等の機能を使ってコンテナ内で作業を行う方法です。

### 1. 起動手順
1. ホスト（Mac）側でDocker（Colimaなど）を起動しておく（例: `colima start`）
2. VS Codeでプロジェクトを開き、「Reopen in Container（コンテナで再度開く）」を実行
3. Dev Container内のターミナルで以下のコマンドを実行：
   ```bash
   composer dev
   ```
   ※開発サーバー、Vite(フロントエンド)、キューなどが一括起動します。

---

## B. Macローカルから軽量起動する方法（推奨・メモリ節約）
コンテナ内部に入らず、ホスト（Mac）のターミナルから直接コンテナを起動・操作する方法です。
Macのメモリ負荷が大幅に軽減されます。

### 1. 起動手順
Macのローカルターミナルで以下の順に実行します。

```bash
# 1. Docker（Colima）を起動する
colima start

# 2. バックエンドのコンテナ（PostgreSQL, Redis, Laravel）をバックグラウンドで起動
./vendor/bin/sail up -d

# 3. 必要に応じてマイグレーションを実行
./vendor/bin/sail artisan migrate

# 4. フロントエンドの開発サーバー（Vite）をローカルで起動
npm run dev
```

* アプリ本体: `http://localhost`
* Viteサーバー: `http://localhost:5173` （または 5174）

※開発を終了してコンテナを停止する場合は `./vendor/bin/sail down` を実行してください。

---

## 共通コマンド集

### コード整形・静的解析 (Lint & Format)
```bash
# コードの整形 (Laravel Pint)
composer format

# 静的解析 (PHPStan)
composer analyse

# 構文チェック (Lint)
composer lint
```

### テスト (Testing)
```bash
# テストの実行 (PHPUnit)
composer test
```

### よく使う便利なコマンド
```bash
# データベースのマイグレーション
php artisan migrate                       # (Dev Containerの場合)
./vendor/bin/sail artisan migrate         # (Macローカルの場合)

# キャッシュの完全クリア
php artisan optimize:clear                # (Dev Containerの場合)
./vendor/bin/sail artisan optimize:clear  # (Macローカルの場合)
```

---

## トラブルシューティング (Troubleshooting)

### 1. `npm install` 実行時にPCがフリーズ・クラッシュする
メモリ不足（特にRAM 8GBの環境）が原因です。
* **対策**: `npm install` を実行する前に、Docker、VS Code、ブラウザの不要なタブなど、メモリを消費するアプリを一時的に終了してください。

### 2. 初回起動時のフロントエンド・エラー解決（Dev Container）
ホスト側（Macなど）で事前に `npm install` を実行していた場合、コンテナ内（Linux）で `composer dev` を実行した際に **「Cannot find native binding」** などのVite関連エラーが発生することがあります。
* **解決策**: Dev Container内のターミナルで `node_modules` を削除し、再インストールします。
  ```bash
  rm -rf node_modules
  npm install
  ```

### 3. 「could not translate host name "pgsql"」というデータベースエラーが出る
このエラーは、コンテナネットワークの外部（ホストのMac）で直接 `composer dev` や `php artisan` コマンドを実行した際に発生します。
* **解決策**:
  * A（Dev Container）の場合は、必ず **Dev Containerの中にあるターミナル** を開き、そこで実行してください。
  * B（Macローカル）の場合は、`php artisan` ではなく `./vendor/bin/sail artisan` を使用してください。
