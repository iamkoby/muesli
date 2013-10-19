<?php

class ApplicationConfiguration
{
	private $name;
	private $application_dir;
	
	public function __construct($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getDir()
	{
		return $this->application_dir;
	}
	public function setDir($dir)
	{
		$this->application_dir = $dir;
	}
}