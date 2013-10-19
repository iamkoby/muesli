<?php

class UserPeer
{
	public static function preparePassword($password)
	{
		return md5('muesli' . $password . 'joy');
	}
	
	public static function getUserWithUsernameAndPassword($username, $password)
	{
		Database::connect();
		$sql = sprintf('SELECT * FROM `users` WHERE `username` = "%s" AND `password` = "%s";', mysql_real_escape_string($username), self::preparePassword($password));
		return Database::queryAndFetchFirst($sql);
	}
	public static function getUserById($id)
	{
		if (!is_int((int)$id)) return false;
		$sql = "SELECT * FROM users WHERE id = $id;";
		return Database::queryAndFetchFirst($sql);
	}
	public static function updateUser($id, $name, $username, $password)
	{
		Database::connect();
		$sql = sprintf('UPDATE `users` SET `name`="%s", `username`="%s" ', mysql_real_escape_string($name), mysql_real_escape_string($username));
		if ($password)
			$sql .= sprintf(', `password`="%s"', self::preparePassword($password));
		$sql .= ' WHERE id = ' . $id . ';';
		return Database::query($sql);
	}
	
	public static function newUser($name, $username, $password)
	{
		Database::connect();
		$sql = sprintf('INSERT INTO `users` (`name`, `username`, `password`) VALUES ("%s","%s","%s");', mysql_real_escape_string($name), mysql_real_escape_string($username), self::preparePassword($password));
		return Database::insert($sql);
	}
	
	public static function updateUserLastEntrance($user_id)
	{
		$sql = sprintf('UPDATE `users` SET `last_entrance` = %d WHERE `id` = %d;', time(), $user_id);
		return Database::query($sql);
	}
	
	public static function getUsers()
	{
		$sql = 'SELECT * FROM `users`;';
		return Database::queryAndFetchAll($sql);
	}
	
	public static function deleteUser($user_id)
	{
		$sql = "DELETE FROM `users` WHERE `id` = $user_id;";
		return Database::query($sql);
	}
}