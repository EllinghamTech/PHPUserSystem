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

use EllinghamTech\Database\SQL\Wrapper;
use EllinghamTech\PHPUserSystem\Session\ISession;
use EllinghamTech\PHPUserSystem\Session\PHPSession;

class UserSystem
{
	public static $db_tables_prefix = '';

	protected static $db = array();

	/**
	 * @var ISession
	 */
	protected static $session = null;

	/** @var General */
	protected static $general = null;

	public const PHPSessions = 'phpsession';
	public const DBSessions = 'dbsession';

	/**
	 * UserSystem initialiser.
	 *
	 * @param Wrapper|Wrapper[] $db
	 * @param string $sessionType
	 */
	public static function init($db, string $sessionType=self::PHPSessions)
	{
		self::$general = new General();

		if(!is_array($db))
		{
			self::setDb($db);
		}
		else
		{
			foreach($db as $name => $obj)
			{
				self::setDb($obj, $name);
			}
		}

		switch($sessionType)
		{
			case self::PHPSessions:
				self::$session = new PHPSession();
		}
	}

	/**
	 * @param object $db
	 * @param string|null $for
	 */
	public static function setDb($db, ?string $for=null) : void
	{
		if($for == null) $for = 'default';

		self::$db[$for] = $db;
	}

	/**
	 * @param null|string $for
	 *
	 * @return Wrapper
	 */
	public static function getDb(?string $for) : ?Wrapper
	{
		if($for != null && isset(self::$db[$for]))
			return self::$db[$for];
		else
			return self::$db['default'];
	}

	public static function session() : ?ISession
	{
		return self::$session;
	}
};
