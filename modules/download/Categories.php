<?php
if (!defined('CMS_SYSTEM')) die();
global $Default_Temp, $urlsite;
//$catid = intval($_GET['id']);

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
$result = $db->sql_query("SELECT catid, title, parent FROM ".$prefix."_download_cat WHERE $where alanguage='$currentlang'");
if($db->sql_numrows($result) != 1) header("Location: index.php");
list($catid, $catname, $parent) = $db->sql_fetchrow($result);
$result = $db->sql_query("SELECT catid, parent FROM {$prefix}_download_cat WHERE $where alanguage='$currentlang' ORDER BY weight, catid ASC");
if ($db->sql_numrows($result) > 0) {
	$i = 0;
	$tempArr = array();
	while ($rows = $db->sql_fetchrow($result)) {
		list($tempArr[$i]['id'], $tempArr[$i]['parent']) = $rows;
		$i++;
	}
}
$newArr = array();
Common::buildTree($tempArr, $newArr);
$searchArray = Common::recursiveArrayKeyExists($catid, $newArr);
if ($searchArray === false) {
	//header("Location: index.php");
}
$kList = '';
if (is_array($searchArray[$catid])) Common::findAllKeys($searchArray[$catid], $kList);
else $kList = strval($catid);
if (substr($kList, -1) == ':') $kList = substr($kList, 0, strlen($kList) - 1);
$kList = explode(':', $kList);

$page_title = $catname;
if($page!=0)
	$page_title.= " - "._PAGE." ".$page;
//keywords
if($catname!="")
	$keywords_site =$catname.", ".utf8_to_ascii($catname);
else
	$header_page_keyword = $hometext;
//description
$description_site = $catname." ".$description_site;
if($page!=0)
	$description_site.= " - "._PAGE." ".$page;

if($parent != 0) {
	$title_cat = page_tilecat($catid, $parent, $catname);
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo;  ".$title_cat."";
} else {
	$catname2 = "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" >$catname</a>";
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; ".$catname2."";
}
$sitelinkmap=$title_home;
include_once("header.php");
OpenTab($title_home);
$result_catindex = $db->sql_query("SELECT catid, title, parent, homelinks FROM {$prefix}_download_cat WHERE parent=$catid AND active=1 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($result_catindex) > 0) 
{
	$perpage=4;
}
else
{
	$perpage=10;
}
// hien thi noi dung danh sach cac bai viet theo chuyen
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;
$query = "SELECT COUNT(*) FROM {$prefix}_download WHERE alanguage='$currentlang' AND (";
$query .= "catid=$catid OR ";
for ($i = 0; $i < count($kList); $i++) $query .= "catid={$kList[$i]} OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ')';
$result = $db->sql_query($query);
list($total) = $db->sql_fetchrow($result);

$query = "SELECT id, title, hometext, time, fattach FROM ".$prefix."_download WHERE alanguage='$currentlang' AND (";
$query .= "catid=$catid OR ";
for ($i = 0; $i < count($kList); $i++) $query .= "catid={$kList[$i]} OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ") ORDER BY time DESC LIMIT $offset, $perpage";
//die($query);
$resultn = $db->sql_query($query);
if($db->sql_numrows($resultn) > 0) {
	OpenContent($catname);
	while(list($id, $title, $hometext, $time, $fattach) = $db->sql_fetchrow($resultn)) {
		$url_download_detail =url_sid("index.php?f=download&do=detail&id=$id");
		$hometext = strip_tags($hometext, '<a><b><u><i><strong><span>');
		$fattach = "$urlsite/$path_upload/download/attachs/$fattach";
		temp_download_index($id, $title, $hometext, $url_download_detail, $fattach);
	}
	if($total > $perpage) {
		$pageurl = "index.php?f=$module_name&do=categories&id=$catid";
		echo paging($total,$pageurl,$perpage,$page);
	}
	CloseContent();
	echo "<br/>";
}

			
//phan hien thi danh sach chuyen muc con cho chuyen muc cap 1 
$result_catindex = $db->sql_query("SELECT catid, title, parent, homelinks FROM {$prefix}_download_cat WHERE parent=$catid AND active=1 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($result_catindex) > 0) 
{
		//OpenContent($catname);
		$i=0;
		while(list($catidcat, $titlecat, $parentcat, $homelinks) = $db->sql_fetchrow($result_catindex)) 
		{
			$url_download_cat =url_sid("index.php?f=download&do=categories&id=$catidcat");
			$margin_left="";
			echo"<div class=\"boxca\">\n";
			echo "<div class=\"boxca-title\"><h2><a href=\"$url_download_cat\">$titlecat</a></h2></div>\n";
			$result_downloadindex = $db->sql_query("SELECT id, title, hometext, images, time FROM {$prefix}_download WHERE active=1 AND ( catid=$catidcat or catid in(SELECT catid FROM {$prefix}_download_cat WHERE parent=$catidcat)) ORDER BY time DESC LIMIT 5");
			$numrows = $db->sql_numrows($result_downloadindex);
			echo "<div style=\"padding:10px\">";
			if($db->sql_numrows($result_downloadindex) > 0) 
			{
				while(list($idnewind, $titlenewind, $hometextind, $imagesind, $timenewind) = $db->sql_fetchrow($result_downloadindex)) 
				{
					
					$url_download_detail =url_sid("index.php?f=download&do=detail&id=$idnewind");
					$hometextind = strip_tags($hometextind,"<a><u><i><b><strong><em>");
					$path_upload_imgnewind = "$path_upload/download";
					?>
					<div class="box-home-more"><ul>
						<li><a href="<?php echo $url_download_detail?>"><?php echo $titlenewind?></a></li>
					</ul></div>
					<?php
					$i++;
				}
				$i=0;
				echo "</div>";
			}
			echo "</div>";
		}
		//CloseContent();
		
}
CloseTab();
include_once("footer.php");

?>