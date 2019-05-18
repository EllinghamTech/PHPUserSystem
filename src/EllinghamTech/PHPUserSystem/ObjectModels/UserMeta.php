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

final class UserMeta
{
	/**
	 * @var int The users ID
	 */
	public $user_id;

	/**
	 * @var string The Name of the meta entry
	 */
	public $meta_name;

	/**
	 * @var array
	 */
	public $meta_value = array();

	/**
	 * UserMeta constructor.
	 *
	 * @param int $user_id
	 * @param array|null $meta
	 */
	public function __construct(int $user_id, ?array $meta)
	{
		$this->user_id = $user_id;

		if($meta != null)
			$this->populate($meta);
	}

	/**
	 * Populates the object properties from an array of values with the keys:
	 * meta_name, meta_value
	 *
	 * @param array $meta
	 */
	public function populate(array $meta) : void
	{
		if(isset($meta['meta_name'])) $this->meta_name = $meta['meta_name'];
		if(isset($meta['meta_value'])) $this->meta_value = $meta['meta_value'];
	}

	/**
	 * @return bool True if the meta entry has multiple values
	 */
	public function isMultiValue() : bool
	{
		return true;
	}

	/**
	 * Makes the entry a multi-value entry
	 */
	public function makeMultiValue() : void
	{
		return;
	}

	/**
	 * Saves the meta entry.
	 *
	 * @return bool True on success, false on failure
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function save() : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserMeta::save($this);
	}

	/**
	 * Deletes the meta entry.
	 *
	 * @return bool True on success, false on failure
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function delete() : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserMeta::delete($this);
	}
};
