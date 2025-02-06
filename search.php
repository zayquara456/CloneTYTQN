<?php
if (!file_exists("config.php")) die();
define('CMS_SYSTEM', true);
@require_once("config.php");

define('IN_SEARCH',TRUE);
global $urlsite;
$module_name = "search";
getlangmod($module_name);

$first_page_res = 10;
$perpage = 10;
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
include("blocks/Menu_Left.php");
//begin check show menu
echo show_left_menu(69);
OpenTab(_MODTITLE);
echo "<div class=\"content\">";
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
	//echo "<form enctype=\"application/x-www-form-urlencoded\"method=\"POST\" onsubmit=\"return checkvalidsearch(this);\" action=\"".url_sid("search.php")."\"><table border=\"0\" align=\"center\" cellpadding=\"3\" style=\"border-collapse: collapse\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"2\">"
			."<tr><td valign=\"top\" colspan='2' valign=\"top\"><h1 class=\"posttitle\">"._SEARCH.":</h1><br><img src=\"$urlsite/templates/$Default_Temp/images/search.jpg\">
			";
			
			echo "<form enctype=\"application/x-www-form-urlencoded\" style=\"padding:0; margin:0;\" action=\"".url_sid("search.php?t=news")."\" method=\"POST\"><table><tr><td valign=\"top\">";
echo "<span style=\"margin-top:20px\">"._KEYWORD_SEARCH.": </span></td><td valign=\"top\"><div class=\"frmsearch\"> <input type=\"text\" name=\"q\" class=\"in1\"><input type=\"hidden\" name=\"csrf\" value=\"$key\" /><input type=\"submit\"  align=\"absbottom\" class=\"btn_hsearch\" value=\""._SEARCH."\">";
echo "</div></td><tr></table></form>";
			echo "</td></tr>";
	echo "	</table>";
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
		echo "</div>";
		include("footer.php");
		exit;
	}

	if((isset($modulelist)) AND ($titlemod == "")) {
		?>
<div id="tabContainer">
    <div id="tabs">
      <ul>
	<?php
	$k=1;
	foreach($modulelist as $modfile) {
			$modview1 = $modnamelist[$modfile]['view'];
			if($modview1 == 0 || ($modview1 == 1 && (defined('IS_USER') || defined('iS_ADMIN'))) || ($modview1 == 2 && defined('iS_ADMIN'))) {
				include("modules/$module_name/mod/".$modfile.".php");
				$mtitle = $modnamelist[$modfile]['custom_title'];
				echo '<li id="tabHeader_'.$k.'">'.$mtitle.' ('.$num_page.')</li>';
				$k++;
			}
		}
	?>
      </ul>
    </div>
    <div id="tabscontent">
	<?php
	$k=1;
	foreach($modulelist as $modfile) {
			$modview1 = $modnamelist[$modfile]['view'];
			if($modview1 == 0 || ($modview1 == 1 && (defined('IS_USER') || defined('iS_ADMIN'))) || ($modview1 == 2 && defined('iS_ADMIN'))) {
				include("modules/$module_name/mod/".$modfile.".php");
				echo '<div  class="tabpage" id="tabpage_'.$k.'">';
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
						echo"<p><b>".$c.".</b>"
						."<b><a href=\"".url_sid("index.php?f=".$modfile."&".$url[$i]."")."\" target=\"_blank\">".$title[$i]."</a></b><br/>"
						."".$text[$i]."</p>";
						$c ++;
					}
					if($num_page > $first_page_res) {
						echo"<p><br/><b><a href=\"".url_sid("".$module_name.".php?t=".$modfile."&q=".$query."")."\">"._MODALLSEARCH.": $num_page "._RESULTS."</a></b></p>";
					}
					
				}else{
					echo"<p>&raquo; "._NORESULTS."</p>";
					//echo ""._INMODULE." <b>".$modnamelist[$modfile]['custom_title']."</b>";
				}
				echo '</div>';
					$k++;
				unset($title);
				unset($text);
				unset($url);
				
			}
		}
	?>
    </div>
  </div>
