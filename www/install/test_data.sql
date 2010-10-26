INSERT INTO user (user_login, user_full_name, user_mail, user_password_nonce, user_password_md5) VALUES
	(
		'longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname_longname',
		'i_have_a_long_name_!i_have_a_long_name_!i_have_a_long_name_!i_have_a_long_name_!i_have_a_long_name_!i_have_a_long_name_!i_have_a_long_name_!i_have_a_long_name_!i_have_a_long_name_!i_have_a_long_name_!i_have_a_long_name_!i_have_a_long_name_!',
		'mail_mail_mail_mail_mail_mail_mail_mail_mail_mail_mail_mail_mail_mail_mail_mail_mail_@domain_domain_domain_domain_domain_domain_domain_domain_domain_domain.tld', 0, '0'),
	('htmlname', 'My name<script>alert("Javascript injection via name");</script>', '<script>alert("Javascript injection via mail");</script>mail@domain.tld', 0, '0'),
	('specialname', "><\"'`�&=}$���%", "><\"'`�&=\t\n\r}$���%@�0.-*/", 0, '0'),
	('urlname/my/login/look/like/an/url', "This should be impossible to create me, but if i exist i should work", "love@url.us", 0, '0');