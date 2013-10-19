<?php

class RoutingHelper extends sfTemplateHelper implements sfTemplateHelperInterface
{
	private $request;
	
	public function __construct(Request $request)
	{
		$this->request = $request;
	}
	
	public function getName()
	{
		return 'routing';
	}
	
	public function url($url)
	{
		return $this->request->getScriptName() . $url;
	}
	
}