<?php
class Users
{
	public static function getList()
	{
		global $g_database;
		return $g_database->fetchAll("SELECT * FROM user");
	}

	public static function getCount()
	{
		global $g_database;
		return $g_database->fetchOne("SELECT COUNT(*) FROM user");
	}

	public static function getUserAsArray($user_login)
	{
		global $g_database;
		return $g_database
			->query("SELECT * FROM user where user_login = ?", $user_login)
			->fetch(Zend_Db::FETCH_ASSOC);
	}

	public static function exists($user_login)
	{
		global $g_database;
		$result = $g_database->fetchOne("SELECT user_id FROM user WHERE user_login = ?", $user_login);
		return ($result !== FALSE);
	}

	public static function getId($user_login)
	{
		global $g_database;
		$result = $g_database->fetchOne("SELECT user_id FROM user WHERE user_login = ?", $user_login);
		if ($result === FALSE) throw new Knb_Error("User with login $user_login doesn't exist.");
		return $result;
	}
	
	public static function updateOneWithoutPassword($user_id, $params)
	{
		global $g_database;
		$g_database->query("UPDATE user
			SET user_login = ?, user_full_name = ?, user_mail = ? WHERE user_id = ?",
			array(
				$params['user_login'], $params['user_full_name'], $params['user_mail'],
				$user_id
				)
			);
	}

	public static function updateOneWithPassword($user_id, $params, $password)
	{
		global $g_database;
		$g_database->query("SET @seed = FLOOR(RAND() * 4294967296)");
		$g_database->query("UPDATE user
			SET user_login = ?, user_full_name = ?, user_mail = ?, user_password_nonce = @seed, user_password_md5 = MD5(CONCAT(@seed, ?))
			WHERE user_id = ?",
			array(
				$params['user_login'], $params['user_full_name'], $params['user_mail'], $password,
				$user_id
				)
			);
	}
	
	public static function deleteOne($user_id)
	{
		global $g_database;
		$g_database->query("DELETE FROM user WHERE user_id = ?", $user_id);
	}

	public static function create($params)
	{
		global $g_database;
		$g_database->query("SET @seed = FLOOR(RAND() * 4294967296)");
		$g_database->query("INSERT INTO user
			(user_login, user_full_name, user_mail, user_password_nonce, user_password_md5)
			VALUES (?, ?, ?, @seed, MD5(CONCAT(@seed, ?)))",
			array(
				$params['user_login'], $params['user_full_name'], $params['user_mail'], $params['user_password']
				)
			);

	}
}
?>