<script src="<?php echo $urlsite?>/js/tabs_old.js"></script>
				<?php
		
	}elseif ((isset($modulelist)) AND ($titlemod != "")) {
		$modview2 = $modnamelist[$titlemod]['view'];
		if($modview2 == 0 || ($modview2 == 1 && (defined('IS_USER') || defined('IS_ADMIN'))) || ($modview2 == 2 && defined('IS_ADMIN'))) {
			$mtitle = $modnamelist[$titlemod]['custom_title'];
			$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) : 1);
			$offset = ($page-1) * $perpage;
			include_once("modules/$module_name/mod/".$titlemod.".php");
			$all_page = ( $num_page ) ? $num_page : 1;
			echo "<table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"2\">"
			."<tr><td valign=\"top\" colspan=2><h1 class=\"posttitle\">"._SEARCH.":</h1><br><img src=\"$urlsite/templates/$Default_Temp/images/search.jpg\">
			";
			
			echo "<form enctype=\"application/x-www-form-urlencoded\" style=\"padding:0; margin:0;\" action=\"".url_sid("search.php")."\" method=\"POST\"><table><tr><td valign=\"top\">";
echo "<span style=\"margin-top:20px\">"._KEYWORD_SEARCH.": </span></td><td valign=\"top\"><div class=\"frmsearch\"> <input type=\"text\" name=\"q\" class=\"in1\"><input type=\"submit\"  align=\"absbottom\" class=\"btn_hsearch\" value=\""._SEARCH."\">";
echo "</div></td><tr></table></form>";
			if($num_page > $first_page_res) {
					echo""._MODALLSEARCH.": $num_page "._RESULTS."";
				}
			echo "</td></tr>";
			
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

					echo"<tr>"
					."<td valign=\"top\" width=100% valign=top><div class=\"search-title\" ><a  href=\"".url_sid("index.php?f=".$titlemod."&".$url[$i]."")."\" target=\"_blank\">$c. ".$title[$i]."</a></div><div class=\"search-content\">"
					."".$text[$i]."</div></td></tr>";
					$c++;

				}
				if($all_page > $perpage) {
					echo "<tr><td valign=\"top\" colspan=\"2\"><div style=\"text-align:right\">";
					$pageurl = "{$module_name}.php?t=$titlemod&q=$query";
					echo paging($all_page,$pageurl,$perpage,$page);
					echo "</div></td></tr>";
				}
			}else{
				echo"<tr><td valign=\"top\" colspan=2>&raquo; "._NORESULTS."</td></tr>";
				// "._INMODULE." <b>".$modnamelist[$titlemod]['custom_title']."</b>
			}
			echo"</table><br/>";
		}
	}

	//form_search($query, $titlemod);

}
echo "</div>";
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
	echo "<form enctype=\"application/x-www-form-urlencoded\" method=\"POST\" onsubmit=\"return checkvalidsearch(this);\" action=\"".url_sid("search.php")."\"><table align=\"center\" border=\"0\" cellpadding=\"3\" style=\"border-collapse: collapse\">";
	echo "	<tr>";
	echo "		<td valign=\"top\" align=\"right\">"._KEYWORD.":</td>";
	echo "		<td valign=\"top\"><input type=\"text\" name=\"q\" value=\"".$q."\" size=\"30\" maxlength=\"20\"></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td valign=\"top\" align=\"right\">"._SEARCHIN.":</td>";
	echo "		<td valign=\"top\"><select name=\"t\">";
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
	echo "		<td valign=\"top\" align=\"right\">&nbsp;</td>";
	echo "		<td valign=\"top\"><input type=\"hidden\" name=\"csrf\" value=\"$key\" /><input type=\"submit\" class=\"sb_but1\" value=\""._SEARCH."\"></td>";
	echo "	</tr>";
	echo "	</table></form>";
}

?>
