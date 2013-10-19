<?php

class UsersController extends Controller
{
	public function preExecute()
	{
		if (!$this->isAuthenticated()) return $this->redirect('/Main/login');
	}
	
	public function indexAction()
	{
		$users = UserPeer::getUsers();
		return $this->render('usersIndex', array('users'=>$users));
	}
	
	public function newAction()
	{
		return $this->render('usersNew');
	}
	
	private function isEqualToConnectedUser($user_id)
	{
		return $this->getUser()->getAttribute('user_id') == $user_id;
	}
	
	public function saveAction()
	{
		$user_id = $this->getRequestParameter('user_id');
		if ($user_id){
			UserPeer::updateUser($user_id, $this->getRequestParameter('name'), $this->getRequestParameter('username'), $this->getRequestParameter('password'));
		} else {
			$user_id = UserPeer::newUser($this->getRequestParameter('name'), $this->getRequestParameter('username'), $this->getRequestParameter('password'));
		}
		$this->redirect("/Users");
	}
	
	public function deleteAction()
	{
		$id = $this->getRequestParameter('id');
		$user = UserPeer::getUserById($id);
		if (!$user) throw new Exception("Couldn't find user with id: $id.", 404);
		return $this->render('usersDelete', array('user'=>$user));
	}
	public function deleteDoAction()
	{
		$user_id = $this->getRequestParameter('user_id');
		if (!$user_id) throw new Exception('Some required parameters are missing: user_id.');
		
		UserPeer::deleteUser($user_id);
		if ($this->isEqualToConnectedUser($user_id)){
			$this->getUser()->clear();
		}
		return $this->redirect('/Users');
	}
	
	public function editAction()
	{
		$id = $this->getRequestParameter('id');
		$user = UserPeer::getUserById($id);
		if (!$user) throw new Exception("Couldn't find user with id: $id.", 404);
		return $this->render('usersEdit', array('user'=>$user));
	}
}