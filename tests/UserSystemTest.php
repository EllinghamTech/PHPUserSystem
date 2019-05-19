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

use EllinghamTech\Database\SQL\SQLite;
use EllinghamTech\Database\SQL\Wrapper;
use EllinghamTech\PHPUserSystem\Session\PHPSession;
use EllinghamTech\PHPUserSystem\UserSystem;
use PHPUnit\Framework\TestCase;

class UserSystemTest extends TestCase
{

	public function testGetSetDb()
	{
		$wrapper = new Wrapper();
		UserSystem::setDb($wrapper, 'Test');

		$this->assertTrue(UserSystem::getDb('Test') instanceof Wrapper);
	}

	public function testInit()
	{
		$wrapper = new SQLite();
		UserSystem::init(array(
			'default' => $wrapper,
			'Test' => $wrapper
		));

		$this->assertTrue(UserSystem::getDb('Test') instanceof SQLite);
		$this->assertTrue(UserSystem::getDb() instanceof SQLite);
	}

	public function testSession()
	{
		$_SESSION['user_id'] = 1;
		$_SESSION['created'] = 0;

		$session = new PHPSession();

		$wrapper = new SQLite();
		UserSystem::init($wrapper, $session);

		$this->assertTrue(UserSystem::session() instanceof PHPSession);
		$this->assertEquals(1, UserSystem::session()->getUserId());

		$_SESSION = array();
	}
}
