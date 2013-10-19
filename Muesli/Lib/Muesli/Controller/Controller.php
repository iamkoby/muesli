<?php

class Controller
{
	private $project;
	private $request;
	private $response;
	private $user;
	private $template_engine;
	
	public function __construct(BaseProject $project, Request $request=null)
	{
		$this->project = $project;
		$this->request = $request;
	}
	
	public function getActionName($action)
	{
		return $action . 'Action';
	}
	public function action($action, $parameters=null)
	{
		$method = $this->getActionName($action);
		if (!method_exists($this, $method))
			throw new NotFoundException('Action not found: ' . $method);
		
		$response = $this->getResponse();
		
		if ($this->preExecute()===false) return $response;
		$actionResult = $this->$method($parameters);
		if ($actionResult) $response->setContent($actionResult);
		$this->postExecute();
		
		return $response;
	}
	
	public function preExecute(){}
	public function postExecute(){}
	
	
	public function getRequest()
	{
		if (!$this->request)
			$this->request = new Request();
		return $this->request;
	}
	public function getRequestParameter($parameter)
	{
		return $this->getRequest()->getParameter($parameter);
	}
	
	public function getResponse()
	{
		if (!$this->response)
			$this->response = new Response();
		
		return $this->response;
	}
	
	public function getTemplateDirectories()
	{
		return $this->getProject()->getTemplateDirectories();
	}
	public function prepareTemplateEngine()
	{
		$loader = new sfTemplateLoaderFilesystem($this->getTemplateDirectories());
		$template_engine = new TemplateEngine($loader);
		$helperSet = new sfTemplateHelperSet(array(new sfTemplateHelperAssets(), new sfTemplateHelperJavascripts(), new sfTemplateHelperStylesheets(), new ProjectHelper($this->getProject()), new RoutingHelper($this->getRequest()), new UserHelper($this->getUser())));
		$template_engine->setHelperSet($helperSet);
		return $template_engine;
	}
	public function getTemplateEngine()
	{
		if (!$this->template_engine){
			$this->template_engine = $this->prepareTemplateEngine();
		}
		return $this->template_engine;
	}
	
	public function getTemplateVariables()
	{
		return array();
	}
	
	public function getProject()
	{
		return $this->project;
	}
	public function getUser()
	{
		if (!$this->user){
			$this->user = new User();
		}
		return $this->user;
	}
	
	public function isAuthenticated()
	{
		return $this->getUser()->isAuthenticated();
	}
	
	public function getProjectName()
	{
		return $this->getProject()->getName();
	}
	
	public function render($template, $vars=array())
	{
		//TODO: check:
		$vars = array_merge($vars, $this->getTemplateVariables());
		$this->getTemplateEngine()->setParameters($vars);
		return $this->getTemplateEngine()->render($template, $vars);
	}
	
	public function forward($controller_name, $action_name)
	{
		$current_controller = get_class($this);
		if ($current_controller == $controller_name.'Controller'){
			$action = $action_name . 'Action';
			return $this->$action();
		}
		//TODO: needs fixing - since the new controller works in its own domain and doesn't effect the current User or Response.
		return $this->getProject()->loadAction($controller_name, $action_name, $this->request);		
	}
	
	public function redirect($url, $status=302)
	{
		$response = $this->getResponse();
        $response->setStatusCode($status);
        $response->setHeader('Location', $this->getRequest()->getScriptName() .$url);
	}

	public function getPagesCacheManager()
	{
		return $this->getProject()->getPagesCacheManager();
	}
	public function getSiteConfiguration()
	{
		return $this->getProject()->getSiteConfiguration();
	}

}

?>