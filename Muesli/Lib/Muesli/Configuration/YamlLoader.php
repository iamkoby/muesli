<?php

abstract class YamlLoader
{
	private $config_dir;
	
	public function __construct($config_dir)
	{
		$this->config_dir = $config_dir;
	}
	
	abstract public function getFileName();
	
	public function getFilePath()
	{
		return $this->config_dir . '/' . $this->getFileName();
	}
	
	public function load()
	{
		if (!file_exists($this->getFilePath())) return array();
		return sfYaml::load($this->getFilePath());
	}
}