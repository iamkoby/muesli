<?php

class MuesliHelperArrayCriteria
{
	//TODO: This entire class needs serious rewriting
	private $limit;
	private $offset=0;
	private $key;
	private $object;
	private $filters = array();
	
	const COMPARE_EQUAL = 1;
	const COMPARE_LIKE  = 2;
	
	public function __construct($key, $object)
	{
		$this->key = $key;
		$this->object = $object;
	}
	
	public function getLimit()
	{
		return $this->limit;
	}
	public function setLimit($value)
	{
		if (!is_int($value)) return false;
		$this->limit = $value;
	}
	
	public function getOffset()
	{
		return $this->offset;
	}
	public function setOffset($offset)
	{
		$this->offset = (int)$offset;
	}
	
	public function getPageNumber()
	{
		return $this->offset / $this->limit + 1;
	}
	
	private function getConfiguration()
	{
		return $this->object->getChildConfiguration($this->key);
	}
	private function getChildrenConfiguration()
	{
		$config = $this->getConfiguration();
		if (!$config || !isset($config['children'])) return false;
		return $config['children'];
	}
	private function getChildConfiguration($child)
	{
		$children = $this->getChildrenConfiguration();
		if (!$children || !isset($children[$child])) return false;
		return $children[$child];
	}
	private function getTypeOfChild($child)
	{
		$child = $this->getChildConfiguration($child);
		if (!$child || !isset($child['type'])) return false;
		return $child['type'];
	}
	
	public function hasFilters()
	{
		return count($this->filters) > 0;
	}
	public function getFilters()
	{
		return $this->filters;
	}
	
	public function where($field, $value, $compare=null)
	{
		$type = $this->getTypeOfChild($field);
		if (!$type) return false;
		if (!isset($this->filters[$field])){
			$this->filters[$field] = array();
			$this->filters[$field]['type'] = strtolower($type);
		}
		$this->filters[$field]['value'] = $value;
		$this->filters[$field]['compare'] = ($compare) ? $compare : self::COMPARE_EQUAL;
	}
	
	public function doQuery()
	{
		$config = $this->object->getChildConfiguration($this->key);
		$address = $this->object->getAddress() . '/' . $this->key;
		$result = new MuesliHelperArrayCriteriaResult($address, $config, $this->object, $this);
		return $result;
	}
	
}