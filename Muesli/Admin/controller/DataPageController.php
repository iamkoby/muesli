<?php
require('ItemController.php');

class DataPageController extends ItemController
{
	
	public function settingsAction()
	{
		$page = $this->getItemFromRequest();
		if (!$page->canEditMeta()) throw new Exception('Page settings cannot be edited.');
		
		return $this->render('DataItems/Page/settings', array('page'=>$page));
	}
	public function settingsSaveAction()
	{
		$page = $this->getItemFromRequest();
		if (!$page->canEditMeta()) throw new Exception('Page settings cannot be edited.');
		$page->save($_POST);
		
		$this->resetPagesCache();
		
		return $this->redirect('/Item/show?address=' . $page->getAddress());
	}
	
}