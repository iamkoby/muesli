<?php

class TemplateEngine extends sfTemplateEngine 
{
	private $parameters=array();

	//TODO: check if this is really necessary:
	public function setParameters(array $parameters)
	{
		$this->parameters = $parameters;
	}
	public function embed($template, array $parameters = array())
	{
		$parameters = array_merge($this->parameters, $parameters);
		echo $this->render($template, $parameters);
	}
	
	
	private function htmlTagTitle($value)
	{
		return '<title>' . $value . '</title>';
	}
	private function htmlTagMetaKeywords($value)
	{
		return '<meta name="keywords" content="' . $value . '" />';
	}
	private function htmlTagMetaDescription($value)
	{
		return '<meta name="description" content="' . $value . '" />';
	}
	
	private function getMuesli()
	{
		return isset($this->parameters['muesli']) ? $this->parameters['muesli'] : false;
	}
	public function pageMeta()
	{
		$muesli = $this->getMuesli();
		if (!$muesli) return false;
		$str = '';
		$page = $muesli->getPage(); if (!$page) return false;
		if ($keywords = $page->getMetaKeywords())
			$str .= $this->htmlTagMetaKeywords($keywords);
		if ($description = $page->getMetaDescription())
			$str .= $this->htmlTagMetaDescription($description);
		return $str;
	}
	
	public function pageTitle($domain='', $default='')
	{
		$muesli = $this->getMuesli();
		if (!$muesli) return $this->htmlTagTitle($domain);
		
		$title = $muesli->getPageTitle();
		if ($title){
			if ($domain) $title = $domain . ' - ' . $title;
		} else $title = $default;
		return $this->htmlTagTitle($title);
	}
}