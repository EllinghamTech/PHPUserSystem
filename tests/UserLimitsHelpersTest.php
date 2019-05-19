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

use EllinghamTech\PHPUserSystem\Helpers\UserLimitsHelpers;
use EllinghamTech\PHPUserSystem\ObjectModels\User;
use EllinghamTech\PHPUserSystem\ObjectModels\UserLimit;
use EllinghamTech\PHPUserSystem\UserFactory;
use PHPUnit\Framework\TestCase;

class UserLimitsHelpersTest extends TestCase
{
	use DatabaseUnit;

	public function testDoAllRequiredRefreshes()
	{
		$this->assertEquals(4, UserLimitsHelpers::doAllRequiredRefreshes());

		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);
		$this->assertEquals(1, $user->user_id);

		$limit = $user->getUserLimit('test_limit');
		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals(1, $limit->limit_refresh_interval);
		$this->assertEquals(1000, $limit->limit_value);

		// Users 2, 3 and 4
		for($x=2; $x<5; $x++)
		{
			$user = UserFactory::getUserByUserId($x);
			$this->assertTrue($user instanceof User);
			$this->assertEquals($x, $user->user_id);

			$limit = $user->getUserLimit('test_limit');
			$this->assertTrue($limit instanceof UserLimit);
			$this->assertEquals(1, $limit->limit_refresh_interval);
			$this->assertEquals(100, $limit->limit_value);
		}
	}
}
