<?php

class Fanficts
{
    private static $database;

    public static function setDatabase($db)
    {
        self::$database = $db;
    }

	public static function getId($user, $title)
	{
		
	}
	
	public static function getOne($id)
	{
		return self::$database->fetchOne(
			'SELECT * FROM fanfict INNER JOIN user ON user.id = fanfict.user_id WHERE user_id = %',
			$id);
	}

	public static function deleteOne($id)
	{
        self::$database->query('DELETE %', $id);
	}
	
	public static function getLast($count)
	{
		
	}
	
	public static function getBetterVotes($count)
	{
		
	}
	
	public static function getMostActive($count)
	{
		
	}
}

?>