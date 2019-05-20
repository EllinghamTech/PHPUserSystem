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

use EllinghamTech\PHPUserSystem\Helpers\UserHelpers;
use EllinghamTech\PHPUserSystem\ObjectModels\User;
use EllinghamTech\PHPUserSystem\ObjectModels\UserToken;
use EllinghamTech\PHPUserSystem\UserFactory;
use PHPUnit\Framework\TestCase;

class UserHelpersTest extends TestCase
{
	use DatabaseUnit;

	public function testCheckIfUserNameExists()
	{
		$this->assertTrue(UserHelpers::checkIfUserNameExists('test_1'));
		$this->assertTrue(UserHelpers::checkIfUserNameExists('test_2'));
		$this->assertFalse(UserHelpers::checkIfUserNameExists('__test_1__'));
		$this->assertFalse(UserHelpers::checkIfUserNameExists('test_3*'));
		$this->assertFalse(UserHelpers::checkIfUserNameExists('test_1%'));
	}

	public function testCheckIfUserEmailExists()
	{
		$this->assertTrue(UserHelpers::checkIfUserEmailExists('blackhole~1@ellingham.dev'));
		$this->assertTrue(UserHelpers::checkIfUserEmailExists('blackhole~2@ellingham.dev'));
		$this->assertFalse(UserHelpers::checkIfUserEmailExists('blackhole~%@ellingham.dev'));
		$this->assertFalse(UserHelpers::checkIfUserEmailExists('blackhole~1%'));
		$this->assertFalse(UserHelpers::checkIfUserEmailExists('%'));
	}

	public function testForgotPasswordByUserName()
	{
		$userToken = UserHelpers::forgotPasswordByUserName('test_1');
		$this->assertTrue($userToken instanceof UserToken);
		$this->assertEquals(1, $userToken->user_id);
		$this->assertEquals('forgot_password', $userToken->token_type);

		$userToken = UserHelpers::forgotPasswordByUserName('does_not_exist');
		$this->assertNull($userToken);
	}

	public function testForgotPasswordByUserEmail()
	{
		$userToken = UserHelpers::forgotPasswordByUserEmail('blackhole~1@ellingham.dev');
		$this->assertTrue($userToken instanceof UserToken);
		$this->assertEquals(1, $userToken->user_id);
		$this->assertEquals('forgot_password', $userToken->token_type);

		$userToken = UserHelpers::forgotPasswordByUserEmail('does_not_exist');
		$this->assertNull($userToken);
	}

	public function testForgotPasswordByUserId()
	{
		$userToken = UserHelpers::forgotPasswordByUserId(1);
		$this->assertTrue($userToken instanceof UserToken);
		$this->assertEquals(1, $userToken->user_id);
		$this->assertEquals('forgot_password', $userToken->token_type);

		$userToken = UserHelpers::forgotPasswordByUserId(1000);
		$this->assertNull($userToken);
	}

	public function testGetUserByForgotPasswordToken()
	{
		$user = UserHelpers::getUserByForgotPasswordToken('L4O2u+xN7Utv9MOV1uMU+SCGgzq/U6kkYydwUiZ7+MU=');
		$this->assertTrue($user instanceof User);
		$this->assertEquals(1, $user->user_id);

		$user = UserHelpers::getUserByForgotPasswordToken('does_not_exist');
		$this->assertNull($user);
	}

	public function testForgotPassword()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);

		$userToken = UserHelpers::forgotPassword($user);
		$this->assertTrue($userToken instanceof UserToken);
		$this->assertEquals(1, $userToken->user_id);
		$this->assertEquals('forgot_password', $userToken->token_type);
	}

	public function testChangeUserPasswordByForgotPasswordToken()
	{
		$this->assertTrue(UserHelpers::changeUserPasswordByForgotPasswordToken('L4O2u+xN7Utv9MOV1uMU+SCGgzq/U6kkYydwUiZ7+MU=', 'test_password_314'));

		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);
		$this->assertEquals(1, $user->user_id);
		$this->assertTrue($user->verifyPassword('test_password_314'));
	}
}
