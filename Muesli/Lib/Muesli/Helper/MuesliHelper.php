<?php

class MuesliHelper
{
	private $page;
	private $site;
	
	public function __construct($page, $site)
	{
		$this->page = $page;
		$this->site = $site;
	}
	
	
	protected function setPage($page_name)
	{
		if (!$page_name || $page_name == $this->page->getName()) return;
		$this->page = $this->site->getPage($page_name);
	}
	public function getPage($page_name=null)
	{
		if ($page_name && $page_name != $this->page->getName()) 
			return $this->site->getPage($page_name);
		else
			return $this->page;
	}
	
	public function isOnPage($page_name)
	{
		return $page_name == $this->page->getName();
	}
	
	public function getPageTitle($page_name=null)
	{
		$page = $this->getPage($page_name);
		if (!$page) return false;
		return $page->getTitle();
	}
	
	public function get($key, $page_name=null)
	{
		$page = $this->getPage($page_name);
		if (!$page) return null; 
		return $page->get($key);
	}
		
	public function itemWithId($key, $id, $page_name=null)
	{
		$array = $this->get($key, $page_name);
		if (!($array instanceof DataItemArray)) return null;
		
		$item = $array->getObjectWithId($id);
		if (!$item) return null;
		return $item;
	}
	public function itemWithName($key, $name, $page_name=null)
	{
		$array = $this->get($key, $page_name);
		if (!($array instanceof DataItemArray)) return null;
		
		$item = $array->getObjectWithName($name);
		if (!$item) return null;
		return $item;
	}
	
	public function query(MuesliHelperArrayCriteria $criteria)
	{
		if (!$criteria) return false;
		return $criteria->doQuery();
	}
	
	public function newCriteria($key, $page_name=null)
	{
		$object = $this->getPage($page_name);
		return new MuesliHelperArrayCriteria($key, $object);
	}	
	public function autoCriteria($key, $page_name=null)
	{
		$crit = $this->newCriteria($key, $page_name);
		if (isset($_GET['_o']))
			$crit->setOffset($_GET['_o']);
		return $crit;
	}
	
	//Synonyms:
	public function __invoke($key, $page_name=null)
	{
		return $this->get($key, $page_name);
	}
	public function item($key, $page_name=null)
	{
		return $this->get($key, $page_name);
	}

}