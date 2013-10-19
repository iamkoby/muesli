<?php

abstract class CacheManager
{
	private $cache_dir;
	
	public function __construct($cache_dir)
	{
		$this->cache_dir = $cache_dir;
	}
	public function getCacheDir()
	{
		return $this->cache_dir;
	}
	
	public function save($file, $content)
	{
		$dir = dirname($file);
		if (!is_dir($dir)){
			mkdir($dir, 0777, true);
			chmod($dir, 0777);
		}
		file_put_contents($file, $content);
	}
	
}