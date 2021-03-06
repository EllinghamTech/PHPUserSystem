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

require('../phphead.php');

$template->breadcrumb = array(
	array('../index.php', 'Home'),
	array('register.php', 'Register')
);

ob_start();
?>
	<h2>Register</h2>
	<form action="../process/register.php" method="post">
		<label>
			<span>Username: </span>
			<input type="text" name="user_name" placeholder="Type your new username" />
		</label>

		<label>
			<span>Password: </span>
			<input type="text" name="user_password" placeholder="Type your new password" />
		</label>

		<label>
			<span>Email: </span>
			<input type="text" name="user_email" placeholder="Type your email" />
		</label>

		<p>This sample is incapable of sending emails/text messages without modification.</p>

		<input type="submit" value="Register" />
	</form>
<?php
$template->content = ob_get_clean();

require('../template.php');
