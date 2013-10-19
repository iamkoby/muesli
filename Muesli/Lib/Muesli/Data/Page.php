<?php

class Page extends DataItemParent 
{
	protected $base_configurations = array('_pageTitle'=>array('admin'=>array('title'=>'כותרת העמוד'), 'type'=>'Text'),
											'_pageMetaDescription'=>array('admin'=>array('title'=>'תיאור העמוד'), 'type'=>'Text'),
											'_pageMetaKeywords'=>array('admin'=>array('title'=>'מילות מפתח'), 'type'=>'Text'));

	public function getName()
	{
		return $this->getAddress();
	}
	
	public function shouldCache()
	{
		return $this->getConfiguration('cache', true); 
	}
	
	public function hasEditableObjects()
	{
		return $this->hasChildren();
	}
	
	public function getTitle()
	{
		//Page title is saved as editable with address: _pageTitle
		return (string)$this->get('_pageTitle');
	}
	public function getMetaDescription()
	{
		return (string)$this->get('_pageMetaDescription');  
	}
	public function getMetaKeywords()
	{
		return (string)$this->get('_pageMetaKeywords');
	}
	
	public function hasAction()
	{
		return $this->hasConfiguration('controller') && $this->hasConfiguration('action');
	}
	public function getController()
	{
		return $this->getConfiguration('controller');
	}
	public function getAction()
	{
		return $this->getConfiguration('action');
	}	
	public function getTemplate()
	{
		return $this->getConfiguration('template');
	}
	
	public function canEditMeta()
	{
		return (bool) $this->getAdminConfiguration('meta',false);
	}
	

	//Admin:
	public function hasBarBlock()
	{
		return true;
	}
	public function hasHeaderBlock()
	{
		return true;
	}
	
}