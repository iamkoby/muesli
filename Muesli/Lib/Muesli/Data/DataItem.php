<?php

class DataItem
{
	private $address;
	private $identifier;
	private $configurations;
	private $parent;
	
	public static function createItem($address, $config, $parent=null)
	{
		$type = isset($config['type'])?$config['type']:'';
		$type = 'DataItem' . $type;
		return new $type($address, $config, $parent);
	}
	
	public function __construct($address, $config, $parent=null)
	{
		$this->address = $address;
		$this->configurations = $config;
		$this->parent = $parent;
		$this->init();
	}
	
	public function __call($name, $arguments){}
	
	public function __toString()
	{
		return '';
	}
	
	public function init(){}
	
	
	public function isRootItem()
	{
		if (!$this->getParent()) return true;
		return false;
	}
	public function getParent()
	{
		return $this->parent;
	}
	public function getAddress()
	{
		return $this->address;
	}
	public function getParentAddress()
	{
		$parent = $this->getParent();
		if (!$parent) return false;
		return $parent->getAddress();
	}
	public function getItemIdentifier()
	{
		if (!$this->identifier){
			$address = $this->getAddress();
			$parts = explode('/', $address);
			if (!$parts) return false;
			$this->identifier = end($parts);
		}
		return $this->identifier;
	}
	
	public function getConfigurations()
	{
		return $this->configurations;
	}
	public function hasConfiguration($parameter, $holder=null)
	{
		if (!$holder) $holder = $this->configurations;
		if (!$holder) return false;
		return isset($holder[$parameter]);
	}
	public function getConfiguration($parameter, $default=null, $holder=null)
	{
		if (!$this->hasConfiguration($parameter, $holder)) return $default;
		if (!$holder) $holder = $this->configurations;
		return $holder[$parameter];
	}
	public function getChildren()
	{
		return array();
	}
	public function getUneditableChildren()
	{
		return array();
	}
	
	//Admin:
	public function getTitle()
	{
		return $this->getConfiguration('title');
	}

	public function hasAdminConfiguration()
	{
		return $this->hasConfiguration('admin');
	}
	public function getAdminConfiguration($parameter, $default=null)
	{
		$admin = $this->getConfiguration('admin');
		if (!$admin) return false;
		return $this->getConfiguration($parameter, $default, $admin);
	}
	public function getAdminTitle()
	{
		$title = $this->getAdminConfiguration('title');
		if (!$title) $title = $this->getTitle();
		return $title;
	}
	public function getAdminDescription()
	{
		return $this->getAdminConfiguration('description');
	}


	public function getFullPath() //TODO: check necessity
	{
		$path = array($this);
		$item = $this;
		while ($item = $item->getParent()){
			array_unshift($path, $item);
		}
		return $path;
	}
	
	//Admin configuration:
	public function canEdit(){
		return false;
	}
	public function canAdmin(){
		return false;
	}

	//Admin templates configuration:
	public function getAdminTemplatesDir()
	{
		return 'DataItems/';
	}
	public function getAdminDefaultTemplatesDir()
	{
		return $this->getAdminTemplatesDir() . 'Default/';
	}
	public function getItemTemplatesDir()
	{
		return $this->getAdminTemplatesDir() . get_class($this) . '/';
	}

	public function hasBarBlock(){}
	public function getBarBlockTemplate()
	{
		return $this->getItemTemplatesDir() . 'BarBlock';
	}
	public function hasHeaderBlock(){}
	public function getHeaderBlockTemplate()
	{
		return $this->getItemTemplatesDir() . 'HeaderBlock';
	}
	public function hasViewContentBlock(){}
	public function getViewContentBlockTemplate()
	{
		if ($this->hasViewContentBlock())
			return $this->getItemTemplatesDir() . 'ViewContentBlock';
		return $this->getAdminDefaultTemplatesDir() . 'ViewContentBlock';
	}
	public function hasEditBarBlock(){}
	public function getEditBarBlockTemplate()
	{
		if ($this->hasEditBarBlock())
			return $this->getItemTemplatesDir() . 'EditBarBlock';
		return $this->getAdminDefaultTemplatesDir() . 'EditBarBlock';
	}
	public function getEditContentBlockTemplate()
	{
		return $this->getItemTemplatesDir() . 'EditContentBlock';
	}
	public function hasTitleBar(){}
	public function getTitleBarTemplate()
	{
		if ($this->hasTitleBar())
			return $this->getItemTemplatesDir() . 'TitleBar';
		return $this->getAdminDefaultTemplatesDir() . 'TitleBar';
	}
	

	//Data:
	public function delete(){}
	public function save(){}
	
	//Form:
	public function getItemForm()
	{
		$form_name = $this->getItemIdentifier();
		if (!isset($_POST[$form_name])) return false;
		return $_POST[$form_name];
	}
	protected static function escapeValue($value)
	{
		return htmlspecialchars(trim($value));
	}
}
