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

- Docker / Docker Compose（Phase 1 以降）
- または PHP 8.3 + Composer（ローカル開発）

### 初期セットアップ

```bash
# リポジトリをクローン
git clone <repository-url>
cd one-line-diary

# 依存関係のインストール
composer install

# 環境ファイルの作成
cp .env.example .env
php artisan key:generate

# データベースの準備（SQLite 利用時）
touch database/database.sqlite
php artisan migrate
```

## Docker 起動手順

> Phase 1 で Docker 環境を構築予定です。

## テスト実行方法

```bash
php artisan test
```

Docker 利用時:

```bash
docker compose exec app php artisan test
```

## CI 説明

> Phase 11 で GitHub Actions を構築予定です。

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
| 1 | Docker 環境構築 | 未着手 |
| 2 | 認証機能（Breeze） | 未着手 |
| 3 | 日記テーブル・モデル | 未着手 |
| 4〜 | CRUD・画像・CI 等 | 未着手 |

詳細は `kaihatu_flow.md` を参照してください。
