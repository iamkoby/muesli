<?php

class PictureTransform
{
	protected $file;
	protected $mime;
	protected $resource;
	
	protected $quality = 75;
	protected $filter;
	protected $filter_parameters;
	protected $resize = array();
	
	protected $loaders = array(
		'image/jpeg' => 'imagecreatefromjpeg',
		'image/jpg' => 'imagecreatefromjpeg',
		'image/gif' => 'imagecreatefromgif',
		'image/png' => 'imagecreatefrompng'
	);
	protected $creators = array(
		'image/jpeg' => 'imagejpeg',
		'image/jpg' => 'imagejpeg',
		'image/gif' => 'imagegif',
		'image/png' => 'imagepng'
	);
	
	public function __construct($options)
	{
		if (!extension_loaded('gd'))
			throw new Exception('GD Library is not loaded.');
		
		if (isset($options['height']))
			$this->setHeight($options['height']);
		if (isset($options['width']))
			$this->setWidth($options['width']);
		if (isset($options['max_height']))
			$this->setMaxHeight($options['max_height']);
		if (isset($options['max_width']))
			$this->setMaxWidth($options['max_width']);
		if (isset($options['aspect_ratio']))
			$this->setAspectRatio($options['aspect_ratio']);
		if (isset($options['quality']))
			$this->setQuality($options['quality']);
		if (isset($options['filter'])){
			$this->setFilter($options['filter']);
			if (isset($options['filter_parameters']))
				$this->setFilterParameters($options['filter_parameters']);
		}
	}
	public function __destruct()
	{
		if ($this->resource) imagedestroy($this->resource);
	}
	
	public function hasEffect()
	{
		return $this->hasResize() || $this->hasFilter();
	}
	
	//File configuration:
	public function setFile($file)
	{
		$this->file = $file;
	}
	public function getFile()
	{
		return $this->file;
	}
	public function setMimeType($mime)
	{
		if (!array_key_exists($mime,$this->loaders)) throw new Exception('Mime type is not supported: ' . $mime);
		$this->mime = $mime;
	}
	public function getMimeType()
	{
		if (!$this->mime){
			$image = getimagesize($this->getFile());
			if ($image) $this->mime = $image['mime'];
		}
		return $this->mime; 
	}
	
	//Quality configuration:
	public function getQuality()
	{
		return $this->quality;
	}
	public function setQuality($quality)
	{
		if (is_numeric($quality) && $quality >= 0 && $quality <= 100)
	    {
	      $this->quality = $quality;
	    }
	}
	
	//Resize configuration:
	public function hasResize()
	{
		return (bool)$this->resize;
	}
	public function setMaxHeight($height)
	{
		if (!is_numeric($height)) return false;
		$this->resize['max_height'] = $height;
	}
	public function setMaxWidth($width)
	{
		if (!is_numeric($width)) return false;
		$this->resize['max_width'] = $width;
	}
	public function setHeight($height)
	{
		if (!is_numeric($height)) return false;
		$this->resize['height'] = $height;
	}
	public function setWidth($width)
	{
		if (!is_numeric($width)) return false;
		$this->resize['width'] = $width;
	}
	public function setAspectRatio($state)
	{
		$this->resize['aspect_ratio'] = (bool)$state;
	}
	
	//Filter configuration:
	public function hasFilter()
	{
		return (bool)$this->filter;
	}
	public function getFilter()
	{
		return $this->filter;
	}
	public function setFilter($filter_name)
	{
		$filter = 'IMG_FILTER_' . strtoupper($filter_name);
		if (!defined($filter)) return false;
		$this->filter = constant($filter);
	}
	public function setFilterParameters($parameters)
	{
		$this->filter_parameters = $parameters;
	}
	
	//Resource handling:
	public function hasResource()
	{
		return (is_resource($this->resource) && get_resource_type($this->resource) == 'gd');
	}
	public function setResource($resource)
	{
		if (is_resource($resource) && get_resource_type($resource)=='gd'){
			$this->resource = $resource;
			return true;
		}
		return false;
	}
	public function getResource()
	{
		if (!$this->resource) {
			$this->setResource($this->loaders[$this->getMimeType()]($this->getFile()));
		} 
		return $this->resource;
	}
	
