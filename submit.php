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
		if(preg_match('/Duplicate/',$error)) {
			header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/site.php?url='.urlencode($url),true,303);
		} else {
			die($error);
		}
	}
	header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/site.php?url='.urlencode($url),true,303);
?>
