<?php

class UserHelper extends sfTemplateHelper implements sfTemplateHelperInterface 
{
	public function __construct(User $user)
	{
		$this->user = $user;
	}
	
	public function getName()
	{
		return 'user';
	}
	
	public function isAuthenticated()
	{
		return $this->user->isAuthenticated();
	}
	public function hasPermission($permission)
	{
		return $this->user->hasPermission($permission);
	}
	public function getAttribute($attribute)
	{
		return $this->user->getAttribute($attribute);
	}
	public function getFlash($parameter, $default=null)
	{
		return $this->user->getFlash($parameter, $default);
	}
	
	public function getUserName()
	{
		return ucfirst($this->getAttribute('user_name'));
	}
}