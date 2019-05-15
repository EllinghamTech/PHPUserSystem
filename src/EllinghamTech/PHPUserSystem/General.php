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

namespace EllinghamTech\PHPUserSystem;

/**
 * @deprecated 1.0 Do not use, refer to Helpers/ instead
 */
class General
{
	/**
	 * Checks if a user exists by User Name
	 *
	 * @param string $user_name
	 *
	 * @return bool
	 * @throws \Exception
	 * @deprecated 1.0 Do not use, refer to Helpers/ instead
	 */
	public function checkIfUserNameExists(string $user_name) : bool
	{
		return ObjectControllers\User::checkIfExists('user_name', $user_name);
	}

	/**
	 * Checks if a user exists by User Email
	 *
	 * @param string $user_email
	 *
	 * @return bool
	 * @throws \Exception
	 * @deprecated 1.0 Do not use, refer to Helpers/ instead
	 */
	public function checkIfUserEmailExists(string $user_email) : bool
	{
		return ObjectControllers\User::checkIfExists('user_email', $user_email);
	}

	/**
	 * Checks if a user exists by User Mobile.
	 *
	 * NOTE: This method is not yet part of the API and might change in the future.
	 *
	 * DO NOT USE
	 *
	 * @param string $user_mobile
	 *
	 * @deprecated 1.0 Do not use, refer to Helpers/ instead
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function checkIfUserMobileExists(string $user_mobile) : bool
	{
		return ObjectControllers\User::checkIfExists('user_mobile', $user_mobile);
	}

	public function forgotPasswordByUserName(string $user_name)
	{
		// TODO: Write this method
	}

	public function forgotPasswordByEmail(string $user_name)
	{
		// TODO: Write this method
	}
};
