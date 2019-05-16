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
use EllinghamTech\PHPUserSystem\ObjectModels\UserLimit;
use EllinghamTech\PHPUserSystem\ObjectModels\UserMeta;
use EllinghamTech\PHPUserSystem\ObjectModels\UserPermission;
use EllinghamTech\PHPUserSystem\ObjectModels\UserPreference;
use EllinghamTech\PHPUserSystem\ObjectModels\UserProfile;
use EllinghamTech\PHPUserSystem\UserFactory;
use EllinghamTech\PHPUserSystem\UserSystem;
use PHPUnit\Framework\TestCase;

require('phphead.php');

class UserTest extends TestCase
{
	public $username;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->username = 'test_'.uniqid();

		initUserSystem();

		UserSystem::$passwordHashAlgo = PASSWORD_DEFAULT;
	}

	public function testCreateUser()
	{
		$user = UserFactory::newUser();

		$user->user_name = $this->username;
		$user->setPassword('test_password');
		$userSave = $user->save();

		$this->assertTrue($userSave);

		// Check fields that should have been populated
		$this->assertNotNull($user->user_id);
		$this->assertNotNull($user->user_created);
		$this->assertTrue($user->verifyPassword('test_password'));
	}

	public function testGetUserProfile()
	{
		$user = UserFactory::newUser();

		$user->user_name = 'test_'.uniqid();
		$user->setPassword('password');
		$userSave = $user->save();

		$this->assertTrue($userSave);

		$profile = $user->getUserProfile();

		$this->assertTrue($profile instanceof UserProfile);
		$this->assertEquals($user->user_id, $profile->user_id);
		$this->assertNotNull($profile->profile_id);
	}

	public function testGetUserPermission()
	{
		$user = UserFactory::newUser();

		$user->user_name = 'test_'.uniqid();
		$user->setPassword('password');
		$userSave = $user->save();

		$this->assertTrue($userSave);

		$permission = $user->getUserPermission('test_permission');

		$this->assertTrue($permission instanceof UserPermission);
		$this->assertEquals($user->user_id, $permission->user_id);
	}

	public function testGetUserLimit()
	{
		$user = UserFactory::newUser();

		$user->user_name = 'test_'.uniqid();
		$user->setPassword('password');
		$userSave = $user->save();

		$this->assertTrue($userSave);

		$limit = $user->getUserLimit('test_limit');

		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals($user->user_id, $limit->user_id);
	}

	public function testGetUserMeta()
	{
		$user = UserFactory::newUser();

		$user->user_name = 'test_'.uniqid();
		$user->setPassword('password');
		$userSave = $user->save();

		$this->assertTrue($userSave);

		$meta = $user->getUserMeta('test_meta');

		$this->assertTrue($meta instanceof UserMeta);
		$this->assertEquals($user->user_id, $meta->user_id);
	}

	public function testGetUserPreference()
	{
		$user = UserFactory::newUser();

		$user->user_name = 'test_'.uniqid();
		$user->setPassword('password');
		$userSave = $user->save();

		$this->assertTrue($userSave);

		$preference = $user->getUserPreference('test_preference');

		$this->assertTrue($preference instanceof UserPreference);
		$this->assertEquals($user->user_id, $preference->user_id);
	}
}
