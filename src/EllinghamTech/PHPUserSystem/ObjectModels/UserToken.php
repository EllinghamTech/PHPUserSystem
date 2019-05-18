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

use EllinghamTech\PHPUserSystem\Exceptions\InvalidState;
use EllinghamTech\PHPUserSystem\UserFactory;

class UserToken
{
	/** @var string The token value */
	public $token;

	/** @var string The tokens type */
	public $token_type;

	/** @var int The user ID */
	public $user_id;

	/** @var int The timestamp the token expires */
	public $expires;

	/** @var bool True if valid, false otherwise */
	public $valid = false;

	/**
	 * UserToken constructor.
	 *
	 * @param null|array $set
	 */
	public function __construct(?array $set=null)
	{
		if($set != null)
			$this->populate($set);
	}

	/**
	 * Populates the object properties from an array of values with the keys:
	 * token, token_type, user_id, token_expires, token_expired
	 *
	 * @param array $set
	 */
	public function populate(array $set) : void
	{
		if(isset($set['token'])) $this->token = $set['token'];
		if(isset($set['token_type'])) $this->token_type = $set['token_type'];
		if(isset($set['user_id'])) $this->user_id = $set['user_id'];
		if(isset($set['token_expires'])) $this->expires = $set['token_expires'];
		if(isset($set['token_expired'])) $this->valid = !(bool)$set['token_expired'];
	}

	/**
	 * Immediately invalidates this token (and saves to the database)
	 *
	 * @return bool
	 * @throws \Exception
	 */
	function invalidate() : bool
	{
		if($this->valid == false) return true;

		$this->valid = false;
		return $this->save();
	}

	/**
	 * Save to database.
	 *
	 * @return bool
	 * @throws \Exception
	 * @throws InvalidState When the token has not been setup correctly to preform this method
	 */
	function save() : bool
	{
		if($this->user_id === null) throw new InvalidState('Token User ID is not set');

		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserToken::save($this);
	}

	/**
	 * Gets the user object.  NULL on error or user doesn't exist.
	 *
	 * @return User|null
	 * @throws \Exception
	 * @throws InvalidState When the token has not been setup correctly to preform this method
	 */
	public function getUser() : ?User
	{
		if($this->user_id === null) throw new InvalidState('Token User ID is not set');

		return UserFactory::getUserByUserId($this->user_id);
	}
}
