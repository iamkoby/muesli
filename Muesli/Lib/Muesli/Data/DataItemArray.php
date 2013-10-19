<?php 

class DataItemArray extends DataItem implements Iterator  
{
	protected $instances = array();
	protected $array = array();
	protected $position = 0;	//for Iterator
	
	public function init()
	{
		$result = Database::getItemsInArrayWithAddress('array',$this->getAddress());
		if ($result)
			$this->array = $result;
	}
	
	public function __toString()
	{
		$str = '';
		for ($i=0; $i<count($this->array); $i++){
			$str .= $this->getObjectAtPosition($i);		
		}
		return $str;
	}
	
	public function count()
	{
		return count($this->array);
	}
	

	public function getAddressForChildWithId($id)
	{
		return $this->getAddress() . '/' . $id;
	}
	
	protected function getChildrenTree()
	{
		return array('children'=>$this->getConfiguration('children'));
	}
	
	public function getEmptyChild()
	{
		return $this->initiateObject(null, null);
	}
	public function createNewChild()
	{
		$child_id = Database::createNewItemInArrayWithAddress($this->getAddress());
		if (!$child_id) return false;
		return $this->initiateObject($child_id, null);
	}
	public function get($child)
	{
		$child_parts = explode('/', $child, 2);
		if (count($child_parts) == 1){
			//If $child is $this own child:
			return $this->getObjectWithId($child);
		} else {
			$parent = $this->get($child_parts[0]);
			return $parent->get($child_parts[1]);
		}
	}
	
	protected function doesHaveItemInPosition($position)
	{
		if (!$this->array) return false;
		return isset($this->array[$position]);
	}
	
	public function initiateObject($id, $name)
	{
		return new DataItemArrayItem($this->getAddressForChildWithId($id), $this->getChildrenTree(), $this, $id, $name);
	}
	public function getObjectAtPosition($position)
	{
		if (!$this->doesHaveItemInPosition($position)) return false;
		if (!isset($this->instances[$position])){
			$item = $this->array[$position];
			$this->instances[$position] = $this->initiateObject($item['id'], $item['name']);
		}
		return $this->instances[$position];
	}
	public function getObjectWithName($name)
	{
		if (!$name) return false;
		$position = $this->getPositionOfObjectWithName($name);
		if ($position === false) return false;
		return $this->getObjectAtPosition($position);
	}
	public function getPositionOfObjectWithName($name)
	{
		if (!$name) return false;
		foreach ($this->array as $position => $item) {
			if (($item['name'] && $item['name'] == $name) || !$item['name'] && $item['id'] == $name){
				return $position;
			}
		}
		return false;
	}
	public function getObjectWithId($id)
	{
		if (!$id) return false;
		$position = $this->getPositionOfObjectWithId($id);
		if ($position === false) return false;
		return $this->getObjectAtPosition($position);
	}
	public function getPositionOfObjectWithId($id)
	{
		if (!$id) return false;
		foreach ($this->array as $position => $item) {
			if ($item['id'] == $id){
				return $position;
			}
		}
		return false;
	}
	
	public function getIdOfItemInPosition($position)
	{
		if (!$this->doesHaveItemInPosition($position)) return false;
		$item = $this->array[$position];
		if (!isset($item['id'])) return false;
		return $item['id'];
	}
	
	public function first()
	{
		return $this->getObjectAtPosition(0);
	}
	public function firstId()
	{
		return $this->getIdOfItemInPosition(0);
	}
	public function randomItem()
	{
		$random = rand(0, count($this->array)-1);
		return $this->getObjectAtPosition($random);
	}
	public function randomId()
	{
		$random = rand(0, count($this->array)-1);
		return $this->getIdOfItemInPosition($random);
	}

	//Implementation of the Iterator interface for foreach:
	public function rewind(){ $this->position = 0; }
	public function current(){ return $this->getObjectAtPosition($this->position); }
	public function key(){ return $this->position; }
	public function next(){ ++$this->position; }
	public function valid(){ return $this->doesHaveItemInPosition($this->position); }
	
	//Admin:
	public function getChildren()
	{
		return $this;
	}
	public function getAdminSenior()
	{
		return $this->getAdminConfiguration('senior');
	}
	public function canAdmin()
	{
		return true;
	}
	public function canEditSorting()
	{
		return $this->getAdminConfiguration('sorting', true);
	}
	public function hasViewContentBlock()
	{
		return true;
	}
	public function hasBarBlock()
	{
		return true;
	}
	public function hasHeaderBlock()
	{
		return true;
	}

	//Database functions:
	public function deleteAll()
	{
		//TODO: (this is not correct as it's just delete their links)
		return Database::deleteAllItemsInArrayWithAddress('array',$this->getAddress());
	}
}