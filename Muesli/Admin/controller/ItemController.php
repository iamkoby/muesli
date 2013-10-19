<?php

class ItemController extends Controller
{
	protected $address;
	
	public function preExecute()
	{
		if (!$this->isAuthenticated()) return $this->redirect('/Main/login');
	}
	
	public function resetPagesCache()
	{
		$this->getPagesCacheManager()->resetCache();
	}

	protected function prepareAddressFromRequest()
	{
		if (!$this->address){
			$address = $this->getRequestParameter('address');
			if (!$address) throw new MissingParameterException('address');
			$this->address = explode('/',$address,2);
		}
	}
	protected function getItemAddressFromRequest()
	{
		$this->prepareAddressFromRequest();
		if (!isset($this->address[1])) return false;
		return $this->address[1];
	}
	protected function getPageNameFromRequest()
	{
		$this->prepareAddressFromRequest();
		return $this->address[0];
	}
	protected function getPageFromRequest()
	{
		$site = $this->getSiteConfiguration(); if (!$site) throw new Exception('Couldn\'t load site.');
		$page_name = $this->getPageNameFromRequest();
		$page = $site->getPage($page_name); if (!$page) throw new NotFoundException("Page not found: $page_name.");
		return $page;
	}
	
	protected function getItemFromRequest()
	{
		$page = $this->getPageFromRequest();
		
		if ($address = $this->getItemAddressFromRequest()){
			$item = $page->get($address);
			if (!$item) throw new NotFoundException('Item not found: '.$address);
		} else 
			$item = $page;
			
		return $item;
	}
	
	public function showAction()
	{
		$item = $this->getItemFromRequest();
		if (!$item->canAdmin()) throw new Exception('Item cannot be viewed by this screen since it is not an object.');
		return $this->render('itemShow', array('item'=>$item));
	}
	
	public function editAction()
	{
		$item = $this->getItemFromRequest();
		if (!$item->canEdit()) throw new Exception('Item cannot be edited.');
		return $this->render('itemEdit', array('item'=>$item));
	}
	
	public function saveAction()
	{
		$item = $this->getItemFromRequest();
		if (!$item->canEdit()) throw new Exception('Item cannot be edited.');
		
		$item->save();
		
		$this->resetPagesCache();
		
		return $this->redirect('/Item/show?address=' . $item->getParentAddress());
	}
	
	
	//TEMP:
	private function updateArray(DataItemArray $array, $address, $addition='')
	{
		$tab = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		foreach ($array as $array_item){
			if (!is_numeric($array_item->getAddress())) throw new Exception('wrong mode. ' . $array_item->getAddress() . ' should be numeric.');
			echo $addition . $tab . $array_item->getAddress() . ' =><br/>';
			
			foreach ($array_item->getChildren() as $child){
				$new_address = $address . '/' . $child->getAddress();
				if ($child instanceof DataItemArray){
					echo $addition . $tab . $tab . $child->getAddress() . ' => array:<br/>';
					$this->updateArray($child, $new_address, $tab.$tab);
					continue;
				}
				
				$table = null;
				$class = get_class($child);
				if ($class == 'DataItemEnum' || $class == 'DataItemBoolean')
					$table = 'enum';
				elseif ($class == 'DataItemLink')
					$table = 'link';
				elseif ($class == 'DataItemPicture')
					$table = 'picture';
				elseif ($class == 'DataItemText')
					$table = 'text';
				elseif ($class == 'DataItemWysiwyg')
					$table = 'wysiwyg';
				
				if ($table){
					echo $addition . $tab . $tab . $child->getAddress() . ' => ' . $new_address . ' - ';
					$sql = 'UPDATE `' . $table . '` SET `address` = "' . $new_address . '" WHERE `address` = "' . $child->getAddress() . '"';
					$ok = mysql_query($sql);
					if ($ok)
						echo ' OK<br/>';
					else
						echo ' Failed<br/>';
				} else {
					echo $addition . $tab . $tab . $child->getAddress() . ' => no database.<br/>';
				}
				
			}
		}
	}

	public function updateAction()
	{
		$page = $this->getItemFromRequest();
		if (!($page instanceof Page)) throw new Exception('not page');
		
		foreach ($page->getChildren() as $item){
			$address = $item->getAddress();
			echo $address . ": ";
			if ($item instanceof DataItemArray){
				echo "<br/>";
				try{
					$this->updateArray($item, $address);
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			} else {
				echo 'Not array!<br/>';
				continue;
			}
		}
	}
	
}