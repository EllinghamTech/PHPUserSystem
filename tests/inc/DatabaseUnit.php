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

use EllinghamTech\Database\SQL\SQLite;
use EllinghamTech\PHPUserSystem\UserSystem;

trait DatabaseUnit
{
	public function setUp() : void
	{
		UserSystem::resetUserSystem();
		UserSystem::init($this->phpHelpersWrapperConnection());
	}

	/**
	 * @return SQLite
	 * @throws Exception
	 */
	protected function phpHelpersWrapperConnection() : SQLite
	{
		$pdo = $this->pdoConnection();

		$wrapper = new SQLite();
		$wrapper->useExistingConnection($pdo);
		return $wrapper;
	}

	/**
	 * Creates a new test SQLite connection with database.
	 *
	 * @return PDO
	 * @throws Exception
	 */
	protected function pdoConnection() : PDO
	{
		$memory_pdo = new PDO('sqlite::memory:');

		if($memory_pdo->exec('PRAGMA foreign_keys = OFF') === false) throw new Exception('Failed to disable foreign keys');

		$sql = file_get_contents(__DIR__ . '/../data/structure.sql');
		if($sql === false) throw new Exception('Failed to open structure SQL file');
		if($memory_pdo->exec($sql) === false) throw new Exception('Failed to create database structure');

		$sql = file_get_contents(__DIR__.'/../data/seed.sql');
		if($sql === false) throw new Exception('Failed to open seed SQL file');
		if($memory_pdo->exec($sql) === false) throw new Exception('Failed to seed database');

		if($memory_pdo->exec('PRAGMA foreign_keys = ON') === false) throw new Exception('Failed to enable foreign keys');

		return $memory_pdo;
	}
}
