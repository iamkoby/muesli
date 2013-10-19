<?php

class ExceptionsHandler
{
	private $project;
	
	public static function register($project)
	{
		$handler = new self();	
		$handler->project = $project;
		set_exception_handler(array($handler,'handle'));
		set_error_handler(array($handler, 'handle_error'));  
	}
	
	public function handle(Exception $e)
	{
		try {
			ob_get_clean();
			$controller = new ExceptionController($this->project);
			if ($this->project->isDebug())
				echo $controller->action('exceptionDev', $e);
			elseif ($e->getCode() == 404)
				echo $controller->action('exception404');
			else
				echo $controller->action('exception500');
		} catch (Exception $e) {
			if ($this->project->isDebug()) die($e);
		}	
	}
	
	public function handle_error($level, $message, $file, $line, $context)
    {
        if (0 === $level) return false;
		throw new ErrorException(sprintf('%s: %s in %s line %d', $level, $message, $file, $line));
    }
	
}