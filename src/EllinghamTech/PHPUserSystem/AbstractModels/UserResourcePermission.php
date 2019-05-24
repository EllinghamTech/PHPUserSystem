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

namespace EllinghamTech\PHPUserSystem\AbstractModels;

use EllinghamTech\PHPUserSystem\InternalAbstract\Permission;

/**
 * Abstract class for granular permissions within a collection.
 *
 * An example is having a departments collection.  The user might have
 * different permissions depending on the department (a resource within the collection).
 *
 * This abstract class allows you to implement this custom behaviour in a more controlled way.
 *
 * You should create a new class for each collection.  Example:
 * class UserDepartmentPermissions extends UserResourcePermission { ... }
 * class UserTopicPermissions extends UserResourcePermission { ... }
 *
 * This class is not an exact science but it can help add structure to your project.
 *
 * @package EllinghamTech\PHPUserSystem\AbstractModels
 */
abstract class UserResourcePermission extends Permission
{
	/** @var int The user ID */
	public $user_id;

	/** @var mixed Resource ID */
	public $resource_id;

	/** @var null|int Permission Value */
	public $permission_value = null;

	/** @var null|string The permission name */
	public $permission_name = null;

	/**
	 * Sets up the class.  Be sure the parent class implements the base constructor, or
	 * its behaviour, if you override it.
	 *
	 * @param int $user_id
	 * @param string $permission_name
	 * @param mixed $resource_id
	 */
	public function __construct(int $user_id, string $permission_name, $resource_id)
	{
		$this->user_id = $user_id;
		$this->permission_name = $permission_name;
		$this->resource_id = $resource_id;

		$this->load();
	}

	/**
	 * Checks if the user has the required permissions for a particular resource ID.
	 *
	 * @param int|string $requiredPermissionFlags
	 *
	 * @return bool True if the user has the required permissions (or more)
	 */
	public function hasPermission($requiredPermissionFlags) : ?bool
	{
		if(is_string($requiredPermissionFlags))
			$requiredPermissionFlags = (int)$this->permissionStringToInt($requiredPermissionFlags);

		$permissionValue = $this->permission_value;

		if($permissionValue == null) return null;

		if($permissionValue >= (int)$requiredPermissionFlags) return true;

		return false;
	}

	/**
	 * Loads the permission model.
	 *
	 * Loads the permission model.  This method should change the $permission_value property
	 * to the assigned permission value.
	 *
	 * @return bool
	 */
	abstract public function load() : bool;

	/**
	 * Saves the user permission for the resource ID.
	 *
	 * @param null|string $permissionName
	 * @param int $permission_value
	 *
	 * @return bool
	 */
	abstract public function save(?string $permissionName, int $permission_value) : bool;

	/**
	 * Deletes the user permission for the resource ID.
	 *
	 * @param null|string $permissionName
	 *
	 * @return bool
	 */
	abstract public function delete(?string $permissionName = null) : bool;
};
