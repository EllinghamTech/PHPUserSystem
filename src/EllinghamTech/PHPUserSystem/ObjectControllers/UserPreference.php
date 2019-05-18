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
final class UserPreference
{
	/**
	 * Creates a new preference for a user
	 *
	 * @param int $user_id
	 * @param array|null $permissions
	 *
	 * @return \EllinghamTech\PHPUserSystem\ObjectModels\UserPreference
	 * @deprecated use load() which creates a new user preference if it does not exist, preventing unintended overwrites
	 */
	public static function create(int $user_id, ?array $permissions = null) : \EllinghamTech\PHPUserSystem\ObjectModels\UserPreference
	{
		return new \EllinghamTech\PHPUserSystem\ObjectModels\UserPreference($user_id, $permissions);
	}

	/**
	 * Note: if no preference is found, a UserPreference object will still be returned
	 * but with a NULL preference value.
	 *
	 * @param int $user_id
	 * @param string $preferenceName
	 *
	 * @return \EllinghamTech\PHPUserSystem\ObjectModels\UserPreference
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function load(int $user_id, string $preferenceName) : \EllinghamTech\PHPUserSystem\ObjectModels\UserPreference
	{
		$preferenceObj = new \EllinghamTech\PHPUserSystem\ObjectModels\UserPreference($user_id, null);
		$preferenceObj->preference_name = $preferenceName;
		$db = UserSystem::getDb('UsersPreferences');

		$sql = 'SELECT * FROM users_preferences WHERE user_id=? AND preference_name=?';
		$res = $db->performQuery($sql, array($user_id, $preferenceName));

		if($row = $res->fetchArray())
			$preferenceObj->populate($row);

		return $preferenceObj;
	}

	/**
	 * Saves a user preference to the database (using SQL Replace)
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserPreference $userPreference
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function save(\EllinghamTech\PHPUserSystem\ObjectModels\UserPreference $userPreference) : bool
	{
		$db = UserSystem::getDb('UsersPreferences');

		$sql = 'REPLACE INTO users_preferences (user_id, preference_name, preference_value) VALUES  (?, ?, ?)';
		$res = $db->performQuery($sql, array($userPreference->user_id, $userPreference->preference_name, $userPreference->preference_value));

		return $res->isSuccess();
	}

	/**
	 * Deletes a user preference from the database.
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserPreference $userPreference
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function delete(\EllinghamTech\PHPUserSystem\ObjectModels\UserPreference $userPreference) : bool
	{
		$db = UserSystem::getDb('UsersPreferences');

		$sql = 'DELETE FROM users_preferences WHERE user_id=? AND preference_name=?';
		$res = $db->performQuery($sql, array($userPreference->user_id, $userPreference->preference_name));

		return $res->isSuccess();
	}
};
