<?php
require_once dirname(__FILE__).'/include/connectDB.php';
require_once dirname(__FILE__).'/include/processCookie.php';
if(!$_REQUEST['url']) die('Must specify a URL!');
$url = preg_replace('/^https?:?\/?\/?(www\.)?/','',$_REQUEST['url']);
$url = explode('/', $url);
$url = mysql_real_escape_string($url[0], $db);
$count = mysql_fetch_assoc(mysql_query("SELECT count(user_id) as count FROM demand WHERE url='$url' LIMIT 1", $db));
header('Content-Type: application/xhtml+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Demand No Flash! - <?php echo htmlentities($url); ?></title>
		<link rel="stylesheet" media="screen" type="text/css" href="main.css" />
	</head>

	<body>
		<?php require 'header.php' ?>
			<h2><?php echo $count['count']; ?> Demands for <a href="http://<?php echo htmlentities($url); ?>"><?php echo htmlentities($url); ?></a></h2>
			<?php
				if($LOGIN_DATA['user_id']) {
					$haz_demanded = mysql_fetch_assoc(mysql_query("SELECT user_id FROM demand WHERE url='$url' AND user_id={$LOGIN_DATA['user_id']} LIMIT 1", $db));
					$haz_demanded = ($haz_demanded['user_id'] == $LOGIN_DATA['user_id']);
					if($haz_demanded) {
						?>
						You have submitted a demand for this site.
						<?php
					} else {
						?>
						<a href="submit.php?url=<?php echo htmlentities($url); ?>">Submit a demand for this site.</a>
						<?php
					}
				}
			?>
			<?php
				if($url == 'youtube.com') {
					?>
					<p>You may get around the Flash requirements of this site with <a href="http://userscripts.org/scripts/show/34765">this Greasemonkey script</a>.</p>
					<?php
				}
				if($url == 'slideshare.com') {
					?>
					<p>You may get around the Flash requirements of this site with <a href="http://userscripts.org/scripts/show/44525">this Greasemonkey script</a>.</p>
					<?php
				}
			?>
			<?php
				$uses = mysql_query("SELECT `use`,count(user_id) as count FROM uses WHERE url='$url' GROUP BY `use`", $db) or die(mysql_error($db));
				$uses_output = array();
				while($use = mysql_fetch_assoc($uses)) {
					$uses_output[] = "<li>{$use['use']} (reported by {$use['count']} users)</li>";
				}
				if(count($uses_output)) {
					echo '<h3>Flash is used on this site for:</h3><ul>';
					echo implode("\n", $uses_output);
					echo '</ul>';
				}
			?>
			<ol>
				<?php
					$demands = mysql_query("SELECT login_id,time FROM demand,login_ids WHERE demand.user_id=login_ids.user_id AND url='$url' ORDER BY time DESC", $db);
					while($item = mysql_fetch_assoc($demands)) {
						?>
						<li><a href="user.php?url=<?php echo htmlentities($item['login_id']); ?>"><?php echo htmlentities($item['login_id']); ?></a> <span class="published"><?php echo date('Y-m-d', $item['time']); ?></span></li>
						<?php
					}
				?>
			</ol>
		<?php require 'footer.php' ?>
	</body>
</html>
