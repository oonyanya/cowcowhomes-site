# views�ɂ���

�z�[���y�[�W�̃e���v���[�g��views�ȉ��ɂ����A�y�[�W�̃X�^�C����stylus�ƌĂ΂����@�ŋL�q�������̂�css�ȉ��ɒu���Ă��܂�

- assets �y�[�W�S�̂Ŏg�p����X�^�C���V�[�g�Ȃǂ�u��
  - css ���ʂ��Ďg���X�^�C���V�[�g
    - pages �y�[�W���Ƃ̃X�^�C���V�[�g
  - img �摜
  - js �X�N���v�g
- data
 - resources.yaml views�Ŏg�p����t�@�C��
- posts
  - en �p��ł̕������Ȃǂ��i�[���Ă��܂�
    - pages �P��y�[�W���i�[���܂�
  - ja ���{��ł̕������Ȃǂ��i�[���Ă��܂�
    - pages �P��y�[�W���i�[���܂�
- views
  - en �p��ł̃z�[���y�[�W
    - _single_page_article.jade �P��y�[�W�̃e���v���[�g
  - ja ���{��ł̃z�[���y�[�W
    - _single_page_article.jade �P��y�[�W�̃e���v���[�g
  - image �T�C�g���Ƃ̃C���[�W��\���܂�
  - layout.jade �R�A�����̃e���v���[�g
  - index.jade �g�b�v�y�[�W

�y�[�W��V����������ꍇ�A�K���ȃt�H���_�[������āA���̒��Ńy�[�W�̓��e�������Ă����܂�

�y�[�W�̓��e�������I�������K��index.jade�Ƃ������O�ŕۑ����āA�i�r�Q�[�V�����Ƀy�[�W���[�g���N�_�Ƃ��郊���N��ǉ����Ă�������

## �w�b�_�[

�w�b�_�[�͉�Ђ̏��ƃi�r�Q�[�V�����A�p�������X�g�Ƒ匩�o���ō\������Ă��܂�

��Ђ̏���#company_infomation�ȉ��ɏ�����A�i�r�Q�[�V������nav.global_nav�ȉ��ɏ�����Ă��܂�

�p�������X�g��nav#bread���g�p���ď����Ă��܂����A�y�[�W���Ƃɓ��e���Ⴄ�̂ŃR���e���c�̂Ƃ���Ő��������܂�

�܂��A�匩�o���̕��������l��#main_titlie�̏���h1�^�O���g�p���ď����Ă܂����A�����̓p�������X�g�Ɠ����ł�

#### �i�r�Q�[�V�����̕ύX�̎d��

���łɂ��镔���ɍ��ڂ�ǉ��������ꍇ�͒P��li��ǉ����邾����OK�ł�

���K�w�Ƀ��j���[��ǉ��������ꍇ��
  ul.top_menu
    li
      a(href='/') HOGE

�����ɒǉ��������ꍇ��

    li.dropdown-parent
      a(href='/') HOGE
      ul.dropdown-content
        li
          a(href='') FUGA
        li
          a(href='') HOGE

�Ƃ��܂��B
���̒i�K�ł͑��K�w�܂ł����l�����ĂȂ��̂ŁA��O�K�w�ȍ~��ǉ����Ă�����ɕ\������܂���

## �R���e���c

�p�������X�g�₻�ꂼ��̃y�[�W�ɕ\�����������e��^�C�g�����L�ڂ��܂�

�p�������X�g�ɒǉ�����ꍇ�Anav#bread�̉��ɂ���ul�^�O��li���g���Ēǉ����Ă�������

�������Ȃ��Ɛ���ɕ\������܂���

�y�[�W�ɕ\�����������e��infomation��sidebar ID�ɏ������Ƃ��ł��܂�

infomation ID�ȉ��ɂ͂ɂ͋L����\�Ȃǈ�Ԍ��������Ȃ��̂������āAsidebar ID�ɂ͋��L�{�^����i�r�Q�[�V�����Ȃǂ������܂�

�����A���܂�ɑ����̕��͂������Ă킩��ɂ����ꍇ�͂����������ɏ����ďڂ������e�͂ق��̃y�[�W�ɏ������Ƃ��ł��܂�

### �T�C�h�o�[�̃i�r�Q�[�V�����̏�����

	#sidebar
		ul.sidenav
			li
				a(href='test') test

### �g�s�b�N�X�̏�����
	#topics
		div
			h3 ���ݕ���
			p �������ܓ��Ђő݂��o���Ă��镨�����Љ�܂�
			a.detail(href='rental/') �ڂ����͂�����
		div
			h3 ��Јē�
			p ���Ђ̊�{����A�N�Z�X�ɂ��Ă��ē��������܂�
			a.detail(href='about/') �ڂ����͂�����

## �t�b�^�[

���쌠��T�C�g�}�b�v��v���C�o�V�[�|���V�[�Ȃǂ������ɋL�q���Ă܂�
���i�K�ł̓t���[�̑f�ނ��g�p���Ă���̂�Powered by�ȍ~�͍폜���Ȃ��ł�������
���ꂪ�Ȃ��ƃf�U�C���̎g�p���Ȃǂ𐿋�����Ă��܂��܂�

## �P��y�[�W�̏�����

��������about.jade������΂킩��Ǝv���܂�
�Ȃ��A�����N�𒣂�ہAURL��about.jade�Ȃ�/posts/ja/pages/about.html�݂����ɏ����܂�

## resources.jade�ɂ���

��Ж���Z���Ȃǋ��ʂ��Ďg�p������̂������Ă���܂�
  