<?php

class Database
{
	public static $connected = false;
	
	public static function connect()
	{
		if (self::$connected) return;
		$config = MuesliConfiguration::getDatabaseConfiguration();
		$ok = mysql_connect($config['SERVER'], $config['USER'], $config['PASSWORD']); if (!$ok) throw new Exception('Couldn\'t connect to database: connection string.');
		$ok = mysql_select_db($config['DATABASE']); if (!$ok) throw new Exception('Couldn\'t connect to database: database name.');
		mysql_query('SET NAMES utf8;');
		self::$connected = true;
		return true;
	}	
	public static function query($sql)
	{
		self::connect();
		Muesli::$db++;
		return mysql_query($sql);
	}
	public static function insert($sql)
	{
		$result = self::query($sql);
		if (!$result) return false;
		return mysql_insert_id();
	}
	public static function queryAndFetchFirstCell($sql)
	{
		$result = self::query($sql);
		if (!$result) return false;
		$row = mysql_fetch_row($result);
		if (!$row || !isset($row[0])) return false;
		return $row[0];
	}
	public static function queryAndFetchFirst($sql)
	{
		$result = self::query($sql);
		if (!$result) return false;
		return mysql_fetch_assoc($result);
	}
	public static function queryAndFetchAll($sql)
	{
		$result = self::query($sql);
		if (!$result) return false;
		$all = array();
	    while ($row = mysql_fetch_assoc($result)) {
	    	if ($row) $all[] = $row;
	    }
	    return $all;
	}
	public static function queryAndFetchAllFirstCells($sql)
	{
		$result = self::query($sql);
		if (!$result) return false;
		$all = array();
	    while ($row = mysql_fetch_row($result)) {
	    	if ($row) $all[] = $row[0];
	    }
	    return $all;
	}
	
	public static function getEditableOfTypeWithAddress($type, $address)
	{
		$sql = 'SELECT * FROM ' . $type . ' WHERE `address`="' . $address . '";';
		$row = self::queryAndFetchFirst($sql);
		return $row;
	}
	
	//Arrays:
		//Delete:
			public static function deleteAllItemsInArrayWithAddress($type, $address)
			{
				$sql = 'DELETE FROM ' . $type . ' WHERE `address` = "' . $address . '";';
				return self::query($sql);
			}
			public static function deleteItemInArrayWithId($id)
			{
				$sql = 'DELETE FROM `array` WHERE `id` = ' . $id . ';';
				return self::query($sql);
			}
		//Get:
			public static function getItemsInArrayWithAddress($type, $address)
			{
				$sql = "SELECT `id`, `name` FROM $type WHERE `address`=\"$address\" ORDER BY `sorting` DESC;";
				return self::queryAndFetchAll($sql);
			}
			public static function getAddressOfArrayItemWithId($id)
			{
				$row = self::getArrayItemWithId($id);
				if (!$row) return false;
				return $row['address'];
			}
			public static function getArrayItemWithId($id)
			{
				$sql = 'SELECT * FROM `array` WHERE `id`=' . $id . ' LIMIT 1;';
				return self::queryAndFetchFirst($sql);
			}
		//New:
			public static function createNewItemInArrayWithAddress($address)
			{
				$time = time();
				$sorting = intval(self::getLowestSortingValueInArrayWithAddress($address));
				self::increaseSortingValueInArrayWithAddress($address);
				$sql = "INSERT INTO `array` (`address`, `sorting`,`updated_at`) VALUES (\"$address\", $sorting, $time);";
				$result = self::query($sql);
				if (!$result) return false;
				return mysql_insert_id();
			}
		//Sorting:
			public static function getLowestSortingValueInArrayWithAddress($address)
			{
				$sql = "SELECT `sorting` FROM `array` WHERE `address` = \"$address\" ORDER BY `sorting` ASC LIMIT 1;";
				return self::queryAndFetchFirstCell($sql);
			}
			public static function increaseSortingValueInArrayWithAddress($address)
			{
				$sql = "UPDATE `array` SET `sorting`=`sorting`+1 WHERE `address` = \"$address\";";
				return self::query($sql);
			}
			public static function getArrayItemWithHigherSortingThan($address, $sorting)
			{
				$sql = "SELECT * FROM `array` WHERE `address`=\"$address\" AND `sorting` > $sorting ORDER BY `sorting` ASC LIMIT 1;";
				return self::queryAndFetchFirst($sql);
			}
			public static function getArrayItemWithLowerSortingThan($address, $sorting)
			{
				$sql = "SELECT * FROM `array` WHERE `address`=\"$address\" AND `sorting` < $sorting ORDER BY `sorting` DESC LIMIT 1;";
				return self::queryAndFetchFirst($sql);
			}
			public static function setSortingValueForItemWithId($id, $sorting)
			{
				$sql = "UPDATE `array` SET `sorting` = $sorting WHERE `id` = $id;";
				return self::query($sql);
			}
	
	//Functions for MuesliHelperCriteria:
	private static function getIdsOfItemsWithFilter($address, $field, $type, $value)
	{
		$sql = 'SELECT `address` FROM ' . $type . ' WHERE `address` LIKE "' . $address . '/%" AND value="'.$value.'";';
		$rows = self::queryAndFetchAllFirstCells($sql);
		$address_length = strlen($address)+1;
		$field_length = -strlen($field) - 1;
		$ids = array();
		foreach ($rows as $row){
			$id = substr($row, $address_length, $field_length);
			if (!is_numeric($id)) continue;
			$ids[] = $id;
		}
		return $ids;
	}
	private static function getIdsOfItemsByCriteria($address, MuesliHelperArrayCriteria $criteria)
	{
		$filters = $criteria->getFilters(); if (!$filters) return false;
		$ids = array();
		foreach ($filters as $field => $filter){
			$new_ids = self::getIdsOfItemsWithFilter($address, $field, $filter['type'], $filter['value']);
			if (!$new_ids) return false;
			if (!$ids) 
				$ids = $new_ids;
			else
				$ids = array_intersect($ids, $new_ids);	
		}
		return $ids;
	}
	public static function getArrayOfIdsOfItemsWithAddress($address)
	{
		$sql = 'SELECT id FROM array WHERE `address`="' . $address . '"';
		return self::queryAndFetchAllFirstCells($sql); 
	}
	public static function getItemsInArrayWithCriteria($type, $address, MuesliHelperArrayCriteria $criteria)
	{
		$sql = 'SELECT id, name FROM '. $type .' WHERE `address`="' . $address . '"';
		if ($criteria->hasFilters()){
			$ids = self::getIdsOfItemsByCriteria($address, $criteria);
			if (!$ids) return array();
			$sql .= ' AND id IN (' . implode(',', $ids) . ')';
		}
		$sql .= ' ORDER BY sorting DESC';
		if ($criteria->getOffset() || $criteria->getLimit())
			$sql .= ' LIMIT ' . $criteria->getOffset() . ',' . $criteria->getLimit();
		return self::queryAndFetchAll($sql);
	}
	public  static function getCountOfItemsInArray($type, $address, MuesliHelperArrayCriteria $criteria)
	{
		if ($criteria->hasFilters()){
			$ids = self::getIdsOfItemsByCriteria($address, $criteria);
			if (!$ids) return 0;
			return count($ids);
		} else {
			$sql = 'SELECT count(id) FROM ' . $type . ' WHERE `address`="' . $address . '";';
			return self::queryAndFetchFirstCell($sql);
		}
	}
}