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

class UserPreference
{
	/** @var int The users ID */
	public $user_id;

	/** @var string The preference name */
	public $preference_name;
	/** @var mixed The preference value */
	public $preference_value = null;

	/**
	 * UserPreference constructor.
	 *
	 * @param int $user_id
	 * @param array|null $preference
	 */
	public function __construct(int $user_id, ?array $preference)
	{
		$this->user_id = $user_id;

		if($preference != null)
			$this->populate($preference);
	}

	/**
	 * Populates the object properties from an array of values with the keys:
	 * preference_name, preference_value
	 *
	 * @param array $preference
	 */
	public function populate(array $preference) : void
	{
		if(isset($preference['preference_name'])) $this->preference_name = $preference['preference_name'];
		if(isset($preference['preference_value'])) $this->preference_value = $preference['preference_value'];
	}

	/**
	 * Saves the user preference to the database
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function save() : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserPreference::save($this);
	}

	/**
	 * Deletes the user preference from the databbase
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function delete() : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserPreference::delete($this);
	}
};
