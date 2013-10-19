<?php

class PageCache
{
	
	public $created_at;
	public $template;
	
	public function __construct($created_at, $template)
	{
		$this->created_at = $created_at;
		$this->template = $template;
	}
	
	public function __toString()
	{
		return $this->template;
	}
	public function isValid($expiration)
	{
		return ($this->created_at > $expiration);
	}
	
}