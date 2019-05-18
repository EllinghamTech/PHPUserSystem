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

use EllinghamTech\PHPUserSystem\ObjectModels\UserLimit;
use EllinghamTech\PHPUserSystem\ObjectModels\UserPermission;
use EllinghamTech\PHPUserSystem\ObjectModels\UserPreference;
use EllinghamTech\PHPUserSystem\ObjectModels\UserProfile;
use EllinghamTech\PHPUserSystem\ObjectModels\UserToken;
use EllinghamTech\PHPUserSystem\UserFactory;
use EllinghamTech\PHPUserSystem\UserSystem;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
	use DatabaseUnit;

	public function testGetUserLimit()
	{
		$user = UserFactory::getUserByUserId(1);
		$limit = $user->getUserLimit('test_limit');

		$this->assertTrue($limit instanceof UserLimit);
		// The limit should have refreshed, so expecting the refresh value
		$this->assertEquals(1000, $limit->limit_value);

		$limit_created = $user->getUserLimit('does_not_exist');

		$this->assertTrue($limit_created instanceof UserLimit);
		$this->assertNull($limit_created->limit_value);
	}

	public function testGetUserProfile()
	{
		$user = UserFactory::getUserByUserId(1);
		$profile = $user->getUserProfile();

		$this->assertTrue($profile instanceof UserProfile);
		$this->assertEquals('15ce037e43d2c0', $profile->profile_id);
		$this->assertEquals('Joey', $profile->display_name);
		$this->assertEquals('Joe Bloggs', $profile->full_name);
		$this->assertEquals('I am a test user.', $profile->profile_summary);

		$user = UserFactory::getUserByUserId(2);
		$profile = $user->getUserProfile();

		$this->assertTrue($profile instanceof UserProfile);
		$this->assertTrue(is_string($profile->profile_id));
	}

	public function testSave()
	{
		$user = UserFactory::getUserByUserId(1);
		$user->user_email = 'test';
		$user->user_mobile = 'mobile';
		$this->assertTrue($user->save());

		// Get again and verify
		$user = UserFactory::getUserByUserId(1);
		$this->assertEquals('test', $user->user_email);
		$this->assertEquals('mobile', $user->user_mobile);
	}

	public function testGetUserPreference()
	{
		$user = UserFactory::getUserByUserId(1);
		$preference = $user->getUserPreference('datetime_format');

		$this->assertEquals('Y-m-d H:i:s', $preference->preference_value);

		$preference = $user->getUserPreference('test');
		$this->assertTrue($preference instanceof UserPreference);
		$this->assertNull($preference->preference_value);
	}

	public function testCreateUserToken()
	{
		$user = UserFactory::getUserByUserId(1);

		$token = $user->createUserToken('test_token');

		$this->assertTrue(is_string($token->token));
		$this->assertEquals('test_token', $token->token_type);
	}

	public function testVerifyPassword()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user->verifyPassword('test'));
	}

	public function testGetUserMeta()
	{
		$user = UserFactory::getUserByUserId(2);
		$meta = $user->getUserMeta('random');

		$this->assertContains('3.14159', $meta->meta_value);
		$this->assertContains('1.77245', $meta->meta_value);
		$this->assertContains('9.86960', $meta->meta_value);
	}

	public function testGetUserToken()
	{
		$user = UserFactory::getUserByUserId(1);
		$token = $user->getUserToken('L4O2u+xN7Utv9MOV1uMU+SCGgzq/U6kkYydwUiZ7+MU=');

		$this->assertTrue($token instanceof UserToken);
		$this->assertEquals('forgot_password', $token->token_type);

		$token = $user->getUserToken('2ZE666W4qtvZbijz82VKAJiSYRorm821We+elggxwVg=');

		$this->assertNull($token);
	}

	public function testGetUserPermission()
	{
		$user = UserFactory::getUserByUserId(1);
		$permission = $user->getUserPermission('test_permission');
		$this->assertTrue($permission instanceof UserPermission);
		$this->assertTrue($permission->hasPermission($permission::EXECUTE_PERMISSION));
	}
}
