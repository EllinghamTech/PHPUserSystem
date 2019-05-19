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
use EllinghamTech\PHPUserSystem\UserFactory;
use EllinghamTech\PHPUserSystem\UserSystem;
use PHPUnit\Framework\TestCase;

class UserLimitTest extends TestCase
{
	use DatabaseUnit;

	public function testIsWithinLimit()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);

		$limit = $user->getUserLimit('test_limit');
		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals(1, $limit->user_id);
		$this->assertEquals(1000, $limit->limit_value);
		$this->assertEquals(1000, $limit->limit_refresh_value);

		$this->assertTrue($limit->isWithinLimit(100));
		$this->assertTrue($limit->isWithinLimit(1000));
		$this->assertFalse($limit->isWithinLimit(1100));
	}

	public function testDelete()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);

		$limit = $user->getUserLimit('test_limit');
		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals(1, $limit->user_id);
		$this->assertEquals(1000, $limit->limit_value);
		$this->assertEquals(1000, $limit->limit_refresh_value);

		$this->assertTrue($limit->delete());

		$limit = $user->getUserLimit('test_limit');
		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals(1, $limit->user_id);
		$this->assertEquals(0, $limit->limit_value);
	}

	public function testDrawDown()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);

		$limit = $user->getUserLimit('test_limit');
		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals(1, $limit->user_id);
		$this->assertEquals(1000, $limit->limit_value);
		$this->assertEquals(1000, $limit->limit_refresh_value);

		$this->assertTrue($limit->drawDown(10));

		$limit = $user->getUserLimit('test_limit');
		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals(1, $limit->user_id);
		$this->assertEquals(990, $limit->limit_value);
		$this->assertEquals(1000, $limit->limit_refresh_value);
	}

	public function testSave()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);

		$limit = $user->getUserLimit('test_limit');
		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals(1, $limit->user_id);
		$this->assertEquals(1000, $limit->limit_value);
		$this->assertEquals(1000, $limit->limit_refresh_value);

		$limit->limit_value = $limit->limit_refresh_value = 100;
		$this->assertTrue($limit->save());

		// Reload
		$limit = $user->getUserLimit('test_limit');
		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals(1, $limit->user_id);
		$this->assertEquals(100, $limit->limit_value);
		$this->assertEquals(100, $limit->limit_refresh_value);
	}

	public function testRefresh_yearly()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);

		$limit = $user->getUserLimit('refresh_test');
		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals(1, $limit->user_id);

		$limit->limit_refresh_value = 100;
		$limit->limit_refresh_when = 1420070400; // 1st Jan 2015, 00:00:00 UTC
		$limit->limit_refresh_interval = 1;
		$limit->limit_refresh_interval_unit = $limit::LIMIT_REFRESH_YEAR;

		// Should return 1st Jan of Next year
		$nextTimestamp = $limit->getNextValidRefreshTimestamp();

		$predictedNextTimestamp = strtotime((date('Y') + 1) . '-01-01 00:00 UTC');

		$this->assertTrue( $nextTimestamp == $predictedNextTimestamp );
	}

	public function testRefresh_monthly()
	{
		$user = UserFactory::getUserByUserId(1);
		$this->assertTrue($user instanceof User);

		$limit = $user->getUserLimit('refresh_test');
		$this->assertTrue($limit instanceof UserLimit);
		$this->assertEquals(1, $limit->user_id);

		$limit->limit_refresh_value = 100;
		$limit->limit_refresh_when = 1546300800; // 1st Jan 2019, 00:00:00 UTC
		$limit->limit_refresh_interval = 1;
		$limit->limit_refresh_interval_unit = $limit::LIMIT_REFRESH_MONTH;

		// Should return 1st Jan of Next year
		$nextTimestamp = $limit->getNextValidRefreshTimestamp();

		$predictedNextTimestamp = strtotime(date('Y') . '-'.(date('m')+1).'-01 00:00 UTC');

		$this->assertTrue( $nextTimestamp == $predictedNextTimestamp );
	}
}
