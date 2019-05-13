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

namespace EllinghamTech\PHPUserSystem\AbstractModels;

use EllinghamTech\PHPUserSystem\InternalAbstract\Limit;

/**
 * Abstract class for granular limits within a collection.
 *
 * An example of this usage could be a forum, where you limit members
 * to a certain number of posts per topic per day.  You would take the
 * topic collection and for each topic, assign each user a daily_posts
 * limit.
 *
 * class TopicDailyPost extends UserResourceLimit
 *
 * @package EllinghamTech\PHPUserSystem\AbstractModels
 */
abstract class UserResourceLimit extends Limit
{
	/** @var mixed The resource ID */
	public $resource_id;

	/**
	 * Sets up the class.  Be sure the parent class implements the base constructor, or
	 * its behaviour, if you override it.
	 *
	 * @param int $user_id
	 * @param mixed $resource_id
	 * @param null|string $limit_name
	 */
	public function __construct(int $user_id, $resource_id, ?string $limit_name=null)
	{
		$this->user_id = $user_id;
		$this->resource_id = $resource_id;
		$this->limit_name = $limit_name;

		$this->init();
	}

	abstract protected function init() : void;
};
