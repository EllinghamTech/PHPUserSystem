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

use EllinghamTech\PHPUserSystem\ObjectModels\User;
use EllinghamTech\PHPUserSystem\ObjectModels\UserPermission;
use EllinghamTech\PHPUserSystem\UserFactory;
use PHPUnit\Framework\TestCase;

class UserPermissionTest extends TestCase
{
	use DatabaseUnit;

	public function testSave()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);

		$permission = $user->getUserPermission('test_permission');
		$this->assertTrue($permission instanceof UserPermission);

		$permission->permission_value += $permission::WRITE_PERMISSION;
		$this->assertTrue($permission->save());

		// Reload permission
		$permission = $user->getUserPermission('test_permission');
		$this->assertTrue($permission instanceof UserPermission);

		$this->assertTrue($permission->hasPermission($permission::EXECUTE_PERMISSION));
	}

	public function testHasPermission()
	{
		$user = UserFactory::getUserByUserId(2);
		$this->assertTrue($user instanceof User);

		$permission = $user->getUserPermission('test_permission');
		$this->assertTrue($permission instanceof UserPermission);

		$this->assertTrue($permission->hasPermission('rw'));
		$this->assertTrue($permission->hasPermission($permission::EXECUTE_PERMISSION));
		$this->assertTrue($permission->hasPermission($permission::WRITE_PERMISSION + $permission::EXECUTE_PERMISSION));
		$this->assertFalse($permission->hasPermission($permission::CUSTOM_PERMISSION_1));
	}

	public function testDelete()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);

		$permission = $user->getUserPermission('test_permission');
		$this->assertTrue($permission instanceof UserPermission);

		$this->assertTrue($permission->delete());

		$permission = $user->getUserPermission('test_permission');
		$this->assertTrue($permission instanceof UserPermission);

		$this->assertEquals($permission::NO_PERMISSION, $permission->permission_value);
	}

	public function testPermissionStringToInt()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);

		$permission = $user->getUserPermission('test_permission');
		$this->assertTrue($permission instanceof UserPermission);

		$this->assertEquals($permission::READ_PERMISSION + $permission::WRITE_PERMISSION, $permission->permissionStringToInt('rw'));
	}
}
