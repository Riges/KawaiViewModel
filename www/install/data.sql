INSERT INTO user (user_login, user_full_name, user_mail, user_password_nonce, user_password_md5) VALUES
	('kuroneko', 'Kuroneko', 'kuroneko@kawai-neko-box.fr', 381478, MD5('381478rigesamelia')),
	('orenjineko', 'Orenjineko', 'orenjineko@kawai-neko-box.fr', 9474657, MD5('9474657vbfox'));

SET @user_riges = (SELECT user_id FROM user WHERE user_login = 'kuroneko');
SET @user_blackfox = (SELECT user_id FROM user WHERE user_login = 'orenjineko');

INSERT INTO group_ (group_name, group_description) VALUES
	('administrators', 'Administrateurs'),
	('news_writers', 'News writers'),
	('news_moderators', 'News moderators'),
	('users', 'Utilisateurs');

SET @group_admin = (SELECT group_id FROM group_ WHERE group_name = 'administrators');
SET @group_news_writers = (SELECT group_id FROM group_ WHERE group_name = 'news_writers');
SET @group_news_moderators = (SELECT group_id FROM group_ WHERE group_name = 'news_moderators');
SET @group_users = (SELECT group_id FROM group_ WHERE group_name = 'users');

INSERT INTO user__group (user_id, group_id) VALUES
	( @user_riges, @group_admin ),
	( @user_blackfox, @group_admin );

INSERT INTO right_ (right_name, right_description) VALUES
	('user_create', 'Create a new user without any check'),
	('user_edit', 'Edit any user'),
	('user_delete', 'Remove any user'),
	('news_create', 'Create any news'),
	('news_edit', 'Edit any news'),
	('news_delete', 'Remove any news');

SET @right_user_create = (SELECT right_id FROM right_ WHERE right_name = 'user_create');	
SET @right_user_edit = (SELECT right_id FROM right_ WHERE right_name = 'user_edit');
SET @right_user_delete = (SELECT right_id FROM right_ WHERE right_name = 'user_delete');
SET @right_news_create = (SELECT right_id FROM right_ WHERE right_name = 'news_create');
SET @right_news_edit = (SELECT right_id FROM right_ WHERE right_name = 'news_edit');
SET @right_news_delete = (SELECT right_id FROM right_ WHERE right_name = 'news_delete');

INSERT INTO group__right (group_id, right_id, group__right_value) VALUES
	( @group_admin, @right_user_create, 'allow' ),
	( @group_admin, @right_user_edit, 'allow' ),
	( @group_admin, @right_user_delete, 'allow' ),
	( @group_admin, @right_news_create, 'allow' ),
	( @group_admin, @right_news_edit, 'allow' ),
	( @group_admin, @right_news_delete, 'allow' ),

	( @group_news_writers, @right_news_create, 'allow' ),

	( @group_news_moderators, @right_news_create, 'allow' ),
	( @group_news_moderators, @right_news_edit, 'allow' ),
	( @group_news_moderators, @right_news_delete, 'allow' );

INSERT INTO news_tag (news_tag_name) VALUES
	('Information'),
	('Useless');

SET @news_tag_information = (SELECT news_tag_id FROM news_tag WHERE news_tag_name = 'Information');
SET @news_tag_useless = (SELECT news_tag_id FROM news_tag WHERE news_tag_name = 'Useless');

INSERT INTO news (user_id, news_title, news_title_url, news_content, news_date, news_publish) VALUES
	(
		@user_riges, 'First news to do some tests', 'first_news_to_do_some_tests',
		'This is the first news, here i test <strong>html</strong> display and any other things, i dunno what to say so i will<br/>shut up :p',
		NOW(), TRUE
	),
	(
		@user_blackfox, 'Second news to do some tests', 'second_news_to_do_some_tests',
		'This is the second news, here i test <strong>html</strong> display and any other things, i dunno what to say so i will<br/>shut up :p',
		NOW(), FALSE
	)
  ;

SET @news_first = LAST_INSERT_ID();

INSERT INTO news__news_tag (news_id, news_tag_id) VALUES
	(@news_first, @news_tag_information),
	(@news_first, @news_tag_useless);
