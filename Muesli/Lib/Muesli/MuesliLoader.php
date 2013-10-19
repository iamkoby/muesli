<?php

class MuesliLoader
{
	private static $classes = array(
		'Muesli' => 'Muesli.php',
		'CacheManager' => 'Cache/CacheManager.php',
		'ConfigCache' => 'Cache/ConfigCache.php',
		'PageCache' => 'Cache/PageCache.php',
		'PagesCache' => 'Cache/PagesCache.php',
		'RoutingCache' => 'Cache/RoutingCache.php',
		'SiteCache' => 'Cache/SiteCache.php',
		'ApplicationConfiguration' => 'Configuration/ApplicationConfiguration.php',
		'MuesliConfiguration' => 'Configuration/MuesliConfiguration.php',
		'YamlLoader' => 'Configuration/YamlLoader.php',
		'Controller' => 'Controller/Controller.php',
		'ExceptionController' => 'Controller/ExceptionController.php',
		'MuesliController' => 'Controller/MuesliController.php',
		'ExceptionsHandler' => 'Exceptions/ExceptionsHandler.php',
		'NotFoundException' => 'Exceptions/NotFoundException.php',
		'MissingParameterException' => 'Exceptions/MissingParameterException.php',
		'BaseProject' => 'Project/BaseProject.php',
		'Request' => 'Request/Request.php',
		'Response' => 'Request/Response.php',
		'User' => 'Request/User.php',
		'Session' => 'Request/Session.php',
		'Route' => 'Routing/Route.php',
		'Routing' => 'Routing/Routing.php',
		'RoutingLoader' => 'Routing/RoutingLoader.php',
		'Page' => 'Data/Page.php',
		'SiteLoader' => 'Project/SiteLoader.php',
		'MuesliObject' => 'Project/MuesliObject.php',
		'TemplateEngine' => 'Templating/TemplateEngine.php',
		'ProjectHelper' => 'Templating/ProjectHelper.php',
		'RoutingHelper' => 'Templating/RoutingHelper.php',
		'UserHelper' => 'Templating/UserHelper.php',
		'Database' => 'DB/Database.php',
	//Model:
		'UserPeer' => '../Model/UserPeer.php',
	);
	private static $prefixes = array(
		'sfTemplate' => '../sfTemplating/',
		'sfYaml' => '../sfYaml/',
		'DataItem' => 'Data/',
		'MuesliHelper' => 'Helper/'
	);
	
	public static function register()
	{
		ini_set('unserialize_callback_func', 'spl_autoload_call');
		spl_autoload_register(array(new self, 'loadClass'));
	}

	public function loadClass($class)
	{
		if (strpos($class, 'Swift') === 0){
			require dirname(__FILE__) . '/../Swift-4.0.6/lib/swift_required.php';
			return true;
		}
		$file = $this->getFileForClass($class); 
		if (!$file) return false;
		
		require dirname(__FILE__) . '/' . $file;
		return true;
	}
	
	private function getFileForClass($class)
	{
		foreach (self::$prefixes as $prefix => $dir){
			if (strpos($class, $prefix) === 0){
				return $dir . $class . '.php';
			}
		}
		if (!isset(self::$classes[$class])) return false;
		return self::$classes[$class];
	}
	
	public static function loadClassFromDirectory($class, $dir)
	{
		if (!file_exists($dir.'/'.$class.'.php')) return false;
		return include $dir.'/'.$class.'.php';
	}
}