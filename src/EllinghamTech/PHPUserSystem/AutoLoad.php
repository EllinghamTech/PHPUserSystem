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
 * Very simple custom autoloader.
 *
 * Just include this file and it will AutoLoad the classes
 * as necessary.
 *
 * @package EllinghamTech\PHPUserSystem
 */


namespace EllinghamTech\PHPUserSystem;

class AutoLoad
{
	/**
	 * If the class can be found, requires the file containing the class.
	 *
	 * @param string $className Fully qualified namespace provided by sql_autoload function
	 */
	public static function load($className)
	{
		$className = explode('\\', $className);
		array_shift($className);
		array_shift($className);
		$className = implode('/', $className);

		if (file_exists(__DIR__ .'/'.$className.'.php')) require(__DIR__ .'/'.$className.'.php');
		else if (file_exists($className.'.php')) require($className.'.php');
	}
}

spl_autoload_register(__NAMESPACE__.'\AutoLoad::load');
