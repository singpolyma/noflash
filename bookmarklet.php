<?php
header('Content-Type: application/xhtml+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Demand No Flash! - Bookmarklet</title>
		<link rel="stylesheet" media="screen" type="text/css" href="main.css" />
	</head>

	<body>
		<?php require 'header.php' ?>
			<h2>Drag the link below to your bookmark bar</h2>
			<p><a href="javascript:void(open('http://<?php echo $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']); ?>/submit.php?url=' + encodeURIComponent(window.location.href)));">Demand No Flash!</a></p>
		<?php require 'footer.php' ?>
	</body>
</html>
