<?php

class DataItemWysiwyg extends DataItem {

	private $html = '';
	private $dbrow_exists;
	
	public function init()
	{
		$dbrow = Database::getEditableOfTypeWithAddress('wysiwyg', $this->getAddress());
		if ($dbrow){
			$this->dbrow_exists = true;
			$this->html = $dbrow['html'];
		}
	}
	
	public function __toString()
	{
		return $this->getHTML();
	}
	
	public function getHTML()
	{
		return $this->html;
	}
	public function noTags()
	{
		return strip_tags($this->getHTML());
	}
	
	//Admin:
	public function canEdit()
	{
		return true; 
	}
	public function getTextDirection()
	{
		return $this->getAdminConfiguration('text_direction','');
	}
	public function hasViewContentBlock()
	{
		return true;
	}
	
	//Form:
	protected static function escapeValue($value)
	{
		return stripslashes($value);
	}
	private function getFormValue()
	{
		$form = $this->getItemForm();
		if (!isset($form['value'])) return false;
		return self::escapeValue($form['value']);
	}
	
	//Data:
	public function save()
	{
		$value = $this->getFormValue();
		if ($value===false) return false;
		if ($this->dbrow_exists)
			$sql = 'UPDATE `wysiwyg` SET `html`="' . mysql_real_escape_string($value) . '", `updated_at` = ' . time() . ' WHERE `address`="' . $this->getAddress() . '";';
		else
			$sql = 'INSERT INTO `wysiwyg` (`address`, `html`, `updated_at`) VALUES ("' . $this->getAddress() . '", "' . mysql_real_escape_string($value) . '", ' . time() . ');';
		return Database::query($sql);
	}
	
	public function delete()
	{
		return Database::query('DELETE FROM `wysiwyg` WHERE `address` = "' . $this->getAddress() .'" LIMIT 1;');
	}
}
