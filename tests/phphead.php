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

function initUserSystem() : void
{
	$database = new SQLite();

	try
	{
		$database->connect(__DIR__.'/data/data.sqlite3');

		$database->performQuery('CREATE TABLE IF NOT EXISTS users (
		   user_id INTEGER PRIMARY KEY AUTOINCREMENT,
		   user_name TEXT,
		   user_password TEXT,
		   user_email TEXT,
		   user_mobile TEXT,
		   user_created INTEGER,
		   user_active INTEGER DEFAULT 1,
		   user_twofactor_enabled INTEGER DEFAULT 0,
		   user_flags INTEGER DEFAULT 0,
		
		   UNIQUE (user_name),
		   UNIQUE (user_email)
		)');

		$database->performQuery('CREATE INDEX IF NOT EXISTS user_email ON users (user_email, user_id)');
		$database->performQuery('CREATE INDEX IF NOT EXISTS user_name ON users(user_name, user_id)');

		$database->performQuery('CREATE TABLE IF NOT EXISTS users_tokens (
		  token TEXT PRIMARY KEY,
		  user_id INTEGER NOT NULL,
		  token_type TEXT,
		  token_expires INTEGER,
		  token_expired INTEGER DEFAULT 0,
		
		  CONSTRAINT fk_users_tokens_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
			ON UPDATE CASCADE
			ON DELETE CASCADE
		)');

		$database->performQuery('CREATE INDEX IF NOT EXISTS user_token ON users_tokens (token, user_id)');
		$database->performQuery('CREATE INDEX IF NOT EXISTS tokens_for_user ON users_tokens (user_id, token_type)');

		$database->performQuery('CREATE TABLE IF NOT EXISTS users_permissions (
		  user_id INTEGER,
		  permission_name TEXT,
		  permission_value INTEGER,
		  permission_expires INTEGER,
		  permission_expires_to INTEGER,
		
		  PRIMARY KEY (user_id, permission_name),
		
		  CONSTRAINT fk_users_permissions_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
			ON UPDATE CASCADE
			ON DELETE CASCADE
		)');

		$database->performQuery('CREATE TABLE IF NOT EXISTS users_limits (
		  user_id INTEGER,
		  limit_name TEXT,
		  limit_value INTEGER,
		  limit_refresh_value INTEGER,
		  limit_refresh_when INTEGER,
		  limit_refresh_interval INTEGER,
		  limit_refresh_interval_unit TEXT,
		
		  PRIMARY KEY (user_id, limit_name),
		
		  CONSTRAINT fk_user_id_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
			ON UPDATE CASCADE
			ON DELETE CASCADE
		)');

		$database->performQuery('CREATE TABLE IF NOT EXISTS users_preferences (
		  user_id INTEGER,
		  preference_name TEXT,
		  preference_value TEXT,
		
		  PRIMARY KEY (user_id, preference_name),
		
		  CONSTRAINT fk_users_preferences_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
			ON UPDATE CASCADE
			ON DELETE CASCADE
		)');

		$database->performQuery('CREATE TABLE IF NOT EXISTS users_meta (
		  user_id INTEGER,
		  meta_name TEXT,
		  meta_number INTEGER DEFAULT 0,
		  meta_value TEXT,
		  meta_sensitive INTEGER DEFAULT 0,
		
		  PRIMARY KEY (user_id, meta_name, meta_number),
		
		  CONSTRAINT fk_users_meta_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
			ON UPDATE CASCADE
			ON DELETE CASCADE
		)');

		$database->performQuery('CREATE TABLE IF NOT EXISTS users_profiles (
			user_id INTEGER PRIMARY KEY,
			profile_id TEXT UNIQUE,
			display_name TEXT,
			full_name TEXT,
			profile_summary TEXT,
			profile_image TEXT,
			
			CONSTRAINT fk_users_profiles_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
			  ON UPDATE CASCADE
			  ON DELETE CASCADE
		);');

		$database->performQuery('CREATE TABLE IF NOT EXISTS users_sessions (
		  session_id TEXT PRIMARY KEY,
		  user_id INTEGER,
		  session_expires INTEGER,
		  session_active INTEGER DEFAULT 1,
		
		  CONSTRAINT fk_users_sessions_on_users FOREIGN KEY (user_id) REFERENCES users (user_id)
			ON UPDATE CASCADE
			ON DELETE CASCADE
		)');

	}
	catch(Exception $e)
	{
		die('COULD NOT CONNECT TO DATABASE');
	}

	UserSystem::init($database);
}
