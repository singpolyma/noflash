<?php
require_once dirname(__FILE__).'/../include/connectDB.php';
require_once dirname(__FILE__).'/../include/processCookie.php';
header('Content-Type: application/xhtml+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Demand No Flash! - Login</title>
		<link rel="stylesheet" media="screen" type="text/css" href="../main.css" />
		<!-- BEGIN ID SELECTOR -->
		<script type="text/javascript" id="__openidselector" src="https://www.idselector.com/selector/d1d99ad63630ac21a2c37bdd6da2720826e0974e" charset="utf-8"></script>
		<!-- END ID SELECTOR -->
		<style type="text/css">
			form#login {
				text-align: center;
				margin-bottom: 4em;
			}
		</style>
	</head>

	<body>
		<?php require dirname(__FILE__).'/../header.php' ?>

		<h2>Login</h2>
		<p>You must login before submitting or voting for websites.</p>
		<?php require(dirname(__FILE__).'/form.php'); ?>

		<?php require dirname(__FILE__).'/../footer.php' ?>
	</body>
</html>
