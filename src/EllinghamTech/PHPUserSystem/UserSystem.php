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
use EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException;
use EllinghamTech\PHPUserSystem\Session\ISession;
use EllinghamTech\PHPUserSystem\Session\PHPSession;

class UserSystem
{
	public static $db_tables_prefix = '';

	/**
	 * @var Wrapper[] An array of db connections
	 */
	protected static $db = array();

	/**
	 * @var null|int Specifies an algorithm to hash the password with, expects a
	 * PASSWORD_ constant value.  Must exist on the runtime environment.  NULL will
	 * result in PASSWORD_ARGON2I if support, PASSWORD_DEFAULT otherwise.
	 */
	public static $passwordHashAlgo = null;

	/**
	 * @var ISession
	 */
	protected static $session = null;

	/**
	 * UserSystem initialiser.
	 *
	 * @param Wrapper|Wrapper[] $db A single database connection, or multiple for each feature
	 * @param ISession|null $session
	 */
	public static function init($db, ?ISession $session = null) : void
	{
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

		if($session === null)
			self::$session = new PHPSession();
		else
			self::$session = $session;

		self::$session->init();

		if(self::$passwordHashAlgo === null)
		{
			if(defined('PASSWORD_ARGON2I')) self::$passwordHashAlgo = PASSWORD_ARGON2I;
			else self::$passwordHashAlgo = PASSWORD_DEFAULT;
		}
	}

	/**
	 * @param object $db
	 * @param string|null $for
	 */
	public static function setDb($db, ?string $for = null) : void
	{
		if($for == null) $for = 'default';

		self::$db[$for] = $db;
	}

	/**
	 * @param string $for
	 *
	 * @return Wrapper
	 * @throws ConfigurationException When databases have not been set up correctly
	 */
	public static function getDb(?string $for = null) : Wrapper
	{
		if($for != null && isset(self::$db[$for]))
			return self::$db[$for];
		else if(isset(self::$db['default']))
			return self::$db['default'];
		else
			throw new ConfigurationException('Configuration does not specify a suitable database connection');
	}

	/**
	 * Returns the current session object.
	 *
	 * @return ISession
	 * @throws ConfigurationException When initialisation has not occurred or failed
	 */
	public static function session() : ISession
	{
		if(self::$session === null) throw new ConfigurationException('Not Initialised');
		return self::$session;
	}
};
