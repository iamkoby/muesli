<?php

class DataItemBoolean extends DataItemEnum
{
	
	public function isTrue()
	{
		return (bool) $this->getValue();
	}
	
	public function getOptions()
	{
		return array(0=>'false', 1=>'true');
	}
	
	public function hasViewContentBlock()
	{
		return true;
	}

}