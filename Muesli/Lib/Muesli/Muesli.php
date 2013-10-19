<?php

class Muesli
{
	public static $db=0;
	public static $start;
	
	public static function version()
	{
		return '1.2';
	}
	public static function acho()
	{
		$time = microtime() - self::$start;
		return 'DB: ' . self::$db . ', time: ' . $time;
	}
}