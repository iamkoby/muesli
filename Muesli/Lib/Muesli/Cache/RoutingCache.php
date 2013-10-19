<?php

class RoutingCache extends ConfigCache
{
	public function getRoutes()
	{
		return $this->getCache();
	}
	public function getFileName()
	{
		return 'routing.php';
	}
}