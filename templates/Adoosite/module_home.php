<?php
if (!defined('CMS_SYSTEM')) die();

function temp_news_cat_index($catid, $titlecat, $idnewind, $titlenewind, $hometextind, $news_pic_index, $othersnewindex, $url_news_detail) {
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">";
	echo "<tr>";
	echo "<td>";
	echo "<div style=\"margin-bottom: 3px\" class=\"content\">$news_pic_index<a href=\"$url_news_detail\" class=\"titlecat\"><h3>$titlenewind</h3></a></div>";
	echo "<div align=\"justify\" class=\"content\">$hometextind</div>";
	echo "<div class=\"viewmore\" style=\"margin-top: 6px\"><a  class=\"ui-state-default ui-corner-all\" id=\"dialog_link\" href=\"$url_news_detail\"><span class=\"ui-icon ui-icon-newwin\"></span>"._READMORE. "</a></div>";
	echo "</td>";
	echo "</tr>";
	if($othersnewindex) {
		echo "<tr><td style=\"padding-left: 8px; padding-top: 10px;\">$othersnewindex</td></tr>";
	}
	echo "</table>";
	
}
function temp_news_other_index($idother,$url_news_other,$titleother)
{
	$str="";
	 $str="<div style=\"margin-bottom: 2px\"><span style=\"padding-right: 8px\"><img border=\"0\" src=\"images/bullet.gif\"  alt=\"bullet\"/></span><a href=\"$url_news_other\">$titleother</a></div>";
	 return $str;
}

?>

<?php
function temp_news_loop_cat_index($catid, $titlecat, $idnewind, $titlenewind, $hometextind, $news_pic_index, $othersnewindex, $url_news_detail, $url_news_cat) 
{ OpenTab("<a href=\"$url_news_cat\">".$titlecat."</a>"); ?>

	<div class="content">
		<img src="<?php echo $news_pic_index ?>" title="<?php $titlenewind?>"/>
		<a href="<?php echo $url_news_detail ?>" class="titlecat">
			<h2><?php echo $titlenewind ?></h2>
		</a>
		<?php echo $hometextind ?>
	</div>
	<div class="cl"></div>
	<div class="viewmore">
		<a  class="ui-state-default ui-corner-all" href="<?php echo $url_news_detail ?>">
			<span class="ui-icon ui-icon-newwin"></span><?php _READMORE ?>
		</a>
	</div>
	<?php if($othersnewindex) {
		echo $othersnewindex;
	} ?>
	
<?php CloseTab();}?>



<?php
function temp_newdetail($id, $title, $time, $hometext, $bodytext, $fattach, $othershow, $images, $others, $others2, $source) {
	global $module_name, $adm_mods_ar, $admin_fold, $url,$urlsite;

	echo "<table border=\"0\"><tr><td>";
	echo "<div class=\"content\"><h4 class=\"title\">$title</h4></div>";
	echo "<div class=\"Author\" style=\"margin-bottom: 14px\">".NameDay($time).", ".ext_time($time,2)."</div>";
	echo "<div>$images <div align=\"justify\"><div style=\"margin-bottom: 5px\"><b>$hometext</b></div><span class=\"content\">$bodytext</span></div></div>";
	if($fattach !="") {
		echo "<div class=\"clearfix\" style=\"padding: 4px; margin-bottom:0px; padding-left: 20px; border-bottom:1px solid #cccccc\">";
		echo "<b>"._FILE_ATTACH.":</b> <img border=\"0\" src=\"images/file.gif\" align=\"absmiddle\">&nbsp;<a href=\"".url_sid("$url")."\" style=\"font: bold 12px arial; color: #007dba; text-decoration: underline\">$fattach</a> (".ext_time($time,2).")</div>";
		echo "</div>";
	}
	if($source !="") {
		echo "<div><div align=\"right\" style=\"margin-top: 20px\"><i><b>$source</i></b></div>";
	}
	if(defined('iS_SADMIN') || defined('iS_RADMIN') || (defined('iS_ADMIN') && in_array($module_name,$adm_mods_ar))) {
		echo "<div align=\"right\" style=\"margin-top: 3px\">[<a href=\"".$admin_fold."/modules.php?f=news&do=edit_news&id=$id\" target=\"mainFrame\">"._EDIT."</a> | <a href=\"".$admin_fold."/modules.php?f=news&do=delete_news&id=$id\" target=\"mainFrame\" onclick=\"return confirm('"._DELETEASK."');\">"._DELETE."</a>]</div>";
	}
	echo "</td></tr></table>";
	echo "<p><span style=\"float:right\"><a href=\"javascript:history.go(-1);\">[<b>"._BACK."</b>]</a> <a href=\"#\">[<b>"._TOP."</b>]</a></span><a href=\"".url_sid("index.php?f=news&do=print&id=".$id."")."\" target=\"_blank\">";
	echo "<img border=\"0\" src=\"$urlsite/images/print.gif\" width=\"32\" height=\"18\" title=\""._PRINT."\"></a> <a href=\"javascript:void(0)\" onclick=\"openNewWindow('".url_sid("index.php?f=news&do=email&id=".$id."")."',220,450)\">";
	echo "<img border=\"0\" src=\"$urlsite/images/email.gif\" width=\"30\" height=\"18\" title=\""._SENDFRIEND."\"></a></p>";
	if($othershow != 1)
	{
		if($others2) {
			echo "<p><b>"._OTHERNEW1.":</b><br/>";
			echo "$others2</p>";
		}
		if($others) {
			echo "<p><b>"._OTHERNEW.":</b><br/>";
			echo "$others</p>";
		}
	}
}

function temp_newcat_start($id, $title, $hometext, $images) {
	echo "<table style=\"margin-bottom: 8px; padding: 5px\"><tr><td>";
	echo "<div style=\"margin-bottom: 4px\">$images ";
	echo "<a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\"><h4 class=\"title2\">$title</h4></a></div>";
	echo "<div align=\"justify\">$hometext</div>";
	echo "<div class=\"viewmore\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\" class=\"strong\">&raquo; "._READMORE."...</a></div>";
	echo "</td></tr></table>";
}

function temp_news_index($id, $title, $hometext, $newspic) {
	echo "<table><tr><td>";
	echo "<div style=\"margin-bottom: 10px; padding-top: 10px\">";
	echo "<div align=\"justify\">$newspic <a href=\"".url_sid("index.php?f=news&do=detail&id=".$id."")."\"><h4 class=\"title2\">$title</h4></a></div><span>$hometext</span></div>";
	echo "<div class=\"viewmore\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\" class=\"strong\">&raquo; "._READMORE."...</a></div>";
	echo "</td></tr></table>";
}
?>