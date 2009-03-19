<?php

require_once "common.php";
session_start();

if($_GET['openid_identifier']) $_GET['openid_url'] = $_GET['openid_identifier'];
if(!strstr($_GET['openid_url'],'.')) $_GET['openid_url'] .= '.myopenid.com';

if($_GET['return_to']) $_SESSION['return_to'] = $_GET['return_to'];
if($_GET['action']) $_SESSION['action'] = $_GET['action'];

// Render a default page if we got a submission without an openid
// value.
if (empty($_GET['openid_url'])) {
    $error = "Expected an OpenID URL.";
    include 'index.php';
    exit(0);
}

$scheme = 'http';
if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
    $scheme .= 's';
}

$openid = $_GET['openid_url'];
$process_url = sprintf("$scheme://%s%s/finish_auth.php",
                       $_SERVER['HTTP_HOST'],
                       dirname($_SERVER['PHP_SELF']));


$trust_root = sprintf("$scheme://%s%s",
                      $_SERVER['SERVER_NAME'],
                      dirname(dirname($_SERVER['PHP_SELF'])));

// Begin the OpenID authentication process.
$auth_request = $consumer->begin($openid);

// Handle failure status return values.
if (!$auth_request) {
    $error = "Authentication error.";
	die($error);
}

$sreg = Auth_OpenID_SRegRequest::build('', array('nickname','fullname','email'), '');
$auth_request->addExtension($sreg);

    // Redirect the user to the OpenID server for authentication.
    // Store the token for this authentication so we can verify the
    // response.

    // For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
    // form to send a POST request to the server.
    if ($auth_request->shouldSendRedirect()) {
        $redirect_url = $auth_request->redirectURL($trust_root,
                                                  $process_url);
        
        // If the redirect URL can't be built, display an error
        // message.
        if (Auth_OpenID::isFailure($redirect_url)) {
            die("Could not redirect to server: " . $redirect_url->message);
        } else {
            // Send redirect.
            header("Location: ".$redirect_url);
        }
    } else {
        // Generate form markup and render it.
        $form_id = 'openid_message';
        $form_html = $auth_request->formMarkup($trust_root, $process_url,
                                               false, array('id' => $form_id));
        
        // Display an error if the form markup couldn't be generated;
        // otherwise, render the HTML.
        if (Auth_OpenID::isFailure($form_html)) {
            displayError("Could not redirect to server: " . $form_html->message);
        } else {
            $page_contents = array(
               "<html><head><title>",
               "OpenID transaction in progress",
               "</title></head>",
               "<body onload='document.getElementById(\"".$form_id."\").submit()'>",
               $form_html,
               "</body></html>");
            
            print implode("\n", $page_contents);
        }
    }

?>