	//Save:
	public function createEmptyResource($height=0, $width=0)
	{
		$resource = $this->getResource();
	    $dest_resource = imagecreatetruecolor((int)$width, (int)$height);
		$mime = $this->getMimeType();

		if ($mime == 'image/gif' || $mime == 'image/png'){	// Preserve alpha transparency
			$index = imagecolortransparent($resource);
			if ($index >= 0){	// Handle transparency
				if ($mime == 'image/png'){	// Always make a transparent background color for PNGs that don't have one allocated already
					imagealphablending($dest_resource, false); 	// Disabled blending
					$color = imagecolorallocatealpha($dest_resource, 0, 0, 0, 127);	// Grab our alpha tranparency color
					imagefill($dest_resource, 0, 0, $color);	// Fill the entire image with our transparent color
					imagesavealpha($dest_resource, true);	// Re-enable transparency blending
				} else {
					$index_color = imagecolorsforindex($resource, $index);	// Grab the current images transparent color
					$index = imagecolorallocate($dest_resource, $index_color['red'], $index_color['green'], $index_color['blue']); // Set the transparent color for the resized version of the image
					imagefill($dest_resource, 0, 0, $index); 	// Fill the entire image with our transparent color
					imagecolortransparent($dest_resource, $index);      // Set the filled background color to be transparent
				}
			}
		}
		return $dest_resource;
	}
	public function createImage($destination)
	{
		$output = null;
		
		$mime = $this->getMimeType();
		switch ($mime)
		{
			case 'image/jpeg':
			case 'image/jpg':
				$output = $this->creators[$mime]($this->resource,$destination,$this->quality);
				break;

			case 'image/png':
				imagesavealpha($this->resource, true);
				$quality =  9 - round($this->quality * (9/100));
				$output = $this->creators[$mime]($this->resource,$destination,$quality, null);
				break;

			case 'image/gif':
				$output = $this->creators[$mime]($this->resource,$destination);
				break;

			default:
				$this->throwException('Mime type is not supported: ' . $mime);
		}
		return $output;
	}
	public function exportTo($destination)
	{
		$resource = $this->getResource(); 
		if (!$this->hasResource()) $this->throwException('Resource is not loaded correctly.');

		if ($this->hasResize()){
			//Current size:
			$sourceHeight = imagesy($resource);
			$sourceWidth = imagesx($resource);
			$destHeight = $destWidth = $cropX = $cropY = 0;
			
			$keep_ratio = isset($this->resize['aspect_ratio']) ? $this->resize['aspect_ratio'] : true;
			$height = isset($this->resize['height']) ? $this->resize['height'] : 0;
			$width = isset($this->resize['width']) ? $this->resize['width'] : 0;
			
			if ($height && $width){
				$resizedHeight = $height;
				$resizedWidth = $width;
				
				if ($keep_ratio){
					$ratio1 = $sourceHeight / $sourceWidth;
					$ratio2 = $resizedHeight / $resizedWidth;
					if ($ratio1 / $ratio2 > 0){
						$destHeight = floor($resizedWidth * $ratio1);
						$destWidth = $resizedWidth;
					} else {
						$destHeight = $resizedHeight;
						$destWidth = floor($resizedHeight / $ratio1);
					}

					$cropY = floor(($destHeight - $height) / 2);
					$cropX = floor(($destWidth - $width) / 2);
				}
			}
			
			
			/*
			if (!$height || !$width){
				$max_height = isset($this->resize['max_height'])) ? $this->resize['max_height'] : 0;
				$max_width = isset($this->resize['max_width'])) ? $this->resize['max_width'] : 0;
				
				if ($max_height && $max_height < $sourceHeight){
					$resizedHeight = $max_height;
				} else {
					$resizedHeight = $sourceHeight;
				}
				if ($max_width && $max_width < $sourceWidth) {
					$resizedWidth = $max_width;
				} else {
					$resizedWidth = $sourceWidth;
				}
				if ($keep_ratio){

				}
			}
			*/
	
			if (isset($resizedHeight) && $resizedHeight && isset($resizedWidth) && $resizedWidth){
				$dest_resource = $this->createEmptyResource($resizedHeight, $resizedWidth);
				if ($dest_resource){
					if (!$destHeight) $destHeight = $resizedHeight;
					if (!$destWidth) $destWidth = $resizedWidth;
					$result = imagecopyresampled($dest_resource,$resource, 0, 0, $cropX, $cropY, $destWidth, $destHeight, $sourceWidth, $sourceHeight);
					if ($result){
						$result = $this->setResource($dest_resource);
						if ($result){
							imagedestroy($resource);
							$resource = $dest_resource;
						}
					}
				}
			}
		}

		if ($this->hasFilter()){
			$filter_params = array($this->getResource(), $this->getFilter());
			if ($this->filter_parameters) $filter_params = array_merge($filter_params, $this->filter_parameters);
			if (!call_user_func_array('imagefilter', $filter_params)) $this->throwException('Could not apply filter: ' . implode(',', $filter_params));
		}

		$result = $this->createImage($destination);
		if (!$result) $this->throwException('Could not export image.');

		return true;
	}
	
	//Exception:
	public function throwException($message)
	{
		@imagedestroy($this->resource);
		throw new Exception($message);
	}
	
}

