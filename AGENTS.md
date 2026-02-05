# AGENTS.md

このファイルはClaude Codeのサブエージェントへの指示を定義します。

## プロジェクト概要

カップル向け家計簿アプリケーション（Laravel 12 + Vue 3 + Inertia.js）

## コーディング規約

### PHP / Laravel

- PSR-12に準拠
- 型宣言を使用する
- Eloquentモデルは `App\Models` に配置
- コントローラーは単一責任の原則に従う
- FormRequestでバリデーションを行う

### Vue / JavaScript

- Composition API (`<script setup>`) を使用
- コンポーネントはPascalCase
- propsとemitsは明示的に定義
- Inertia.jsの `useForm` でフォーム処理

### CSS

- Tailwind CSSのユーティリティクラスを優先
- DaisyUIコンポーネントを活用
- カスタムCSSは最小限に

## ファイル構成

```
app/
  Http/Controllers/    # コントローラー
  Models/              # Eloquentモデル
resources/
  js/
    Components/        # 再利用可能なVueコンポーネント
    Pages/             # Inertiaページコンポーネント
    Layouts/           # レイアウトコンポーネント
  css/                 # スタイルシート
routes/
  web.php              # Webルート
database/
  migrations/          # マイグレーション
```

## 禁止事項

- ファイルの削除（rm, mv, chmod禁止）
- .envファイルの読み書き
- 確認なしでの破壊的な変更
- 不要なパッケージの追加

## テスト

変更後は必要に応じて `composer test` でテストを実行すること。