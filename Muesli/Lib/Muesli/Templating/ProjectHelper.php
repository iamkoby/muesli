<?php

class ProjectHelper extends sfTemplateHelper implements sfTemplateHelperInterface 
{
	private $project;
	
	public function __construct(BaseProject $project)
	{
		$this->project = $project;
	}
	
	public function getName()
	{
		return 'project';
	}
	
	public function getProjectName()
	{
		return $this->project->getName();
	}
}