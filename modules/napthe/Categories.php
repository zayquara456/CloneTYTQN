<?php
if (!defined('CMS_SYSTEM')) die();
$where=$idbuc="";
$catid = isset($_GET['id']) ? intval($_GET['id']) : 0;
$t = isset($_GET['t']) ? $_GET['t'] : "";
$n = isset($_GET['n']) ? $_GET['n'] : "";
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$t=trim($t);
if($catid!=0)
	$where.="catid=$catid AND ";
if($n!=0)
	$where.="catid=$n AND ";
if($t!="")
	$where.="permalink='$t' AND ";
$resul_cat_1 = $db->sql_query("SELECT catid, title, parent FROM  ".$prefix."_question_cat   WHERE  $where  alanguage='$currentlang'");
list($catid,$catname,$parent) = $db->sql_fetchrow($resul_cat_1);
if($parent != 0) {
	$title_cat = page_tilecat($catid, $parent, $catname);
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; <a href=\"".url_sid("index.php?f=question")."\">"._HOI_DAP."</a> &rsaquo;  ".$title_cat."";
} else {
	$catname2 = "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" >$catname</a>";
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; <a href=\"".url_sid("index.php?f=question")."\">"._HOI_DAP."</a> &rsaquo; ".$catname2."";
}
$sitelinkmap=$title_home;

include_once("header.php");
OpenTab($title_home);
echo "<div class=\"content\"><h1 class=\"posttitle\">$catname</h1>";

$perpage = 10;

$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;

$query = "SELECT COUNT(*) FROM {$prefix}_question WHERE alanguage='$currentlang'";
$result = $db->sql_query($query);
list($total) = $db->sql_fetchrow($result);
$query = "SELECT id, title, content, time, name, email,hits FROM ".$prefix."_question WHERE active=1 AND catid= $catid AND alanguage='$currentlang' ORDER BY time DESC LIMIT $offset, $perpage";
$resultn = $db->sql_query($query);

if($db->sql_numrows($resultn) > 0) {
	while(list($id, $title, $content, $time, $name, $email,$hits) = $db->sql_fetchrow($resultn)) {
		$rwtitle = utf8_to_ascii(url_optimization($title));
		$url_detail =url_sid("index.php?f=question&do=detail&id=$id");
		
		//echo "<div class=\"qtitle\"><a href=\"".url_sid("$url_detail")."\">$title</a></div>";
		echo "<div class=\"boxde\">";
		$resul_cat = $db->sql_query("SELECT c.catid, c.title FROM ".$prefix."_question AS q INNER JOIN ".$prefix."_question_cat AS c ON c.catid = q.catid  WHERE  q.id = $id");
		list($catid, $titlecat) = $db->sql_fetchrow($resul_cat);
		$rwtitlecat = utf8_to_ascii(url_optimization($titlecat));
		$result_total = $db->sql_query("SELECT COUNT(*) FROM {$prefix}_answer WHERE qid=$id AND active=1");
		list($total) = $db->sql_fetchrow($result_total);		
		echo "<div class=\"boxde-img\"><a href=\"$url_detail\"><img title=\"$title\" alt=\"$title\" src=\"$urlsite/images/logo.gif\"/></a></div>";
		echo "<div class=\"boxde-title\"><a href=\"$url_detail\">$title</a></div>";
		//echo "<div class=\"qname fl\">"._THOI_GIAN."".ext_time($time,2)."</div>";
		echo "<div class=\"boxde-content\">".CutString($content,300)."</div>";

		//echo "<div class=\"qrow\"></div>";
		echo "</div>";
	}
	
	if($total > $perpage) {
		echo "<div>";
		$pageurl = "index.php?f=$module_name";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</div>";
	}
}
echo "</div>";

CloseTab();
?>
<style>

#advfixedfloat { /* required to avoid jumping */
  width:210px;
  float:left;
  margin-left:10px;
}

#advright {

  top: 0;
  margin-top: 0px;
}


#advright ol li {
  border-top: 1px solid purple;
}

#advright ol li:first-child {
  border-top: 0;
}

#advright.fixed {
  position: fixed;
  top: 0;
}
</style>
<style type="text/css">
.tabbox{width:455px; float:left}
</style>
<script>
$(function () {
  
  var msie6 = $.browser == 'msie' && $.browser.version < 7;
  
  if (!msie6) {
    var top = $('#advright').offset().top - parseFloat($('#advright').css('margin-top').replace(/auto/, 0));
    $(window).scroll(function (event) {
      // what the y position of the scroll is
      var y = $(this).scrollTop();
      
      // whether that's below the form
      if (y >= top) {
        // if so, ad the fixed class
        $('#advright').addClass('fixed');
      } else {
        // otherwise remove it
        $('#advright').removeClass('fixed');
      }
    });
  }  
});
</script>
<div id="advfixedfloat">
      <div id="advright">
<?php
echo "<div class=\"adv-right\">";
echo advertising(10);
echo "</div>      </div>
    </div>";

include_once("footer.php");

?>