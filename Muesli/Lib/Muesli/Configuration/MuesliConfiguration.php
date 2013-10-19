<?php

class MuesliConfiguration
{
	private static $muesli_dir;
	private static $project_dir;
	private static $web_dir;
	private static $uploads_dir;
	private static $database_config;
	private static $email_config;
	
	public static function getMuesliDir()
	{
		if (!self::$muesli_dir)
			self::$muesli_dir = realpath(dirname(__FILE__).'/../../../');
		
		return self::$muesli_dir;
	}
	public static function setMuesliDir($dir)
	{
		self::$muesli_dir = $dir;
	}
	
	public static function getProjectDir()
	{
		if (!self::$project_dir)
			self::$project_dir = realpath(dirname(__FILE__).'/../../../../Project/');
		
		return self::$project_dir;
	}
	public static function setProjectDir($dir)
	{
		self::$project_dir = $dir;
	}
	
	public static function getWebDir()
	{
		if (!self::$web_dir)
			self::$web_dir = realpath(dirname(__FILE__).'/../../../../Web/');
		
		return self::$web_dir;
	}
	public static function setWebDir($dir)
	{
		self::$web_dir = $dir;
	}
	
	public static function getUploadsDir()
	{
		if (!self::$uploads_dir)
			self::$uploads_dir = self::getWebDir() . '/uploads';
	
		return self::$uploads_dir;
	}
	
	public static function setDatabaseConfiguration($config)
	{
		if (!is_array($config)) return false;
		self::$database_config = $config;
	}
	public static function getDatabaseConfiguration()
	{
		return self::$database_config;
	}
	
	public static function setEmailConfiguration($config)
	{
		if (!is_array($config)) return false;
		self::$email_config = $config;
	}
	public static function getEmailConfiguration()
	{
		return self::$email_config;
	}
}