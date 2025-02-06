<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
$id = isset($_GET['group']) ? $_GET['group'] : $_POST['group'];
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	//begin permission menu
	$resultck = $db->sql_query("SELECT menu FROM ".$prefix."_adminmenus_permission WHERE admingroup='$id'");
		if($db->sql_numrows($resultck) > 0)
		{
			while(list($menusid)=$db->sql_fetchrow($resultck))
			{
				
				if(in_array($menusid, $catidm)!=1)
				{
					$db->sql_query("DELETE FROM ".$prefix."_adminmenus_permission WHERE admingroup='$id'");
				}
			}
		}
		for($i =0; $i < sizeof($catidm); $i ++)
		{
		$resultck = $db->sql_query("SELECT * FROM ".$prefix."_adminmenus_permission WHERE admingroup='$id' AND menu=$catidm[$i]");
			if($db->sql_numrows($resultck) == 0) {
				$db->sql_query("INSERT INTO ".$prefix."_adminmenus_permission (admingroup,menu) VALUES ($id,$catidm[$i])");
			}
		}
	//end permission menu
	//begin permission news
	$resultck = $db->sql_query("SELECT news FROM ".$prefix."_news_permission WHERE admingroup='$id'");
	if($db->sql_numrows($resultck) > 0)
	{
		while(list($newsid)=$db->sql_fetchrow($resultck))
		{
			
			if(in_array($newsid, $catidn)!=1)
			{
				$db->sql_query("DELETE FROM ".$prefix."_news_permission WHERE admingroup='$id'");
			}
		}
	}
	for($i =0; $i < sizeof($catidn); $i ++)
	{
	$resultck = $db->sql_query("SELECT * FROM ".$prefix."_news_permission WHERE admingroup='$id' AND news=$catidn[$i]");
		if($db->sql_numrows($resultck) == 0) {
			$db->sql_query("INSERT INTO ".$prefix."_news_permission (admingroup,news) VALUES ($id,$catidn[$i])");
		}
	}
	//end permission news

	//begin permission document
	$resultck = $db->sql_query("SELECT document FROM ".$prefix."_document_permission WHERE admingroup='$id'");
	if($db->sql_numrows($resultck) > 0)
	{
		while(list($documentid)=$db->sql_fetchrow($resultck))
		{
			if(in_array($documentid, $catidd)!=1)
			{
				$db->sql_query("DELETE FROM ".$prefix."_document_permission WHERE admingroup='$id'");
			}
		}
	}
	for($i =0; $i < sizeof($catidd); $i ++)
	{
		$resultck = $db->sql_query("SELECT * FROM ".$prefix."_document_permission WHERE admingroup='$id' AND document=$catidd[$i]");
		if($db->sql_numrows($resultck) == 0) {
			$db->sql_query("INSERT INTO ".$prefix."_document_permission (admingroup,document) VALUES ($id,$catidd[$i])");
		}
	}
	//end permission document
	
	//begin permission video
	$resultck = $db->sql_query("SELECT video FROM ".$prefix."_video_permission WHERE admingroup='$id'");
	if($db->sql_numrows($resultck) > 0)
	{
		while(list($videoid)=$db->sql_fetchrow($resultck))
		{
			if(in_array($videoid, $catidv)!=1)
			{
				$db->sql_query("DELETE FROM ".$prefix."_video_permission WHERE admingroup='$id'");
			}
		}
	}
	for($i =0; $i < sizeof($catidv); $i ++)
	{
		$resultck = $db->sql_query("SELECT * FROM ".$prefix."_video_permission WHERE admingroup='$id' AND video=$catidv[$i]");
		if($db->sql_numrows($resultck) == 0) {
			$db->sql_query("INSERT INTO ".$prefix."_video_permission (admingroup,video) VALUES ($id,$catidv[$i])");
		}
	}
	//end permission video
	
}

$result = $db->sql_query("SELECT * FROM ".$prefix."_admingroup WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=$adm_modname&do=group"); exit;
}
list($idgroup, $titlegroup, $permissiongroup, $activegroup) = $db->sql_fetchrow($result);
include("page_header.php");
$sql="";
	$sql="SELECT * FROM ".$prefix."_admingroup ORDER BY id DESC";
	$result = $db->sql_query($sql);
