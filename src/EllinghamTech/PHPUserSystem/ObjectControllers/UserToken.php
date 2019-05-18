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
class UserToken
{
	/**
	 * Gets a token by token value, NULL if token does not exist
	 *
	 * @param string $token
	 * @param bool $allowExpired
	 *
	 * @return null|\EllinghamTech\PHPUserSystem\ObjectModels\UserToken
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function getToken(string $token, bool $allowExpired = false) : ?\EllinghamTech\PHPUserSystem\ObjectModels\UserToken
	{
		$tokenObj = new \EllinghamTech\PHPUserSystem\ObjectModels\UserToken(null);
		$db = UserSystem::getDb('UsersTokens');

		$sql = 'SELECT * FROM users_tokens WHERE token=?';
		if(!$allowExpired) $sql .= ' AND token_expired=0 AND token_expires>'.time();

		$res = $db->performQuery($sql, $token);

		if(!($tokenRow = $res->fetchArray())) return null;
		$tokenObj->populate($tokenRow);

		return $tokenObj;
	}

	/**
	 * Creates a new token
	 *
	 * @param string|null $token_type
	 *
	 * @return \EllinghamTech\PHPUserSystem\ObjectModels\UserToken
	 * @throws \Exception
	 */
	public static function create(?string $token_type = null) : \EllinghamTech\PHPUserSystem\ObjectModels\UserToken
	{
		$tokenObj = new \EllinghamTech\PHPUserSystem\ObjectModels\UserToken();

		// Continue to use base 64?
		$tokenObj->token = base64_encode(random_bytes(32)); // What if random_bytes throws an Exception?
		$tokenObj->expires = time() + 172800;
		$tokenObj->valid = true;
		$tokenObj->token_type = $token_type;

		return $tokenObj;
	}

	/**
	 * Saves a token to the database
	 *
	 * @param \EllinghamTech\PHPUserSystem\ObjectModels\UserToken $userToken
	 *
	 * @return bool
	 * @throws \EllinghamTech\Exceptions\Data\NoConnection
	 * @throws \EllinghamTech\Exceptions\Data\QueryFailed
	 * @throws \EllinghamTech\PHPUserSystem\Exceptions\ConfigurationException
	 */
	public static function save(\EllinghamTech\PHPUserSystem\ObjectModels\UserToken $userToken) : bool
	{
		$db = UserSystem::getDb('UsersTokens');

		$sql = 'REPLACE INTO users_tokens (token, user_id, token_type, token_expires, token_expired) VALUES (?, ?, ?, ?, ?)';
		$res = $db->performQuery($sql, array($userToken->token, $userToken->user_id, $userToken->token_type, $userToken->expires, $userToken->valid));

		return $res->isSuccess();
	}
};
