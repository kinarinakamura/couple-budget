
# レビューコマンド（PR番号指定用）
---
name: review
description: PR番号を指定してレビューする。/review <PR番号> で呼び出す。
disable-model-invocation: true
---

PR #$ARGUMENTS をレビューしてください。

1. `gh pr view $ARGUMENTS` でPRの概要を確認
2. `gh pr diff $ARGUMENTS` で差分を取得
3. code-review スキルを使ってレビュー