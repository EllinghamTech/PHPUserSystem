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

use EllinghamTech\PHPUserSystem\ObjectControllers\User as UserController;
use EllinghamTech\PHPUserSystem\ObjectControllers\UserToken as UserTokenController;
use EllinghamTech\PHPUserSystem\ObjectModels\User;
use EllinghamTech\PHPUserSystem\ObjectModels\UserToken;

class UserHelpers
{
	/**
	 * Checks if a user exists by User Name
	 *
	 * @param string $user_name
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function checkIfUserNameExists(string $user_name) : bool
	{
		return UserController::checkIfExists('user_name', $user_name);
	}

	/**
	 * Checks if a user exists by User Email
	 *
	 * @param string $user_email
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function checkIfUserEmailExists(string $user_email) : bool
	{
		return UserController::checkIfExists('user_email', $user_email);
	}

	/**
	 * Checks if a user exists by User Mobile.
	 *
	 * NOTE: This method is not yet part of the API and might change in the future.
	 *
	 * DO NOT USE
	 *
	 * @param string $user_mobile
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function checkIfUserMobileExists(string $user_mobile) : bool
	{
		return UserController::checkIfExists('user_mobile', $user_mobile);
	}

	/**
	 * Creates a forgot_password token for the user
	 *
	 * @param User $user
	 *
	 * @return UserToken|null
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ObjectNotSaved
	 */
	public static function forgotPassword(User $user) : ?UserToken
	{
		return $user->createUserToken('forgot_password');
	}

	/**
	 * Creates a forgot_password token for the user by User ID
	 *
	 * @param int $user_id
	 *
	 * @return UserToken|null
	 * @throws \Exception
	 */
	public static function forgotPasswordByUserId(int $user_id) : ?UserToken
	{
		$tokenObj = UserTokenController::create('forgot_password');
		$tokenObj->user_id = $user_id;
		return $tokenObj;
	}

	/**
	 * Creates a forgot_password token for the user by User Name
	 *
	 * @param string $user_name
	 *
	 * @return UserToken|null
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ObjectNotSaved
	 */
	public static function forgotPasswordByUserName(string $user_name) : ?UserToken
	{
		$user = UserController::loadFromUserName($user_name);
		if($user === null) return null;
		return self::forgotPassword($user);
	}

	/**
	 * Creates a forgot_password token for the user by User Email
	 *
	 * @param string $user_email
	 *
	 * @return UserToken|null
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ObjectNotSaved
	 */
	public static function forgotPasswordByUserEmail(string $user_email) : ?UserToken
	{
		$user = UserController::loadFromUserEmail($user_email);
		if($user === null) return null;
		return self::forgotPassword($user);
	}

	/**
	 * Gets the user object from a forgot_password token, null on failure
	 * (not found), exception on error.
	 *
	 * @param string $token
	 *
	 * @return User|null
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\InvalidState
	 */
	public static function getUserByForgotPasswordToken(string $token) : ?User
	{
		$userToken = UserTokenController::getToken($token);

		if($userToken === null) return null;
		if(strcmp($userToken->token_type, 'forgot_password') !== 0) return null;

		$user = $userToken->getUser();

		if($user === null) return null;
		return $user;
	}
};
