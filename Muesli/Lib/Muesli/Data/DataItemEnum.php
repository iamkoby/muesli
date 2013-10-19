<?php

class DataItemEnum extends DataItem 
{
	private $value = '';
	private $dbrow_exists;
	
	public function init()
	{
		$dbrow = Database::getEditableOfTypeWithAddress('enum', $this->getAddress());
		if ($dbrow){
			$this->dbrow_exists = true;
			$this->value = $dbrow['value'];
		}
	}
	
	public function __toString()
	{
		return (string)$this->getStringRepresentation();
	}
	
	public function getValue()
	{
		return ($this->value) ? $this->value : $this->getDefaultValue();
	}
	
	public function getStringRepresentation()
	{
		$options =  $this->getOptions();
		if (!$options) return '';
		return $this->getConfiguration($this->getValue(), '', $options);
	}
	
	public function getDefaultValue()
	{
		return $this->getConfiguration('default', 0);
	}
	public function getOptions()
	{
		return $this->getConfiguration('options', array());
	}
	
	//Admin:
	public function canEdit()
	{
		return true;
	}
	
	//Form:
	protected function validateOptionExists($option)
	{
		if ($option === null) return false;
		
		$options =  $this->getOptions();
		if (!$options) return false;
		return isset($options[$option]);
	}
	
	//Data:
	public function save()
	{	
		$form = $this->getItemForm();
		$value = intval($form['value']);
		if (!$this->validateOptionExists($value)) return false;
		
		if ($this->dbrow_exists){
			$sql = 'UPDATE `enum` SET `value` = ' . $value . ', `updated_at`=' . time() . ' WHERE `address`="' . $this->getAddress() . '";';
		} else {
			$sql = 'INSERT INTO `enum` (`address`, `value`, `updated_at`) VALUES ("' . $this->getAddress() . '", ' . $value . ', ' . time() . ');';
		}

		return Database::query($sql);
	}
	
	public function delete()
	{
		return Database::query('DELETE FROM `enum` WHERE `address` = "' . $this->getAddress() .'" LIMIT 1;');
	}
	
}