ajaxload_content();
echo "<form id=\"frm\" action=\"modules.php?f=$adm_modname&do=permission&group=$id\" method=\"POST\">";
echo "<div id=\"pagecontent\">";
echo "<div id=\"".$adm_modname."_main\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"6\" class=\"header\">Nhóm quản trị: $titlegroup</td></tr>";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row4\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"hidden\" name=\"group\" value=\"$idgroup\"><input type=\"submit\" class=\"button2\" id=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></div>\n";
echo "</div>";

?>

<ul id="countrytabs" class="shadetabs">
<li><a href="#" rel="country4" class="selected">Menu</a></li>
<li><a href="#" rel="country1" class="selected">Nội dung</a></li>
<li><a href="#" rel="country2">Tài liệu</a></li>
<li><a href="#" rel="country3">Video</a></li>
</ul>

<div style="border:1px solid gray; margin-bottom: 1em; padding: 10px">

<div id="country1" class="tabcontent">
<?php
/////////////////////////////////////////////////////////////////
$resultcat = $db->sql_query("SELECT catid, title, active, weight, counts, startid, onhome, homelinks, parent FROM {$prefix}_news_cat WHERE parent=0 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($resultcat) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheckn(){\n";
	echo "	var f=fetch_object('frm');\n";
	echo "	if(f.checkalln.checked){\n";
	echo "		CheckAllCheckbox(f,'catidn[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'catidn[]');\n";
	echo "	}\n";
	echo "}\n";
	echo "</script>\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">Chuyên mục</td></tr>";
	echo "	<tr>\n";
	echo "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkalln\" onclick=\"javascript:check_uncheckn();\" title=\""._CHECKALL."\"></td>\n";
	echo "		<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "	</tr>\n";
while(list($catid, $title, $active, $weight, $counts, $startid, $onhome, $homelinks, $parent) = $db->sql_fetchrow($resultcat)) {
echo "	<tr>\n";
		$resultcked = $db->sql_query("SELECT * FROM ".$prefix."_news_permission WHERE news=$catid AND admingroup='$id'");
		if($db->sql_numrows($resultcked) > 0) {
			$checked = "checked";
		}
		else
		{
			$checked = "";
		}
		echo "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"catidn[]\" $checked value=\"{$catid}\"></td>\n";
		echo "<td class=\"row1\"><strong>$title</strong></td>\n";
		echo "</tr>\n";
		echo childnews($catid, $id);
		
}
echo "</table>";
}
function childnews($catid,$id, $text="&nbsp;&nbsp;") {
	global $db, $prefix, $adm_modname, $scolor1, $ajax_active;
	$treeTempm ="";
	$result = $db->sql_query("SELECT catid, title, active, weight, counts, startid, onhome, homelinks, parent FROM ".$prefix."_news_cat WHERE parent='$catid' ORDER BY weight");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($catid, $title, $active, $weight, $counts, $startid, $onhome, $homelinks, $parent) = $db->sql_fetchrow($result)) {
			$resultcked = $db->sql_query("SELECT * FROM ".$prefix."_news_permission WHERE news=$catid AND admingroup='$id'");
		if($db->sql_numrows($resultcked) > 0) {
			$checked = "checked";
		}
		else
		{
			$checked = "";
		}
		$treeTempm .= "<tr>\n";
		$treeTempm .= "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" $checked name=\"catidn[]\" value=\"{$catid}\"></td>\n";
		$treeTempm .= "<td class=\"row1\">$text- $title</td>\n";
		$treeTempm .= "</tr>\n";
		$treeTempm .= childnews($catid, $id, $text);
		}
	}
	return $treeTempm;
}
?>
</div>
<div id="country3" class="tabcontent">
<?php
/////////////////////////////////////////////////////////////////
$resultcat = $db->sql_query("SELECT catid, title, active, weight, counts, startid, onhome, homelinks, parent FROM {$prefix}_video_cat WHERE alanguage='$currentlang' AND parent='0' ORDER BY weight");
if($db->sql_numrows($resultcat) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheckdv(){\n";
	echo "	var f=fetch_object('frm');\n";
	echo "	if(f.checkallv.checked){\n";
	echo "		CheckAllCheckbox(f,'catidv[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'catidv[]');\n";
	echo "	}\n";
	echo "}\n";
	echo "</script>\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">Chuyên mục</td></tr>";
	echo "	<tr>\n";
	echo "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkallv\" onclick=\"javascript:check_uncheckdv();\" title=\""._CHECKALL."\"></td>\n";
	echo "		<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "	</tr>\n";
while(list($catid, $title, $active, $weight, $counts, $startid, $onhome, $homelinks, $parent) = $db->sql_fetchrow($resultcat)) {
echo "	<tr>\n";
		$resultcked = $db->sql_query("SELECT * FROM ".$prefix."_video_permission WHERE video=$catid AND admingroup='$id'");
		if($db->sql_numrows($resultcked) > 0) {
			$checked = "checked";
		}
		else
		{
			$checked = "";
		}
		echo "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"catidv[]\" $checked value=\"{$catid}\"></td>\n";
		echo "<td class=\"row1\"><strong>$title</strong></td>\n";
		echo "</tr>\n";
		echo childvideo($catid, $id);
		
}
echo "</table>";
}
function childvideo($catid,$id, $text="&nbsp;&nbsp;") {
	global $db, $prefix, $adm_modname, $scolor1, $ajax_active;
	$treeTempm ="";
	$result = $db->sql_query("SELECT catid, title, active, weight, counts, startid, onhome, homelinks, parent FROM ".$prefix."_video_cat WHERE parent='$catid' ORDER BY weight");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($catid, $title, $active, $weight, $counts, $startid, $onhome, $homelinks, $parent) = $db->sql_fetchrow($result)) {
			$resultcked = $db->sql_query("SELECT * FROM ".$prefix."_video_permission WHERE video=$catid AND admingroup='$id'");
		if($db->sql_numrows($resultcked) > 0) {
			$checked = "checked";
		}
		else
		{
			$checked = "";
		}
		$treeTempm .= "<tr>\n";
		$treeTempm .= "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" $checked name=\"catidv[]\" value=\"{$catid}\"></td>\n";
		$treeTempm .= "<td class=\"row1\">$text- $title</td>\n";
		$treeTempm .= "</tr>\n";
		$treeTempm .= childvideo($catid, $id, $text);
		}
	}
	return $treeTempm;
}
?>
</div>
<div id="country2" class="tabcontent">
<?php
/////////////////////////////////////////////////////////////////
$resultcat = $db->sql_query("SELECT catid, title, active, weight, counts, startid, onhome, homelinks, parent FROM {$prefix}_document_cat WHERE  alanguage='$currentlang'  AND parent='0' ORDER BY weight");
if($db->sql_numrows($resultcat) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheckd(){\n";
	echo "	var f=fetch_object('frm');\n";
	echo "	if(f.checkalld.checked){\n";
	echo "		CheckAllCheckbox(f,'catidd[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'catidd[]');\n";
	echo "	}\n";
	echo "}\n";
	echo "</script>\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">Chuyên mục</td></tr>";
	echo "	<tr>\n";
	echo "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkalld\" onclick=\"javascript:check_uncheckd();\" title=\""._CHECKALL."\"></td>\n";
	echo "		<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "	</tr>\n";
while(list($catid, $title, $active, $weight, $counts, $startid, $onhome, $homelinks, $parent) = $db->sql_fetchrow($resultcat)) {
echo "	<tr>\n";
		$resultcked = $db->sql_query("SELECT * FROM ".$prefix."_document_permission WHERE document=$catid AND admingroup='$id'");
		if($db->sql_numrows($resultcked) > 0) {
			$checked = "checked";
		}
		else
		{
			$checked = "";
		}
		echo "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"catidd[]\" $checked value=\"{$catid}\"></td>\n";
		echo "<td class=\"row1\"><strong>$title</strong></td>\n";
		echo "</tr>\n";
		echo childcat($catid, $id);
		
}
echo "</table>";
}
function childcat($catid,$id, $text="&nbsp;&nbsp;") {
	global $db, $prefix, $adm_modname, $scolor1, $ajax_active;
	$treeTempm ="";
	$result = $db->sql_query("SELECT catid, title, active, weight, counts, startid, onhome, homelinks, parent FROM ".$prefix."_document_cat WHERE parent='$catid' ORDER BY weight");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($catid, $title, $active, $weight, $counts, $startid, $onhome, $homelinks, $parent) = $db->sql_fetchrow($result)) {
			$resultcked = $db->sql_query("SELECT * FROM ".$prefix."_document_permission WHERE document=$catid AND admingroup='$id'");
		if($db->sql_numrows($resultcked) > 0) {
			$checked = "checked";
		}
		else
		{
			$checked = "";
		}
		$treeTempm .= "<tr>\n";
		$treeTempm .= "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" $checked name=\"catidd[]\" value=\"{$catid}\"></td>\n";
		$treeTempm .= "<td class=\"row1\">$text- $title</td>\n";
		$treeTempm .= "</tr>\n";
		$treeTempm .= childcat($catid, $id, $text);
		}
	}
	return $treeTempm;
}
?>
</div>

