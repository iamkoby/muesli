<?php 

abstract class BaseProject
{
	protected $app;
	protected $debug = false;
	protected $defaultLanguage = 'eng';
	protected $name;
	
	protected $pagesCacheManager;
	protected $siteConfiguration;
	
	//Init:
	public function __construct($debug=false)
	{
		ExceptionsHandler::register($this);
		$this->setDebug($debug);
		$this->init();
	}
	
	abstract function init();
	
	//Getters/Setters:
	public function getName()
	{
		return $this->name;
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	public function setDefaultLanguage($language)
	{
		$this->defaultLanguage = $language;
	}
	public function setDebug($state)
	{
		$this->debug = (bool)$state;
		if ($this->debug) {
			ini_set('display_errors', 1);
			error_reporting(-1);
		} else {
			ini_set('display_errors', 0);
		}
	}
	public function isDebug()
	{
		return $this->debug;
	}
	public function setApplication(ApplicationConfiguration $app)
	{
		$this->app = $app;
	}
	
	//Process request:
	private function processRequest($request)
	{
		//Get route:
		$routingCache = new RoutingCache($this->getApplicationCacheDir(), new RoutingLoader($this->getApplicationConfigDir()));
		$routing = new Routing($routingCache->getRoutes());
		$route = $routing->findRouteByPath($request->getPath());
		if (!$route) throw new NotFoundException('Route not found: ' . $request->getPath());
		
		$request->setRoute($route);
		
		if ($route->hasPage()){
			$site = $this->getSiteConfiguration();
			$page = $site->getPage($route->getPage());
			if (!$page) throw new Exception('The page for this routing was not found: ' . $route->getPage());
			
			$muesliHelper = new MuesliHelper($page, $site);
			if ($page->hasAction()){
				$response = $this->loadAction($page->getController(), $page->getAction(), $request, $muesliHelper);
			} else {
				$template = $page->getTemplate(); if (!$template) throw new Exception('No template was given for the required route');
				$controller = new MuesliController($this, $request);
				$controller->setMuesliHelper($muesliHelper);
				$controller->setCaching($page->shouldCache());
				$response = $controller->action('template', $template);
			}
		} else {
			$response = $this->loadAction($route->getController(), $route->getAction(), $request);
		}
		
		return $response;
	}
	
	public function loadAction($controller_name, $action_name, $request=null, $muesliHelper=null)
	{
		if (!$controller_name) throw new Exception('No controller was given for the required route');
		if (!$action_name) throw new Exception('No action was given for the required route');
		
		$controller_name .= 'Controller';
		if (!MuesliLoader::loadClassFromDirectory($controller_name,$this->getControllerDir())) throw new NotFoundException('Controller class not found: ' . $controller_name);
		$controller = new $controller_name($this, $request);
		if ($muesliHelper) $controller->setMuesliHelper($muesliHelper);
		return $controller->action($action_name);
	}
	
	//Dispatchers:
	public function run()
	{
		$app = new ApplicationConfiguration('Frontend');
		$app->setDir(MuesliConfiguration::getProjectDir());
		$this->setApplication($app);
		
		//Initiate request:
		$request = new Request();

		//Check for cached version:
		if ($request->shouldTryCache()){
			$pagesCache = $this->getPagesCacheManager();
			$cachedPage = $pagesCache->getCachedHTMLAtPath($request->getFullPath());
			if ($cachedPage){
				if ($this->isDebug()) echo '<h1 style="font-size:20px;">' . Muesli::acho() . '</h1>';
				echo $cachedPage;
				return;
			}
		}

		$response = $this->processRequest($request);
		
		//Save cache if needed:
		if (isset($pagesCache)){
			if ($response instanceof Response && $response->shouldCache()){
				$pagesCache->createCachedPageForPathWithTemplate($request->getFullPath(), $response);
			}
		}
	
		echo $response;
		if ($this->isDebug()) echo '<h1 style="font-size:20px;">' . Muesli::acho() . '</h1>';
	}
	public function runAdmin()
	{
		$app = new ApplicationConfiguration('Admin');
		$app->setDir(MuesliConfiguration::getMuesliDir().'/Admin');
		$this->setApplication($app);
		echo $this->processRequest(new Request());
	}
	
	//Cache:
	public function getPagesCacheManager()
	{
		if (!$this->pagesCacheManager){
			$this->pagesCacheManager = new PagesCache($this->getPagesCacheDir());
		}
		return $this->pagesCacheManager;
	}
	public function getSiteConfiguration()
	{
		if (!$this->siteConfiguration){
			$this->siteConfiguration = new SiteCache($this->getProjectCacheDir(), new SiteLoader($this->getProjectConfigDir()));
		}
		return $this->siteConfiguration;
	}

	//Directories configuration: //TODO: move to ApplicationConfiguration (more oop) or leave here (allowing easy configuration in Project class)?
	public function getProjectConfigDir()
	{
		return MuesliConfiguration::getProjectDir() . '/config';
	}
	public function getApplicationConfigDir()
	{
		return $this->app->getDir() . '/config';
	}
	public function getTemplateDirectories()
	{
		$app_dir = $this->app->getDir();
		$muesli_dir = MuesliConfiguration::getMuesliDir();
		return array($app_dir . '/templates/%name%.php', $app_dir . '/layouts/%name%.php', $muesli_dir . '/Default/templates/%name%.php', $muesli_dir . '/Default/layouts/%name%.php');
	}
	public function getControllerDir()
	{
		return $this->app->getDir() . '/controller';
	}
	public function getProjectCacheDir()
	{
		return MuesliConfiguration::getProjectDir().'/cache';
	}
	public function getApplicationCacheDir()
	{
		return $this->getProjectCacheDir() . '/' . $this->app->getName();
	}
	public function getPagesCacheDir()
	{
		return $this->getProjectCacheDir() . '/pages';
	}

}