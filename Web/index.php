<?php 
require_once('../Project/project.php');
$debug = ($_SERVER['REMOTE_ADDR']=='127.0.0.1');
$project = new Project($debug);
$project->run();
?>