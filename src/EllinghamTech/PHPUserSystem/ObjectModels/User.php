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
use EllinghamTech\PHPUserSystem\UserSystem;

class User
{
	/**
	 * @var null|int The user ID (null if this is a new user)
	 */
	public $user_id = null;

	/**
	 * @var string The user name
	 */
	public $user_name;

	/**
	 * @var string The user email address
	 */
	public $user_email;

	/**
	 * @var string The users mobile number
	 */
	public $user_mobile;

	/**
	 * @var null|int The timestamp of user creation
	 */
	public $user_created = null;

	/**
	 * @var bool True if the user is enabled
	 */
	public $user_active = true;

	/**
	 * @var int A set of customisable flags for the user
	 */
	public $user_flags = 0;

	/**
	 * @var string The users password (in a hashed format, see setPassword and verifyPassword)
	 */
	public $user_password;

	/**
	 * User constructor.
	 *
	 * @param null|array $userDetailsArray
	 */
	public function __construct(?array $userDetailsArray=null)
	{
		if($userDetailsArray == null) return;

		$this->populate($userDetailsArray);
	}

	/**
	 * Populates the object properties from an array of values with the keys:
	 * user_id, user_name, user_email, user_mobile, user_created, user_active, user_password
	 *
	 * @param array $userDetailsArray
	 */
	public function populate(array $userDetailsArray) : void
	{
		if(isset($userDetailsArray['user_id'])) $this->user_id = $userDetailsArray['user_id'];
		if(isset($userDetailsArray['user_name'])) $this->user_name = $userDetailsArray['user_name'];
		if(isset($userDetailsArray['user_email'])) $this->user_email = $userDetailsArray['user_email'];
		if(isset($userDetailsArray['user_mobile'])) $this->user_mobile = $userDetailsArray['user_mobile'];
		if(isset($userDetailsArray['user_created'])) $this->user_created = $userDetailsArray['user_created'];
		if(isset($userDetailsArray['user_active'])) $this->user_active = (bool)$userDetailsArray['user_active'];
		if(isset($userDetailsArray['user_password'])) $this->user_password = $userDetailsArray['user_password'];
		if(isset($userDetailsArray['user_flags'])) $this->user_flags = $userDetailsArray['user_flags'];
	}

	/**
	 * Hashes a plain text password and stores it ready to be saved.
	 *
	 * @param string $password
	 */
	public function setPassword(string $password) : void
	{
		$this->user_password = password_hash($password, UserSystem::$passwordHashAlgo);
	}

	/**
	 * Using a plain text input, verifies the password against
	 * the stored hash.
	 *
	 * @param string $password
	 *
	 * @return bool
	 */
	public function verifyPassword(string $password) : bool
	{
		return password_verify($password, $this->user_password);
	}

	/**
	 * Saves the user to the Database
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function save() : bool
	{
		return \EllinghamTech\PHPUserSystem\ObjectControllers\User::save($this);
	}

	/**
	 * Gets a user permission.  If the permission does not exist, it will be created.
	 *
	 * @param string $permissionName
	 *
	 * @return UserPermission
	 * @throws ObjectNotSaved
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function getUserPermission(string $permissionName) : UserPermission
	{
		if($this->user_id == null) throw new ObjectNotSaved();
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserPermission::load($this->user_id, $permissionName);
	}

	/**
	 * Gets a user limit.  If the limit does not exist, it will be created.
	 *
	 * @param string $limitName
	 * @param bool $lockForUpdate
	 *
	 * @return UserLimit
	 * @throws ObjectNotSaved
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function getUserLimit(string $limitName, bool $lockForUpdate = false) : UserLimit
	{
		if($this->user_id == null) throw new ObjectNotSaved();
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserLimit::loadFromUserIdAndLimitName($this->user_id, $limitName, $lockForUpdate);
	}

	/**
	 * Gets a user permission.  If the permission does not exist, it will be created.
	 *
	 * @param string $preferenceName
	 *
	 * @return UserPreference
	 * @throws ObjectNotSaved
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function getUserPreference(string $preferenceName) : UserPreference
	{
		if($this->user_id == null) throw new ObjectNotSaved();
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserPreference::load($this->user_id, $preferenceName);
	}

	/**
	 * Gets a user meta entry.  If the meta entry does not exist, it will be created.
	 *
	 * @param string $metaName
	 *
	 * @return UserMeta
	 * @throws ObjectNotSaved
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function getUserMeta(string $metaName) : UserMeta
	{
		if($this->user_id == null) throw new ObjectNotSaved();
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserMeta::load($this->user_id, $metaName);
	}

	/**
	 * Gets the user profile.  If the profile does not exist, it will be created.
	 *
	 * @return UserProfile
	 * @throws ObjectNotSaved
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function getUserProfile() : UserProfile
	{
		if($this->user_id == null) throw new ObjectNotSaved();
		return \EllinghamTech\PHPUserSystem\ObjectControllers\UserProfile::loadFromUserId($this->user_id);
	}

	/**
	 * Gets a user token.  If the user token does not exists, or does not belong
	 * to this user, then NULL is returned.
	 *
	 * @param string $token
	 *
	 * @return UserToken|null
	 * @throws ObjectNotSaved
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public function getUserToken(string $token) : ?UserToken
	{
		if($this->user_id == null) throw new ObjectNotSaved();

		$tokenObject = \EllinghamTech\PHPUserSystem\ObjectControllers\UserToken::getToken($token);

		if($tokenObject->user_id != $this->user_id) return null;
		else return $tokenObject;
	}

	/**
	 * Creates a user token for this user.
	 *
	 * @param string $token_type
	 *
	 * @return UserToken
	 * @throws ObjectNotSaved
	 * @throws \Exception
	 */
	public function createUserToken(?string $token_type = null) : UserToken
	{
		if($this->user_id == null) throw new ObjectNotSaved();

		$tokenObj = \EllinghamTech\PHPUserSystem\ObjectControllers\UserToken::create($token_type);
		$tokenObj->user_id = $this->user_id;
		return $tokenObj;
	}
}
