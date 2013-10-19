<?php

class DataItemText extends DataItem {

	private $text = '';
	private $dbrow_exists;
	
	public function init()
	{
		$dbrow = Database::getEditableOfTypeWithAddress('text', $this->getAddress());
		if ($dbrow){
			$this->dbrow_exists = true;
			$this->text = $dbrow['text'];
		}
	}
	
	public function __toString()
	{
		return $this->getText();
	}
	
	public function getText()
	{
		return $this->text;
	}
	public function truncate($length)
	{
		$text = $this->getText();
		if (mb_strlen($text, 'utf-8') <= $length)
			return $text;
			
		return mb_substr($text, 0, $length, 'utf-8') . '...';
	}
	
	public function canEdit()
	{
		return true;
	}
	
	private function getFormValue()
	{
		$form = $this->getItemForm();
		if (!isset($form['value'])) return false;
		return self::escapeValue($form['value']);
	}
	
	public function save()
	{
		$value = $this->getFormValue();
		if ($value===false) return false;
		if ($this->dbrow_exists)
			$sql = 'UPDATE `text` SET `text`="' . $value . '", `updated_at` = ' . time() . ' WHERE `address`="' . $this->getAddress() . '";';
		else
			$sql = 'INSERT INTO `text` (`address`, `text`, `updated_at`) VALUES ("' . $this->getAddress() . '", "' . mysql_real_escape_string($value) . '", ' . time() . ');';
		return Database::query($sql);
	}
	public function delete()
	{
		return Database::query('DELETE FROM `text` WHERE `address` = "' . $this->getAddress() .'" LIMIT 1;');
	}
}
