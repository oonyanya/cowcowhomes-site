牛牛不動産のサイトです

## 注意事項

.\source\image\フォルダー以下のファイルはgitでは管理していません。daisaku@cowcowhomes.comというアカウントのgoogleドライブ

- xamppをCドライブのルートにインストールする
- node.jsをインストールする
- このリポジトリーをgithubから落として、このリポジトリーがあるフォルダーに移動する
- .\gdrive-windows-x64.exe listを実行する
- なんちゃらかんちゃらを開けと書いてあるので、それをブラウザーで開き、daisaku@cowcowhomes.comと同期する
- gdriveの使用許可を求められるので許可する
- .\gdrive-windows-x64.exe sync download 1gMOz4dX75PRZ4E1DyHftNw7xGZOKIO3w .\source\image\を実行する
- npm install hexo-cli -gを実行する
- npm installを実行する
- watch.cmdを起動して、ブラウザーでlocalhostと入力する

※Tortise GitとGit for Windowsも同時に必要になります

## 物件一覧の変更の仕方

bloggerにログインしてください。

# 記事の作り方
hexo new page [title]ではまともに作れません
手動で以下の感じになるように記事を作ってください

/[language]/[title]/index.md

index.mdは以下のようにしてください

---
layout: page
title: [title]
language: [laguage]
date: 2020-11-20 11:42:59
tags:
---

拡張子をpugにすればpugも使用可能です