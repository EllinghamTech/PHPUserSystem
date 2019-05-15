<html lang="en">
<head>
	<title>Samples</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link href="https://dev.ellingham.tech/phpusersystem/samples/styles.css" rel="stylesheet" type="text/css">
	<script src="https://dev.ellingham.tech/phpusersystem/samples/jquery.js"></script>
	<script src="https://dev.ellingham.tech/phpusersystem/samples/main.js"></script>
</head>
<body>
	<main>
		<nav>
			<ul>
				<?php

				use EllinghamTech\PHPUserSystem\UserSystem;

				foreach($template->breadcrumb as $navItem)
				{
					echo '<li><a href="', $navItem[0], '">', $navItem[1], '</a></li>';
				}
				?>
			</ul>
		</nav>
		<h1>PHPUserSystem by Ellingham Technologies</h1>
		<section>
			<p>By default this sample uses a single SQLite database and the built-in PHP Sessions handling.  You
				should ensure the configuration you use meets your needs.</p>
		</section>
		<section>
			<h2>Current Status</h2>
			<table>
				<tr>
					<td><b>Logged In:</b></td>
					<td><?php echo (UserSystem::Session()->isLoggedIn() ? 'Yes' : 'No'); ?></td>
				</tr>
				<?php
				if(UserSystem::Session()->isLoggedIn()) :

				$user = UserSystem::Session()->user();
				?>
				<tr>
					<td><b>Username:</b></td>
					<td><?php echo $user->user_name; ?></td>
				</tr>
				<?php endif; ?>
			</table>
		</section>

		<hr />

		<?php
		echo $template->content;
		?>
	</main>
</body>
</html>
