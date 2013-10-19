<?php

class Request
{
	private $path;
	private $route;
	private $parameters;
	private $initialized;
	
	public function getPath()
	{
		//TODO: To fix this - this doesn't work with: /muesli.php but only with  /muesli.php/
		if (!$this->path)
			if (isset($_SERVER['HTTP_X_REWRITE_URL'])){ //patch for iis:
				$_SERVER['HTTP_X_REWRITE_URL'] = str_replace($_SERVER['PHP_SELF'], '', $_SERVER['HTTP_X_REWRITE_URL']);
				if ($pos = strpos($_SERVER['HTTP_X_REWRITE_URL'], '?'))
					$this->path = substr($_SERVER['HTTP_X_REWRITE_URL'], 0, $pos);
				else $this->path = $_SERVER['HTTP_X_REWRITE_URL'];
			} else 
				$this->path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : $_SERVER['REQUEST_URI']);
		
		return $this->path;
	}
	public function getFullPath()
	{
		if (isset($_SERVER['HTTP_X_REWRITE_URL'])) return $_SERVER['HTTP_X_REWRITE_URL']; //Patch for iis
		
		$path = $this->getPath();
		if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) $path .= '?' . $_SERVER['QUERY_STRING'];
		return $path;
	}
	
	public function getScriptName()
	{
		return $_SERVER['SCRIPT_NAME'];
	}
	
	public function initialize()
	{
		if ($this->initialized) return;
		$this->parameters = array_merge($this->route->getRoutingVariables(), $_GET, $_POST);		
	}
	public function getParameter($parameter, $default=null)
	{
		$this->initialize();
		if (!isset($this->parameters[$parameter])) return $default;
		return $this->parameters[$parameter];
	}
	
	public function getParameters()
	{
		$this->initialize();
		return $this->parameters;
	}
	
	public function setRoute(Route $route)
	{
		$this->route = $route;
		if (!$route) return;
	}
	
	public function shouldTryCache()
	{
		if (isset($_GET['cache']) && !$_GET['cache'])
			return false;
		return true;
	}
	
	public function validateRequired($required)
	{
		if (!is_array($required)) $required = array($required);
		
		foreach ($required as $r){
			if (!$this->getParameter($r)) return false;
		}
		return true;
	}
	public function validateEmail($field_name)
	{
		$email = $this->getParameter($field_name);
		if (!$email) return false;
		return preg_match("/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/", $email);
	}
}