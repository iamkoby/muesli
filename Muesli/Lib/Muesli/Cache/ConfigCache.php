<?php

abstract class ConfigCache extends CacheManager
{
	private $cache;
	private $loader;
	
	public function __construct($cache_dir, YamlLoader $loader)
	{
		parent::__construct($cache_dir);
		$this->loader = $loader;
	}
	
	public function setConfigDir($dir)
	{
		$this->config_dir = $dir;
	}

	public function getCache()
	{
		if (!$this->cache){
			if (!$this->doesCacheExist())
				$this->createCache();
		
			$this->cache = include($this->getPathForCacheFile());
		}
		return $this->cache;
	}
	
	public function getPathForCacheFile()
	{
		return $this->getCacheDir() . '/' . $this->getFileName();
	}
	abstract public function getFileName();
	
	public function doesCacheExist()
	{
		return file_exists($this->getPathForCacheFile());	
	}
	public function createCache()
	{
		$config = $this->loader->load();
		$str = '<?php return ' . var_export($config,true) . ';';
		$this->save($this->getPathForCacheFile(), $str);
	}
}