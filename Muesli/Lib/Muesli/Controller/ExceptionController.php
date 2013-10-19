<?php

class ExceptionController extends Controller
{
	public function exception404Action()
	{
		$this->getResponse()->setStatusCode(404);
		return $this->render('404');
	}
	
	public function exception500Action()
	{
		$this->getResponse()->setStatusCode(500);
		return $this->render('500');
	}
	
	public function exceptionDevAction(Exception $exception)
	{
		$this->getResponse()->setStatusCode(500);
		return $this->render('_exception', array('e'=>$exception));
	}
}