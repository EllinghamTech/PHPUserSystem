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

/**
 * @internal
 */
namespace EllinghamTech\PHPUserSystem\ObjectControllers;

use EllinghamTech\PHPUserSystem\UserSystem;

/**
 * @internal
 */
class UserLimit
{
	/**
	 * Loads a user limit from User ID and Limit Name.  If the limit does not
	 * exist in the database, a new limit is created.
	 *
	 * @param int $user_id
	 * @param string $limitName
	 * @param bool $lockForUpdate
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserLimit|null $userLimit
	 *
	 * @return \EllinghamTech\PHPUserSystem\ObjectModels\UserLimit
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function loadFromUserIdAndLimitName(int $user_id, string $limitName, bool $lockForUpdate = false, ?\EllinghamTech\PHPUserSystem\ObjectModels\UserLimit $userLimit=null) : \EllinghamTech\PHPUserSystem\ObjectModels\UserLimit
	{
		$obj = $userLimit ?? new \EllinghamTech\PHPUserSystem\ObjectModels\UserLimit($user_id);
		$db = UserSystem::getDb('UsersLimits');
		$obj->limit_name = $limitName;

		switch($db->database_type)
		{
			case 'SQLite':
				if($lockForUpdate && !isset($sql)) throw new \RuntimeException('Database does not support row locking');

				$sql = 'SELECT * FROM users_limits WHERE user_id=? AND limit_name=?';

				$res = $db->performQuery($sql, [$user_id, $obj->limit_name]);
				$row = $res->fetchArray();

				break;
			default:
				if($lockForUpdate)
					$sql = 'SELECT * FROM users_limits WHERE user_id=? AND limit_name=? FOR UPDATE';
				else
					$sql = 'SELECT * FROM users_limits WHERE user_id=? AND limit_name=?';

				$res = $db->performQuery($sql, [$user_id, $obj->limit_name]);
				$row = $res->fetchArray();
		}

		if($row)
		{
			$obj->limit_name = $row['limit_name'];
			$obj->limit_value = $row['limit_value'];
			$obj->limit_refresh_value = $row['limit_refresh_value'];
			$obj->limit_refresh_when = $row['limit_refresh_when'];
			$obj->limit_refresh_interval = $row['limit_refresh_interval'];
			$obj->limit_refresh_interval_unit = $row['limit_refresh_interval_unit'];
		}

		if($userLimit == null && $obj->needsRefresh())
			$obj->doRefresh();

		return $obj;
	}

	/**
	 * Saves the user limit (currently using SQL REPLACE).
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserLimit $userLimit
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function save(\EllinghamTech\PHPUserSystem\ObjectModels\UserLimit $userLimit) : bool
	{
		$db = UserSystem::getDb('UsersLimits');

		$sql = 'REPLACE INTO users_limits (user_id, limit_name, limit_value, limit_refresh_value, limit_refresh_when, limit_refresh_interval, limit_refresh_interval_unit) VALUES (?, ?, ?, ?, ?, ?, ?)';
		$res = $db->performQuery($sql, array(
				$userLimit->user_id,
				$userLimit->limit_name,
				$userLimit->limit_value,
				$userLimit->limit_refresh_value,
				$userLimit->limit_refresh_when,
				$userLimit->limit_refresh_interval,
				$userLimit->limit_refresh_interval_unit)
		);

		return $res->isSuccess();
	}

	/**
	 * Updates the User Limit.
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserLimit $userLimit
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function update(\EllinghamTech\PHPUserSystem\ObjectModels\UserLimit $userLimit) : bool
	{
		$db = UserSystem::getDb('UsersLimits');

		$sql = 'UPDATE users_limits SET limit_name=?, limit_value=?, limit_refresh_value=?, limit_refresh_when=?, limit_refresh_interval=?, limit_refresh_interval_unit=? WHERE user_id=?';
		$res = $db->performQuery($sql, array(
				$userLimit->limit_name,
				$userLimit->limit_value,
				$userLimit->limit_refresh_value,
				$userLimit->limit_refresh_when,
				$userLimit->limit_refresh_interval,
				$userLimit->limit_refresh_interval_unit,
				$userLimit->user_id)
		);

		return $res->isSuccess();
	}

	/**
	 * Deletes the user limit from the database.
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserLimit $userLimit
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function delete(\EllinghamTech\PHPUserSystem\ObjectModels\UserLimit $userLimit) : bool
	{
		$db = UserSystem::getDb('UsersLimits');

		$sql = 'DELETE FROM users_limits WHERE user_id=? AND limit_name=?';
		$res = $db->performQuery($sql, array(
				$userLimit->user_id,
				$userLimit->limit_name
			)
		);

		return $res->isSuccess();
	}

	/**
	 * Draw down from the user limit
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserLimit $userLimit
	 * @param int $value
	 *
	 * @return bool
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function drawDown(\EllinghamTech\PHPUserSystem\ObjectModels\UserLimit $userLimit, int $value) : bool
	{
		$db = UserSystem::getDb('UsersLimits');

		try
		{
			$sql = 'SELECT limit_value FROM users_limits WHERE user_id=? AND limit_name=?';
			$res = $db->performQuery($sql, array(
				$userLimit->user_id,
				$userLimit->limit_name
			));
			$row = $res->fetchArray();

			// If the entry does not exist, we save the instance as a whole.
			if(!$row)
			{
				$userLimit->limit_value = $userLimit->limit_value - $value;
				return $userLimit->save();
			}

			if($value > $row['limit_value']) return false;

			$sql = 'UPDATE users_limits SET limit_value=limit_value-? WHERE user_id=? AND limit_name=?';
			$db->performQuery($sql, array(
				$value,
				$userLimit->user_id,
				$userLimit->limit_name
			));

			return true;
		}
		catch(\Exception $e)
		{
			return false;
		}
	}
};
