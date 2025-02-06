<?php
if (!defined('CMS_ADMIN')) die("Illegal File Access.");

//if (defined('iS_RADMIN')) {
	include("page_header.php");
	
	
	require_once("./language/$currentlang/langEdit.php");
	
	if (isset($_POST["create"])) {
		Common::recursiveCopy("./language/{$_POST['base']}", "./language/{$_POST['name']}");
		Common::recursiveCopy(RPATH."language/{$_POST['base']}", RPATH."language/{$_POST['name']}");
	}

echo <<<EOT
<table border="0" width="100%" cellspacing="0" cellpadding="4" class="tableborder">
	<form method="POST" action="modules.php?f=$adm_modname">
	<tr><td colspan="2" class="header">
EOT;
	$handle = opendir("./language");
	$langarr = array();
	while (false !== ($file = readdir($handle))) {
		if ((is_dir("./language/$file")) && ($file != '.') && ($file != '..')) {
			$langarr[] = $file;
		}
	}
	closedir($handle);
	echo _CREATE_NEW_LANGUAGE."</td></tr>\n";
	echo "<tr>\n";
	echo '<tr><td class="row1" width="20%">'._BASED_ON.": </td>\n";
	echo "<td class=\"row1\">\n";
	echo '<select name="base">'."\n";
	for ($i = 0; $i < count($langarr); $i++) {
		echo '<option value="'.$langarr[$i].'">'.$langarr[$i].'</option>';
	}
	echo "</select>\n";
	echo "</td></tr>\n";
	echo '<tr><td class="row1" width="20%">'._NAME.": </td>\n";
	echo '<td class="row1"><input type="text" name="name" size="100"></td></tr>'."\n";
	echo '<tr><td class="row3" colspan="2" align="center"><input type="submit" name="create" value="'._ADD."\"></td></tr>\n";
	echo "</form>\n";
	echo "</table><br/>\n";
echo <<<EOT
<table border="0" width="100%" cellspacing="0" cellpadding="4" class="tableborder">
	<form method="POST" action="modules.php?f=$adm_modname">
	<tr><td colspan="2" class="header">
EOT;
	echo _LANG_EDITOR."</td></tr>\n";
	
	echo "<tr>\n";
	echo '<td class="row3" width="30%">'._CHOOSE_LANGUAGE."</td>\n";
	echo '<td class="row1">';
	echo '<select name="lang">'."\n";
	for ($i = 0; $i < count($langarr); $i++) {
		echo '<option value="'.$langarr[$i].'">'.$langarr[$i].'</option>';
	}
	echo "</select>\n";
	echo '<input type="submit" name="edit" value="'._GO.'">';
	echo "</form>\n";
	echo "</td>\n";
	echo "</tr>\n";
	
	echo "</table>";
	
	if (isset($_POST["edit"])) {
echo <<<EOT
<br/><table border="0" width="100%" cellspacing="0" cellpadding="4" class="tableborder">
	<form method="POST" action="modules.php?f=$adm_modname">
	<tr><td colspan="4" class="header">
EOT;
		echo _LANG_EDITOR." {$_POST['lang']}</td></tr>\n";
		echo "<tr>\n";
		echo '<td class="row3sd">'._TITLE."</td>\n";
		echo '<td class="row1sd" align="center">'._LANGUAGE."</td>\n";
		echo '<td class="row3sd" align="center">'._TYPE."</td>\n";
		echo '<td class="row1sd" align="center">'._EDIT."</td>\n";
		echo "</tr>\n";
		
		$handle2 = opendir(RPATH."language/{$_POST['lang']}");
		while (false !== ($file2 = readdir($handle2))) {
			if (!is_dir($file2) && (Common::getExt($file2) == 'php')) {
				echo "<tr>\n";
				echo '<td class="row3">'.$file2."</td>\n";
				echo '<td class="row1" align="center">'.ucfirst($_POST['lang'])."</td>\n";
				echo '<td class="row3" align="center">'._USER."</td>\n";
				echo '<td class="row1" align="center" width="30"><a href="?f='.$adm_modname."&do=edit&lang={$_POST['lang']}&type=user&file=$file2\">".'<img border="0" src="images/edit.png">'."</a></td>\n";
				echo "</tr>\n";
			}
		}
		closedir($handle2);
	
		$handle2 = opendir("./language/{$_POST['lang']}");
		while (false !== ($file2 = readdir($handle2))) {
			if (!is_dir($file2) && (Common::getExt($file2) == 'php')) {
				echo "<tr>\n";
				echo '<td class="row3">'.$file2."</td>\n";
				echo '<td class="row1" align="center">'.ucfirst($_POST['lang'])."</td>\n";
				echo '<td class="row3" align="center">'._ADMIN."</td>\n";
				echo '<td class="row1" align="center" width="30"><a href="?f='.$adm_modname."&do=edit&lang={$_POST['lang']}&type=admin&file=$file2\">".'<img border="0" src="images/edit.png">'."</a></td>\n";
				echo "</tr>\n";
			}
		}
		closedir($handle2);
		echo "</form></table>";
	}
	
	include_once("page_footer.php");
//} else {
//	header("Location: body.php");
//}