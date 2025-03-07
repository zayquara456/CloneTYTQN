<?php
if (!file_exists("config.php")) die();
define('CMS_SYSTEM', true);
@require_once("config.php");

define('IN_SEARCH',TRUE);

$module_name = "search";
getlangmod($module_name);

$first_page_res = 3;
$perpage = 15;
$text_long = 200;

$load_hf = 0;

$result_mods = $db->sql_query("SELECT title, custom_title, view FROM ".$prefix."_modules WHERE active=1 ORDER BY title");
while ($row = $db->sql_fetchrow($result_mods)) {
	$modnamelist[$row['title']] = $row;
}

$handle=opendir("modules/$module_name/mod");
while ($file = readdir($handle)) {
	if (substr(strtolower($file), -4) == ".php") $filec = substr($file, 0, strlen($file) - 4);
	else $filec = $file;
	foreach($modnamelist as $mnm) {
		if($filec == $mnm['title']) {
			$modulelist[] = $filec;
		}
	}
}
closedir($handle);

$query = nospatags(isset($_POST['q']) ? $_POST['q'] : (isset($_GET['q']) ? nospatags($_GET['q']) : ""));
$query =  str_replace('"', '', $query);
$query =  str_replace('>', '', $query);
$query =  str_replace('<', '', $query);
$query =  str_replace("'", "", $query);
$query = trim($query);
$titlemod = nospatags((isset($_POST['t'])) ? $_POST['t'] : (isset($_GET['t']) ? $_GET['t'] : ""));

