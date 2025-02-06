<?php
if (!defined('CMS_ADMIN')) die("Illegal File Access.");

if (defined('iS_RADMIN')) {
	require_once("./language/$currentlang/langEdit.php");
	include_once("page_header.php");
	

	if ($_GET["type"] == "user") $path = RPATH."language/{$_GET["lang"]}/{$_GET["file"]}";
	elseif ($_GET["type"] == "admin") $path = "./language/{$_GET["lang"]}/{$_GET["file"]}";

	if (isset($_POST["subup"])) {
		$_POST["constName"] = substr($_POST["constName"], 0, strlen($_POST["constName"]) - 1);
		$cx = explode(',', $_POST["constName"]);
		$content = "<?php\n";
		$charlist = "\\'";
		for ($i = 0; $i < count($cx); $i++) {
			$_POST[$cx[$i]] = addcslashes($_POST[$cx[$i]], $charlist);
			$_POST[$cx[$i]] = str_replace(array('&quot;', '&'), array('$S%(!"', '&amp;'), $_POST[$cx[$i]]);
			$_POST[$cx[$i]] = str_replace('$S%(!"', '"', $_POST[$cx[$i]]);
			$content .= "define('{$cx[$i]}','{$_POST[$cx[$i]]}');\n";
		}
		$content .= "?>\n";
		@chmod($path, 0777);
		@$file = fopen($path, "w");
		@fwrite($file, $content);
		@fclose($file);
		@chmod($path, 0644);
	}

	echo '<form method="POST" id="frm" action="modules.php?f='.$adm_modname."&do=$do&type={$_GET["type"]}&lang={$_GET["lang"]}&file={$_GET["file"]}\">\n";
	echo '<table border="0" width="100%" cellspacing="0" cellpadding="3" class="tableborder">'."\n";
	echo '<tr><td class="header" colspan="2">'._LANG_EDITOR."</td></tr>\n";

	$constName = "";
	$farr = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	for ($i = 0; $i < count($farr); $i++) {
		$farr[$i] = trim($farr[$i]);
		if (substr($farr[$i], 0, 6) == "define") {
			$temp = substr($farr[$i], 7, strlen($farr[$i]) - 9); // 7 for define( & the last 2 for );
			$temp = explode(',', $temp);
			$temp[0] = substr($temp[0], 1, strlen($temp[0]) - 2);
			$temp[1] = stripslashes(substr($temp[1], 1, strlen($temp[1]) - 2));
			$temp[1] = str_replace('"', '&quot;', $temp[1]);
			$constName .= "{$temp[0]},";
			echo "<tr>\n";
			echo '<td width="30%" class="row1"><strong>'.$temp[0]."</strong></td>\n";
			echo '<td class="row1"><input type="text" size="100" name="'.$temp[0].'" value="'.$temp[1]."\"></td>\n";
			echo "</tr>\n";
		}
	}

	echo '<input type="hidden" name="constName" value="'.$constName."\">\n";
	echo '<input type="hidden" name="subup" value="1">'."\n";
	echo '<tr><td colspan="2" class="row3" align="center"><input type="submit" name="submit1" class="button2" value="'._SAVECHANGES."\"></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";

echo <<<EOT
<script>
	document.onkeydown = function(e) {
		var key=(!is_ie)?e.which:window.event.keyCode
		var ctrlPress=(!is_ie)?e.ctrlKey:window.event.ctrlKey
		if((key==83)&&(ctrlPress)) {
			fetch_object('frm').submit();
		}
	}
</script>
EOT;
	include_once("page_footer.php");
} else {
	header("Location: body.php");
}
?>