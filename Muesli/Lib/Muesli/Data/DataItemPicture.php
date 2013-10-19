<?php

class DataItemPicture extends DataItem {

	private $dbrow_exists = false;
	private $filename = '';
	private $filetype = '';
	private $alt = '';
	private $title = '';
	
	public function init()
	{
		$dbrow = Database::getEditableOfTypeWithAddress('picture', $this->getAddress());
		if ($dbrow){
			$this->dbrow_exists = true;
			$this->filename = $dbrow['filename'];
			$this->filetype = $dbrow['filetype'];
			$this->alt = $dbrow['alt'];
			$this->title = $dbrow['title'];
		}
	}
	
	public function __toString()
	{
		return $this->html();
	}
	public function html($class='')
	{
		return $this->getHtmlForVersionWithClass($this->getFirstVersion(), $class);
	}
	public function version($version, $class='')
	{
		return $this->getHtmlForVersionWithClass($version, $class);
	}
	
	public function hasPicture()
	{
		return (bool)$this->filename;
	}
	
	public function getSrc()
	{
		return $this->getSrcForVersion($this->getFirstVersion());
	}
	public function getSrcForVersion($version)
	{
		if (!$version) return false;
		if (!$this->dbrow_exists || !$this->filename) return $this->getEmptySrcForVersion($version);
		return "/uploads/{$this->filename}_$version.$this->filetype";
	}
	public function getBaseFilename()
	{
		//TODO: really?
		return md5($this->getAddress()) . '_' . time();
	}
	public function getFileExtenstion()
	{
		return $this->filetype;
	}
	public function getPicAlt()
	{
		return $this->alt;
	}
	public function getPicTitle()
	{
		return $this->title;
	}
	public function getVersions()
	{
		return $this->getConfiguration('versions',false);
	}
	public function getVersion($version)
	{
		$versions = $this->getVersions(); if (!$versions) return false;
		if (!isset($versions[$version])) return false;
		return $versions[$version];
	}
	public function getFirstVersion()
	{
		$versions = $this->getVersions(); if (!$versions) return false;
		$names = array_keys($versions); if (!$names) return false;
		return $names[0];
	}
	public function getEmptySrcForVersion($version_name)
	{
		$version = $this->getVersion($version_name);
		if (!$version) return false;
		if (!isset($version['empty_src'])) return false;
		return $version['empty_src'];
	}
	
	public function getHtmlForVersionWithClass($version, $class='')
	{
		$src = $this->getSrcForVersion($version);
		if (!$src) return '';		
		return '<img class="' . $class . '" src="' . $src . '" alt="' . $this->getPicAlt() . '" title="' . $this->getPicTitle() . '" />';
	}
	
	//Admin:
	public function canEdit()
	{
		return true;
	}
	public function hasViewContentBlock()
	{
		return true;
	}
	
	//Paths:
	public static function getUploadsDir()
	{
		return MuesliConfiguration::getUploadsDir() . '/';
	}
	public static function getOriginalsDir()
	{
		return self::getUploadsDir() . 'originals/';
	}
	public function deleteFiles()
	{
		$filename = self::getUploadsDir() . $this->filename;
		$ext = $this->filetype;
		foreach (array_keys($this->getVersions()) as $version){
			$file = $filename . '_' . $version . '.' . $ext;
			if (file_exists($file)){
				unlink($file);
			}
		}
	}
	
	//Form:
	public function getUploadedFile()
	{
		return $_FILES[$this->getItemIdentifier()];
	}
	
	//Data:
	public function save()
	{
		require_once('DataItemPicture/UploadedPicture.php');
		
		$form = $this->getItemForm();
		$picture=null;

		//If picture changed:
		$file = $this->getUploadedFile();
		if ($file && $file['error'] != UPLOAD_ERR_NO_FILE){	//File Upload
			//check that there was no error:
			if ($file['error'] != UPLOAD_ERR_OK) throw new Exception('There was an error uploading file. code: ' . $file['error']);
			$uploaded = new UploadedPicture($file['tmp_name']);
			//Make sure that the uploaded file is a picture:
			if ($uploaded->isPicture(true)){
				//save the picture as original:
				$new_file = self::getOriginalsDir() . 'upload_' . time() . rand(0,1000);
				$uploaded->moveUploadedFile($new_file);
				$picture = $uploaded;
			}
			
/*		} elseif ($form['library']) { 	//Use of picture from originals:
			//Check file exists:
			if (file_exists(self::getOriginalsDir() . $form['library'])){
				//select the picture as original:
				$picture = new UploadedPicture($form['library']);
			}
*/			
		} elseif ($form['src']) {	//Use of picture from the web:
			//download the picture as original:
			$file =  self::getOriginalsDir() . 'web_' . time() . rand(0,1000);
			$result = copy($form['src'], $file);
			if (!$result) throw new Exception('Could not download file from remote server.');
			
			//Check if file is a picture:
			$uploaded = new UploadedPicture($file);
			if ($uploaded->isPicture(true)){
				//Everything is ok, use it:
				$picture = $uploaded;
			}
		}
		
		if ($picture){
			//save as unique + versions by preferences:
			$file = self::getUploadsDir() . $this->getBaseFilename();	//Unique file, but available for overwriting.
			$prev_file = self::getUploadsDir() . $this->filename;
			$file_ext = $picture->getExtension();
			foreach ($this->getVersions() as $version_name => $version){
				$file_with_version = $file . '_' . $version_name . '.' . $file_ext;
				$transform = new PictureTransform($version);
				$picture->exportTo($file_with_version, $transform);
			}
		}
		
		$picAlt = self::escapeValue($form['alt']);
		$picTitle = self::escapeValue($form['title']);
		
		if ($this->dbrow_exists)
			if ($picture)
				$sql = 'UPDATE `picture` SET `filename`="' . $this->getBaseFilename() . '", `filetype`="' . $picture->getExtension() . '", `alt`="' . $picAlt . '", `title`="' . $picTitle . '", `updated_at`=' . time() . ' WHERE `address`="' . $this->getAddress() . '";';
			else
				$sql = 'UPDATE `picture` SET `alt`="' . $picAlt . '", `title`="' . $picTitle . '", `updated_at`=' . time() . ' WHERE `address`="' . $this->getAddress() . '";';
		else
			if ($picture)
				$sql = 'INSERT INTO `picture` (`address`, `filename`, `filetype`, `alt`, `title`, `updated_at`) VALUES ("' . $this->getAddress() . '", "' . $this->getBaseFilename() . '", "' . $picture->getExtension() . '", "' . $picAlt . '", "' . $picTitle . '", ' . time() . ');';
			else
				$sql = 'INSERT INTO `picture` (`address`, `alt`, `title`, `updated_at`) VALUES ("' . $this->getAddress() . '", "' . $picAlt . '", "' . $picTitle . '", ' . time() . ');';
		
		$result = Database::query($sql);
		
		if (!$result)
			$this->deleteFiles();
		
		return $result;
	}
	
	public function delete()
	{
		$result = Database::query('DELETE FROM `picture` WHERE `address` = "' . $this->getAddress() .'" LIMIT 1;');
		if ($result)
			$this->deleteFiles();
		return $result;
	}
	
}
