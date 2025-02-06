<?php
if (!defined('CMS_SYSTEM')) die();

$where="";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$t = isset($_GET['t']) ? $_GET['t'] : "";
$c = isset($_GET['c']) ? $_GET['c'] : "";
if($id!=0)
	$where.="n.id=$id AND ";
if($t!="")
	$where.="n.permalink='$t' AND ";
if($c!="")
	$where.="c.permalink='$c' AND ";

$qid=$id;
$result = $db->sql_query("SELECT n.id, n.catid, c.title, c.parent FROM ".$prefix."_question AS n,".$prefix."_question_cat AS c WHERE $where n.catid=c.catid AND n.alanguage='$currentlang'");
if($db->sql_numrows($result) != 1) {
	$rwtitlecat = utf8_to_ascii(url_optimization($titlecat));
	//header("Location: ".url_sid("index.php?f=$module_name")."");	
}
list($id,$catid,$catname,$parent) = $db->sql_fetchrow($result);
if($parent != 0) {
	$title_cat = page_tilecat($catid, $parent, $catname);
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; ".$title_cat."";
} else {
	$catname2 = "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" >$catname</a>";
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; ".$catname2."";
}
$sitelinkmap=$title_home;
include_once("header.php");
OpenTab("");
echo "<div class=\"content\">";

$perpage = 10;

$db->sql_query("UPDATE ".$prefix."_question SET hits=hits+1 WHERE id=$id"); 

$query = "SELECT COUNT(*) FROM {$prefix}_question WHERE alanguage='$currentlang'";
$result = $db->sql_query($query);
list($total) = $db->sql_fetchrow($result);

$query = "SELECT id, title, content, time, name, email,hits FROM ".$prefix."_question WHERE active='1' AND id = $id ";
$resultn = $db->sql_query($query);
if($db->sql_numrows($resultn) > 0) {
	list($id, $title, $content, $time, $name, $email,$hits) = $db->sql_fetchrow($resultn);
		$rwtitle = utf8_to_ascii(url_optimization($title));
		$url_detail =url_sid("index.php?f=question&do=detail&id=$id&t=$rwtitle");
		echo "<h1 class=\"posttitle\">$title</h1>";
		echo "<div class=\"question-answer\" style=\"padding-top:0px;\">";
		echo "<div class=\"qname fl\">"._NGUOI_GUI."<b>$name</b> "._THOI_GIAN." ".ext_time($time,2)."</div>";	
		echo "<div class=\"qname fl\">&nbsp;|&nbsp;"._LUOT_XEM."($hits)</div>";
		echo "<div class=\"cl\"><b>"._NOI_DUNG."</b></div>";
		echo "<div align=\"justify\">$content</div>";
		echo "</div>";
		//echo "<div class=\"qrow\"></div>";


///================== DS tra loi		
echo "<div class=\"box-border\" style=\"margin-top:10px;\">";

$resul_answer = $db->sql_query("SELECT id, content, time, name, email FROM ".$prefix."_answer WHERE active=1 AND qid=$id  ORDER BY time DESC ");
if($db->sql_numrows($resul_answer) > 0) 
{
	$query = "SELECT COUNT(*) FROM {$prefix}_answer WHERE active=1 AND qid=$qid ";
	$result = $db->sql_query($query);
	list($total) = $db->sql_fetchrow($result);

	while(list($idanswer,$contentanswer, $timeanswer, $nameanswer, $emailanswer) = $db->sql_fetchrow($resul_answer)) 
	{		
		echo "<h1 class=\"posttitle\">"._CONTENT_ANSWER.":</h1>";
		echo "<div class=\"qname cl\" style=\"margin-top:5px;\">"._NGUOI_GUI."<b>$nameanswer</b> "._THOI_GIAN."".ext_time($timeanswer,2)."</div>";
				
		echo "<div class=\"cl\" ><b>"._NOI_DUNG."</b></div>";
		echo "<div align=\"justify\">$contentanswer</div>";
		echo "<div class=\"qrow\"></div>";
	
	}
echo "</div>";		
$new_others = "";
$result_others = $db->sql_query("SELECT id, title, time FROM ".$prefix."_question WHERE id<'$id' AND catid='$catid' ORDER BY time DESC LIMIT 8");
if($db->sql_numrows($result_others) > 0) {
	while(list($idot, $titleot, $timeot) = $db->sql_fetchrow($result_others)) {
		$new_others .= "&bull; <a href=\"".url_sid("index.php?f=$module_name&do=detail&id=$idot")."\" class=\"hometext\">$titleot</a><br/>";// <span class=\"newsothers\">(".ext_time($timeot,1).")</span>
	}
}		
$new_others2 = "";
$result_others2 = $db->sql_query("SELECT id, title, time FROM ".$prefix."_question WHERE id>'$id' AND catid='$catid' ORDER BY time ASC LIMIT 8");
if($db->sql_numrows($result_others2) > 0) {
	while(list($idot2, $titleot2, $timeot2) = $db->sql_fetchrow($result_others2)) {
		$new_others2 .= "&raquo; <a href=\"".url_sid("index.php?f=$module_name&do=detail&id=$idot2")."\" class=\"hometext\">$titleot2</a> <span class=\"newsothers\"></span><br/>";//(".ext_time($timeot2,1).")
	}
} 			
?>
<div class="cl"></div>
<?php if($new_others2) {?>
			<div class="line-other"><div class="title-other"><?php echo _OTHERNEW1 ?>:</div></div><div class="title-other-list" >
			<?php echo $new_others2 ?></div>
		<?php }
		if($new_others) {?>
			<div class="line-other"><div class="title-other"><?php echo _OTHERNEW ?>:</div></div><div class="title-other-list" >
			<?php echo $new_others ?></div>
		<?php }?>

<?php	
//====================
$active = 1;
$contentanswer = $emailanswer = $nameanswer = $err_name =  $err_email =  $err_content = '';		
if( isset($_POST['subup']) && $_POST['subup'] == 1) 
{
	$err = 0;
	$nameanswer = ($_POST['nameanswer']);
	$active = 1;
	$contentanswer = $escape_mysql_string(trim($_POST['contentanswer']));
	$emailanswer = ($_POST['emailanswer']);

	
	if (empty($nameanswer)) 
	{
		$err_name = "<font color=\"red\">"._ERROR_NAME."</font>";
		$err = 1;
	}	
	if (empty($nameanswer)) 
	{
		$err_content = "<font color=\"red\">"._ERROR_CONTENT."</font>";
		$err = 1;
	}
	
	if (!$err) 
	{
		$insertIntoTable = "{$prefix}_answer";
		$query = "INSERT INTO $insertIntoTable (id, qid, name, alanguage, content, email, active,time) VALUES (NULL, $id, '$nameanswer', '$currentlang', '$contentanswer', '$emailanswer', $active,".time().")";
		$result = $db->sql_query($query);
		
		//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CAMNANG_CREATE_CAMNANG);
		
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('"._THANKS_ANSWER."');";
		echo "window.location.href=\"index.php?f=".$module_name."&do=detail&id=$qid\"";
		echo "</script>";
	}
}	
}

}

//echo "</div>";

echo "<div class=\"add-question\">";	
	echo "<span class=\"add\"><a href=\"$urlsite/index.php?f=question&do=create\">"._ADD_QUESTION."</a></span>";
echo "</div>";
//echo "</div>";
echo "</div>";
CloseTab();
include_once("footer.php");

?>