<div id="country4" class="tabcontent">
<?php
/////////////////////////////////////////////////////////////////
$resultcat = $db->sql_query("SELECT mid, title, active, weight, url, target FROM {$prefix}_adminmenus WHERE alanguage='$currentlang' AND parentid='0' ORDER BY weight");
if($db->sql_numrows($resultcat) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheckm(){\n";
	echo "	var f=fetch_object('frm');\n";
	echo "	if(f.checkallm.checked){\n";
	echo "		CheckAllCheckbox(f,'catidm[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'catidm[]');\n";
	echo "	}\n";
	echo "}\n";
	echo "</script>\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">Menu Admin</td></tr>";
	echo "	<tr>\n";
	echo "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkallm\" onclick=\"javascript:check_uncheckm();\" title=\""._CHECKALL."\"></td>\n";
	echo "		<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "	</tr>\n";
while(list($menusid, $title, $active, $weight, $url, $target) = $db->sql_fetchrow($resultcat)) {
echo "<tr>\n";
		$resultcked = $db->sql_query("SELECT * FROM ".$prefix."_adminmenus_permission WHERE menu=$menusid  AND admingroup='$id'");
		if($db->sql_numrows($resultcked) > 0) {
			$checked = "checked";
		}
		else
		{
			$checked = "";
		}
		echo "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"catidm[]\" $checked value=\"{$menusid}\"></td>\n";
		echo "<td class=\"row1\"><strong>$title</strong></td>\n";
		echo "</tr>\n";
		echo childmenu($menusid, $id);
}
echo "</table>";
}
function childmenu($menusid, $id, $text="&nbsp;&nbsp;") {
	global $db, $prefix, $adm_modname, $scolor1, $ajax_active;
	$treeTempm ="";
	$result = $db->sql_query("SELECT mid, title, active, weight, url, target FROM ".$prefix."_adminmenus WHERE parentid='$menusid' ORDER BY weight");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($mid, $title, $active, $weight, $url, $target) = $db->sql_fetchrow($result)) {
			$resultcked = $db->sql_query("SELECT * FROM ".$prefix."_adminmenus_permission WHERE menu='$mid' AND admingroup='$id'");
			
		if($db->sql_numrows($resultcked) > 0) {
			$checked = "checked";
		}
		else
		{
			$checked = "";
		}
		$treeTempm .= "<tr>\n";
		$treeTempm .= "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" $checked name=\"catidm[]\" value=\"{$mid}\"></td>\n";
		$treeTempm .= "		<td class=\"row1\">$text- $title</td>\n";
		$treeTempm .= "</tr>\n";
		$treeTempm .= childmenu($mid, $id, $text);
		}
	}
	return $treeTempm;
}
?>
</div>

</div>

<script type="text/javascript">

var countries=new ddtabcontent("countrytabs")
countries.setpersist(true)
countries.setselectedClassTarget("link") //"link" or "linkparent"
countries.init()

</script>
<?php
echo "</form>";
include_once("page_footer.php");
?>