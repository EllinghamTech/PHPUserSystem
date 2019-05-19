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
use EllinghamTech\PHPUserSystem\Session\PHPSession;
use EllinghamTech\PHPUserSystem\UserSystem;
use PHPUnit\Framework\TestCase;

class PHPSessionTest extends TestCase
{
	use DatabaseUnit
	{
		setUp as baseSetUp;
	}

	public function setUp(): void
	{
		$this->markTestSkipped('Issue with this test with Travis CI PHP 7.1.  Test succeeds under PHP 7.1 on local machines.');

		$this->baseSetUp();

		$_SESSION = array(
			'user_id' => 1,
			'created' => time(),
			'msg' => array(
				'test' => array(
					'This is a test session message'
				)
			)
		);

		UserSystem::session()->init();
		if(UserSystem::session()->getLastError() !== null) throw new Exception(UserSystem::session()->getLastError());
	}

	public function tearDown() : void
	{
		$_SESSION = null;
	}

	public function testUserLogin()
	{
		$this->assertTrue(UserSystem::session()->userLogin(2));

		$this->assertEquals(2, UserSystem::session()->getUserId());
	}

	public function testUser()
	{
		$this->assertTrue(UserSystem::session()->user() instanceof User);
		$this->assertEquals(1, UserSystem::session()->user()->user_id);
	}

	public function testUserLogout()
	{
		$this->assertTrue(UserSystem::session()->userLogout());
		$this->assertNull(UserSystem::session()->getUserId());
	}

	public function testIsLoggedIn()
	{
		$this->assertTrue(UserSystem::session()->isLoggedIn());
	}

	public function testGetUserId()
	{
		$this->assertEquals(1, UserSystem::session()->getUserId());
	}

	public function testClearAllSessionMessages()
	{
		$this->assertTrue(UserSystem::session()->checkSessionMessages('test'));

		UserSystem::session()->clearAllSessionMessages();

		$this->assertFalse(UserSystem::session()->checkSessionMessages('test'));
	}

	public function testSetSessionMessage()
	{
		$this->assertTrue(UserSystem::session()->setSessionMessage('message_name', 'message_value'));
		$this->assertTrue(UserSystem::session()->checkSessionMessages('message_name'));
	}

	public function testGetSessionMessages()
	{
		$this->assertContains('This is a test session message', UserSystem::session()->getSessionMessages('test'));
	}

	public function testCheckSessionMessages()
	{
		$this->assertTrue(UserSystem::session()->checkSessionMessages('test'));
		$this->assertFalse(UserSystem::session()->checkSessionMessages('does_not_exist'));
	}

	public function testClearSessionMessages()
	{
		$this->assertTrue(UserSystem::session()->setSessionMessage('message_name', 'message_value'));
		$this->assertTrue(UserSystem::session()->checkSessionMessages('message_name'));
		$this->assertTrue(UserSystem::session()->checkSessionMessages('test'));

		UserSystem::session()->clearSessionMessages('test');

		$this->assertFalse(UserSystem::session()->checkSessionMessages('test'));
		$this->assertTrue(UserSystem::session()->checkSessionMessages('message_name'));
	}
}
