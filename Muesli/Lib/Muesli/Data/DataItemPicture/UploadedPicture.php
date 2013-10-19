<?php

require_once('PictureTransform.php');

class UploadedPicture
{
	private $file;
	private $getimagesize;
	
	protected $types = array(
	    'image/jpeg' => 'jpg',
	    'image/gif' => 'gif',
	    'image/png' => 'png'
	  );
	
	public function __construct($file)
	{
		if (!extension_loaded('gd'))
			throw new Exception('GD Library is not loaded.');
			
		$this->setFile($file);
	}
	
	//File configurations:
	public function setFile($file)
	{
		$this->file = $file;
	}
	public function getFile()
	{
		return $this->file;
	}
	public function moveUploadedFile($new_file)
	{
		$result = move_uploaded_file($this->getFile(), $new_file);
		if (!$result) return false;
		$this->setFile($new_file);
		return true;
	}
	
	//Save:
	public function exportTo($destination, PictureTransform $transform)
	{
		if ($transform && $transform->hasEffect()){
			$transform->setFile($this->getFile());
			$transform->setMimeType($this->getMime());
			return $transform->exportTo($destination);
		} else {
			return copy($this->getFile(), $destination);
		}
	}
	
	//File assessments:
	public function getimagesize()
	{
		if ($this->getimagesize === null)
			$this->getimagesize = getimagesize($this->getFile());
		return $this->getimagesize;
	}
	public function isPicture($delete_if_not=false)
	{
		$image = $this->getimagesize();
		if (!$image){
			if ($delete_if_not) unlink($this->getFile());
			return false;
		}
		return true;
	}
	public function getExtension()
	{
		$mime = $this->getMime();
		if (!array_key_exists($mime, $this->types)) throw new Exception('File type is not recognizable.');
		return $this->types[$mime];
	}
	public function getMime()
	{
		$image = $this->getimagesize();
		if (!$image) return false;
		return $image['mime'];
	}
}