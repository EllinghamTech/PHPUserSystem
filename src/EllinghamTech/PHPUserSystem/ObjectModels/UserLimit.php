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

namespace EllinghamTech\PHPUserSystem\ObjectModels;

use EllinghamTech\PHPUserSystem\InternalAbstract\Limit;

class UserLimit extends Limit
{
	/**
	 * UserLimit constructor.
	 *
	 * @param int $user_id
	 */
	public function __construct(int $user_id)
	{
		$this->user_id = $user_id;
	}

	/**
	 * Populates the object properties from an array of values with the keys:
	 * limit_name, limit_value, limit_refresh_value, limit_refresh_when, limit_refresh_interval, limit_refresh_interval_unit
	 *
	 * @param array $limitDetails
	 */
	public function populate(array $limitDetails) : void
	{
		if (isset($limitDetails['limit_name'])) $this->limit_name = $limitDetails['limit_name'];
		if (isset($limitDetails['limit_value'])) $this->limit_value = $limitDetails['limit_value'];
		if (isset($limitDetails['limit_refresh_value'])) $this->limit_refresh_value = $limitDetails['limit_refresh_value'];
		if (isset($limitDetails['limit_refresh_when'])) $this->limit_refresh_when = $limitDetails['limit_refresh_when'];
		if (isset($limitDetails['limit_refresh_interval'])) $this->limit_refresh_interval = $limitDetails['limit_refresh_interval'];
		if (isset($limitDetails['limit_refresh_interval_unit'])) $this->limit_refresh_interval_unit = $limitDetails['limit_refresh_interval_unit'];
	}

	/**
	 * Reloads and locks the UserLimit for an update,
	 * use within a transaction.
	 *
	 * @return bool True on success, false of failure
	 */
	public function reloadAndLockForUpdate() : bool
	{
		try
		{
			$_this = \EllinghamTech\PHPUserSystem\ObjectControllers\UserLimit::loadFromUserIdAndLimitName($this->user_id, $this->limit_name, true, $this);
			if($_this === $this) return true;
			else return false;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	/**
	 * Refresh the user limits
	 *
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 * @throws \Exception
	 */
	public function doRefresh() : void
	{
		$timestamp = $this->getNextValidRefreshTimestamp();
		if($timestamp == null) return;

		$this->limit_refresh_when = $timestamp;
		$this->limit_value = $this->limit_refresh_value;

		// This should only fail if the row does not exist?
		\EllinghamTech\PHPUserSystem\ObjectControllers\UserLimit::update($this);
	}

	/**
	 * Draw down a set amount from the user limit
	 *
	 * @param int $value
	 *
	 * @return bool
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function drawDown(int $value = 1) : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserLimit::drawDown($this, $value);
	}

	/**
	 * Saves the User Limit to the database.
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function save() : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserLimit::save($this);
	}

	/**
	 * Deletes the user limit from the database.
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function delete() : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserLimit::delete($this);
	}
};
