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

/**
 * @internal
 */
namespace EllinghamTech\PHPUserSystem\ObjectControllers;

use EllinghamTech\PHPUserSystem\UserSystem;

/**
 * @internal
 */
class UserProfile
{
	/**
	 * Loads a user profile from the user ID.  If the user does not have a profile, a new
	 * profile is created (and saved as an empty profile).  If the user does not exist,
	 * NULL is returned.
	 *
	 * @param int|null $user_id
	 *
	 * @return \EllinghamTech\PHPUserSystem\ObjectModels\UserProfile
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function loadFromUserId(int $user_id) : ?\EllinghamTech\PHPUserSystem\ObjectModels\UserProfile
	{
		$userProfile = new \EllinghamTech\PHPUserSystem\ObjectModels\UserProfile();
		$userProfile->user_id = $user_id;
		$db = UserSystem::getDb('UsersProfiles');

		$sql = 'SELECT * FROM users_profiles WHERE user_id=?';
		$res = $db->performQuery($sql, [$user_id]);
		$row = $res->fetchArray();

		if($row)
			$userProfile->populate($row);
		else // If the profile does not exist, it is created
		{
			if(!User::checkIfExists('user_id', $userProfile->user_id)) return null;
			$userProfile->save();
		}

		return $userProfile;
	}

	/**
	 * Loads a user profile from profile ID (a random string generated when first created).  NULL if the
	 * profile does not exist
	 *
	 * @param string $profile_id
	 *
	 * @return null|\EllinghamTech\PHPUserSystem\ObjectModels\UserProfile
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function loadFromProfileId(string $profile_id) : ?\EllinghamTech\PHPUserSystem\ObjectModels\UserProfile
	{
		$db = UserSystem::getDb('UsersProfiles');

		$sql = 'SELECT * FROM users_profiles WHERE profile_id=?';
		$res = $db->performQuery($sql, [$profile_id]);
		$row = $res->fetchArray();

		if($row)
			return new \EllinghamTech\PHPUserSystem\ObjectModels\UserProfile($row);

		return null;
	}

	/**
	 * Saves the user profile
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserProfile $userProfile
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function save(\EllinghamTech\PHPUserSystem\ObjectModels\UserProfile $userProfile) : bool
	{
		$db = UserSystem::getDb('UsersProfiles');

		$sql = 'REPLACE INTO users_profiles (user_id, profile_id, display_name, full_name, profile_summary, profile_image) VALUES (?, ?, ?, ?, ?, ?)';
		$res = $db->performQuery($sql, [
			$userProfile->user_id,
			$userProfile->profile_id,
			$userProfile->display_name,
			$userProfile->full_name,
			$userProfile->profile_summary,
			$userProfile->profile_image
		]);

		return $res->isSuccess();
	}
}
