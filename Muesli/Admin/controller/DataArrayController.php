<?php
require('ItemController.php');

class DataArrayController extends ItemController
{
	
	//New:
	public function newAction()
	{
		$item = $this->getItemFromRequest();
		return $this->render('DataItems/DataItemArray/new', array('item'=>$item));
	}
	
	public function saveNewAction()
	{
		$item = $this->getItemFromRequest();
		$child = $item->createNewChild();
		if (!$child) throw new Exception('Could not create a new item in this array: ' . $item->getAddress());
		
		$child->save($_POST);
		
		$this->resetPagesCache();
		
		return $this->redirect('/Item/show?address=' . $item->getAddress());
	}
	
	//Delete:
	public function deleteAction()
	{
		$item = $this->getItemFromRequest();	
		return $this->render('DataItems/DataItemArray/delete', array('item'=>$item));
	}
	public function deleteDoAction()
	{
		$item = $this->getItemFromRequest();
		$parent_address = $item->getParentAddress();
		$item->delete();
		
		$this->resetPagesCache();
		
		return $this->redirect('/Item/show?address=' . $parent_address);
	}
	public function deleteAllAction()
	{
		$item = $this->getItemFromRequest();	
		return $this->render('DataItems/DataItemArray/deleteAll', array('item'=>$item));
	}
	public function deleteAllDoAction()
	{
		$item = $this->getItemFromRequest();
		$item->deleteAll();
		
		$this->resetPagesCache();
		
		return $this->redirect('/Item/show?address=' . $item->getAddress());
	}
	
	
	//Sorting:
	public function sortingAction()
	{
		$item = $this->getItemFromRequest();
		return $this->render('DataItems/DataItemArray/sorting', array('item'=>$item));
	}
	public function saveSortingAction()
	{
		$item_id = $this->getRequestParameter('item');
		if (!$item_id) throw new Exception('Missing required parameter: item (id)');
		
		$direction = $this->getRequestParameter('direction');
		if (!$direction) throw new Exception('Missing required parameter: direction');
		
		$item = Database::getArrayItemWithId($item_id);
		
		if ($direction == 'up'){
			$higher = Database::getArrayItemWithHigherSortingThan($item['address'], $item['sorting']);
			if ($higher){
				Database::setSortingValueForItemWithId($item['id'], $higher['sorting']);
				Database::setSortingValueForItemWithId($higher['id'], $item['sorting']);
			} else throw new Exception('Could not find an item in this array with higher sorting value.');
		} elseif ($direction == 'down') {
			$lower = Database::getArrayItemWithLowerSortingThan($item['address'], $item['sorting']);
			if ($lower){
				Database::setSortingValueForItemWithId($item['id'], $lower['sorting']);
				Database::setSortingValueForItemWithId($lower['id'], $item['sorting']);
			} else throw new Exception('Could not find an item in this array with lower sorting value.');
		}
		
		$this->resetPagesCache();
		
		return 'OK';
	}
	
}