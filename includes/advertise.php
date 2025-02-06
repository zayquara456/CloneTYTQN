<?php
if ((!defined('CMS_SYSTEM')) && !defined('CMS_ADMIN')) die();
require_once(RPATH.DATAFOLD."/config_advertise.php");
function advertising($bnid) 
{
	$advc = "";
	global $db, $prefix, $path_upload, $urlsite, $currentlang, $module_name, $home, $AdTimeout;
	//$path_upload=$urlsite."/".$path_upload;
	$bnid = intval($bnid);
	$result = $db->sql_query("SELECT bwidth, bheight, abs, type FROM ".$prefix."_advertise_banners WHERE active='1' AND bnid='$bnid'");
	if($db->sql_numrows($result) > 0) {
		list($bwidth, $bheight, $absbn, $bntype) = $db->sql_fetchrow($result);
		if($bntype == 0) {
			$result_bn = $db->sql_query("SELECT id, target, images, imgtext, module FROM ".$prefix."_advertise WHERE bnid='$bnid' AND alanguage='$currentlang' AND active='1' ORDER BY weight");
		} else {
			$result_bn = $db->sql_query("SELECT id, target, images, imgtext, module FROM ".$prefix."_advertise WHERE bnid='$bnid' AND alanguage='$currentlang' AND active='1' ORDER BY rand() DESC");
		}
		if (($bntype == 1) && ($db->sql_numrows($result_bn) > 1)) {
			$idArr = array();
			$targetArr = array();
			$imagesArr = array();
			$imgtextArr = array();
			$bmodulesArr = array();
			while ($tempArr = $db->sql_fetchrow($result_bn)) list($idArr[], $targetArr[], $imagesArr[], $imgtextArr[], $bmodulesArr[]) = $tempArr;
			$advc .= "\n<script>\n";
			
			$advc .= "id = new Array(";
			for ($i = 0; $i < (count($idArr) - 1); $i++) $advc .= "{$idArr[$i]},";
			$advc .= "{$idArr[$i]})\n";
			
			$advc .= "target = new Array(";
			for ($i = 0; $i < (count($targetArr) - 1); $i++) $advc .= "{$targetArr[$i]},";
			$advc .= "{$targetArr[$i]})\n";
			
			$advc .= "links = new Array(";
			for ($i = 0; $i < (count($idArr) - 1); $i++) $advc .= "'".url_sid("click.php?id={$idArr[$i]}")."',";
			$advc .= "'".url_sid("click.php?id={$idArr[$i]}")."')\n";
			
			$advc .= "images = new Array(";
			for ($i = 0; $i < (count($imagesArr) - 1); $i++) $advc .= "'{$imagesArr[$i]}',";
			$advc .= "'{$imagesArr[$i]}')\n";
			
			$advc .= "imgtext = new Array(";
			for ($i = 0; $i < (count($imgtextArr) - 1); $i++) $advc .= "'{$imgtextArr[$i]}',";
			$advc .= "'{$imgtextArr[$i]}')\n";
			
			$advc .= "bmodules = new Array(";
			for ($i = 0; $i < (count($bmodulesArr) - 1); $i++) $advc .= "'{$bmodulesArr[$i]}',";
			$advc .= "'{$bmodulesArr[$i]}')\n";

			$advc .= "home = $home\nmodule_name = '$module_name'\npath_upload = '$path_upload'\nbwidth = $bwidth\nbheight = $bheight\nabsbn = $absbn\n</script>\n";
			
			$advc .= "<div id=\"randomBanner_{$bnid}\"></div>";
			
			$advc .= "<script>updateAd($bnid);setInterval(\"updateAd($bnid)\", $AdTimeout)</script>";
		}
		if ($db->sql_numrows($result_bn) > 0) {
			$bmod_arr = array();
			while($tempArr = $db->sql_fetchrow($result_bn)) {
				list($id, $target, $images, $imgtext, $bmodules) = $tempArr;
				$bmod_arr = @explode("|",$bmodules);
				if($bmodules == "all" || (in_array("home",$bmod_arr) && ($home == 1)) || in_array($module_name,$bmod_arr)) {
					$bnext = Common::getExt($images);
					if($target == 1) {
						$bntarget ="_blank";
					} else {
						$bntarget = "_self";
					}
					if($bnext == "swf") {
						$advc .= show_flash("FlashID_$id","$urlsite/$path_upload/adv/$images","$bwidth","$bheight");
					} else {
						$advc .= "<div align=\"center\" style=\"margin-bottom: 8px;\"><a href=\"$urlsite/click.php?id=$id\" target=\"$bntarget\" title=\"$imgtext\"><img border=\"0\" title=\"$imgtext\" alt=\"$imgtext\" src=\"$urlsite/$path_upload/adv/$images\" width=\"$bwidth\"";
						if($absbn == 1) {
							$advc .= " height=\"$bheight\"";
						}
						$advc .= "></a></div>";
					}
				}
			}
		}
	}
	return $advc;
}
?>