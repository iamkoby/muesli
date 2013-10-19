<?php

class DataItemParent extends DataItem 
{
	private $instances = array();
	protected $base_configurations;
	
	public function getBaseConfigurationForChild($child)
	{
		if (!isset($this->base_configurations[$child])) return null;
		return $this->base_configurations[$child];
	}
	private function getChildAddress($child)
	{
		return $this->getAddress() . '/' . $child;
	}
	public function getChildrenConfiguration($parent=null){
		return $this->getConfiguration('children',null,$parent);
	}
	public function getChildConfiguration($child)
	{
		$children = $this->getChildrenConfiguration();
		return $this->getConfiguration($child,null,$children);
		//$child_path = explode('/', $child, 2);
		//while ($child = array_shift($child_path)){
		//	$config = $this->getConfiguration($child,null,$children);
		//	if ($child_path) $children = $this->getChildrenConfiguration($config);
		//}
		//return $config;
	}
	public function hasChildren()
	{
		return (bool)$this->getConfiguration('children');
	}
	public function getChildren()
	{
		$children = array();
		$children_configuration = $this->getChildrenConfiguration();
		if ($children_configuration){
			foreach(array_keys($children_configuration) as $child){
				$children[] = $this->get($child);
			}
		}
		return $children;
	}
	public function getUneditableChildren()
	{
		$uneditable = array();
		$children = $this->getChildren();
		foreach ($children as $child){
			if (!$child->canEdit())
				$uneditable[] = $child;
		}
		return $uneditable;
	}
	
	public function get($child)
	{
		//If $child was already requested or it's not the child of $this:
		if (!isset($this->instances[$child])){
			$child_parts = explode('/', $child, 2);
			if (count($child_parts) == 1){
				//If $child is $this own child:
				$configuration = $this->getChildConfiguration($child);
				if (!$configuration) $configuration = $this->getBaseConfigurationForChild($child);
				if (!$configuration) return null;
				$this->instances[$child] = self::createItem($this->getChildAddress($child), $configuration, $this);
			} else {
				$parent = $this->get($child_parts[0]);
				return $parent->get($child_parts[1]);
			}
		}
		return $this->instances[$child];
	}
	
	public function __invoke($child)
	{
		return $this->get($child);
	}
	
	
	//Admin:
	public function canAdmin()
	{
		return true;
	}	
	public function canEdit()
	{
		return true;
	}
	public function hasViewContentBlock()
	{
		return true;
	}
	
	//Data:
	public function save()
	{
		foreach ($_POST as $child_name => $form){
			$child = $this->get($child_name);
			if (!$child) continue;
			$child->save();
		}
	}
	public function delete()
	{
		foreach ($this->getChildren() as $child){
			$child->delete();
		}
	}
	
}