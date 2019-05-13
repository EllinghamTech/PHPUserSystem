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

require('phphead.php');

$template->breadcrumb = array(
	array('index.php', 'Home'),
);

ob_start();
?>
<p>The PHPUserSystem is a simplified (yet powerful) user management solution.  It is not however a
	complete out of the box solution.  You will need to write some code to get it working!</p>

<h2>Samples</h2>

<h3>With Helper Classes</h3>
<ul class="samples_list">
	<li><a href="actions_helper/login.php">Login</a></li>
</ul>

<h3>Without Helper Classes</h3>
<ul class="samples_list">
	<li><a href="actions/login.php">Login</a></li>
	<li><a href="actions/register.php">Register</a></li>
</ul>

<h3>Misc</h3>
<ul class="samples_list">
	<li><a href="process/logout.php">Logout</a></li>
</ul>
<?php
$template->content = ob_get_clean();

require('template.php');
