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

namespace EllinghamTech\PHPUserSystem\ObjectModels;

use EllinghamTech\PHPUserSystem\InternalAbstract\Permission;

class UserPermission extends Permission
{
	/** @var int The users ID */
	public $user_id;

	/** @var string The permission name */
	public $permission_name;
	/** @var int The permission value */
	public $permission_value = 0;

	/**
	 * UserPermission constructor.
	 *
	 * @param int $user_id
	 * @param array|null $permission
	 */
	public function __construct(int $user_id, ?array $permission)
	{
		$this->user_id = $user_id;

		if($permission != null)
			$this->populate($permission);
	}

	/**
	 * Populates the object properties from an array of values with the keys:
	 * permission_name, permission_value
	 *
	 * @param array $permission
	 */
	public function populate(array $permission) : void
	{
		if(isset($permission['permission_name'])) $this->permission_name = $permission['permission_name'];
		if(isset($permission['permission_value'])) $this->permission_value = $permission['permission_value'];
	}

	/**
	 * Checks where the user has the permission to perform to specific task.
	 *
	 * $requiredPermissionFlags can be a string containing the letters x (execute), r (read),
	 * m (modify), w (write), d (delete) or s (special).  It can also be an integer containing
	 * the necessary flags, e.g.
	 * READ_PERMISSION + WRITE_PERMISSION + MODIFY_PERMISSION = 14 = Read, Write (/create) and Modify
	 *
	 * @param string|int $requiredPermissionFlags (string using x,r,m,w,d,s OR int value 0 - 4095)
	 *
	 * @return bool True if permission allowed
	 */
	public function hasPermission($requiredPermissionFlags) : bool
	{
		if(is_string($requiredPermissionFlags))
			$requiredPermissionFlags = (int)$this->permissionStringToInt($requiredPermissionFlags);

		if($this->permission_value >= $requiredPermissionFlags) return true;

		return false;
	}

	/**
	 * Saves the user permission
	 *
	 * @return bool True on success, false on failure.
	 * @throws \Exception
	 */
	public function save() : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserPermission::save($this);
	}

	/**
	 * Deletes the user permission
	 *
	 * @return bool True on success, false on failure.
	 * @throws \Exception
	 */
	public function delete() : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserPermission::delete($this);
	}
};
