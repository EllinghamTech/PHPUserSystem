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

namespace UserLimits;

require('phphead.php');

use EllinghamTech\PHPUserSystem\ObjectModels\UserLimit;
use EllinghamTech\PHPUserSystem\UserFactory;
use PHPUnit\Framework\TestCase;

class UserLimitTests extends TestCase
{
	public $limit = null;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

		initUserSystem();

		// See if user with ID = 1 exists, if not create
		$user = UserFactory::getUserByUserId(1);

		if ($user == null)
		{
			$user = UserFactory::newUser();
			$user->user_name = 'Test' . uniqid();
			$user->save();
		}

		$this->limit = $user->getUserLimit('test_limit');
	}

	public function testRefresh_yearly1()
	{
		$this->limit->limit_refresh_when = 1546300800; // 1st Jan 2019, 00:00:00 UTC
		$this->limit->limit_refresh_interval = 1;
		$this->limit->limit_refresh_interval_unit = $this->limit::LIMIT_REFRESH_YEAR;

		// Should return 1st Jan of Next year
		$nextTimestamp = $this->limit->getNextValidRefreshTimestamp();

		$predictedNextTimestamp = strtotime((date('Y') + 1) . '-01-01 00:00 UTC');

		$this->assertTrue( $nextTimestamp == $predictedNextTimestamp );
	}

	public function testRefresh_yearly2()
	{
		$this->limit->limit_refresh_when = 1420070400; // 1st Jan 2015, 00:00:00 UTC
		$this->limit->limit_refresh_interval = 1;
		$this->limit->limit_refresh_interval_unit = $this->limit::LIMIT_REFRESH_YEAR;

		// Should return 1st Jan of Next year
		$nextTimestamp = $this->limit->getNextValidRefreshTimestamp();

		$predictedNextTimestamp = strtotime((date('Y') + 1) . '-01-01 00:00 UTC');

		$this->assertTrue( $nextTimestamp == $predictedNextTimestamp );
	}

	public function testRefresh_monthly()
	{
		$this->limit->limit_refresh_when = 1546300800; // 1st Jan 2019, 00:00:00 UTC
		$this->limit->limit_refresh_interval = 1;
		$this->limit->limit_refresh_interval_unit = $this->limit::LIMIT_REFRESH_MONTH;

		// Should return 1st Jan of Next year
		$nextTimestamp = $this->limit->getNextValidRefreshTimestamp();

		$predictedNextTimestamp = strtotime(date('Y') . '-'.(date('m')+1).'-01 00:00 UTC');

		$this->assertTrue( $nextTimestamp == $predictedNextTimestamp );
	}

	public function testRefresh_yearly()
	{
		$this->limit->limit_refresh_when = 1420070400; // 1st Jan 2015, 00:00:00 UTC
		$this->limit->limit_refresh_interval = 1;
		$this->limit->limit_refresh_interval_unit = $this->limit::LIMIT_REFRESH_YEAR;

		// Should return 1st Jan of Next year
		$nextTimestamp = $this->limit->getNextValidRefreshTimestamp();

		$predictedNextTimestamp = strtotime(date('Y')+1 . '-01-01 00:00 UTC');

		$this->assertTrue( $nextTimestamp == $predictedNextTimestamp );
	}
}
