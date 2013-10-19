<?php

class MuesliHelperArrayCriteriaResult extends DataItemArray 
{
	private $criteria;
	private $full_count = 0;
	
	public function __construct($address, $config, $parent, $criteria)
	{
		$this->setCriteria($criteria);
		parent::__construct($address, $config, $parent);
	}
	
	public function init()
	{
		if (!$this->criteria) parent::init();
		
		$result = Database::getItemsInArrayWithCriteria('array',$this->getAddress(), $this->criteria);
		if ($result){
			$this->array = $result;
			$this->full_count = Database::getCountOfItemsInArray('array', $this->getAddress(), $this->criteria);
		}
	}
	
	public function getCriteria()
	{
		return $this->criteria;
	}
	public function setCriteria(MuesliHelperArrayCriteria $criteria)
	{
		$this->criteria = $criteria;
	}
	public function getLimit()
	{
		return $this->getCriteria()->getLimit();
	}
	public function getOffset()
	{
		return $this->getCriteria()->getOffset();
	}
	
	//Pagination functions:
	public function getNumberOfPages()
	{
		if (!$this->getLimit()) return 1;
		$count = ceil($this->full_count / $this->getLimit());
		if (!$count) $count = 1;
		return $count; 
	}	
	public function needsPagination()
	{
		return $this->getNumberOfPages() > 1;
	}
	public function getCurrentPage()
	{
		if (!$this->getLimit()) return 1;
		return floor($this->getOffset() / $this->getLimit())+1;
	}
	public function isFirstPage()
	{
		return $this->getOffset() == 0;
	}
	public function isLastPage()
	{
		return $this->getOffset() + $this->getLimit() >= $this->full_count;
	}
	public function prevOffset()
	{
		$offset = $this->getOffset() - $this->getLimit();
		if ($offset<0) $offset = 0;
		return $offset;
	}
	public function nextOffset()
	{
		$offset = $this->getOffset()+$this->getLimit();
		if ($offset > $this->full_count) $offset = ($this->getNumberOfPages()-1)*$this->getLimit();
		return $offset;
	}
}