<?php

class Route
{
	
	private $name;
	private $options;
	private $vars;
	
	public function __construct($name, $options, $vars=array())
	{
		$this->name = $name; 
		$this->options = $options;
		$this->vars = $vars;
	}
	
	public function setRoutingVariables($vars)
	{
		$this->vars = $vars;
	}
	public function getRoutingVariables()
	{
		return array_merge($this->options, $this->vars);
	}
	
	
	public function getLanguage()
	{
		if (isset($this->options['lang'])) return $this->options['lang'];
		elseif (isset($this->vars['lang'])) return $this->vars['lang'];
		else return false;
	}
	
	public function hasPage()
	{
		return isset($this->options['page']) || isset($this->vars['page']);
	}
	public function getPage()
	{
		if (isset($this->options['page'])) return $this->options['page'];
		elseif (isset($this->vars['page'])) return $this->vars['page'];
		else return false;
	}
	
	public function getController()
	{
		if (isset($this->options['controller'])) return $this->options['controller'];
		elseif (isset($this->vars['controller'])) return $this->vars['controller'];
		else return false;
	}
	
	public function getAction()
	{
		if (isset($this->options['action'])) return $this->options['action'];
		elseif (isset($this->vars['action'])) return $this->vars['action'];
		else return false;
	}

}