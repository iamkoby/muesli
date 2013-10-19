<?php

class Routing
{
	private $routes;
	
	public function __construct($routes)
	{		
		if (!$routes) $routes = array();
		$this->routes = $routes;
	}
	
	/**
	 * Finds a route for the given path:
	 *
	 * @param String $path
	 * @return MuesliRoute
	 */
	public function findRouteByPath($path)
	{
		if (strlen($path)>1 && substr($path,-1,1)=='/') $path = substr($path,0,-1);
		foreach ($this->routes as $route_name => $route_options){
			if (preg_match('/:([A-Za-z_]+)/',$route_options['url'])){
				$regex = preg_replace('/:([A-Za-z_]+)/',"(?'$1'[^:?./]+)",$route_options['url']);
				$regex = '/^' . str_replace('/','\/', $regex) . '$/';				//Escape backslashes
				preg_match_all($regex, $path, $matches);
				if ($matches[0]){
					preg_match_all('/:([A-Za-z]+)/', $route_options['url'], $var_names);
					$vars = array();
					foreach ($var_names[1] as $v){
						$vars[$v] = $matches[$v][0];
					}
					return new Route($route_name, $route_options, $vars);
				}
			} elseif ($path == $route_options['url']){
				return new Route($route_name, $route_options);
			}
		}
		return false;
	}
	
}