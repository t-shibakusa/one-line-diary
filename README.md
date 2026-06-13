# 一行日記

HanaPrime スキルチェック課題として作成する Laravel 一行日記システムです。

1日1件、140文字以内の日記を登録・管理できる Web アプリケーションです。

## 使用技術

- Laravel 12
- PHP 8.3
- MySQL 8（Docker 環境）
- Docker Compose
- PHPUnit
- Laravel Pint
- GitHub Actions

## セットアップ

### 前提条件

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)（Docker / Docker Compose 含む）

---

## Docker でアプリを起動する

Docker Compose で **app（PHP）・nginx（Web）・mysql（DB）** の3コンテナを起動します。

| サービス | 役割 | ホストからのアクセス |
|---------|------|---------------------|
| nginx | Web サーバー | http://localhost:8081 |
| app | PHP-FPM（Laravel 実行） | `docker compose exec app ...` |
| mysql | データベース | localhost:3306 |

> **ポートについて:** nginx はホストの **8081** 番ポートに公開しています（8080 が使用中の場合に備えて 8081 を使用）。変更する場合は `docker-compose.yml` の `nginx.ports` を編集してください。

### 初回セットアップ（クローン直後）

```bash
# 1. リポジトリをクローン
git clone <repository-url>
cd one-line-diary

# 2. コンテナをビルド・起動
docker compose up -d --build

# 3. PHP 依存関係をインストール
docker compose exec app composer install

# 4. 環境ファイルを作成
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate

# 5. フロントエンドアセットのビルド（Laravel Breeze）
docker run --rm -v "%cd%:/app" -w /app node:22-alpine sh -c "npm install && npm run build"

# 6. データベースのマイグレーション
docker compose exec app php artisan migrate
```

**7. ブラウザで確認**

http://localhost:8081 にアクセスすると Laravel のトップページが表示されます。

認証機能:

| URL | 内容 |
|-----|------|
| http://localhost:8081/register | ユーザー登録 |
| http://localhost:8081/login | ログイン |
| http://localhost:8081/dashboard | ダッシュボード（要ログイン） |

> **Windows（PowerShell）でのフロントエンドビルド:** 上記 `%cd%` の代わりに `${PWD}` を使用してください。
>
> ```powershell
> docker run --rm -v "${PWD}:/app" -w /app node:22-alpine sh -c "npm install && npm run build"
> ```

### 2回目以降の起動

```bash
cd one-line-diary
docker compose up -d
```

停止する場合:

```bash
docker compose down
```

DB データも含めて完全にリセットする場合:

```bash
docker compose down -v
docker compose up -d --build
docker compose exec app composer install
docker run --rm -v "${PWD}:/app" -w /app node:22-alpine sh -c "npm install && npm run build"
docker compose exec app php artisan migrate
```

### よく使う Docker コマンド

```bash
# コンテナの状態確認
docker compose ps

# コンテナログの確認
docker compose logs -f

# app コンテナ内で Artisan コマンドを実行
docker compose exec app php artisan <command>

# テスト実行
docker compose exec app php artisan test

# コードスタイルチェック
docker compose exec app ./vendor/bin/pint --test

# app コンテナにシェルで入る
docker compose exec app bash
```

### トラブルシューティング

**500 エラーが表示される場合**

storage の書き込み権限が原因のことがあります。以下を実行してください。

```bash
docker compose exec app chmod -R 777 storage bootstrap/cache
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
```

**ポート 8081 が使用中の場合**

`docker-compose.yml` の nginx ポートを変更し、`.env` の `APP_URL` も合わせて更新してください。

```yaml
# docker-compose.yml
ports:
  - "8082:80"  # 例: 8082 に変更
```

```env
# .env
APP_URL=http://localhost:8082
```

---

### ローカル開発（Docker 未使用時）

PHP 8.3 + Composer がインストールされている場合:

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
# .env の DB_CONNECTION=sqlite に変更
php artisan migrate
php artisan serve
```

## テスト実行方法

```bash
php artisan test
```

Docker 利用時:

```bash
docker compose exec app php artisan test
```

## CI 説明

GitHub Actions（`.github/workflows/ci.yml`）で、push / pull request 時に以下を自動実行します。

1. PHP 8.3 環境のセットアップ
2. `composer install`
3. `.env` 作成・`key:generate`・SQLite マイグレーション
4. `php artisan test`
5. `./vendor/bin/pint --test`

ローカルでも同じ確認ができます。

```bash
docker compose exec app php artisan test
docker compose exec app ./vendor/bin/pint --test
```

## セキュリティ方針

| 項目 | 対策 |
|------|------|
| 未ログインアクセス | 日記関連ルートは `auth` ミドルウェアで保護し、未ログイン時はログイン画面へリダイレクト |
| 他人の日記操作 | `DiaryPolicy` で view / update / delete を制御し、自分の日記のみ操作可能 |
| 他人の画像閲覧 | 画像は `GET /diaries/{diary}/image` 経由で配信し、Policy 通過後のみ `Storage::response()` で返却 |
| CSRF | Laravel 標準の CSRF トークン検証（フォームに `@csrf` を付与） |
| XSS | Blade の `{{ }}` で出力をエスケープ（日記本文・フラッシュメッセージ等） |
| SQL Injection | Eloquent / Query Builder を使用し、生 SQL は使わない |
| 機密情報 | `.env` は `.gitignore` で Git 管理対象外 |
| 画像保存 | `storage/app/private/diary_images`（`diary_images` ディスク）に保存し、`public` 配下には置かない |

上記は `tests/Feature/DiarySecurityTest.php` および各 Feature Test で確認しています。

## 設計方針

- Laravel 標準機能を最大限活用した保守性の高い設計
- Policy による認可制御
- 1日1件制限をアプリ層と DB 層の両方で担保
- 画像は private storage に保存し、認可後に配信
- Feature Test 中心の品質担保

## 工夫した点

> 各 Phase 完了に合わせて追記していきます。

## 開発フェーズ

| Phase | 内容 | 状態 |
|-------|------|------|
| 0 | Laravel プロジェクト初期構築 | 完了 |
| 1 | Docker 環境構築 | 完了 |
| 2 | 認証機能（Breeze） | 完了 |
| 3 | 日記テーブル・モデル | 完了 |
| 4 | 日記一覧表示 | 完了 |
| 5 | 日記登録 | 完了 |
| 6 | 日記編集 | 完了 |
| 7 | 日記削除 | 完了 |
| 8 | 日付検索 | 対象外（実装しない） |
| 9 | 画像アップロード | 完了 |
| 10 | セキュリティ確認 | 完了 |
| 11 | GitHub Actions CI | 完了 |
| 12 | README 整備 | 未着手 |
| 13 | 最終確認 | 未着手 |

詳細は `kaihatu_flow.md` を参照してください。
