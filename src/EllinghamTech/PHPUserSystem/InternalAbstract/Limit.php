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

namespace EllinghamTech\PHPUserSystem\InternalAbstract;

abstract class Limit
{
	/** @var int The User ID */
	public $user_id;

	/** @var string The Limit Name */
	public $limit_name = '';

	/** @var int The Current Limit Value */
	public $limit_value = null;

	/** @var null|int The Limit Value set on Refresh */
	public $limit_refresh_value = null;

	/** @var int Next timestamp to refresh */
	public $limit_refresh_when = 0;

	/** @var int Interval between refreshes */
	public $limit_refresh_interval = 0;

	/** @var null|string Unit for the interval between refreshes, default (null) is seconds */
	public $limit_refresh_interval_unit = self::LIMIT_REFRESH_SECONDS;

	public const LIMIT_REFRESH_INTERVAL_NEVER = 0;
	public const LIMIT_REFRESH_INTERVAL_MANUAL = 0; // Same as NEVER

	public const LIMIT_REFRESH_SECONDS = null;
	public const LIMIT_REFRESH_YEAR = 'year';
	public const LIMIT_REFRESH_MONTH = 'month';
	public const LIMIT_REFRESH_QUARTER = 'quarter';
	public const LIMIT_REFRESH_WEEK = 'week';
	public const LIMIT_REFRESH_DAY = 'day';

	/**
	 * Does the Limit need to be refreshed?
	 *
	 * @return bool
	 */
	public function needsRefresh() : bool
	{
		return (($this->limit_refresh_when > 0 || $this->limit_refresh_when == null) && $this->limit_refresh_when <= time());
	}

	/**
	 * Calculates the next timestamp for Refresh.  It will be based on the last refresh dates.
	 *
	 *
	 * @param null|int $timestamp If timestamp is NULL, uses $this->limit_refresh_when
	 * @return int|null Null of no valid timestamp or current timestamp is already in the future
	 */
	public function getNextValidRefreshTimestamp(?int $timestamp = null) : ?int
	{
		if($timestamp == null) $timestamp = $this->limit_refresh_when;
		if($timestamp == null || $timestamp == 0) return null;
		$currentTime = time();

		if($timestamp > $currentTime) return null;

		$nextTimestamp = $timestamp;

		// This needs to be changed!
		// Maybe there is an approach that does require a loop?
		// Issue: can exceed any reasonable execution time if the $nextTimestamp is
		// significantly lower than the current time and the refresh interval is small.
		while($nextTimestamp < $currentTime)
		{
			$nextTimestamp = $this->getNextRefreshTimestamp($nextTimestamp);
		}

		return $nextTimestamp;
	}

	private function getNextRefreshTimestamp(int $timestamp) : int
	{
		$amount = $this->limit_refresh_interval;

		switch($this->limit_refresh_interval_unit)
		{
			case self::LIMIT_REFRESH_DAY:
				$dateInterval = new \DateInterval('P'.$amount.'D');
				break;
			case self::LIMIT_REFRESH_WEEK:
				$dateInterval = new \DateInterval('P'.$amount.'W');
				break;
			case self::LIMIT_REFRESH_MONTH:
				$dateInterval = new \DateInterval('P'.$amount.'M');
				break;
			case self::LIMIT_REFRESH_QUARTER:
				$dateInterval = new \DateInterval('P'.($amount * 3).'M');
				break;
			case self::LIMIT_REFRESH_YEAR:
				$dateInterval = new \DateInterval('P'.$amount.'Y');
				break;
			default:
				$dateInterval = new \DateInterval('PT'.$amount.'S');
				break;
		}

		$date = new \DateTime('@'.$timestamp, new \DateTimeZone('UTC'));
		$date->add($dateInterval);
		return $date->getTimestamp();
	}

	/**
	 * Gets the current limit.  Null should be returned
	 * if there is no limit (i.e. infinite)
	 *
	 * @return int|null
	 */
	public function getLimit() : ?int
	{
		return $this->limit_value;
	}

	/**
	 * Returns true if the input number is within the current limit.
	 *
	 * If the current limit is NULL, this will always return true.
	 *
	 * @param int $value The input number
	 *
	 * @return bool
	 */
	public function isWithinLimit(int $value = 1) : bool
	{
		return ($this->getLimit() ?? $value) >= $value;
	}

	/**
	 * Refreshes the limit.  Exception on failure.
	 *
	 * @return void
	 * @throws \Exception
	 */
	abstract public function doRefresh() : void;

	/**
	 * Draw down from limit, reducing the current limit by
	 * the input number.
	 *
	 * @param int $value The input number
	 *
	 * @return bool
	 */
	abstract public function drawDown(int $value = 1) : bool;

	/**
	 * Save the object from the database
	 *
	 * @return bool
	 */
	abstract public function save() : bool;

	/**
	 * Delete the object from the database
	 *
	 * @return bool
	 */
	abstract public function delete() : bool;
}
