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

/**
 * @internal
 */
final class UserPermission
{
	/**
	 * Creates a new user permission
	 *
	 * @param int $user_id
	 * @param array|null $permissions
	 *
	 * @return \EllinghamTech\PHPUserSystem\ObjectModels\UserPermission
	 *
	 * @deprecated use load() which creates a new user permission if it does not exist, preventing unintended overwrites
	 */
	public static function create(int $user_id, ?array $permissions = null) : \EllinghamTech\PHPUserSystem\ObjectModels\UserPermission
	{
		return new \EllinghamTech\PHPUserSystem\ObjectModels\UserPermission($user_id, $permissions);
	}

	/**
	 * Note: if no permission is found, a UserPermission object will still be returned
	 * but with a NO_PERMISSION (0) permission value.
	 *
	 * @param int $user_id
	 * @param string $permissionName
	 *
	 * @return \EllinghamTech\PHPUserSystem\ObjectModels\UserPermission
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function load(int $user_id, string $permissionName) : \EllinghamTech\PHPUserSystem\ObjectModels\UserPermission
	{
		$permissionObj = new \EllinghamTech\PHPUserSystem\ObjectModels\UserPermission($user_id, null);
		$permissionObj->permission_name = $permissionName;
		$db = UserSystem::getDb('UsersPermissions');

		$sql = 'SELECT * FROM users_permissions WHERE user_id=? AND permission_name=?';
		$res = $db->performQuery($sql, array($user_id, $permissionName));

		if($row = $res->fetchArray())
			$permissionObj->populate($row);

		return $permissionObj;
	}

	/**
	 * Saves the user permission to the database
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserPermission $userPermission
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function save(\EllinghamTech\PHPUserSystem\ObjectModels\UserPermission $userPermission) : bool
	{
		$db = UserSystem::getDb('UsersPermissions');

		$sql = 'REPLACE INTO users_permissions (user_id, permission_name, permission_value) VALUES  (?, ?, ?)';
		$res = $db->performQuery($sql, array($userPermission->user_id, $userPermission->permission_name, $userPermission->permission_value));

		return $res->isSuccess();
	}

	/**
	 * Deletes the user permission from the database
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserPermission $userPermission
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function delete(\EllinghamTech\PHPUserSystem\ObjectModels\UserPermission $userPermission) : bool
	{
		$db = UserSystem::getDb('UsersPermissions');

		$sql = 'DELETE FROM users_permissions WHERE user_id=? AND permission_name=?';
		$res = $db->performQuery($sql, array($userPermission->user_id, $userPermission->permission_name));

		return $res->isSuccess();
	}
};
