<?php

class Fanficts
{
	public static function getId($user, $title)
	{
		
	}
	
	public static function getOne($id)
	{
		return $g_database->fetchOne(
			'SELECT * FROM fanfict INNER JOIN user ON user.id = fanfict.user_id WHERE user_id = %',
			$id);
	}

	public static function deleteOne($id)
	{
		global $g_database;
		$g_database->query('DELETE %', $id);
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