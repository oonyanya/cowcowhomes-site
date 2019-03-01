# cowcowhomes-site

牛牛不動産のサイトです

## 注意事項

assets/imgフォルダー以下のファイルはgitでは管理していません。daisaku@cowcowhomes.comというアカウントのgoogleドライブ

.\gdrive-windows-x64.exe sync upload .\assets\img\ 1gMOz4dX75PRZ4E1DyHftNw7xGZOKIO3w

## Setup

- xamppをCドライブのルートにインストールする
- node.js 6.9.2をインストールする
- GraphicsMagick-1.3.25-Q16-win64-dll.exeを[SourceForge](https://sourceforge.net/projects/graphicsmagick/files/graphicsmagick-binaries/1.3.25/GraphicsMagick-1.3.25-Q16-win64-dll.exe/download)からダウンロードして、インストールする
- コマンドプロンプトを管理者権限で立ち上げて、npm -i roots -gを実行する
- このリポジトリーをgithubから落として、このリポジトリーがあるフォルダーに移動する
- .\gdrive-windows-x64.exe listを実行する
- なんちゃらかんちゃらを開けと書いてあるので、それをブラウザーで開き、daisaku@cowcowhomes.comと同期する
- gdriveの使用許可を求められるので許可する
- .\gdrive-windows-x64.exe sync download 1gMOz4dX75PRZ4E1DyHftNw7xGZOKIO3w .\assets\img\を実行する
- npm installを実行する
- watch.cmdを起動して、ブラウザーでlocalhostと入力する

※Tortise GitとGit for Windowsも同時に必要になります

## 物件一覧の変更の仕方

craiglistやbloggerにログインしてください。
craiglistに投稿する際は

COWCOWHOMES.CO.LTD
TEL 03-6458-1098
URL http://cowcowhomes.com
Real estate business license number No. 13(1)99902

の文字を入れてください

## ホームページやスタイルシートの変更の仕方

詳しいことはviews.mdに書いてあるので、このファイルを参照したうえで変更してください。変更した場合、watch.cmdを実行しないと反映されません。また、本番環境に反映したい場合は物件一覧に書いてある手順に従ってください。
