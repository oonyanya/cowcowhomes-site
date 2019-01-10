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

既存の物件を編集したい場合、必ずUTF-8(BOMなし)で保存してください。指定された形式で保存しないとwatch.cmdがエラーメッセージを出します

もし、不要な場合はファイルを削除して、watch.cmdを実行するとホームページの物件一覧に表示されなくなります

新しい物件を作りたい場合、以下の手順に従ってください

- manage_post.ps1を起動する(commandにはaddと入力し、typeにはrentalと入力します。idはアルファベットと数字のみの文字列を入力します。import_image_pathは空欄もしくは画像があるディレクトリへのフルパスを入力します)
- UTF-8(BOMなし)で編集可能なテキストエディターで編集する
- watch.cmdを再起動する

※UTF-8(BOMなし)で編集可能なテキストエディターは秀丸、FooEditor、EmEditor、gPadなどがあります

今、存在する物件はlistで確認することができます。commandにremoveを指定すると物件を削除することができ、importを指定するとすでにある物件に画像を追加することができます

## 民泊一覧の変更の仕方

やり方は物件一覧と同じですが、typeにはaccommodationと入力します

## _template.jadeのフォーマット
	---
	title: '1R 町屋駅近く'
	date: 14/12/2016
	id: 'post1'
	_content: false
	---

	extends /ja/rental/rental_articles

	block rental_article
		:markdown
			京成線町屋駅徒歩9分

			礼金 (0 yen)

			敷金 (60,000 yen)

			家賃 65,000yen(60,000 + 5,000 yen)

			保険料など... (ask us please)

			仲介手数料 (32,500 yen)
	block thumbnail
		!= image('accommodation/ueno/post1/inside.png')

titleは物件一覧に表示する名前、dataは物件の作成日、IDはHTMLのIDを表します。_contentは物件ごとにページを作るかどうかを表します

「:markdown」以下の部分は本文でそれぞれの行が段落を表します

一つの段落が終わった場合、空行を開ける必要があります

block thumbnail以下はそれぞれの物件に表示したい画像を表します。aのhrefの値は拡大時の画像を表し、data-lightboxの値はグループ名を表します。グループ名を変えると拡大時のページめくりがうまくいかなくなります。imgのsrcの値はサムネイルを表します。

画像はassets\imgいかに置きます。例えば、上野にあるpost1というファイル名で作成した民泊のページに拡大時の画像を置きたい場合、/assets/img/accommodation/ueno/post1に置きます。画像を表示したい場合は!= image('accommodation/ueno/post1')という感じにします。このとき、asset/img/は書かないようにしてください。書くと正しく動作しません。

## 変更を本番に反映させる方法

- 変更理由を書いてTortiseGitなどのソフトでcommitを実行する
- 同じソフトでpushを実行する
- 設定してなければシステムの環境設定でFTP_SERVER_HOSTにftp://ユーザー名@FTPサーバーのアドレスと設定する
- cowcowhomesディレクトリーでコマンドプロンプトを立ち上げ、deploy.comを実行する

## ホームページやスタイルシートの変更の仕方

詳しいことはviews.mdに書いてあるので、このファイルを参照したうえで変更してください。変更した場合、watch.cmdを実行しないと反映されません。また、本番環境に反映したい場合は物件一覧に書いてある手順に従ってください。
