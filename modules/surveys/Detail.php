<?php
if (!defined('CMS_SYSTEM')) {
    die();
}

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT question, votes, anwsers, options, totalvotes FROM ".$prefix."_survey WHERE id='$id' AND active='1' AND alanguage='$currentlang'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: ".url_sid("index.php?f=$module_name"));
	exit;
}	

$db->sql_query("UPDATE ".$prefix."_survey SET hits=hits+1 WHERE id=$id");

list($title, $votes, $anwsers, $options, $totalvotes) = $db->sql_fetchrow($result);
$anwsers_ar = @explode("|",$anwsers);
$votes_ar = @explode("|",$votes);

include("header.php");
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
echo "			openNewWindow('".url_sid("index.php?f=surveys&do=result&id=".$id."&vote='+poll,300,550,1").");";
echo "		}			";
echo "	}	";
echo "</script>	";
echo "<div class=\"\" style=\"margin-bottom: 5px; background-color: #F0F0F0; padding: 5px\"><a href=\"".url_sid("surveys.php")."\"><b>"._MODTITLE."</b></a></div>";
echo "<img border=\"0\" src=\"images/survey.gif\">&nbsp;<span class=\"titlearl\"><b>$title</b></span>";
echo "<br/>";
OpenTable();
echo "<form method=\"POST\" action=\"".url_sid("index.php?f=$module_name&do=result&id=$id")."\"><table align=\"center\" border=\"0\" cellpadding=\"3\" style=\"border-collapse: collapse\">";
for($i =0; $i < $options; $i ++) {
	echo "<tr><td><input type=\"radio\" name=\"vote\" value=\"$i\"></td><td>$anwsers_ar[$i]</td></tr>";
}
echo "<tr><td colspan=\"2\"><input type=\"button\" id=\"submit\" value=\""._VOTE."\" onclick=\"checkVote(vote);\" class=\"sb_but\"> <input type=\"button\" onclick=\"openNewWindow('".url_sid("index.php?f=$module_name&do=result&id=$id")."',300,500,1)\" value=\""._RESULT."\" class=\"sb_but\"></td></tr>";	
echo "</table></form>";
CloseTable();

include("footer.php");

?>