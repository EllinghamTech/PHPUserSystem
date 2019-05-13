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

namespace EllinghamTech\PHPUserSystem;

class UserFactory
{
	/**
	 * Gets a user by their User ID
	 *
	 * @param int $user_id
	 *
	 * @return ObjectModels\User|null
	 * @throws \Exception
	 */
	public static function getUserByUserId(int $user_id): ?ObjectModels\User
	{
		return ObjectControllers\User::loadFromUserId($user_id);
	}

	/**
	 * Gets a user by their User Name
	 *
	 * @param string $user_name
	 *
	 * @return ObjectModels\User|null
	 * @throws \Exception
	 */
	public static function getUserByUserName(string $user_name): ?ObjectModels\User
	{
		return ObjectControllers\User::loadFromUserName($user_name);
	}

	/**
	 * Gets a user by their User Email
	 *
	 * @param string $user_email
	 *
	 * @return ObjectModels\User|null
	 * @throws \Exception
	 */
	public static function getUserByUserEmail(string $user_email): ?ObjectModels\User
	{
		return ObjectControllers\User::loadFromUserEmail($user_email);
	}

	/**
	 * Gets a user profile by profile ID
	 *
	 * @param string $profile_id
	 *
	 * @return ObjectModels\UserProfile|null
	 * @throws \Exception
	 */
	public static function getUserProfileByProfileId(string $profile_id): ?ObjectModels\UserProfile
	{
		return ObjectControllers\UserProfile::loadFromProfileId($profile_id);
	}

	/**
	 * Gets a user profile by user ID
	 *
	 * @param int $user_id
	 *
	 * @return ObjectModels\UserProfile|null
	 * @throws \Exception
	 */
	public static function getUserProfileByUserId(int $user_id): ?ObjectModels\UserProfile
	{
		return ObjectControllers\UserProfile::loadFromUserId($user_id);
	}


	/**
	 * Creates a new user
	 *
	 * @return ObjectModels\User
	 */
	public static function newUser(): ObjectModels\User
	{
		return ObjectControllers\User::create();
	}

	/**
	 * Gets a user token by the token string
	 *
	 * @param string $token
	 *
	 * @return ObjectModels\UserToken|null
	 * @throws \Exception
	 */
	public static function getToken(string $token): ?ObjectModels\UserToken
	{
		return ObjectControllers\UserToken::getToken($token);
	}
};
