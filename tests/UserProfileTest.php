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
use PHPUnit\Framework\TestCase;

require('phphead.php');

class UserProfileTest extends TestCase
{
	use DatabaseUnit;

	public function testGetUser()
	{
		$userProfile = \EllinghamTech\PHPUserSystem\ObjectControllers\UserProfile::loadFromUserId(1);

		$this->assertTrue($userProfile instanceof UserProfile);
		$this->assertEquals('15ce037e43d2c0', $userProfile->profile_id);

		$user = $userProfile->getUser();

		$this->assertTrue($user instanceof User);
		$this->assertEquals(1, $user->user_id);
		$this->assertEquals('test_1', $user->user_name);
	}

	public function testSave()
	{
		$userProfile = \EllinghamTech\PHPUserSystem\ObjectControllers\UserProfile::loadFromUserId(2);

		$this->assertTrue($userProfile instanceof UserProfile);

		$userProfile->full_name = 'test';
		$profile_id = $userProfile->profile_id;
		$userProfile->save();

		$userProfile = \EllinghamTech\PHPUserSystem\ObjectControllers\UserProfile::loadFromProfileId($profile_id);

		$this->assertEquals(2, $userProfile->user_id);
		$this->assertEquals('test', $userProfile->full_name);
	}
}
