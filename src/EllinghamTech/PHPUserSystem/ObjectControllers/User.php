<?php
/***************************************************************************************************
 * Copyright (c) 2019. Ellingham Technologies Ltd
 * Website: https://ellinghamtech.co.uk
 * Developer Site: https://ellingham.dev
 *
 * MIT License
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 **************************************************************************************************/

/**
 * @internal
 */
namespace EllinghamTech\PHPUserSystem\ObjectControllers;

use EllinghamTech\PHPUserSystem\UserSystem;

class User
{
	/**
	 * Loads a user by user ID, returns NULL if the user does not already exist.
	 *
	 * @param int $user_id
	 *
	 * @return null|\EllinghamTech\PHPUserSystem\ObjectModels\User
	 * @throws \Exception
	 */
	public static function loadFromUserId(int $user_id) : ?\EllinghamTech\PHPUserSystem\ObjectModels\User
	{
		$db = UserSystem::getDb('Users');
		$user = array();

		$sql = 'SELECT * FROM users WHERE user_id=?';
		$res = $db->performQuery($sql, $user_id);

		if(!($user = $res->fetchArray())) return null;

		return new \EllinghamTech\PHPUserSystem\ObjectModels\User($user);
	}

	/**
	 * Loads a user by User Name, returns NULL if the user name could not be found in the database
	 *
	 * @param string $user_name
	 *
	 * @return null|\EllinghamTech\PHPUserSystem\ObjectModels\User
	 * @throws \Exception
	 */
	public static function loadFromUserName(string $user_name) : ?\EllinghamTech\PHPUserSystem\ObjectModels\User
	{
		$db = UserSystem::getDb('Users');
		$user = array();

		$sql = 'SELECT * FROM users WHERE user_name=?';
		$res = $db->performQuery($sql, $user_name);

		if(!($user = $res->fetchArray())) return null;

		return new \EllinghamTech\PHPUserSystem\ObjectModels\User($user);
	}

	/**
	 * Loads a user by their email address.  Returns null if the email address could not be found in the database.
	 *
	 * @param string $user_email
	 *
	 * @return null|\EllinghamTech\PHPUserSystem\ObjectModels\User
	 * @throws \Exception
	 */
	public static function loadFromUserEmail(string $user_email) : ?\EllinghamTech\PHPUserSystem\ObjectModels\User
	{
		$db = UserSystem::getDb('Users');
		$user = array();

		$sql = 'SELECT * FROM users WHERE user_email=?';
		$res = $db->performQuery($sql, $user_email);

		if(!($user = $res->fetchArray())) return null;

		return new \EllinghamTech\PHPUserSystem\ObjectModels\User($user);
	}

	/**
	 * Creates a new user object.
	 *
	 * @return \EllinghamTech\PHPUserSystem\ObjectModels\User
	 */
	public static function create() : \EllinghamTech\PHPUserSystem\ObjectModels\User
	{
		return new \EllinghamTech\PHPUserSystem\ObjectModels\User(null);
	}

	/**
	 * Saves a user to the database
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\User $user
	 *
	 * @return bool
	 * @throws \RuntimeException
	 * @throws \Exception
	 */
	public static function save(\EllinghamTech\PHPUserSystem\ObjectModels\User $user) : bool
	{
		if($user->user_id == null) return self::insert($user);
		$db = UserSystem::getDb('Users');

		$sql = 'SELECT count(1) AS `c` FROM users WHERE user_id=?';
		$res = $db->performQuery($sql, $user->user_id);
		$row = $res->fetchArray();

		if($row['c'] == 1)
			return self::update($user);
		else
			return self::insert($user);
	}

	/**
	 * Updates the user
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\User $user
	 *
	 * @throws \Exception
	 *
	 * @return bool True on success, false or exception on failure
	 */
	public static function update(\EllinghamTech\PHPUserSystem\ObjectModels\User $user) : bool
	{
		$db = UserSystem::getDb('Users');

		$sql = 'UPDATE users SET user_name=?, user_password=?, user_email=?, user_mobile=?, user_active=?, user_flags=? WHERE user_id=?';
		$res = $db->performQuery($sql,
			array(
				$user->user_name,
				$user->user_password,
				$user->user_email,
				$user->user_mobile,
				$user->user_active ? 1 : 0,
				$user->user_flags,
				$user->user_id)
		);

		if($res->numRows() == 1) return true;
		else return false;
	}

	/**
	 * Inserts the user to the database
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\User $user
	 *
	 * @throws \Exception
	 *
	 * @return bool True on success
	 */
	public static function insert(\EllinghamTech\PHPUserSystem\ObjectModels\User $user) : bool
	{
		$db = UserSystem::getDb('Users');

		$sql = 'INSERT INTO users (user_name, user_password, user_email, user_mobile, user_created, user_active, user_flags) VALUES (?, ?, ?, ?, ?, ?, ?)';
		$res = $db->performQuery($sql,
			array(
				$user->user_name,
				$user->user_password,
				$user->user_email,
				$user->user_mobile,
				time(),
				$user->user_active ? 1 : 0,
				$user->user_flags
			)
		);

		if($res->numRows() != 1) return false;

		$sql = 'SELECT * FROM users WHERE user_id=?';
		$res = $db->performQuery($sql, $res->insertId());
		$row = $res->fetchArray();

		if(!$row) return false; // Exception instead?

		$user->populate($row);

		return true;
	}

	/**
	 * Checks if the user exists in the database depending on $field of
	 * 'user_email', 'user_name' or 'user_mobile'.
	 *
	 * @param string $field
	 * @param string $value
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 */
	public static function checkIfExists(string $field, $value) : bool
	{
		$db = UserSystem::getDb('Users');
		$field = strtolower($field);

		switch($field)
		{
			case 'user_email':
			case 'user_name':
			case 'user_mobile':
				break;
			default:
				throw new \RuntimeException('Field not available');
		}

		$sql = 'SELECT count(1) AS `c` FROM users WHERE '.$field.'=?';
		$res = $db->performQuery($sql, $value);

		if(!($row = $res->fetchArray())) return false;

		return !($row['c'] == 0);
	}
};
