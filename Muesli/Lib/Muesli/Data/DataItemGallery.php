<?php 

class DataItemGallery extends DataItemArray
{
	public function getPictures()
	{
		return $this->getChildren();
	}
	
	public function getItemTemplatesDir()
	{
		return $this->getAdminTemplatesDir() . 'DataItemArray/';
	}
	
	public function hasViewContentBlock()
	{
		return true;
	}
	public function getViewContentBlockTemplate()
	{
		return $this->getAdminTemplatesDir() . get_class($this) . '/ViewContentBlock';
	}
	
	protected function getChildrenTree()
	{
		return array('children'=>array('picture'=>array('type'=>'Picture', 'versions'=>$this->getConfiguration('versions'))));
	}
	
	public function getAdminSenior()
	{
		return 'picture';
	}
}