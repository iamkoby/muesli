<?php

class DataItemLink extends DataItem {
	
	private $title = '';
	private $href = '';
	private $target = 0;
	private $dbrow_exists;
	
	public function init()
	{
		$dbrow = Database::getEditableOfTypeWithAddress('link', $this->getAddress());
		if ($dbrow){
			$this->dbrow_exists = true;
			$this->title = $dbrow['title'];
			$this->href = $dbrow['href'];
			$this->target = $dbrow['target'];
		}
	}
	
	public function __toString()
	{
		if (!$this->title && !$this->href) return '';
		$title = ($this->title) ? $this->title : $this->href;
		$target = $this->getTarget();
		if ($target) $target = ' target="' . $this->getTarget() . '"';
		return '<a href="' . $this->getHref() . '"' . $target . '>' . $title . '</a>';
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	public function getHref()
	{
		return $this->href;
	}
	
	public function getTarget()
	{
		if ($this->shouldOpenInNewWindow())
			return '_blank';

		return '';
	}
	public function shouldOpenInNewWindow()
	{
		if (!$this->target) return true;
		return false;
	}
	
	//Admin:
	public function canEdit()
	{
		return true;
	}
	public function canEditTitle()
	{
		return $this->getAdminConfiguration('option_title' , true);
	}
	public function canEditHref()
	{
		return $this->getAdminConfiguration('option_link', true);
	}
	public function canUploadFile()
	{
		return $this->getAdminConfiguration('option_file', false);
	}
	public function canEditTarget()
	{
		return $this->getAdminConfiguration('option_target', false);
	}
	
	//Data:
	public function save()
	{	
		$form = $this->getItemForm();
		$file=null;

		if ($this->canUploadFile()){
			//If file was uploaded:
			$file = $_FILES[$this->getItemIdentifier()];
			if ($file['error'] != 4){	//File Upload
				//check that there was no error:
				if ($file['error'] != UPLOAD_ERR_OK) throw new Exception('There was an error uploading file. code: ' . $file['error']);
				//Construct file name:
				$dot_location = strrpos($file['name'], '.');
				if ($dot_location)
					$file_ext = substr($file['name'], $dot_location);
				else 
					$file_ext = '';
				$new_file =  md5($this->getAddress()) . $file_ext;
				//Save the file with its new name:
				move_uploaded_file($file['tmp_name'], MuesliConfiguration::getUploadsDir() . '/' . $new_file);
				$form['href'] = '/uploads/' . $new_file;
			}
		}
		if (isset($form['title'])){
			$form['title'] = self::escapeValue($form['title']);
		}
		
		if ($this->dbrow_exists){
			$sql = 'UPDATE `link` SET `updated_at`=' . time(); 
			if (isset($form['title']) && $form['title']) $sql .= ',`title`="' . $form['title'] . '"';
			if (isset($form['href']) && $form['href']) $sql .= ',`href`="' . $form['href'] . '"';
			if (isset($form['target'])) $sql .= ', `target`=1'; else $sql .= ', `target`=0';
			$sql .= ' WHERE `address`="' . $this->getAddress() . '";';
		} else {
			$sql = 'INSERT INTO `link` (`address`, `updated_at`';
			$values = ') VALUES ("' . $this->getAddress() . '",' . time();
			if (isset($form['title']) && $form['title']){
				$sql .= ',`title`';
				$values .= ', "' . $form['title'] . '"';
			}
			if (isset($form['href']) && $form['href']){
				$sql .= ', `href`';
				$values .= ', "' . $form['href'] . '"';
			}
			$sql .= ', `target`';
			if (isset($form['target']))
				$values .= ', 1';
			else 
				$values .= ', 0';
			$sql .= $values . ');';
		}

		return Database::query($sql);
	}
	
	public function delete()
	{
		return Database::query('DELETE FROM `link` WHERE `address` = "' . $this->getAddress() .'" LIMIT 1;');
	}

}
