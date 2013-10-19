<?php

class PagesCache extends CacheManager
{
	
	public function getCachedFilePathForPath($path)
	{
		if (!$path) return false;
		$path_md5 = md5($path);
		return $this->getCacheDir() . '/' . $path_md5 . '.php';
	}
	
	public function isCachedPageAtPathExists($path)
	{
		if (!$path) return false;
		return file_exists($this->getCachedFilePathForPath($path));
	}
	
	public function getCachedHTMLAtPath($path)
	{
		if (!$path) return false;
		if ($this->isCachedPageAtPathExists($path)){
			$expiration = $this->getCacheTimeStampTime();
			if (!$expiration) $expiration = 0;
			$cachedPage = include($this->getCachedFilePathForPath($path));
			if (!($cachedPage instanceof PageCache)) return false;
			if (!$cachedPage->isValid($expiration)) return false;
			return $cachedPage;
		} else
			return false;
	}
	
	public function createCachedPageForPathWithTemplate($path, $template)
	{
		if (!$path) return false;
		$str = '<?php $template = <<<EOTMUESLI' . "\n" . (string)$template . "\nEOTMUESLI;\n" . 'return new PageCache('.time().', $template);';
		return $this->save($this->getCachedFilePathForPath($path), $str);
	}
	
	public function resetCache()
	{
		$this->saveCacheTimeStampFile();
	}
	
	protected function saveCacheTimeStampFile()
	{
		$str = time();
		$this->save($this->getCacheTimeStampFilename(), $str);
	}
	protected function getCacheTimeStampFilename()
	{
		return $this->getCacheDir() . '/expired.php';
	}
	public function getCacheTimeStampTime()
	{
		$filename = $this->getCacheTimeStampFilename();
		if (!file_exists($filename)) return false;
		$timestamp = file_get_contents($filename);
		return $timestamp;
	}
}