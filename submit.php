<?php
	if(!$_REQUEST['url']) die('Must specify a URL!');
	require_once dirname(__FILE__).'/include/connectDB.php';
	require_once dirname(__FILE__).'/include/processCookie.php';
	if(!$LOGIN_DATA['user_id']) die('Must be logged in!');
	$url = explode('/',preg_replace('/^https?:?\/?\/?/','',$_REQUEST['url']));
	$url = preg_replace('/^www\./','',$url[0]);
	$url = mysql_real_escape_string($url, $db);
	$result = mysql_query("INSERT INTO demand (url, user_id, time) VALUES ('$url',{$LOGIN_DATA['user_id']}, ".time().")", $db);
	if($result === FALSE) {
		$error = mysql_error($db);
		if(!preg_match('/Duplicate/',$error)) {
			die($error);
		}
	}
	if($_REQUEST['submit']) {
		foreach($_REQUEST['uses'] as $use) {
			$use = mysql_real_escape_string($use, $db);
			$result = mysql_query("INSERT INTO uses (url, user_id, `use`) VALUES ('$url',{$LOGIN_DATA['user_id']},'$use')", $db);
			if($result === FALSE) {
				$error = mysql_error($db);
				if(!preg_match('/Duplicate/',$error)) {
					die($error);
				}
			}
		}
		header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/site.php?url='.urlencode($url),true,303);
	} else {
		header('Content-Type: application/xhtml+xml; charset=utf-8');
		echo '<?xml version="1.0" encoding="utf-8"?>';
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Demand No Flash! - Select Uses</title>
		<link rel="stylesheet" media="screen" type="text/css" href="main.css" />
	</head>
	<body>
		<h2>How is Adobe Flash used on <?php echo $url; ?>?</h2>
		<form method="post" action="" id="uses-form" style="margin-right:1em;">
			<input type="checkbox" name="uses[]" value="video/audio" id="video-audio" /> <label for="video-audio">Video/Audio</label>
			<input type="checkbox" name="uses[]" value="navigation" id="navigation" /> <label for="navigation">Navigation</label>
			<input type="checkbox" name="uses[]" value="visual effects" id="visual-effects" /> <label for="visual-effects">Visual Effects / Animations</label>
			<input type="checkbox" name="uses[]" value="interactive content" id="interactive-content" /> <label for="interactive-content">Interactive Content (ie games, slideshows)</label>
			<input type="checkbox" name="uses[]" value="advertisements" id="advertisements" /> <label for="advertisements">Advertisements</label>
			<input type="checkbox" name="uses[]" value="visualizations" id="visualizations" /> <label for="visualizations">Visualizations / Graphs</label>
			<input type="checkbox" name="uses[]" value="whole site" id="whole-site" /> <label for="whole-site">Whole Site</label>
			<input type="checkbox" name="uses[]" value="other" id="other" /> <label for="other">Other</label>
			<div style="margin-top:1em;"><input type="submit" name="submit" value="Submit" /></div>
		</form>
	</body>
</html>
		<?php
	}
?>
