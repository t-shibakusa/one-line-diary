# 一行日記

HanaPrime スキルチェック課題として作成した Laravel 一行日記システムです。

1日1件、140文字以内の日記を登録・管理できる Web アプリケーションです。ログインしたユーザーは自分の日記のみ一覧・登録・編集・削除でき、任意で画像を添付できます。

## 機能一覧

### 認証（Laravel Breeze）

| 機能 | 状態 |
|------|------|
| ユーザー登録 | 実装済み |
| ログイン / ログアウト | 実装済み |
| パスワードリセット | 実装済み（Breeze 標準） |

### 日記管理

| 機能 | 状態 | 備考 |
|------|------|------|
| 日記一覧 | 実装済み | 日付降順・1ページ5件 |
| 日記登録 | 実装済み | 1日1件・本文140文字以内 |
| 日記編集 | 実装済み | 自分の日記のみ |
| 日記削除 | 実装済み | 自分の日記のみ |
| 画像アップロード | 実装済み | jpg / jpeg・最大2MB |
| 植物育成 | 実装済み | 日記累計数に応じて成長 |
| 日付検索 | 未実装 | スコープ外（後述） |

### 植物育成

日記の累計投稿数に応じて、ユーザーごとの植物が成長します（DB には保存せず、日記件数からリアルタイム算出）。

| レベル | 条件 | 表示名 |
|-------|------|--------|
| 1 | 0〜2件 | 種 |
| 2 | 3〜6件 | 芽 |
| 3 | 7〜13件 | 若葉 |
| 4 | 14〜29件 | 鉢植え |
| 5 | 30件以上 | 木 |

日記一覧画面上部に植物カードを表示し、LV1〜LV20 の成長画像（`public/images/plants/`）を段階的に切り替えます。

### UI（デザインのみ）

サイドバーの「設定」は実装済みです。以下は未実装です。

- ホーム
- カレンダー（日記一覧内のカレンダーは実装済み）
- マイページ

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

# 7. （任意）サンプルデータの投入
docker compose exec app php artisan db:seed --class=DiarySeeder
```

**8. ブラウザで確認**

http://localhost:8081 にアクセスすると Laravel のトップページが表示されます。

| URL | 内容 |
|-----|------|
| http://localhost:8081/register | ユーザー登録 |
| http://localhost:8081/login | ログイン |
| http://localhost:8081/diaries | 日記一覧（要ログイン） |
| http://localhost:8081/diaries/create | 日記登録（要ログイン） |

**動作確認用アカウント（DiarySeeder 実行時）**

| 項目 | 値 |
|------|-----|
| メールアドレス | `test@example.com` |
| パスワード | `password` |

Seeder 実行後、上記アカウントでログインすると、直近11件分のサンプル日記が一覧に表示されます。

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
3. `npm ci` / `npm run build`（Vite アセットのビルド）
4. `.env` 作成・`key:generate`・SQLite マイグレーション
5. `php artisan test`
6. `./vendor/bin/pint --test`

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

- **1日1件制限の二重担保** — バリデーション（同日の重複チェック）と DB の `unique(user_id, diary_date)` 制約の両方で、同一ユーザーが同日に複数登録できないようにした
- **Policy による認可制御** — 日記の閲覧・編集・削除および画像配信を `DiaryPolicy` で統一し、Controller / FormRequest から一貫して適用
- **画像の private storage 保存** — 画像は `storage/app/private/diary_images` に保存し、認可後に専用ルート（`/diaries/{diary}/image`）経由でのみ配信。`public` 直リンクを防ぐ
- **Feature Test による品質担保** — 認証・CRUD・画像・セキュリティを含む 60 件のテストで主要機能をカバー
- **Docker による環境統一** — app / nginx / mysql の 3 コンテナ構成で、評価者が README の手順だけで起動できる
- **GitHub Actions による CI** — push / PR 時にテストと Pint を自動実行し、品質を継続的に確認
- **植物育成** — 日記累計数からレベルを算出し DB に保存しないため、日記削除時も自動で正しい状態に再計算される
- **デザイン準拠レイアウト** — 左固定サイドバー + グリーン系カラーの UI を Tailwind CSS で再現

## 今後の改善案

- **日付検索** — 仕様上の機能だが、今回のスコープ外とした。カレンダー UI と連携した検索が考えられる
- **カレンダー・マイページ・設定** — 現状はサイドバーに表示のみ。各画面のルートと機能を追加
- **画像サムネイル** — 一覧表示用にリサイズ画像を生成し、表示速度を改善
- **プロフィール連携** — Breeze のプロフィール画面とサイドバーの「マイページ」を統合
- **E2E テスト** — Playwright 等でブラウザ操作の自動テストを追加

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
| 12 | README 整備 | 完了 |
| 13 | 最終確認 | 完了 |

詳細は `kaihatu_flow.md` を参照してください。

## 最終確認（Phase 13）

提出前に以下を確認済みです（2026-06-13）。

| 確認項目 | 結果 |
|---------|------|
| Docker 新規構築（`docker compose down -v` → `up -d --build`） | OK |
| マイグレーション | OK |
| テスト（60件） | すべて PASS |
| Laravel Pint | PASS |
| 主要ルート応答（`/` 200、`/login` 200、`/diaries` 未ログイン時 302） | OK |
| `.env` が Git 管理外 | OK（`.gitignore` 対象） |
| デバッグコード（`dd` / `dump` 等） | なし |
