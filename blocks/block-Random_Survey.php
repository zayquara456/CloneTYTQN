<?php
if (!defined('CMS_SYSTEM')) exit;

if(file_exists(DATAFOLD."/config_surveys.php")) require(DATAFOLD."/config_surveys.php");

global $db, $prefix, $currentlang, $module_name, $Default_Temp;

$content = "";

if($module_name != "surveys") {
	$bl_arr = array();
	$bl_arr[] = $bl_l;
	$bl_arr[] = $bl_r;
	$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
	$correctArr = array();
	for ($i = 0; $i < count($bl_arr); $i++) {
		for ($h = 0; $h < count($bl_arr[$i]); $h++) {
			$temp = explode("@", $bl_arr[$i][$h]);
			if (($temp[5] == $currentlang) && ($temp[6] == $basename)) {
				$correctArr = $temp;
				break;
			}
		}
	}
	$content .= "<div class=\"div-block fl\" style=\"width:162px; margin-right:7px\">";
	$content .= "<div class=\"div-tblock\">{$correctArr[1]}</div>";
	if($svblocktype == 1) {
		$result = $db->sql_query("SELECT id, question, votes, anwsers, options, totalvotes FROM ".$prefix."_survey WHERE active=1 AND alanguage='$currentlang' ORDER BY rand() DESC LIMIT 1");
	} else {
		$result = $db->sql_query("SELECT id, question, votes, anwsers, options, totalvotes FROM ".$prefix."_survey WHERE active=1 AND alanguage='$currentlang' ORDER BY time DESC LIMIT 1");
	}
	if($db->sql_numrows($result) > 0) {
		list($idsv, $titlesv, $votes, $anwsers, $options, $totalvotes) = $db->sql_fetchrow($result);
		$anwsers_ar = @explode("|",$anwsers);
		$votes_ar = @explode("|",$votes);

		echo "<script language=\"javascript\">";
		echo "	function checkVote(vote) {";
		echo "		var vcheck = 0;";
		echo "		var poll = '';";
		echo "		for(var i=0; i < vote.length; i ++) {";
		echo "			if (vote[i].checked == true) {";
		echo "				poll = i;";
		echo "				vcheck = 1;";
		echo "			}	";
		echo "		}";
		echo "		if(vcheck == 0) {";
		echo "			alert('"._PLCHOOSEAS."');";
		echo "			return false;";
		echo "		} else {";
		echo "			openNewWindow('".url_sid("index.php?f=surveys&do=result&id=".$idsv."&vote='+poll,300,550,1").");";
		echo "		}			";
		echo "	}	";
		echo "</script>	";

		$content .= "<div class=\"div-cblock\" style=\"padding:5px 5px 5px 5px; line-height:16px\"><form method=\"POST\" action=\"".url_sid("index.php?f=surveys&do=result&id=$idsv")."\">";
		$content .= "<div><b>$titlesv</b></div>";
		for($i =0; $i < $options; $i ++) {
			$content .= "<div><input type=\"radio\" name=\"vote\" value=\"$i\"></td><td>$anwsers_ar[$i]</div>";
		}
		$content .= "<div style=\"margin-top:5px\"><input type=\"button\" value=\""._VOTE."\" onclick=\"checkVote(vote);\" class=\"sb_but1\">&nbsp;<a href=\"#\" title=\""._RESULT."\" onclick=\"openNewWindow('".url_sid("index.php?f=surveys&do=result&id=$idsv")."',300,550,1)\" style=\"position: relative; left: 2px\">"._RESULT."</a></div>";
		$content .= "</form></div></div>";
		$content .= "<div class=\"fl\">".advertising(10)."</div>";
		$content .= "<div class=\"cl\"></div>";
	}
}
?>