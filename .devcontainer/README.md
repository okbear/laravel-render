# Dev Container セットアップ

## 起動方法

1. VS Code でこのフォルダを開く
2. コマンドパレット (`Cmd+Shift+P`) → `Dev Containers: Reopen in Container`
3. コンテナのビルドが完了したら、コンテナ内のターミナルで以下を実行

```bash
# DB マイグレーション（初回のみ）
php artisan migrate
```

## Sail コマンド（コンテナ外から使う場合）

```bash
# 起動
./vendor/bin/sail up -d

# 停止
./vendor/bin/sail down

# Artisan
./vendor/bin/sail artisan migrate
```

## サービス構成

| サービス | ポート |
|---------|--------|
| Laravel (PHP 8.5) | http://localhost:80 |
| MySQL 8.4 | localhost:3306 |
| Redis | localhost:6379 |
| Vite (HMR) | localhost:5173 |
