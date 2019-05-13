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

namespace EllinghamTech\PHPUserSystem\AbstractModels;

abstract class UserCustomPermissions
{
	public const NO_PERMISSION = 0;
	public const EXECUTE_PERMISSION = 1;
	public const READ_PERMISSION = 2;
	public const MODIFY_PERMISSION = 4;
	public const WRITE_PERMISSION = 8;
	public const DELETE_PERMISSION = 16;
	public const SPECIAL_PERMISSION = 32;

	public const CUSTOM_PERMISSION_1 = 64;
	public const CUSTOM_PERMISSION_2 = 128;
	public const CUSTOM_PERMISSION_3 = 256;
	public const CUSTOM_PERMISSION_4 = 512;
	public const CUSTOM_PERMISSION_5 = 1024;
	public const CUSTOM_PERMISSION_6 = 2048;

	public function __construct()
	{
	}
};
