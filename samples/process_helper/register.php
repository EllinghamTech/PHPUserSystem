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

use EllinghamTech\PHPUserSystem\Helpers\UserHelpers;
use EllinghamTech\PHPUserSystem\UserFactory;

require('../phphead.php');

if(UserHelpers::checkIfUserNameExists($_POST['user_name']))
	die('Username already exists');

if(UserHelpers::checkIfUserEmailExists($_POST['user_email']))
	die('Email already exists');

// Now we know the user is unique, we should verify some inputs

if(!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL))
	die('Email not valid');

// You should also check other properties, e.g. limit the username to alphanumeric, etc.

// Use the factory to create a new user object
$user = UserFactory::newUser();

// Assign some details
$user->user_name = $_POST['user_name'];
$user->user_email = $_POST['user_email'];
$user->setPassword($_POST['user_password']); // the setPassword method will hash the password for us

var_dump($user->save());

die('<a href="../index.php">Success!  Back to samples index</a>');
