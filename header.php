      <!--[if lt IE 7]>
      <div id="ie6Warning">
      <h2>Time to upgrade your browser</h2>
      <p>If you're reading this, you're surfing using Internet Explorer 6, an eight-year-old browser that cannot cope with the demands of the modern internet. For the best web experience, we strongly recommend upgrading to <a href="http://www.google.com/chrome">Google Chrome</a>, <a href="http://www.getfirefox.com/">Firefox</a>, <a href="http://www.opera.com/">Opera</a>, <a href="http://www.apple.com/safari/">Safari</a>, or a more recent version of <a href="http://www.microsoft.com/windows/downloads/ie/getitnow.mspx">Internet Explorer</a>.</p>
      </div>
      <![endif]-->
		<div id="header">
			<?php require_once dirname(__FILE__).'/include/setup.php'; ?>
			<?php if($LOGIN_DATA['user_id']) : ?>
				<div id="actions"><a href="<?php echo APPROOT; ?>login/out.php">logout</a></div>
			<?php else : ?>
				<div id="actions"><a href="<?php echo APPROOT; ?>login/">login</a></div>
			<?php endif; ?>
			<h1><a href="index.php">Demand No Flash!</a></h1>
			<p>We want you to support standards.</p>
		</div>

		<p id="intro">Demand the end of required Adobe Flash for websites you use every day, using a <a href="<?php echo APPROOT; ?>bookmarklet.php">simple bookmarklet</a>.
		   It's an easy, and active way to support web standards and accesibility.
		</p>
