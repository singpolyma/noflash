<?php
require_once dirname(__FILE__).'/include/setup.php';
require_once dirname(__FILE__).'/include/connectDB.php';
header('Content-Type: application/xhtml+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Demand No Flash! - Top Demands</title>
		<link rel="stylesheet" media="screen" type="text/css" href="main.css" />
	</head>

	<body>
		<?php require 'header.php' ?>
		<h2>Top Demands</h2>
			<ol>
				<?php
					$top10 = mysql_query("SELECT count(user_id) as count, url FROM demand GROUP BY url ORDER BY count DESC", $db);
					while($item = mysql_fetch_assoc($top10)) {
						?>
						<li><span class="count"><?php echo $item['count']; ?></span> <a href="site.php?url=<?php echo $item['url']; ?>"><?php echo $item['url']; ?></a></li>
						<?php
					}
				?>
			</ol>
		<?php require 'footer.php' ?>
	</body>
</html>
