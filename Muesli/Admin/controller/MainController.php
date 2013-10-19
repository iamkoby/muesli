<?php

class MainController extends Controller
{
	public function indexAction()
	{
		if (!$this->isAuthenticated()) 
			return $this->loginAction();

		return $this->render('mainIndex');
	}
	
	public function loginAction()
	{
		return $this->render('mainLogin');
	}
	
	public function loginDoAction()
	{
		$user = UserPeer::getUserWithUsernameAndPassword($this->getRequestParameter('username'), $this->getRequestParameter('password'));
		if (!$user){
			$this->getUser()->setFlash('error','failed');
			return $this->redirect('/Main/login');	
		}
		$this->getUser()->setAuthenticated(true);
		$this->getUser()->setPermissions($user['permissions']);
		$this->getUser()->setAttribute('user_id', $user['id']);
		$this->getUser()->setAttribute('user_name', $user['name']);
		UserPeer::updateUserLastEntrance($user['id']);
		return $this->redirect('/');
	}
	
	public function logoutAction()
	{
		$this->getUser()->clear();
		return $this->redirect('/');
	}
}