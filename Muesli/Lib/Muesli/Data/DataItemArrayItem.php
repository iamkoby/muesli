<?php

class DataItemArrayItem extends DataItemParent
{
	private $id;
	private $name;
	
	public function __construct($address, $config, $parent, $id, $name)
	{
		parent::__construct($address, $config, $parent);
		
		$this->id = $id;
		$this->name = $name;
	}
	
	public function getId()
	{
		return $this->id;
	}
	public function getName()
	{
		if ($this->name)
			return $this->name;
		else
			return $this->id;
	}
	
	//Admin:
	public function getAdminTitle()
	{
		return $this->getId();
	}
	public function getFullTitle()
	{
		$title = $this->getId();
		if ($this->name) $title .= ' (' . $this->name . ')';
		return $title . ':';
	}
	public function getAdminSenior()
	{
		return $this->getParent()->getAdminSenior();
	}
	public function getViewContentBlockTemplate()
	{
		if ($this->hasViewContentBlock())
			return $this->getAdminTemplatesDir() . 'DataItemParent/ViewContentBlock';
		return $this->getAdminDefaultTemplatesDir() . 'ViewContentBlock';
	}
	public function hasTitleBar()
	{
		return true;
	}
	public function hasEditBarBlock()
	{
		return true;
	}
	public function getEditContentBlockTemplate()
	{
		return $this->getAdminTemplatesDir() . 'DataItemParent/EditContentBlock';
	}
	
	
	//Data:
	public function delete()
	{
		//First, delete the children:
		parent::delete();
		
		return Database::deleteItemInArrayWithId($this->getId());
	}
}