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

namespace EllinghamTech\PHPUserSystem\InternalAbstract;

/**
 * An abstract class that defines the Permission constants
 * and basic methods.
 *
 * @package EllinghamTech\PHPUserSystem\InternalAbstract
 */
abstract class Permission
{
	/**
	 * The permission is undefined/unknown
	 */
	public const UNDEFINED_PERMISSION = null;

	/**
	 * No permission granted
	 */
	public const NO_PERMISSION = 0;

	/**
	 * Permission to execute
	 */
	public const EXECUTE_PERMISSION = 1;

	/**
	 * Permission to read
	 */
	public const READ_PERMISSION = 2;

	/**
	 * Permission to modify
	 */
	public const MODIFY_PERMISSION = 4;

	/**
	 * Permission to write
	 */
	public const WRITE_PERMISSION = 8;

	/**
	 * Permission to delete
	 */
	public const DELETE_PERMISSION = 16;

	/**
	 * Permission to "special" e.g. perform a certain task/function
	 */
	public const SPECIAL_PERMISSION = 32;

	/**
	 * A permission that is reserved for custom usage
	 */
	public const CUSTOM_PERMISSION_1 = 64;

	/**
	 * A permission that is reserved for custom usage
	 */
	public const CUSTOM_PERMISSION_2 = 128;

	/**
	 * A permission that is reserved for custom usage
	 */
	public const CUSTOM_PERMISSION_3 = 256;

	/**
	 * A permission that is reserved for custom usage
	 */
	public const CUSTOM_PERMISSION_4 = 512;

	/**
	 * A permission that is reserved for custom usage
	 */
	public const CUSTOM_PERMISSION_5 = 1024;

	/**
	 * A permission that is reserved for custom usage
	 */
	public const CUSTOM_PERMISSION_6 = 2048;

	/**
	 * Takes a string, such as "xr", and returns the integer/binary flag equivalent.
	 *
	 * Cannot handle Custom Permissions (CUSTOM_PERMISSION_x).
	 *
	 * x = Execute (EXECUTE_PERMISSION, 1)
	 * r = Read (READ_PERMISSION, 2)
	 * m = Modify (MODIFY_PERMISSION, 4)
	 * w = Write (WRITE_PERMISSION, 8)
	 * d = Delete (DELETE_PERMISSION, 16)
	 * s = Special (SPECIAL_PERMISSION, 32)
	 *
	 * Examples:
	 * xr => EXECUTE_PERMISSION + READ_PERMISSION = 3
	 * rw => READ_PERMISSION + WRITE_PERMISSION = 10
	 *
	 * @param string $requiredPermissionFlags The permission string
	 *
	 * @return int The permission flags integer
	 */
	public function permissionStringToInt(string $requiredPermissionFlags) : int
	{
		$required_string = strtolower(trim($requiredPermissionFlags));
		if(strlen($required_string) > 6) throw new \UnexpectedValueException();

		$intPermissionFlags = 0;

		if(strpos($required_string, 'x') !== false) $intPermissionFlags += self::EXECUTE_PERMISSION;
		if(strpos($required_string, 'r') !== false) $intPermissionFlags += self::READ_PERMISSION;
		if(strpos($required_string, 'm') !== false) $intPermissionFlags += self::MODIFY_PERMISSION;
		if(strpos($required_string, 'w') !== false) $intPermissionFlags += self::WRITE_PERMISSION;
		if(strpos($required_string, 'd') !== false) $intPermissionFlags += self::DELETE_PERMISSION;
		if(strpos($required_string, 's') !== false) $intPermissionFlags += self::SPECIAL_PERMISSION;

		return $intPermissionFlags;
	}
}