$eror_query = "";
include("header.php");
OpenTab(_MODTITLE);
if($query =="") {
	echo "<script>";
	echo "function checkvalidsearch(Forma) {";
	echo "if (Forma.q.value == \"\") {";
	echo "Forma.q.focus();";
	echo "return false;";
	echo "}";
	echo "	return true;";
	echo "}";
	echo "</script>";
	echo "<form method=\"POST\" onsubmit=\"return checkvalidsearch(this);\" action=\"".url_sid("search.php")."\"><table border=\"0\" align=\"center\" cellpadding=\"3\" style=\"border-collapse: collapse\">";
	echo "	<tr>";
	echo "		<td align=\"right\">"._KEYWORD.":</td>";
	echo "		<td><input type=\"text\" name=\"q\" size=\"30\" maxlength=\"20\"></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td align=\"right\">"._SEARCHIN.":</td>";
	echo "		<td><select name=\"titlemod\">";
	echo "<option value=\"\">"._ALLSITE."</option>";
	foreach($modulelist as $modlist) {
		$modview = $modnamelist[$modlist]['view'];
		if($modview == 0 || ($modview == 1 && (defined('IS_USER') || defined('IS_ADMIN'))) || ($modview == 2 && defined('IS_ADMIN'))) {
			$mtitle = $modnamelist[$modlist]['custom_title'];
			echo "<option value=\"$modlist\">$mtitle</option>";
		}
	}
	echo "</select></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td align=\"right\">&nbsp;</td>";
	echo "		<td><input type=\"submit\" class=\"sb_but1\" value=\""._SEARCH."\"></td>";
	echo "	</tr>";
	echo "	</table></form>";
}else{
	if(strlen($query) < 2) {
		$eror_query = _ONMINSIMBOL;
	}

	if (preg_match("![^a-zA-Z0-9_]!", $titlemod)) {
		$eror_query = _ERORHAK;
	}

	if($eror_query !="") {
		OpenTable();
		echo "<center>"._ERROR.": $eror_query<br/><br/>"._GOBACK."</center>";
		CloseTable();
		CloseTab();
		include("footer.php");
		exit;
	}

	if((isset($modulelist)) AND ($titlemod == "")) {
		foreach($modulelist as $modfile) {
			$modview1 = $modnamelist[$modfile]['view'];
			if($modview1 == 0 || ($modview1 == 1 && (defined('IS_USER') || defined('iS_ADMIN'))) || ($modview1 == 2 && defined('iS_ADMIN'))) {
				include("modules/$module_name/mod/".$modfile.".php");
				$mtitle = $modnamelist[$modfile]['custom_title'];
				echo"<table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" height=\"100%\">"
				."<tr><td colspan=2><font color=\"red\"><b>"._SEARCHIN." ".$mtitle.":</b></font></td></tr>";
				$c = 1;
				if($num_page > 0) {
					if($num_page > $first_page_res) { $for_count = $first_page_res; } else { $for_count = $num_page; }
					for($i=0; $i < $for_count; $i++) {
						$text[$i] = str_replace("<br/>", " ", $text[$i]);
						$text[$i] = strip_tags($text[$i]);
						if(strlen($text[$i]) > $text_long) {
							$query_pos = strpos($text[$i], $query);
							if(($query_pos > $text_long) AND ($query_pos < strlen($text[$i])-$text_long)) {
								$text[$i] = "".substr($text[$i], $query_pos-($text_long/2), $text_long)."";
								$tlist = explode(" ", $text[$i]);
								$text[$i] = substr($text[$i], strlen($tlist[0])+1);
								$text[$i] = substr($text[$i], 0, strlen($text[$i])-strlen($tlist[sizeof($tlist)-1])-1);
								$text[$i] = "...".$text[$i]."...";

							} else if(($query_pos > $text_long) AND ($query_pos > strlen($text[$i])-$text_long)) {
								$text[$i] = "".substr($text[$i], -$text_long)."";
								$tlist = explode(" ", $text[$i]);
								$text[$i] = substr($text[$i], strlen($tlist[0])+1);
								$text[$i] = "...".$text[$i]."";
							} else {
								$text[$i] = "".substr($text[$i], 0, $text_long)."";
								$tlist = explode(" ", $text[$i]);
								$text[$i] = substr($text[$i], 0, strlen($text[$i])-strlen($tlist[sizeof($tlist)-1])-1);
								$text[$i] = "".$text[$i]."...";
							}
						}
						echo"<tr><td valign=top><b>".$c.".</b></td>"
						."<td width=100% valign=top><b><a href=\"".url_sid("index.php?f=".$modfile."&".$url[$i]."")."\" target=\"_blank\">".$title[$i]."</a></b><br/>"
						."".$text[$i]."</td></tr>";
						$c ++;
					}
					if($num_page > $first_page_res) {
						echo"<tr><td colspan=2><b><a href=\"".url_sid("".$module_name.".php?t=".$modfile."&q=".$query."")."\">"._MODALLSEARCH.": $num_page "._RESULTS."</a></b><br/><br/></td></tr>";
					}

				}else{
					echo"<tr><td colspan=2>&raquo; "._INMODULE." <b>".$modnamelist[$modfile]['custom_title']."</b> "._NORESULTS."</td></tr>";
				}

				echo"</table><br/>";
				unset($title);
				unset($text);
				unset($url);
			}
		}
	}elseif ((isset($modulelist)) AND ($titlemod != "")) {
		$modview2 = $modnamelist[$titlemod]['view'];
		if($modview2 == 0 || ($modview2 == 1 && (defined('IS_USER') || defined('IS_ADMIN'))) || ($modview2 == 2 && defined('IS_ADMIN'))) {
			$mtitle = $modnamelist[$titlemod]['custom_title'];
			$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) : 1);
			$offset = ($page-1) * $perpage;
			include_once("modules/$module_name/mod/".$titlemod.".php");
			$all_page = ( $num_page ) ? $num_page : 1;
			echo "<table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" height=\"100%\">"
			."<tr><td colspan=2><font color=\"red\"><b>"._SEARCHIN." ".$mtitle.":</b></font></td></tr>";
			if($num_page > 0) {
				$c = 1;
				if($page > 1) { $c = $perpage*$page - $perpage + 1;}
				for($i =0; $i < count($title); $i++) {
					$text[$i] = str_replace("<br/>", " ", $text[$i]);
					$text[$i] = strip_tags($text[$i]);
					if(strlen($text[$i]) > $text_long) {
						$query_pos = strpos($text[$i], $query);
						if(($query_pos > $text_long) AND ($query_pos < strlen($text[$i])-$text_long)) {
							$text[$i] = substr($text[$i], $query_pos-($text_long/2), $text_long);
							$tlist = explode(" ", $text[$i]);
							$text[$i] = substr($text[$i], strlen($tlist[0])+1);
							$text[$i] = substr($text[$i], 0, strlen($text[$i])-strlen($tlist[sizeof($tlist)-1])-1);
							$text[$i] = "...".$text[$i]."...";

						} else if(($query_pos > $text_long) AND ($query_pos > strlen($text[$i])-$text_long)) {
							$text[$i] = substr($text[$i], -$text_long);
							$tlist = explode(" ", $text[$i]);
							$text[$i] = substr($text[$i], strlen($tlist[0])+1);
							$text[$i] = "...".$text[$i];
						} else {
							$text[$i] = substr($text[$i], 0, $text_long);
							$tlist = explode(" ", $text[$i]);
							$text[$i] = substr($text[$i], 0, strlen($text[$i])-strlen($tlist[sizeof($tlist)-1])-1);
							$text[$i] = $text[$i]."...";
						}
					}

					echo"<tr><td valign=top><b>$c.</b></td>"
					."<td width=100% valign=top><b><a href=\"".url_sid("index.php?f=".$titlemod."&".$url[$i]."")."\" target=\"_blank\">".$title[$i]."</a></b><br/>"
					."".$text[$i]."</td></tr>";
					$c++;

				}
				if($all_page > $perpage) {
					echo "<tr><td colspan=\"2\"><hr>";
					$pageurl = "{$module_name}.php?t=$titlemod&q=$query";
					echo paging($all_page,$pageurl,$perpage,$page);
					echo "</td></tr>";
				}
			}else{
				echo"<tr><td colspan=2>&raquo; "._INMODULE." <b>".$modnamelist[$titlemod]['custom_title']."</b> "._NORESULTS."</td></tr>";
			}
			echo"</table><br/>";
		}
	}

	form_search($query, $titlemod);

}

