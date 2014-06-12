<?php
class Users
{
    private static $database;

    public static function setDatabase($db)
    {
        self::$database = $db;
    }

	public static function getList()
	{
		return self::$database->fetchAll("SELECT * FROM user");
	}

	public static function getCount()
	{
		return self::$database->fetchOne("SELECT COUNT(*) FROM user");
	}

	public static function getUserAsArray($user_login)
	{
		return self::$database
			->query("SELECT * FROM user where user_login = ?", $user_login)
			->fetch(Zend_Db::FETCH_ASSOC);
	}

	public static function exists($user_login)
	{
		$result = self::$database->fetchOne("SELECT user_id FROM user WHERE user_login = ?", $user_login);
		return ($result !== FALSE);
	}

	public static function getId($user_login)
	{
		$result = self::$database->fetchOne("SELECT user_id FROM user WHERE user_login = ?", $user_login);
		if ($result === FALSE) throw new Knb_Error("User with login $user_login doesn't exist.");
		return $result;
	}
	
	public static function updateOneWithoutPassword($user_id, $params)
	{
        self::$database->query("UPDATE user
			SET user_login = ?, user_full_name = ?, user_mail = ? WHERE user_id = ?",
			array(
				$params['user_login'], $params['user_full_name'], $params['user_mail'],
				$user_id
				)
			);
	}

	public static function updateOneWithPassword($user_id, $params, $password)
	{
        self::$database->query("SET @seed = FLOOR(RAND() * 4294967296)");
        self::$database->query("UPDATE user
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
        self::$database->query("DELETE FROM user WHERE user_id = ?", $user_id);
	}

	public static function create($params)
	{
		try
		{
			self::$database->beginTransaction();
			self::$database->query("SET @seed = FLOOR(RAND() * 4294967296)");
			self::$database->query("INSERT INTO user
				(user_login, user_first_name, user_full_name, user_mail, user_password_nonce)
				VALUES (?, ?, ?, ?, @seed)",
				array(
					$params['user_login'], $params['user_first_name'], $params['user_full_name'], $params['user_mail']
					)
				);
			$user_id = (int)self::$database->fetchOne("SELECT LAST_INSERT_ID()");
			self::$database->query("UPDATE user
				SET user_password_md5 = MD5(CONCAT(user_password_nonce, ?))
				WHERE user_id = ?",
				array(
					$params['user_password'],
					$user_id
					)
				);
			self::$database->commit();
		} catch (Exception $e) {
			self::$database->rollBack();
			return 0;
		}
		
		return $user_id;
	}
?>
