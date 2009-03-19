<?php
require_once dirname(__FILE__).'/include/connectDB.php';
require_once dirname(__FILE__).'/include/processCookie.php';
header('Content-Type: application/xhtml+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Demand No Flash!</title>
		<link rel="stylesheet" media="screen" type="text/css" href="main.css" />
		<!-- BEGIN ID SELECTOR -->
		<script type="text/javascript" id="__openidselector" src="https://www.idselector.com/selector/d1d99ad63630ac21a2c37bdd6da2720826e0974e" charset="utf-8"></script>
		<!-- END ID SELECTOR -->
	</head>

	<body>
		<?php require 'header.php' ?>
		<div id="top10">
			<h2>The Top 10</h2>
			<ol>
				<?php
					$top10 = mysql_query("SELECT count(user_id) as count, url FROM demand GROUP BY url ORDER BY count DESC LIMIT 10", $db);
					while($item = mysql_fetch_assoc($top10)) {
						?>
						<li><span class="count"><?php echo $item['count']; ?></span> <a href="site.php?url=<?php echo $item['url']; ?>"><?php echo $item['url']; ?></a></li>
						<?php
					}
				?>
			</ol>
			<a href="list.php" rel="next">See all &raquo;</a>
		</div>

		<div id="recent">
			<?php if($LOGIN_DATA['user_id']) : ?>
				<form method="get" action="submit.php">
					<input type="text" value="http://" name="url" />
					<input type="submit" value="Demand" />
				</form>
			<?php else : ?>
				<?php require(dirname(__FILE__).'/login/form.php'); ?>
			<?php endif; ?>

			<h2>Latest Demands</h2>
			<ol>
				<?php
					$latest = mysql_query("SELECT url,login_id FROM demand,login_ids WHERE demand.user_id=login_ids.user_id ORDER BY time DESC LIMIT 5", $db);
					while($item = mysql_fetch_assoc($latest)) {
						?>
						<li><a href="site.php?url=<?php echo $item['url']; ?>"><?php echo $item['url']; ?></a> <span class="byline">by <a href="user.php?url=<?php echo htmlentities($item['login_id']); ?>"><?php echo preg_replace('/\/$/','',preg_replace('/^http:?\/?\/?(www\.)?/','',$item['login_id'])); ?></a></span></li>
						<?php
					}
				?>
			</ol>
		</div>
		<?php require 'footer.php' ?>
	</body>
</html>
