<?php
if (!defined('CMS_SYSTEM')) { die(); }

$id = intval($_GET['id']);
$result = $db->sql_query("SELECT title, time, hometext, bodytext, images, imgtext, source FROM ".$prefix."_camnang WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	die();
}

require("templates/$Default_Temp/index.php");

list($title, $time, $hometext, $bodytext, $images, $imgtext, $source) = $db->sql_fetchrow($result);
$auts  ="";
if($source !="") {
	if($source !="") {
		$auts .= "(<i>$source</i>)";
	}
}

$get_path = get_path($time);
$path_upload_img = "$path_upload/camnang/$get_path";

if($images !="") {
	$images1 = $images;
	if($imgtext !="") {
		$imgtext = "<tr><td height=\"20\">$imgtext</td></tr>";
	}
		
	if(file_exists("$path_upload_img/thumb_".$images."")) {
		$images = "thumb_".$images."";
	}
	
	$sizepic = @getimagesize("$path_upload_img/$images1");
	if($sizepic[0] > $sizecamnang) {
		$sizetb = $sizecamnang+4;
		$artpic = "<table border=\"0\" align=\"$pic_align\" width=\"$sizetb\" cellspacing=\"0\" style=\"border-collapse: collapse\">
	<tr>
		<td><a href=\"javascript:void(0)\" onClick=\"openNewWindow('".url_sid("index.php?f=".$module_name."&do=viewpic&id=$id")."',$sizepic[1],$sizepic[0])\" title=\""._VIEWIMG."\">
<img border=\"0\" src=\"$path_upload_img/$images\" width=\"$sizecamnang\"></a></td>
	</tr>$imgtext
</table>";
}else{
	$artpic = "<table border=\"0\" align=\"$pic_align\" cellspacing=\"0\" style=\"border-collapse: collapse\">
	<tr>
		<td class=\"loadbox\"><img border=\"0\" src=\"$path_upload_img/$images\"></td>
	</tr>$imgtext
</table>";
}
}else{
	$artpic ="";
}
echo "<html>";
echo "<head>";
echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\">";
echo "<title>$sitename - "._PRINT."</title>";
echo "<link rel=\"StyleSheet\" href=\"templates/".$Default_Temp."/css/styles.css\" type=\"text/css\">";
echo "<script language=\"javascript\" src=\"js/common.js\"></script>";
echo "</head>";
echo "<body style=\"margin: 0px\" bgcolor=\"#EDEDED\">";
OpenBox();
echo "<script language=\"JavaScript\">";
echo "<!-- Begin";
echo "function varitext(text){";
echo "text=document";
echo "print(text)";
echo "}";
echo "//  End -->";
echo "</script>";
echo "<div style=\"padding: 10px\" class=\"clearfix\">";
echo "<table align=\"center\" border=\"0\" width=\"600\" cellpadding=\"0\" style=\"border-collapse: collapse\">";
echo "<tr><td>";
echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\" height=\"22\">";
echo "<tr>";
echo "<td class=\"time\" height=\"24\">".NameDay($time).", ".ext_time($time,3)." GMT+7</td><td align=\"right\"><input type=\"button\" onclick=\"varitext()\" value=\""._PRINT."\" class=\"input1\"> <input class=\"input1\" type=\"button\" onclick=\"window.close()\" value=\""._CLOSEWIN."\"></td>";
echo "</tr>";
echo "</table>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td><h4>$title</h4></td>";
echo "</tr>";
echo "<tr>";
echo "<td height=\"3\"></td>";
echo "</tr>";
echo "<tr>";
echo "<td>$artpic <div align=\"justify\"><font class=\"hometext\">$hometext</font><br><font class=\"bodytext\">$bodytext</font></div></td>";
echo "</tr>";
echo "<tr>";
echo "<td height=\"10\"></td>";
echo "</tr>";
echo "<tr>";
echo "<td align=\"right\" class=\"bodytext\">$auts</td>";
echo "</tr>";
echo "<tr><td height=\"8\"></td></tr>";
echo "<tr><td height=\"2\" bgcolor=\"#DC0312\"></td></tr>";
echo "<tr><td height=\"4\"></td></tr>";
echo "<tr><td height=\"22\" align=\"center\">"._CAMNANGURL.": $siteurl/".url_sid("index.php?f=".$module_name."&do=detail&id=$id")."</td></tr>";
echo "<tr><td height=\"2\" bgcolor=\"#C0C0C0\"></td></tr>";
echo "<tr><td height=\"22\">";
echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\" height=\"22\">";
echo "<tr>";
echo "<td height=\"22\"><font class=\"bodytext\">&copy; $sitename</font></td><td align=\"right\"><font class=\"bodytext\">Email: $adminmail</font></td>";
echo "</tr>";
echo "</table>";
echo "</td></tr>";
echo "</table>";
echo "</div>";
CloseBox();
echo "</body></html>";

?>