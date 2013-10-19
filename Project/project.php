<?php

require_once '../Muesli/Lib/Muesli/MuesliLoader.php';
MuesliLoader::register();

class Project extends BaseProject
{
	public function init()
	{
		date_default_timezone_set('Asia/Jerusalem');
		$this->setName('חרל"פ');
		//$this->setDefaultLanguage('heb');
		MuesliConfiguration::setDatabaseConfiguration(array(
			'SERVER' => 'localhost',
			'USER' => 'root',
			'PASSWORD' => '123456',
			'DATABASE' => 'harlap'
		));
	}
}