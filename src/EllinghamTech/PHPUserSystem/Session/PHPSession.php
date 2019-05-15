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
use EllinghamTech\PHPUserSystem\UserFactory;

class PHPSession implements ISession
{
	protected $logged_in = false;
	protected $user_id = null;
	protected $session_created = null;
	protected $session = null;

	public function init() : void
	{
		@session_start();

		if(!isset($_SESSION['user_id'])) return;
		if(!isset($_SESSION['created'])) return;

		try
		{
			$this->logged_in = true;
			$this->user_id = $_SESSION['user_id'];
			$this->session_created = new \DateTime();
			$this->session_created->setTimestamp($_SESSION['created']);
			$this->session = $_SESSION;
		}
		catch(\Exception $e)
		{
			$this->logged_in = false;
			$this->user_id = null;
			$this->session_created = null;
			$this->session = $_SESSION;
		}
	}

	public function user() : ?User
	{
		if(!$this->logged_in) return null;

		return UserFactory::getUserByUserId($this->user_id);
	}

	public function userLogin(int $user_id) : bool
	{
		try
		{
			$user = UserFactory::getUserByUserId($user_id);

			$this->logged_in = true;
			$this->user_id = $_SESSION['user_id'] = $user->user_id;

			$_SESSION['created'] = time();
			$this->session_created = new \DateTime;
			$this->session_created->setTimestamp($_SESSION['created']);
			$this->session = $_SESSION;

			return true;
		}
		catch(\Exception $e)
		{
			return false;
		}
	}

	public function userLogout() : bool
	{
		@session_destroy();
		@session_unset();
		return true;
	}

	public function userLogoutAll() : bool
	{
		return false;
	}

	public function isLoggedIn() : bool
	{
		return $this->logged_in;
	}

	public function getUserId() : ?int
	{
		return $this->user_id;
	}

	public function setSessionMessage(string $name, string $value) : bool
	{
		$_SESSION['msg'][$name][] = $value;
		return true;
	}

	public function getSessionMessages(string $name) : ?array
	{
		return (isset($_SESSION['msg'][$name]) ? $_SESSION['msg'][$name] : null);
	}

	public function checkSessionMessages(string $name) : bool
	{
		return (isset($_SESSION['msg'][$name]) ? true : false);
	}

	public function clearSessionMessages(string $name) : void
	{
		if(isset($_SESSION['msg'][$name]))
			unset($_SESSION['msg'][$name]);
	}

	public function clearAllSessionMessages() : void
	{
		if(isset($_SESSION['msg']))
			unset($_SESSION['msg']);
	}
}
