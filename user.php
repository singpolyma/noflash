<?php
require_once dirname(__FILE__).'/include/setup.php';
$path_extra = dirname(__FILE__).'/include/';
$path = ini_get('include_path');
$path = $path_extra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);
require 'Auth/OpenID.php';
require_once dirname(__FILE__).'/include/connectDB.php';
if(!$_REQUEST['url']) die('Must specify a URL!');
$url = Auth_OpenID::normalizeUrl($_REQUEST['url']);
$url = mysql_real_escape_string($url, $db);
header('Content-Type: application/xhtml+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Demand No Flash! - User <?php echo htmlentities($url); ?></title>
		<link rel="stylesheet" media="screen" type="text/css" href="main.css" />
	</head>

	<body>
		<?php require 'header.php' ?>
			<h2>Demands by <?php echo htmlentities($url); ?></h2>
			<ol>
				<?php
					$demands = mysql_query("SELECT url,time FROM demand,login_ids WHERE demand.user_id=login_ids.user_id AND login_id='$url' ORDER BY time DESC", $db);
					while($item = mysql_fetch_assoc($demands)) {
						?>
						<li><a href="site.php?url=<?php echo htmlentities($item['url']); ?>"><?php echo htmlentities($item['url']); ?></a> <span class="published"><?php echo date('Y-m-d', $item['time']); ?></span></li>
						<?php
					}
				?>
			</ol>
		<?php require 'footer.php' ?>
	</body>
</html>
