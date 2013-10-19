<?php

class MuesliController extends Controller
{
	private $muesliHelper;
	
	public function setCaching($shouldCache=true)
	{
		$this->getResponse()->setCaching($shouldCache);
	}
	public function setMuesliHelper(MuesliHelper $muesliHelper)
	{
		$this->muesliHelper = $muesliHelper;
	}
	public function getTemplateVariables()
	{
		return array('muesli'=>$this->muesliHelper);
	}
	
	public function templateAction($template)
	{
		$request = $this->getRequest();
		return $this->render($template, $request->getParameters());
	}
}