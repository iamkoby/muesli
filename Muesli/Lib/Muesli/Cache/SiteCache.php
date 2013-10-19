<?php

class SiteCache extends ConfigCache
{
	public function getArrayOfPagesNames()
	{
		return array_keys($this->getCache());
	}
	public function getPage($page_name)
	{
		$site = $this->getCache();
		if (!isset($site[$page_name])) return false;
		return new Page($page_name, $site[$page_name]);
	}
	public function getFileName()
	{
		return 'site.php';
	}
}