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

namespace EllinghamTech\PHPUserSystem\Session;

use EllinghamTech\PHPUserSystem\ObjectModels\User;
use EllinghamTech\Session\IBasicSession;

interface ISession extends IBasicSession
{
	/**
	 * Initialise the session.  Is called by UserSystem one UserSystem initialisation.
	 */
	public function init() : void;

	/**
	 * Logs a user in by user ID.  CHECK THE USERS PASSWORD, ETC BEFORE USING THIS METHOD.
	 *
	 * @param int $user_id
	 *
	 * @return bool
	 */
	public function userLogin(int $user_id) : bool;

	/**
	 * Logs the user out
	 *
	 * @return bool
	 */
	public function userLogout() : bool;

	/**
	 * Logs the user out of all active sessions for the current user
	 *
	 * @return bool
	 */
	public function userLogoutAll() : bool;

	/**
	 * If logged in, gets the user object
	 *
	 * @return User|null
	 */
	public function user() : ?User;

	/**
	 * Returns true if the current session has an active user login
	 *
	 * @return bool
	 */
	public function isLoggedIn() : bool;

	/**
	 * Gets the user ID
	 *
	 * @return int|null
	 */
	public function getUserId() : ?int;

	/**
	 * Returns the last error caused within the session.  Null if there
	 * has been no error.
	 *
	 * @return \Exception|null
	 */
	public function getLastError() : ?\Exception;
};
