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
use EllinghamTech\PHPUserSystem\ObjectModels\UserProfile;
use EllinghamTech\PHPUserSystem\ObjectModels\UserToken;
use EllinghamTech\PHPUserSystem\UserFactory;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
	use DatabaseUnit;

	public function testNewUser()
	{
		$user = UserFactory::newUser();
		$this->assertTrue($user instanceof User);
		$this->assertNull($user->user_id);
	}

	public function testGetUserByUserName()
	{
		$user = UserFactory::getUserByUserName('test_3');
		$this->assertTrue($user instanceof User);
		$this->assertEquals(3, $user->user_id);

		$user = UserFactory::getUserByUserName('does_not_exist');
		$this->assertNull($user);
	}

	public function testGetUserByUserEmail()
	{
		$user = UserFactory::getUserByUserEmail('blackhole~3@ellingham.dev');
		$this->assertTrue($user instanceof User);
		$this->assertEquals(3, $user->user_id);

		$user = UserFactory::getUserByUserEmail('does_not_exist');
		$this->assertNull($user);
	}

	public function testGetUserProfileByUserId()
	{
		$userProfile = UserFactory::getUserProfileByUserId(1);
		$this->assertTrue($userProfile instanceof UserProfile);
		$this->assertEquals(1, $userProfile->user_id);
		$this->assertEquals('15ce037e43d2c0', $userProfile->profile_id);

		$userProfile = UserFactory::getUserProfileByUserId(9);
		$this->assertNull($userProfile);
	}

	public function testGetUserProfileByProfileId()
	{
		$userProfile = UserFactory::getUserProfileByProfileId('15ce037e43d2c0');
		$this->assertTrue($userProfile instanceof UserProfile);
		$this->assertEquals(1, $userProfile->user_id);

		$userProfile = UserFactory::getUserProfileByProfileId('does_not_exist');
		$this->assertNull($userProfile);
	}

	public function testGetToken()
	{
		$userToken = UserFactory::getToken('L4O2u+xN7Utv9MOV1uMU+SCGgzq/U6kkYydwUiZ7+MU=');
		$this->assertTrue($userToken instanceof UserToken);
		$this->assertEquals(1, $userToken->user_id);

		$userToken = UserFactory::getToken('2ZE666W4qtvZbijz82VKAJiSYRorm821We+elggxwVg=');
		$this->assertNull($userToken);

		$userToken = UserFactory::getToken('2ZE666W4qtvZbijz82VKAJiSYRorm821We+elggxwVg=', true);
		$this->assertTrue($userToken instanceof UserToken);
		$this->assertEquals(1, $userToken->user_id);
	}

	public function testGetUserByUserId()
	{
		$user = UserFactory::getUserByUserId(3);
		$this->assertTrue($user instanceof User);
		$this->assertEquals(3, $user->user_id);

		$user = UserFactory::getUserByUserId(9);
		$this->assertNull($user);
	}
}
