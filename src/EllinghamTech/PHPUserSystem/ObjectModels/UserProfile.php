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

use EllinghamTech\PHPUserSystem\Exceptions\ObjectNotSaved;

class UserProfile
{
	public $user_id = null;
	public $profile_id = null;

	public $display_name;
	public $full_name;
	public $profile_summary;
	public $profile_image;

	/**
	 * UserProfile constructor.
	 *
	 * @param array|null $data
	 */
	public function __construct(?array $data = null)
	{
		$this->profile_id = uniqid($this->user_id);

		if($data != null)
			$this->populate($data);
	}

	/**
	 * Populate the user profile properties from an array
	 *
	 * @param array $set
	 */
	public function populate(array $set) : void
	{
		if(isset($set['user_id'])) $this->user_id = $set['user_id'];
		if(isset($set['profile_id'])) $this->profile_id = $set['profile_id'];

		if(isset($set['display_name'])) $this->display_name = $set['display_name'];
		if(isset($set['full_name'])) $this->user_id = $set['full_name'];
		if(isset($set['profile_summary'])) $this->profile_summary = $set['profile_summary'];
		if(isset($set['profile_image'])) $this->profile_image = $set['profile_image'];
	}

	/**
	 * Saves the user profile
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function save() : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserProfile::save($this);
	}

	/**
	 * Gets the user for the user profile
	 *
	 * @return User
	 * @throws ObjectNotSaved
	 * @throws \Exception
	 */
	public function getUser() : User
	{
		if($this->user_id == null) throw new ObjectNotSaved();
		return \EllinghamTech\PHPUserSystem\ObjectControllers\User::loadFromUserId($this->user_id);
	}
}
