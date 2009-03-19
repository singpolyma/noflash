<?php

if($_COOKIE['user_openid'] && !$_COOKIE['noflash_session'] && !$_REQUEST['error']) {
	require_once dirname(__FILE__).'/setup.php';
   $at = $_SERVER['SCRIPT_URI'].'?'.$_SERVER['QUERY_STRING'];
   header('Location: http://'.$_SERVER['HTTP_HOST'].'/'.APPROOT.'/login/try_auth.php?openid_identifier='.urlencode($_COOKIE['user_openid']).'&return_to='.urlencode($at),true,303);//login
   exit;
}//end if user_openid

if($_COOKIE['noflash_session']) {
	require_once dirname(__FILE__).'/connectDB.php';
	$LOGIN_DATA = mysql_query("SELECT user_id,nickname FROM users WHERE session_id='".mysql_real_escape_string($_COOKIE['noflash_session'],$db)."' LIMIT 1",$db) or die(mysql_error());
	$LOGIN_DATA = mysql_fetch_assoc($LOGIN_DATA);//in case we ever change data model
}

?>
