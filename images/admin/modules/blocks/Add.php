<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

if (!defined('_BLOCKSADD')) require_once("language/$currentlang/blocks.php");

$tip = isset($_POST['tip']) ? intval($_POST['tip']) : (isset($_GET['tip']) ? intval($_GET['tip']) : "");

$adm_pagetitle2 = _BLOCKSADD;
include_once("page_header.php");

if(empty($tip)) {
	echo "$tip<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"2\" class=\"header\">"._BLOCKSADD."</td></tr>";
	echo "<tr>\n";
	echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._CHOOSETYPE."</b></td>\n";
	echo "<td  class=\"row3\"><select name=\"tip\">";
	$tip_ar = array('File', 'HTML', 'RSS/RDF');
	for ($i=0; $i < sizeof($tip_ar); $i++) {
		$a = $i +1;
		echo "<option value=\"$a\">".ucfirst($tip_ar[$i])."</option>\n";
	}
	echo "</select> <input type=\"submit\" value=\""._GO."\" class=\"button1\"></td></tr>";
	echo "</table></form>";
	include_once("page_footer.php");
} else {
	$err_title = $title = $err_blf = $err_cont = $content = $err_rss = $url ="";
	$active = $showtitle = 1;
	if(isset($_POST['submit']) && !empty($_POST['submit'])) {
		$title = trim(stripslashes(resString($_POST['title'])));
		$content = trim(stripslashes(resString($_POST['content'])));
		$url = trim(stripslashes(resString($_POST['url'])));
		$bposition = trim(stripslashes(resString($_POST['bposition'])));
		$active = intval($_POST['active']);
		$showtitle = intval($_POST['showtitle']);
		$refresh = intval($_POST['refresh']);
		$headline = intval($_POST['headline']);
		$blockfile = $_POST['blockfile'];
		$expire = intval($_POST['expire']);
		$action = intval($_POST['action']);
		$link = trim(stripslashes(resString($_POST['link'])));
		$bmodule = isset($_POST['bmodule']) ? $_POST['bmodule'] : "";
		$bkey = intval($_POST['bkey']) - 1;
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
			$content=""; $blockfile = "";
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
				$rdf ="";
				$rdf = parse_url($url);
				$fp ="";
				$fp = @fsockopen($rdf['host'], 80, $errno, $errstr, 15);
				if (!$fp) {
					OpenDiv();
					echo "<center><b>"._RSSFAIL."</b><br/><br/>";
					echo ""._RSSTRYAGAIN."<br/><br/></center>";
					CloseDiv();
					$bkey1 = $bkey+1;
					echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=modules.php?f=".$adm_modname.".&do=add&tip=$bkey1\">";
					include_once("page_footer.php");
					exit;
				}
				if ($fp) {
					fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n");
					@fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
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
						if ($items[$i] == "" AND @$cont != 1) {
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

		$result = $db->sql_query("SELECT weight FROM ".$prefix."_blocks WHERE bposition='$bposition' AND blanguage='$currentlang' ORDER BY weight DESC");
		$row = $db->sql_fetchrow($result);
		$weight = $row['weight'];
		$weight++;

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

		$expire = intval($expire);
		if ($expire != 0) {
			$expire = time() + ($expire * 86400);
		}

		if(!$err) {
			$btime = time();
			if($content != "") {
				@chmod("".RPATH."".DATAFOLD."/blocks/block-".$btime.".php", 0777);
				@$file = fopen("".RPATH."".DATAFOLD."/blocks/block-".$btime.".php", "w");
				$content2 = "<?php\n\n";
				$content2 .= "if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) {\n";
				$content2 .= "die();\n";
				$content2 .= "}\n";
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
				@chmod("".RPATH."".DATAFOLD."/blocks/block-".$btime.".php", 0604);
				$blockfile = "block-".$btime.".php";
			}

			$db->sql_query("INSERT INTO ".$prefix."_blocks (bid, bkey, title, url, bposition, weight, active, refresh, time, blanguage, blockfile, view, expire, action, link, module, showtitle) VALUES (NULL, '$bkey', '$title', '$url', '$bposition', '$weight', '$active', '$refresh', '$btime', '$currentlang', '$blockfile', '$view', '$expire', '$action', '$link2', '$bmodule_up', '$showtitle')");
			fixweight();
			blist();
			updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADD);
			Header("Location: modules.php?f=".$adm_modname."");
		}
	}

	echo"<form action=\"modules.php?f=$adm_modname&do=$do&tip=$tip\" method=\"post\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"2\" class=\"header\">"._BLOCKSADD."</td></tr>";
	echo "<tr><td width=\"30%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td><td  class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"30\" maxlength=\"60\"></td></tr>";
	if($tip==3) {
		echo"<tr><td align=\"right\"  class=\"row1\"><b>"._RSSFILE."</b></td><td  class=\"row3\">$err_rss<input type=\"text\" name=\"url\" value=\"$url\" size=\"30\" maxlength=\"200\">&nbsp;&nbsp;"
		."<select name=\"headline\">"
		."<option name=\"headline\" value=\"0\" selected>"._CUSTOM."</option>";
		$sql = "select rid, sitename from ".$prefix."_rss";
		$res = $db->sql_query($sql);
		while($row = $db->sql_fetchrow($res)) {
			$hid = $row['rid'];
			$htitle = $row['sitename'];
			if($hid == $headline) { $seld = " selected"; }else{ $seld =""; }
			echo "<option name=\"headline\" value=\"$hid\"$seld>$htitle</option>";
		}
		echo "</select>&nbsp;[ <a href=\"?f=".$adm_modname."&do=Setup_Rss\">Setup</a> ]<br/><font class=\"tiny\">";
		echo ""._SETUPHEADLINES."</font></td></tr>";
	} else {
		echo "<input type=\"hidden\" name=\"url\" value=\"\">";
		echo "<input type=\"hidden\" name=\"headline\" value=\"0\">";
	}

	if($tip!=3) {
		echo "<tr><td align=\"right\"  class=\"row1\"><b>"._BLOCKLINK."</b></td><td align=\"left\"  class=\"row3\"><input type='text' name='link' size='50'>&nbsp;&nbsp;<font class=\"grey\">"._LINKINCLUDE."</font></td></tr>";
	} else {
		echo "<input type=\"hidden\" name=\"link\" value=\"\">";
	}

	if($tip==1) {
		echo "<tr><td align=\"right\"  class=\"row1\"><b>"._FILENAME."</b></td><td class=\"row3\">";
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
				$sql = "select * from ".$prefix."_blocks where blockfile='$blockslist[$i]' AND blanguage='$currentlang'";
				$result = $db->sql_query($sql);
				if ($numrows = $db->sql_numrows($result) == 0) {
					if($blockslist[$i] == $blockfile) {$seld =" selected"; }else{ $seld =""; }
					echo "<option value=\"$blockslist[$i]\"$seld>$bl</option>\n";
				}
			}
		}
		echo "</select>&nbsp;&nbsp;<font class=\"grey\">"._FILEINCLUDE."</font></td></tr>";
	} else {
		echo "<input type=\"hidden\" name=\"blockfile\" value=\"\">";
	}
	if($tip==2) {
		echo"<tr><td align=\"right\"  class=\"row1\"><b>"._CONTENT."</b></td><td  class=\"row3\">$err_cont";
		editor("content", $content);
		echo "</td></tr>";
	} else {
		echo "<input type=\"hidden\" name=\"content\" value=\"\">";
	}
	echo "<tr><td align=\"right\"  class=\"row1\"><b>"._POSITION."</b></td><td  class=\"row3\"><select name=\"bposition\"><option name=\"bposition\" value=\"l\">"._LEFT."</option>"
	."<option name=\"bposition\" value=\"c\">"._CENTERUP."</option>"
	."<option name=\"bposition\" value=\"d\">"._CENTERDOWN."</option>"
	."<option name=\"bposition\" value=\"r\">"._RIGHT."</option></select></td></tr>";
	if($active == 1) {
		echo "<tr><td align=\"right\"  class=\"row1\"><b>"._ACTIVE."?</b></td><td  class=\"row3\"><input type=\"radio\" name=\"active\" value=\"1\" checked> "._YES." &nbsp;&nbsp;"
		."<input type=\"radio\" name=\"active\" value=\"0\"> "._NO."</td></tr>";
	} else {
		echo "<tr><td align=\"right\"  class=\"row1\"><b>"._ACTIVE."?</b></td><td  class=\"row3\"><input type=\"radio\" name=\"active\" value=\"1\"> "._YES." &nbsp;&nbsp;"
		."<input type=\"radio\" name=\"active\" value=\"0\" checked> "._NO."</td></tr>";
	}
	if($showtitle == 1) {
		echo "<tr><td align=\"right\"  class=\"row1\"><b>"._SHOWTITLE."?</b></td><td  class=\"row3\"><input type=\"radio\" name=\"showtitle\" value=\"1\" checked> "._YES." &nbsp;&nbsp;"
		."<input type=\"radio\" name=\"showtitle\" value=\"0\"> "._NO."</td></tr>";
	} else {
		echo "<tr><td align=\"right\"  class=\"row1\"><b>"._SHOWTITLE."?</b></td><td  class=\"row3\"><input type=\"radio\" name=\"showtitle\" value=\"1\"> "._YES." &nbsp;&nbsp;"
		."<input type=\"radio\" name=\"showtitle\" value=\"0\" checked> "._NO."</td></tr>";
	}
	echo "<tr><td align=\"right\"  class=\"row1\"><b>"._EXPIRATION."</b></td><td  class=\"row3\"><input type=\"text\" name=\"expire\" size=\"3\" maxlength=\"3\" value=\"0\"> "._DAYS."</td></tr>"
	."<tr><td align=\"right\"  class=\"row1\"><b>"._AFTEREXPIRATION."</b></td><td  class=\"row3\"><select name=\"action\">"
	."<option name=\"action\" value=\"d\">"._DEACTIVATE."</option>"
	."<option name=\"action\" value=\"r\">"._DELETE."</option></select></td></tr>";
	if($tip==3) {
		echo"<tr><td align=\"right\"  class=\"row1\"><b>"._REFRESHTIME."</b></td><td  class=\"row3\"><select name=\"refresh\">"
		."<option name=\"refresh\" value=\"1800\">1/2 "._HOUR."</option>"
		."<option name=\"refresh\" value=\"3600\" selected>1 "._HOUR."</option>"
		."<option name=\"refresh\" value=\"18000\">5 "._HOURS."</option>"
		."<option name=\"refresh\" value=\"36000\">10 "._HOURS."</option>"
		."<option name=\"refresh\" value=\"86400\">24 "._HOURS."</option></select>&nbsp;<font class=\"tiny\">"._ONLYHEADLINES."</font></td></tr>";
	} else {
		echo "<input type=\"hidden\" name=\"refresh\" value=\"0\">";
	}
	echo"<tr><td align=\"right\"  class=\"row1\"><b>"._VIEWPRIV."</b></td><td  class=\"row3\"><select name=\"view\">"
	."<option value=\"0\" >"._ALL."</option>"
	."<option value=\"1\" >"._MVADMIN."</option>"
	."</select>"
	."</td></tr>";
	echo  "<tr valign=\"top\">\n<td align=\"right\" class=\"row1\"><b>"._DISPLAYAREA.": </b></td>\n<td class=\"row3\">";

	echo "<table border=\"0\" style=\"border-collapse: collapse\" cellpadding=\"2\" cellspacing=\"0\"><tr>\n";
	if(@in_array("all",$bmodule)) { $seldall =" checked"; } else { $seldall =""; }
	if(@in_array("home",$bmodule) && !@in_array("all",$bmodule)) { $seldhome =" checked"; } else { $seldhome =""; }
	echo "<td><input type=\"checkbox\" name=\"bmodule[]\" value=\"all\"$seldall> "._ALL."</td>\n"
	."<td><input type=\"checkbox\" name=\"bmodule[]\" value=\"home\"$seldhome> "._HOMEPAGE."</td>\n"
	."</tr>\n";
	echo "<tr>\n";
	$a =0;
	for($l=0;$l < sizeof($listmods);$l++) {
		$title = ereg_replace("_", " ", $listmods[$l]);
		$xstitle = strtolower($listmods[$l]);
		$seld ="";
		if(@in_array($listmods[$l],$bmodule) && !in_array("all",$bmodule)) {
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
	echo "<input type=\"hidden\" name=\"tip\" value=\"$tip\">";
	echo "<tr><td colspan=\"2\" class=\"row3\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></form></td></tr></table>";
	echo "<br/>";
	include_once("page_footer.php");
}

?>