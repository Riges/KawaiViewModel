<?php
class News
{
    private static $database;

    public static function setDatabase($db)
    {
        self::$database = $db;
    }

	public static function getList($publish = false)
	{
		if($publish)
		{
			$rows = self::$database->fetchAll("SELECT *, unix_timestamp(news_date) as unixDate FROM news INNER JOIN user ON news.user_id = user.user_id WHERE news.news_publish=1 ORDER BY news.news_date DESC");
		}
		else
		{
			$rows = self::$database->fetchAll("SELECT *, unix_timestamp(news_date) as unixDate  FROM news INNER JOIN user ON news.user_id = user.user_id ORDER BY news.news_date DESC");
		}
		return $rows;
	}

	public static function getCount()
	{
		return self::$database->fetchOne("SELECT COUNT(*) FROM news");
	}
	
	public static function getNewsAsArray($year, $month, $day, $url_title)
	{
		$sql_query = <<<EOD
			SELECT *, unix_timestamp(news_date) as unixDate
				FROM news
					INNER JOIN user ON news.user_id = user.user_id
				WHERE
					news_title_url = ?
					AND date(news_date) = ?
EOD;
		return self::$database
			->query($sql_query, array($url_title, "$year-$month-$day"))
			->fetch(Zend_Db::FETCH_ASSOC);
	}
	
	public static function exists($_POST)
	{
		$result = self::$database->fetchOne("SELECT news_title_url FROM news WHERE news_title_url = ? and DATE(news_date) = DATE(FROM_UNIXTIME(?))", array($_POST["news_title_url"], $_POST["news_date"]));
		return ($result !== FALSE);
	}
	
	public static function create($params)
	{
		global $g_user;
        self::$database->query("INSERT INTO news
			(user_id, news_title, news_title_url, news_content, news_date, news_publish)
			VALUES (?, ?, ?, ?, FROM_UNIXTIME(?), ?)",
			array(
				$g_user->getId(), $params['news_title'], $params['news_title_url'], $params['news_content'], $params['news_date'], $params['news_publish']
				)
			);

	}
}
?>