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

use EllinghamTech\Exceptions\Data\QueryFailed;
use EllinghamTech\PHPUserSystem\UserSystem;

/**
 * @internal
 */
final class UserMeta
{
	/**
	 * Note: if no meta data is found and object with a NULL value property is returned
	 *
	 * @param int $user_id
	 * @param string $metaName
	 *
	 * @return \EllinghamTech\PHPUserSystem\ObjectModels\UserMeta
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function load(int $user_id, string $metaName) : \EllinghamTech\PHPUserSystem\ObjectModels\UserMeta
	{
		$metaObj = new \EllinghamTech\PHPUserSystem\ObjectModels\UserMeta($user_id);
		$metaObj->meta_name = $metaName;
		$db = UserSystem::getDb('UsersMeta');

		$sql = 'SELECT * FROM users_meta WHERE user_id=? AND meta_name=?';
		$res = $db->performQuery($sql, array($user_id, $metaName));

		while($row = $res->fetchArray())
		{
			$metaObj->meta_value[] = $row['meta_value'];
		}

		return $metaObj;
	}

	/**
	 * Deletes the meta data if the value is NULL.  This method performs the save within
	 * a transaction.
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserMeta $userMeta
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 * @throws \RuntimeException
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \Exception
	 */
	public static function save(\EllinghamTech\PHPUserSystem\ObjectModels\UserMeta $userMeta) : bool
	{
		$db = UserSystem::getDb('UsersMeta');

		$sql = '';
		$values = array();

		if(!is_array($userMeta->meta_value) && $userMeta->meta_value !== null) throw new \RuntimeException('Meta value must be an array of values');
		if($userMeta->meta_value === null || count($userMeta->meta_value) == 0) self::delete($userMeta);

		$sql_parts = array();

		foreach ($userMeta->meta_value as $number => $value)
		{
			$sql_parts[] = '(?, ?, ?, ?)';
			$values = array_merge($values, array($userMeta->user_id, $userMeta->meta_name, $value, $number));
		}

		$sql = 'INSERT INTO users_meta (user_id, meta_name, meta_value, meta_number) VALUES ' . implode(', ', $sql_parts);

		try
		{
			$db->transaction_begin();
			$db->performQuery('DELETE FROM users_meta WHERE user_id=? AND meta_name=?', array($userMeta->user_id, $userMeta->meta_name));
			$db->performQuery($sql, $values);
			$db->transaction_commit();

			return true;
		}
		catch (QueryFailed $e)
		{
			$db->transaction_rollback();
			throw $e;
		}
		catch (\Exception $e)
		{
			$db->transaction_rollback();
			throw $e;
		}
	}

	/**
	 * Deletes the user meta entry from the database
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserMeta $userMeta
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function delete(\EllinghamTech\PHPUserSystem\ObjectModels\UserMeta $userMeta) : bool
	{
		$db = UserSystem::getDb('UsersMeta');

		$res = $db->performQuery('DELETE FROM users_meta WHERE user_id=? AND meta_name=?', array($userMeta->user_id, $userMeta->meta_name));
		return $res->isSuccess();
	}
};
