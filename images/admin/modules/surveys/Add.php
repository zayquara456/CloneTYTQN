<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$numqs = intval(isset($_POST['numqs']) ? $_POST['numqs'] : 0);

$stop = $title = "";

if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = nospatags($_POST['title']);
	$anwsers = $_POST['anwsers'];

	if($title == "") {
		$stop .="- "._ERROR1."<br/>";
		$err = 1;
	}

	for($i =0; $i < sizeof($anwsers); $i ++) {
		if($anwsers[$i] !="") {
			$anwsers_vl[] = $anwsers[$i];
			$votes[] = "0";
		}
	}

	if(sizeof($anwsers_vl) < 2) {
		$stop .="- "._ERROR2."<br/>";
		$err = 1;
	}
	if(!$err) {
		$anwsers_up = @implode("|",$anwsers_vl);
		$votes_up = @implode("|",$votes);
		$db->sql_query("INSERT INTO ".$prefix."_survey (id, question, votes, anwsers, options, time, alanguage) VALUES(NULL, '$title', '$votes_up', '$anwsers_up', '".sizeof($anwsers_vl)."', '".time()."', '$currentlang')");
		header("Location: modules.php?f=".$adm_modname."");
		exit;
	}
}

include("page_header.php");

if($stop) {
	echo "<table align=\"center\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td class=\"header\">"._ERROR."</td></tr>";
	echo "<tr><td class=\"row1\">$stop</td></tr>";
	echo "</table><br/>";
}

if(empty($numqs)) {
	echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
	echo "<tr><td class=\"header\" colspan=\"2\">"._SVADD."</td></tr>";
	echo "<tr>\n";
	echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._NUMQS."</b></td>\n";
	echo "<td class=\"row3\">\n";
	echo "<select name=\"numqs\">";
	for($i = 2; $i <= 20; $i ++) {
		$seld ="";
		if($i == 5) { $seld =" selected";}
		echo "<option value=\"$i\"$seld>$i</option>";
	}
	echo "</select> </td>\n";
	echo "</tr>\n";
	echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._GO."\" class=\"button2\"></td></tr>";
	echo "</table></form>";
} else {
	echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
	echo "<tr><td class=\"header\" colspan=\"2\">"._SVADD."</td></tr>";
	echo "<tr>\n";
	echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._QUESTION."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"text\" size=\"60\" name=\"title\" value=\"$title\"></td></tr>\n";
	for($i = 0; $i < $numqs; $i ++) {
		$a = $i + 1;
		if(!isset($anwsers[$i])) { $anwsers[$i] =""; }
		echo "<tr>\n";
		echo "<td class=\"row1\" align=\"right\"><b>"._ANWSER." $a:</b></td>\n";
		echo "<td class=\"row2\"><input type=\"text\" size=\"60\" name=\"anwsers[]\" value=\"$anwsers[$i]\"></td>\n";
		echo "</tr>\n";
	}
	echo "<input type=\"hidden\" name=\"numqs\" value=\"$numqs\">";
	echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
	echo "</table></form>";
}

include_once("page_footer.php");
?>