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
 * Class User2dMatrixPermissions
 *
 * @package EllinghamTech\PHPUserSystem\AbstractModels
 */
abstract class User2dMatrixPermissions extends Permission
{
	/** @var int The user ID */
	public $user_id;

	public $permission_name;

	public $resource_id_1;

	public $resource_id_2;

	public $permission_value;

	/**
	 * Sets up the class.  Be sure the parent class implements the base constructor, or
	 * its behaviour, if you override it.
	 *
	 * @param int $user_id
	 * @param string $permission_name
	 * @param $resource_id_1
	 * @param $resource_id_2
	 */
	public function __construct(int $user_id, string $permission_name, $resource_id_1, $resource_id_2)
	{
		$this->user_id = $user_id;
		$this->permission_name = $permission_name;
		$this->resource_id_1 = $resource_id_1;
		$this->resource_id_2 = $resource_id_2;

		$this->init();
	}

	/**
	 * Checks if the user has the required permission.
	 *
	 * @param int|string $requiredPermissionFlags
	 *
	 * @return bool|null True if the user has the required permissions (or more), null on undefined permission
	 */
	public function hasPermission($requiredPermissionFlags) : ?bool
	{
		if(is_string($requiredPermissionFlags))
			$requiredPermissionFlags = (int)$this->permissionStringToInt($requiredPermissionFlags);

		if($this->permission_value == null) return null;

		if($this->permission_value >= (int)$requiredPermissionFlags) return true;

		return false;
	}

	/**
	 * Loads the user permission.
	 */
	abstract protected function init() : void;

	/**
	 * Saves the user permission.
	 *
	 * @return bool
	 */
	abstract public function save() : bool;

	/**
	 * Deletes the user permission.
	 *
	 * @return bool
	 */
	abstract public function delete() : bool;
};
