<?php
define('CMS_ADMIN', true);
require_once("../config.php");
require_once("language/$currentlang/main.php");

if(defined('iS_ADMIN')) {
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	echo "<head>\n";
	echo "<title>Admin Control Panel</title>\n";
	echo "</head>\n";

	echo "	<frameset rows=\"30,*\">\n";
	echo "		<frame name=\"topFrame\" frameborder=\"0\" framespacing=\"0\" noresize=\"noresize\" target=\"mainFrame\" scrolling=\"no\" src=\"top.php\">\n";
	echo "		<frame frameborder=\"0\" framespacing=\"0\" noresize=\"noresize\" name=\"mainFrame\" src=\"body.php\">\n";
	echo "	</frameset>\n";
	echo "	<noframes>\n";
	echo "	<body>\n";
	echo "	<p>This page uses frames, but your browser doesn't support them.</p>\n";
	echo "	</body>\n";
	echo "	</noframes>\n";
	echo "</html>\n";
} else {
	header("Location: login.php");
}
?>