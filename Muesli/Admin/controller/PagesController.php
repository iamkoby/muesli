<?php

class PagesController extends Controller
{
	public function preExecute()
	{
		if (!$this->isAuthenticated()) return $this->redirect('/Main/login');
	}
	
	public function indexAction()
	{
		$site = $this->getSiteConfiguration();
		$pages_names = $site->getArrayOfPagesNames();
		$pages = array();
		foreach ($pages_names as $page_name){
			$page = $site->getPage($page_name); if (!$page) throw new NotFoundException("Page not found: $page_name.");
			if ($page->hasEditableObjects() || $page->hasAdminConfiguration())
				$pages[$page_name] = $page;
		}
		return $this->render('pagesIndex', array('pages'=>$pages));
	}
	
	/* DEPRECATED:
	public function showAction()
	{
		$site = $this->getSite();
		$page_name = $this->getRequestParameter('page'); if (!$page_name) throw new MissingParameterException('page');
		$page = $site->getPage($page_name); if (!$page) throw new NotFoundException("Page not found: $page_name.");
		return $this->render('pagesShow', array('page'=>$page));
	}
	
	public function saveAction()
	{
		$site = $this->getSite();
		$page_name = $this->getRequestParameter('page'); if (!$page_name) throw new MissingParameterException('page');
		$page = $site->getPage($page_name);
		$editables = $page->getEditablesObject();
		
		foreach ($_POST as $editable => $value){
			$object = $editables->get($editable);
			$object->save($value);
		}
		foreach ($_FILES as $file => $value){
			$object = $editables->get($file);
			$object->save($value);
		}
		return $this->redirect('/Pages/show?page='.$page_name, 200);
	}
	*/
}