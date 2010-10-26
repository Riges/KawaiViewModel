<?php

class Path
{
	public static function combineWithAlternateRoot($path1, $path2, $root, $separator = '/')
	{
		if (strlen($path1) == 0) return $path2;
		if (strlen($path2) == 0) return $path1;
		
		if ($path2[0] == $separator)
		{
			return rtrim($root, $separator) . $separator . ltrim($path2, $separator);
		}
		else
		{
			$path1 = rtrim($path1, $separator);
			return $path1 . $separator . $path2;			
		}
	}
	
	public static function combine($path1, $path2, $separator = '/')
	{
		if (strlen($path1) == 0) return $path2;
		if (strlen($path2) == 0) return $path1;
		
		if ($path2[0] == $separator)
		{
			return $path2;
		}
		else
		{
			$path1 = self::rtrimSlashes($path1);
			return $path1 . $separator . $path2;			
		}
	}
	
	public static function combineAlways($path1, $path2, $separator = '/')
	{
		if (strlen($path1) == 0) return $path2;
		if (strlen($path2) == 0) return $path1;

		$path1 = self::rtrimSlashes($path1);
		$path2 = self::ltrimSlashes($path2);

		return $path1 . $separator . $path2;
	}
	
	/**
	 * Trim slashed on the right of a path.
	 */
	public static function rtrimSlashes($uri)
	{
		return rtrim($uri, '/');
	}
	
	/**
	 * Trim slashed on the left of a path.
	 */
	public static function ltrimSlashes($uri)
	{
		return ltrim($uri, '/');
	}
	
	/**
	 * Trim slashed on the right and left of a path.
	 */
	public static function trimSlashes($uri)
	{
		return trim($uri, '/');
	}

	/**
	 * Explode a path in all his components.
	 * '/test/me/with/some/path' => array('test', 'me', 'with', 'some', 'path')
	 */
	public static function explode($path)
	{
		$array = explode('/', self::trimSlashes($path));
		
		if ( (count($array) == 1) && ($array[0] == '') )
		{
			return array();
		}
		else
		{
			return $array;
		}
	}		
}
?>