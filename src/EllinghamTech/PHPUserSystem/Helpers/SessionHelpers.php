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

namespace EllinghamTech\PHPUserSystem\Helpers;

use EllinghamTech\PHPUserSystem\UserFactory;
use EllinghamTech\PHPUserSystem\UserSystem;

class SessionHelpers
{
	/**
	 * Attempts to log the user in using their username and password.
	 *
	 * @param string $user_name User Name
	 * @param string $password Password (plain text)
	 *
	 * @return bool True on success, false on failure
	 */
	public static function tryLoginUserName(string $user_name, string $password) : bool
	{
		try
		{
			$user = UserFactory::getUserByUserName($user_name);

			if($user == null) return false;

			// Verifies the password is correct against the stored hashed password
			if(!$user->verifyPassword($password))
				return false;

			return UserSystem::session()->userLogin($user->user_id);
		}
		catch(\Exception $e)
		{
			return false;
		}
	}

	/**
	 * Attempts to log the user in using their email and password.
	 *
	 * @param string $user_email User Email
	 * @param string $password Password (plain text)
	 *
	 * @return bool True on success, false on failure
	 */
	public static function tryLoginUserEmail(string $user_email, string $password) : bool
	{
		try
		{
			$user = UserFactory::getUserByUserEmail($user_email);

			if($user == null) return false;

			// Verifies the password is correct against the stored hashed password
			if(!$user->verifyPassword($password))
				return false;

			return UserSystem::session()->userLogin($user->user_id);
		}
		catch(\Exception $e)
		{
			return false;
		}
	}
};
