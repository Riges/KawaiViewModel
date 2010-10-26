CREATE TABLE user (
	user_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_login VARCHAR(255) NOT NULL, -- Should be in URI form : no special char or space
	user_full_name VARCHAR(255) NOT NULL,
	user_mail VARCHAR(255) NOT NULL,
	user_avatar_url VARCHAR(255) DEFAULT NULL,
	user_password_nonce INT(32) NOT NULL, -- Generate with FLOOR(RAND() * 4294967296)
	user_password_md5 VARCHAR(32) NOT NULL, -- Result of MD5(CONCAT(user_password_nonce, user_password))
	user_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE session (
	user_id INT(10) UNSIGNED NOT NULL
		REFERENCES user.user_id
			ON DELETE CASCADE
			ON UPDATE CASCADE,
	session_key VARCHAR(32) NOT NULL,
	session_start_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE group_ (
	group_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	group_name VARCHAR(255) NOT NULL,
	group_description TEXT NOT NULL
) ENGINE = InnoDB;

CREATE TABLE user__group (
	user_id INT(10) UNSIGNED NOT NULL
		REFERENCES user.user_id
			ON DELETE CASCADE
			ON UPDATE CASCADE,
	group_id INT(10) UNSIGNED NOT NULL
		REFERENCES group_.group_id
			ON DELETE CASCADE
			ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE right_ (
	right_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	right_name VARCHAR(255) NOT NULL, -- Used in the source code
	right_description TEXT NOT NULL -- Displayed to the user
) ENGINE = InnoDB;

CREATE TABLE user__right (
	user_id INT(10) UNSIGNED NOT NULL
		REFERENCES user.user_id
			ON DELETE CASCADE
			ON UPDATE CASCADE,
	right_id INT(10) UNSIGNED NOT NULL
		REFERENCES right_.right_id
			ON DELETE CASCADE
			ON UPDATE CASCADE,
	user__right_value ENUM('allow', 'deny') DEFAULT 'deny' NOT NULL
) ENGINE = InnoDB;

CREATE TABLE group__right (
	group_id INT(10) UNSIGNED NOT NULL
		REFERENCES group_.group_id
			ON DELETE CASCADE
			ON UPDATE CASCADE,
	right_id INT(10) UNSIGNED NOT NULL
		REFERENCES right_.right_id
			ON DELETE CASCADE
			ON UPDATE CASCADE,
	group__right_value ENUM('allow', 'deny') DEFAULT 'deny' NOT NULL
) ENGINE = InnoDB;

CREATE TABLE news (
	news_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id INT(10) UNSIGNED NOT NULL
		REFERENCES user.user_id
			ON DELETE CASCADE
			ON UPDATE CASCADE,
	news_title VARCHAR(255) NOT NULL,
	news_title_url VARCHAR(255) NOT NULL,
	news_content TEXT NOT NULL,
	news_date DATETIME NOT NULL,
	news_publish BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE = InnoDB;

CREATE TABLE news_tag (
	news_tag_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	news_tag_name VARCHAR( 255 ) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE news__news_tag (
	news_id INT(10) NOT NULL
		REFERENCES news.news_id
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	news_tag_id INT(10) NOT NULL
		REFERENCES newstags.newstags_id
					ON DELETE CASCADE
					ON UPDATE CASCADE
) ENGINE = InnoDB;
/*
CREATE TABLE fanficts (
	fanfict_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	fanfict_title VARCHAR( 255 ) NOT NULL ,
	fanfict_author_id INT(10) UNSIGNED NOT NULL
		REFERENCES users.user_id
			ON DELETE CASCADE
			ON UPDATE CASCADE,
	fanfict_creation_date DATE NOT NULL,
	INDEX(fanfict_author_id)
) ENGINE = InnoDB;*/

