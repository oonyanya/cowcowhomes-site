# cowcowhomes-site

�����s���Y�̃T�C�g�ł�

## ���ӎ���

assets/img�t�H���_�[�ȉ��̃t�@�C����git�ł͊Ǘ����Ă��܂���Bdaisaku@cowcowhomes.com�Ƃ����A�J�E���g��google�h���C�u

.\gdrive-windows-x64.exe sync upload .\assets\img\ 1gMOz4dX75PRZ4E1DyHftNw7xGZOKIO3w

## Setup

- xampp��C�h���C�u�̃��[�g�ɃC���X�g�[������
- node.js 6.9.2���C���X�g�[������
- GraphicsMagick-1.3.25-Q16-win64-dll.exe��[SourceForge](https://sourceforge.net/projects/graphicsmagick/files/graphicsmagick-binaries/1.3.25/GraphicsMagick-1.3.25-Q16-win64-dll.exe/download)����_�E�����[�h���āA�C���X�g�[������
- �R�}���h�v�����v�g���Ǘ��Ҍ����ŗ����グ�āAnpm -i roots -g�����s����
- ���̃��|�W�g���[��github���痎�Ƃ��āA���̃��|�W�g���[������t�H���_�[�Ɉړ�����
- .\gdrive-windows-x64.exe list�����s����
- �Ȃ񂿂�炩�񂿂����J���Ə����Ă���̂ŁA������u���E�U�[�ŊJ���Adaisaku@cowcowhomes.com�Ɠ�������
- gdrive�̎g�p�������߂���̂ŋ�����
- .\gdrive-windows-x64.exe sync download 1gMOz4dX75PRZ4E1DyHftNw7xGZOKIO3w .\assets\img\�����s����
- npm install�����s����
- watch.cmd���N�����āA�u���E�U�[��localhost�Ɠ��͂���

��Tortise Git��Git for Windows�������ɕK�v�ɂȂ�܂�

## �����ꗗ�̕ύX�̎d��

�����̕�����ҏW�������ꍇ�A�K��UTF-8(BOM�Ȃ�)�ŕۑ����Ă��������B�w�肳�ꂽ�`���ŕۑ����Ȃ���watch.cmd���G���[���b�Z�[�W���o���܂�

�����A�s�v�ȏꍇ�̓t�@�C�����폜���āAwatch.cmd�����s����ƃz�[���y�[�W�̕����ꗗ�ɕ\������Ȃ��Ȃ�܂�

�V������������肽���ꍇ�A�ȉ��̎菇�ɏ]���Ă�������

- manage_post.ps1���N������(command�ɂ�add�Ɠ��͂��Atype�ɂ�rental�Ɠ��͂��܂��Bid�̓A���t�@�x�b�g�Ɛ����݂̂̕��������͂��܂��Bimport_image_path�͋󗓂������͉摜������f�B���N�g���ւ̃t���p�X����͂��܂�)
- UTF-8(BOM�Ȃ�)�ŕҏW�\�ȃe�L�X�g�G�f�B�^�[�ŕҏW����
- watch.cmd���ċN������

��UTF-8(BOM�Ȃ�)�ŕҏW�\�ȃe�L�X�g�G�f�B�^�[�͏G�ہAFooEditor�AEmEditor�AgPad�Ȃǂ�����܂�

���A���݂��镨����list�Ŋm�F���邱�Ƃ��ł��܂��Bcommand��remove���w�肷��ƕ������폜���邱�Ƃ��ł��Aimport���w�肷��Ƃ��łɂ��镨���ɉ摜��ǉ����邱�Ƃ��ł��܂�

## �����ꗗ�̕ύX�̎d��

�����͕����ꗗ�Ɠ����ł����Atype�ɂ�accommodation�Ɠ��͂��܂�

## _template.jade�̃t�H�[�}�b�g
	---
	title: '1R �����w�߂�'
	date: 14/12/2016
	id: 'post1'
	_content: false
	---

	extends /ja/rental/rental_articles

	block rental_article
		:markdown
			�����������w�k��9��

			��� (0 yen)

			�~�� (60,000 yen)

			�ƒ� 65,000yen(60,000 + 5,000 yen)

			�ی����Ȃ�... (ask us please)

			����萔�� (32,500 yen)
	block thumbnail
		!= image('accommodation/ueno/post1/inside.png')

title�͕����ꗗ�ɕ\�����閼�O�Adata�͕����̍쐬���AID��HTML��ID��\���܂��B_content�͕������ƂɃy�[�W����邩�ǂ�����\���܂�

�u:markdown�v�ȉ��̕����͖{���ł��ꂼ��̍s���i����\���܂�

��̒i�����I������ꍇ�A��s���J����K�v������܂�

block thumbnail�ȉ��͂��ꂼ��̕����ɕ\���������摜��\���܂��Ba��href�̒l�͊g�厞�̉摜��\���Adata-lightbox�̒l�̓O���[�v����\���܂��B�O���[�v����ς���Ɗg�厞�̃y�[�W�߂��肪���܂������Ȃ��Ȃ�܂��Bimg��src�̒l�̓T���l�C����\���܂��B

�摜��assets\img�����ɒu���܂��B�Ⴆ�΁A���ɂ���post1�Ƃ����t�@�C�����ō쐬���������̃y�[�W�Ɋg�厞�̉摜��u�������ꍇ�A/assets/img/accommodation/ueno/post1�ɒu���܂��B�摜��\���������ꍇ��!= image('accommodation/ueno/post1')�Ƃ��������ɂ��܂��B���̂Ƃ��Aasset/img/�͏����Ȃ��悤�ɂ��Ă��������B�����Ɛ��������삵�܂���B

## �ύX��{�Ԃɔ��f��������@

- �ύX���R��������TortiseGit�Ȃǂ̃\�t�g��commit�����s����
- �����\�t�g��push�����s����
- �ݒ肵�ĂȂ���΃V�X�e���̊��ݒ��FTP_SERVER_HOST��ftp://���[�U�[��@FTP�T�[�o�[�̃A�h���X�Ɛݒ肷��
- cowcowhomes�f�B���N�g���[�ŃR�}���h�v�����v�g�𗧂��グ�Adeploy.com�����s����

## �z�[���y�[�W��X�^�C���V�[�g�̕ύX�̎d��

�ڂ������Ƃ�views.md�ɏ����Ă���̂ŁA���̃t�@�C�����Q�Ƃ��������ŕύX���Ă��������B�ύX�����ꍇ�Awatch.cmd�����s���Ȃ��Ɣ��f����܂���B�܂��A�{�Ԋ��ɔ��f�������ꍇ�͕����ꗗ�ɏ����Ă���菇�ɏ]���Ă��������B