CloseTab();
include("footer.php");

function form_search($q, $titlemod) {
	global $db, $prefix, $module_name, $modulelist, $modnamelist;
	echo "<script>";
	echo "function checkvalidsearch(Forma) {";
	echo "if (Forma.q.value == \"\") {";
	echo "Forma.q.focus();";
	echo "return false;";
	echo "}";
	echo "	return true;";
	echo "}";
	echo "</script>";
	echo "<form method=\"POST\" onsubmit=\"return checkvalidsearch(this);\" action=\"".url_sid("search.php")."\"><table align=\"center\" border=\"0\" cellpadding=\"3\" style=\"border-collapse: collapse\">";
	echo "	<tr>";
	echo "		<td align=\"right\">"._KEYWORD.":</td>";
	echo "		<td><input type=\"text\" name=\"q\" value=\"".$q."\" size=\"30\" maxlength=\"20\"></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td align=\"right\">"._SEARCHIN.":</td>";
	echo "		<td><select name=\"t\">";
	echo "<option value=\"\">"._ALLSITE."</option>";
	foreach($modulelist as $modlist) {
		$modview = $modnamelist[$modlist]['view'];
		if($modview == 0 || ($modview == 1 && (defined('IS_USER') || defined('IS_ADMIN'))) || ($modview == 2 && defined('IS_ADMIN'))) {
			$mtitle = $modnamelist[$modlist]['custom_title'];
			$seld ="";
			if($modlist == $titlemod) { $seld =" selected"; }
			echo "<option value=\"$modlist\"$seld>$mtitle</option>";
		}
	}
	echo "</select></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td align=\"right\">&nbsp;</td>";
	echo "		<td><input type=\"submit\" class=\"sb_but1\" value=\""._SEARCH."\"></td>";
	echo "	</tr>";
	echo "	</table></form>";
}

?>