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

use EllinghamTech\PHPUserSystem\ObjectModels\UserLimit;
use EllinghamTech\PHPUserSystem\UserSystem;

class UserLimitsHelpers
{
	/**
	 * Refreshes all user limits that need refreshing, by selecting and locking
	 * all rows that need refreshing, creating the UserLimit object, performing the
	 * doRefresh method.
	 *
	 * Returns a count of the number of user limits updated.
	 *
	 * @return int|null
	 * @throws \Exception
	 */
	public function doAllRequiredRefreshes() : int
	{
		$count = 0;
		$db = UserSystem::getDb('users_limits');

		try
		{
			$db->transaction_begin();

			$sql = 'SELECT * FROM users_limits WHERE limit_refresh_when<=UNIX_TIMESTAMP() FOR UPDATE';
			$res = $db->performQuery($sql);

			while($row = $res->fetchArray())
			{
				$obj =  new UserLimit($row['user_id']);
				$obj->limit_name = $row['limit_name'];
				$obj->limit_value = $row['limit_value'];
				$obj->limit_refresh_value = $row['limit_refresh_value'];
				$obj->limit_refresh_when = $row['limit_refresh_when'];
				$obj->limit_refresh_interval = $row['limit_refresh_interval'];
				$obj->limit_refresh_interval_unit = $row['limit_refresh_interval_unit'];

				if($obj->needsRefresh())
					$obj->doRefresh();

				$count++;
			}

			$db->transaction_commit();
		}
		catch(\Exception $e)
		{
			$db->transaction_rollback();
			throw $e;
		}

		return $count;
	}
}
