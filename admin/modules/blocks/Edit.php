<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$adm_pagetitle2 = _EDITBLOCK;

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);

$result = $db->sql_query("SELECT bkey, title, url, bposition, weight, active, refresh, blanguage, blockfile, view, expire, action, link, module, showtitle FROM ".$prefix."_blocks WHERE bid='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	die();
}

list($tip, $title, $url, $bposition, $weight, $active, $refresh, $blang, $blockfile, $view, $expire, $action, $link, $bmodule, $showtitle) = $db->sql_fetchrow($result);

include("page_header.php");

$err_title = $err_rss = $err_cont = $err_blf ="";
$content ="";
if(isset($_POST['submit']) && $_POST['submit'] !="") {
	$title = trim(stripslashes(resString($_POST['title'])));
	$content = trim(stripslashes(resString($_POST['content'])));
	$url = trim(stripslashes(resString($_POST['url'])));
	$bposition = trim(stripslashes(resString($_POST['bposition'])));
	$oldposition = trim(stripslashes(resString($_POST['oldposition'])));
	$active = intval($_POST['active']);
	$showtitle = intval($_POST['showtitle']);
	$refresh = intval($_POST['refresh']);
	$headline = intval($_POST['headline']);
	$blockfile = $_POST['blockfile'];
	$expire = intval($_POST['expire']);
	$action = intval($_POST['action']);
	$link = trim(stripslashes(resString($_POST['link'])));
	$bmodule = $_POST['bmodule'];
	$bkey = intval($_POST['bkey']);
	$blang = $_POST['blang'];
	$weight = intval($_POST['weight']);
	$view = intval($_POST['view']);

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}

	if($bkey == 0 && $blockfile == "0") {
		$err_blf = "<font color=\"red\">"._ERROR2."</font><br/>";
		$err = 1;
	}

	if($bkey == 1 && strlen($content) < 10) {
		$err_cont = "<font color=\"red\">"._ERROR3."</font><br/>";
		$err = 1;
	}

	if($bkey==2 && ($url!="" || $headline!=0)) {
		if ($headline != 0) {
			$result = $db->sql_query("select sitename, headlinesurl from ".$prefix."_rss where rid='$headline'");
			$row = $db->sql_fetchrow($result);
			$title = $row['sitename'];
			$url = $row['headlinesurl'];
		}
		if ($url != "") {
			if (!preg_match("!^http(?:s)?://!i",$url)) {
				$url = "http://$url";
			}
			$rdf = $fp ="";
			$rdf = parse_url($url);
			$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
			if (!$fp) {
				OpenDiv();
				echo "<center><b>"._RSSFAIL."</b><br/><br/>";
				echo ""._RSSTRYAGAIN."<br/><br/></center>";
				CloseDiv();
				echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=".$adm_modname.".php?do=add&tip=$bkey&blang=$blang\">";
				include_once("page_footer.php");
				exit;
			}
			if ($fp) {
				fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n");
				fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
				$string = "";
				while(!feof($fp)) {
					$pagetext = fgets($fp,228);
					$string .= chop($pagetext);
				}
				fputs($fp,"Connection: close\r\n\r\n");
				fclose($fp);
				$items = explode("</item>",$string);
				$content = "<font class=\"content\">";
				for ($i=0;$i<10;$i++) {
					$link = ereg_replace(".*<link>","",$items[$i]);
					$link = ereg_replace("</link>.*","",$link);
					$title2 = ereg_replace(".*<title>","",$items[$i]);
					$title2 = ereg_replace("</title>.*","",$title2);
					if ($items[$i] == "" AND $cont != 1) {
						$content = "";
					} else {
						if (strcmp($link,$title2) AND $items[$i] != "") {
							$cont = 1;
							$content .= "<img border=\"0\" src=\"images/arrow2.gif\" width=\"10\" height=\"5\">&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br/>\n";
						}
					}
				}
			}
		}
	}

	$link2 = "";
	if ($link != "") {
		if (!preg_match("!^http(?:s)?://!i",$link)) {
			$link2 = "http://$link";
		} else { $link2 = $link; }
	}

	if(@in_array("all",$bmodule)) {$bmodule_up ="all"; }else{
		$bmodule_up = @implode("|",$bmodule);
	}
	if($bmodule_up =="") { $bmodule_up = "all"; }

	if (!empty($expire)) {
		$expire_up = time() + ($expire * 3600);
	}

	if ($oldposition != $bposition) {
		$result = $db->sql_query("SELECT weight FROM ".$prefix."_blocks WHERE bposition='$bposition' ORDER BY weight DESC");
		$row = $db->sql_fetchrow($result);
		$weight = $row['weight'];
		$weight++;
	}

	if(!$err) {
		$btime = time();
		if($content != "") {
			@chmod("".RPATH."".DATAFOLD."/blocks/$blockfile", 0777);
			@$file = fopen("".RPATH."".DATAFOLD."/blocks/$blockfile", "w");
			$content2 = "<?php\n\n";
			$fctime = date("d-m-Y H:i:s",filectime ("".RPATH."".DATAFOLD."/blocks/$blockfile"));
			$fmtime = date("d-m-Y H:i:s");
			$content2 .= "// File: $blockfile.\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
			$content2 .= "\$bl_arr = array();\n";
				$content2 .= "\$bl_arr[] = \$bl_l;\n";
				$content2 .= "\$bl_arr[] = \$bl_r;\n";
				$content2 .= "\$basename = pathinfo(__FILE__, PATHINFO_BASENAME);\n";
				$content2 .= "\$correctArr = array();\n";
				$content2 .= "for (\$i = 0; \$i < count(\$bl_arr); \$i++) \n";
				$content2 .= "{\n";
				$content2 .= "	for (\$h = 0; \$h < count(\$bl_arr[\$i]); \$h++) \n";
				$content2 .= "	{\n";
				$content2 .= "		\$temp = explode(\"@\", \$bl_arr[\$i][\$h]);\n";
				$content2 .= "		if ((\$temp[5] == \$currentlang) && (\$temp[6] == \$basename)) \n";
				$content2 .= "		{\n";
				$content2 .= "			\$correctArr = \$temp;\n";
				$content2 .= "			break;\n";
				$content2 .= "		}\n";
				$content2 .= "	}\n";
				$content2 .= "}\n";
				$content2 .= "\n";
				$content2 .= "\$content = \"\";\n";
				$content2 .= "\$content .= \"<div class=\\\"div-block\\\">\";\n";
				$content2 .= "\$content .= \"<div class=\\\"div-tblock\\\">{\$correctArr[1]}</div>\";\n";
				$content2 .= "\$content .= \"<div class=\\\"div-cblock\\\">\";\n";
				$content2 .= "\$content .= \"".htmlspecialchars(stripslashes($content))."\";\n";
				$content2 .= "\$content .= \"</div>\";\n";
				$content2 .= "\$content .= \"</div>\";\n";
				$content2 .= "\n";
				$content2 .= "?>";
			@$writefile = fwrite($file, $content2);
			@fclose($file);
			@chmod("".RPATH."".DATAFOLD."/blocks/$blockfile", 0604);
		}

		$db->sql_query("UPDATE ".$prefix."_blocks SET bkey='$bkey', title='$title', url='$url', bposition='$bposition', active='$active', refresh='$refresh', blockfile='$blockfile', view='$view', expire='$expire_up', action='$action', link='$link', module='$bmodule_up', showtitle='$showtitle' WHERE bid='$id'"); //, weight='$weight'
		fixweight();
		blist();
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT);
		header("Location: modules.php?f=".$adm_modname."");
	}
}

