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
use EllinghamTech\PHPUserSystem\ObjectModels\UserToken;
use EllinghamTech\PHPUserSystem\UserFactory;
use PHPUnit\Framework\TestCase;

class UserTokenTest extends TestCase
{
	use DatabaseUnit;

	public function testGetUser()
	{
		$userToken = UserFactory::getToken('L4O2u+xN7Utv9MOV1uMU+SCGgzq/U6kkYydwUiZ7+MU=');
		$this->assertTrue($userToken instanceof UserToken);
		$this->assertEquals('forgot_password', $userToken->token_type);

		$user = $userToken->getUser();
		$this->assertTrue($user instanceof User);
		$this->assertEquals(1, $user->user_id);
	}

	public function testInvalidate()
	{
		$userToken = UserFactory::getToken('L4O2u+xN7Utv9MOV1uMU+SCGgzq/U6kkYydwUiZ7+MU=');
		$this->assertTrue($userToken instanceof UserToken);
		$this->assertEquals('forgot_password', $userToken->token_type);
		$this->assertTrue($userToken->invalidate());

		// Reload
		$userToken = UserFactory::getToken('L4O2u+xN7Utv9MOV1uMU+SCGgzq/U6kkYydwUiZ7+MU=');
		$this->assertNull($userToken);
	}
}
