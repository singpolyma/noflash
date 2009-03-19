<?php

require_once "common.php";
session_start();

$return = $_SESSION['return_to']; unset($_SESSION['return_to']);
$action = $_SESSION['action']; unset($_SESSION['action']);
if(!$return) $return = 'http://'.$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF']));
if(!strstr($return, '?')) $return .= '?';

$process_url = sprintf("http://%s%s/finish_auth.php",
                       $_SERVER['HTTP_HOST'],
                       dirname($_SERVER['PHP_SELF']));

// Complete the authentication process using the server's response.
$response = $consumer->complete($process_url);

if($action == 'add')
	require_once dirname(dirname(__FILE__)).'/include/processCookie.php';

if ($response->status == Auth_OpenID_CANCEL) {
    // This means the authentication was cancelled.
    if($action == 'add')
	    header('Location: http://'.$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF'])),true,303);//redirect to home
    else
	    header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/out.php',true,303);//logout, redirect to home
} else if ($response->status == Auth_OpenID_FAILURE) {
	$msg = "OpenID authentication failed: " . $response->message;
	header('Location: '.$return.'&error='.urlencode($msg),true,303);
	die;
} else if ($response->status == Auth_OpenID_SUCCESS) {

   setcookie("user_openid",$response->identity_url,time()+(3600*1000),'/');//set cookie

	$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
	$sreg = $sreg_resp->contents();

   require(dirname(__FILE__).'/../include/connectDB.php');//connect to database
   $user = mysql_query("SELECT user_id FROM login_ids WHERE login_id='".mysql_real_escape_string($response->identity_url,$db)."' LIMIT 1", $db) or die(mysql_error());//get user_id
   $user = mysql_fetch_assoc($user);
   if($user && $action == 'add') {
   	$msg = 'That OpenID is already in the system!';
		header('Location: '.$return.'&error='.urlencode($msg),true,303);
   	die;
   }//end if user && add
   if(!$user) {//non-existant user, create
   	if($action != 'add') {
		$fullname = explode(' ', @$sreg['fullname']);
		$nickname = mysql_real_escape_string(@$sreg['nickname'],$db);
		mysql_query("INSERT INTO users (nickname) VALUES ('$nickname')", $db) or die(mysql_error());//insert new user
		$userid = mysql_insert_id();
   	} else $userid = $LOGIN_DATA['user_id'];
	mysql_query("INSERT INTO login_ids (user_id,login_id) VALUES ($userid,'".mysql_real_escape_string($response->identity_url,$db)."')", $db) or die(mysql_error());//insert user's OpenID
	$session_id = sha1('a'.$userid.microtime(true).rand(-999999,999999).'noflash');
	mysql_query("UPDATE users SET session_id='$session_id', session_timeout=".(time()+60*60*25)." WHERE user_id=".$userid,$db) or die(mysql_error());
	setcookie("noflash_session",$session_id,0,'/');//set cookie
	@mysql_close($db);
	header('Location: '.$return,true,303);//redirect
	exit;
	}//end if user
	$session_id = sha1('a'.$userid.microtime(true).rand(-999999,999999).'noflash');
	mysql_query("UPDATE users SET session_id='$session_id', session_timeout=".(time()+60*60*25)." WHERE user_id=".$user['user_id'],$db) or die(mysql_error());
	setcookie("noflash_session",$session_id,0,'/');//set cookie
	@mysql_close($db);
	header('Location: '.$return,true,303);//redirect
	exit;

}//end if-elses OpenID status

?>