echo"<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"post\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._EDITBLOCK."</td></tr>";
echo "<tr><td width=\"25%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td><td class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"30\" maxlength=\"60\"></td></tr>";
if($tip==2) {
	echo"<tr><td align=\"right\" class=\"row1\"><b>"._RSSFILE."</b></td><td class=\"row3\">$err_rss<input type=\"text\" name=\"url\" value=\"$url\" size=\"30\" maxlength=\"200\"></td></tr>";
	echo "<input type=\"hidden\" name=\"blockfile\" value=\"$blockfile\">";
}else{
	echo "<input type=\"hidden\" name=\"url\" value=\"\">";
}

if($tip!=2) {
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._BLOCKLINK."</b></td><td align=\"left\" class=\"row3\"><input type=\"text\" name=\"link\" value=\"$link\" size='50'>&nbsp;&nbsp;<font class=\"grey\">"._LINKINCLUDE."</font></td></tr>";
} else {
	echo "<input type=\"hidden\" name=\"link\" value=\"\">";
}

if($tip==0) {
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._FILENAME."</b></td><td class=\"row3\">";
	echo "$err_blf<select name=\"blockfile\">";
	echo "<option name=\"blockfile\" value=\"0\">"._CHOOSEBLOCKS."</option>";
	$blocksdir = dir("../blocks");
	while($func=$blocksdir->read()) {
		if(substr($func, 0, 6) == "block-") {
			$blockslist .= "$func ";
		}
	}
	closedir($blocksdir->handle);
	$blockslist = explode(" ", $blockslist);
	sort($blockslist);
	for ($i=0; $i < sizeof($blockslist); $i++) {
		if($blockslist[$i]!="") {
			$bl = ereg_replace("block-","",$blockslist[$i]);
			$bl = ereg_replace(".php","",$bl);
			$bl = ereg_replace("_"," ",$bl);
			$sql = "select * from ".$prefix."_blocks where blockfile='$blockslist[$i]' AND blockfile!='$blockfile' AND blanguage='$blang'";
			$result = $db->sql_query($sql);
			if ($numrows = $db->sql_numrows($result) == 0) {
				if($blockslist[$i] == $blockfile) {$seld =" selected"; }else{ $seld =""; }
				echo "<option value=\"$blockslist[$i]\"$seld>$bl</option>\n";
			}
		}
	}
	echo "</select>&nbsp;&nbsp;<font class=\"grey\">"._FILEINCLUDE."</font></td></tr>";
} else {
	echo "<input type=\"hidden\" name=\"blockfile\" value=\"$blockfile\">";
}
if($tip==1) {
	@include("../".DATAFOLD."/blocks/$blockfile");
	echo"<tr><td align=\"right\" class=\"row1\"><b>"._CONTENT."</b></td><td class=\"row3\">$err_cont";
	editor("content", $content);
	echo "</td></tr>";
} else {
	echo "<input type=\"hidden\" name=\"content\" value=\"\">";
}
$oldposition = $bposition;
echo "<input type=\"hidden\" name=\"oldposition\" value=\"$oldposition\">";
$pos_array = array("l","c","r","d");
$posname_array = array(_LEFT,_CENTERUP,_RIGHT,_CENTERDOWN);
echo "<tr><td align=\"right\" class=\"row1\"><b>"._POSITION."</b></td><td class=\"row3\"><select name=\"bposition\">";
for($pc=0;$pc < sizeof($pos_array);$pc++) {
	echo "<option name = \"bposition\" value=\"$pos_array[$pc]\"";
	if($bposition== "$pos_array[$pc]") { echo " selected"; }
	echo ">$posname_array[$pc]</option>\n";
}
echo "</select></td></tr>";
if ($active == 1) {
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._ACTIVE."?</b></td><td class=\"row3\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td></tr>";
} elseif ($active == 0) {
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._ACTIVE."?</b></td><td class=\"row3\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td></tr>";
}
if($showtitle == 1) {
	echo "<tr><td align=\"right\"  class=\"row1\"><b>"._SHOWTITLE."?</b></td><td  class=\"row3\"><input type=\"radio\" name=\"showtitle\" value=\"1\" checked> "._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"showtitle\" value=\"0\"> "._NO."</td></tr>";
} else {
	echo "<tr><td align=\"right\"  class=\"row1\"><b>"._SHOWTITLE."?</b></td><td  class=\"row3\"><input type=\"radio\" name=\"showtitle\" value=\"1\"> "._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"showtitle\" value=\"0\" checked> "._NO."</td></tr>";
}
echo "<tr><td align=\"right\" class=\"row1\"><b>"._EXPIRATION."</b> ("._HOURS.")</td><td class=\"row3\">";
if (!empty($expire)) {
	$oldexpire = $expire;
	$expire = intval(($expire - time()) / 3600);
	$exp_day = $expire / 24;
	echo "<input type=\"text\" name=\"expire\" value=\"$expire\" size=\"20\">&nbsp;<b>$expire "._HOURS." (".substr($exp_day,0,5)." "._DAYS.")</b>";
} else {
	echo "<input type=\"text\" name=\"expire\" value=\"0\" size=\"4\" maxlength=\"3\"> "._DAYS."";
}
echo "</td></tr>";
echo "<tr><td align=\"right\" class=\"row1\"><b>"._AFTEREXPIRATION."</b></td><td class=\"row3\"><select name=\"action\">";
if ($action == 0) {
	echo "<option name=\"action\" value=\"0\" selected>"._DEACTIVATE."</option>"
	."<option name=\"action\" value=\"1\">"._DELETE."</option>";
} elseif ($action == 1) {
	echo "<option name=\"action\" value=\"0\">"._DEACTIVATE."</option>"
	."<option name=\"action\" value=\"1\"  selected>"._DELETE."</option>";
}
echo "</select></td></tr>";
if ($url != "") {
	$refr_array = array(1800, 3600, 18000, 36000, 86400);
	$refrnm_array = array("1/2", "1", "5", "10", "24");
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._REFRESHTIME."</b></td><td class=\"row3\"><select name=\"refresh\">";
	for($ri=0;$ri < sizeof($refr_array);$ri++) {
		echo "<option name=\"refresh\" value=\"$refr_array[$ri]\"";
		if($refresh == $refr_array[$ri]) { echo " selected"; }
		echo ">".$refrnm_array[$ri]." "._HOUR."</option>";
	}
	echo "</select>";
} else {
	echo "<input type=\"hidden\" name=\"refresh\" value=\"$refresh\">";
}
$view_array = array(_ALL, _MVADMIN);
echo"<tr><td align=\"right\" class=\"row1\"><b>"._VIEWPRIV."</b></td><td class=\"row3\"><select name=\"view\">";
for($vi=0;$vi < sizeof($view_array);$vi++) {
	echo "<option value=\"$vi\"";
	if($view==$vi) { echo " selected"; }
	echo">$view_array[$vi]</option>";
}
echo "</select>"
."</td></tr>"
. "<tr valign=\"top\" >\n<td align=\"right\" class=\"row1\"><b>"._DISPLAYAREA.": </b></td>\n<td class=\"row3\">";
$bmodule_arr = @explode("|",$bmodule);
echo "<table border=\"0\" style=\"border-collapse: collapse\" cellpadding=\"4\" cellspacing=\"0\"><tr>\n";
echo "<td><input type=\"checkbox\" name=\"bmodule[]\" value=\"all\"";
if(@in_array("all",$bmodule_arr)) { echo " checked";}
echo "> "._ALL."</td>\n"
."<td><input type=\"checkbox\" name=\"bmodule[]\" value=\"home\"";
if(@in_array("home",$bmodule_arr) && !@in_array("all",$bmodule_arr)) {echo " checked"; }
echo "> "._HOMEPAGE."</td>\n"
."</tr></table>\n";
echo "<table border=\"0\" style=\"border-collapse: collapse\" cellpadding=\"2\" cellspacing=\"0\"><tr>\n";
$a =0;
for($l=0;$l < sizeof($listmods);$l++) {
	$title = ereg_replace("_", " ", $listmods[$l]);
	$xstitle = strtolower($listmods[$l]);
	$seld ="";
	if(@in_array($listmods[$l],$bmodule_arr) && !in_array("all",$bmodule_arr)) {
		$seld =" checked";
	}

	if(!@in_array($title,$listmods_noaccept)) {
		$a ++;
		echo "<td style=\"padding-right: 30px\"><input type=\"checkbox\" name=\"bmodule[]\" value=\"$listmods[$l]\"$seld> ".$listmods_custom[$l]."</td>";
	}
	if($a == 2) { $a =0; echo "</tr>"; }
}
echo"</table>"
. "</td>\n</tr>\n";
echo "<input type=\"hidden\" name=\"bkey\" value=\"$tip\">";
echo "<input type=\"hidden\" name=\"weight\" value=\"$weight\">";
echo "<input type=\"hidden\" name=\"blang\" value=\"$blang\">"
."<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='".$adm_modname.".php'\"></form></td></tr>"
."</table>";
include_once("page_footer.php");
?>