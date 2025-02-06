<?php
if (!defined('CMS_SYSTEM')) die();
global $Default_Temp, $urlsite;
$path_upload_cat = "$path_upload/document_cat";
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
$result = $db->sql_query("SELECT catid, title, seo_title, seo_description, seo_keyword, images, startid, parent FROM ".$prefix."_document_cat WHERE $where alanguage='$currentlang'");
//die("SELECT catid, title, seo_title, seo_description, seo_keyword, images, startid, parent FROM ".$prefix."_document_cat WHERE $where alanguage='$currentlang'");
if($db->sql_numrows($result) != 1) header("Location: index.php");
list($catid, $catname, $title_seo, $description_seo, $keyword_seo, $images_cat, $startid, $parent) = $db->sql_fetchrow($result);
$result = $db->sql_query("SELECT catid, parent FROM {$prefix}_document_cat WHERE $where alanguage='$currentlang' ORDER BY weight, catid ASC");
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
if($title_seo=="")
	$page_title = $catname;
else
	$page_title = $title_seo;
if($page!=0)
	$page_title.= " - "._PAGE." ".$page;
//keywords
if($keyword_seo=="")
	$keywords_site =$catname.", ".utf8_to_ascii($catname);
else
	$keywords_site = $keywords_seo;
//description
if($description_seo=="")
	$description_site = $catname." ".$description_site;
else
	$description_site = $description_seo;

if($page!=0)
	$description_site.= " - "._PAGE." ".$page;
//images
if($images_cat!="")
	$siteimage = $urlsite."/".$path_upload_cat."/".$images_cat;

if($parent != 0) {
	$title_cat = page_tilecat($catid, $parent, $catname);
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo;  ".$title_cat."";
} else {
	$catname2 = "<a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" >$catname</a>";
	$title_home = "<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &rsaquo; ".$catname2."";
}
$perpage=25;
include_once("header.php");
if($catid==21 || $t=='do-an-quy-hoach')
{

//OpenTab($title_home);
//CloseTab();
$w_array=array(280, 280, 390, 191, 191);
$h_array=array(200, 152, 160, 192, 192);
?>
<div class="ui-title"><?php echo $catname;?></div>
<div class="hotdocument">
	<div class="hotdocument-content">
		<div class="fl">
	<?php
	$result = $db->sql_query("SELECT catid, title, images, guid FROM {$prefix}_document_cat WHERE active=1 AND parent=21 AND alanguage='$currentlang' ORDER BY weight LIMIT 5");
	
if($db->sql_numrows($result) > 0) 
{
	$i=0;
	$j=0;
	while(list($s_catid, $s_titlecat, $s_images, $s_guid) = $db->sql_fetchrow($result)) 
	{
		$i++;
	?>
		<div class="style<?php echo $i;?>">
			<a href="<?php echo url_sid($s_guid);?>"><?php echo resize_image($s_titlecat,$s_images,$path_upload_cat,$path_upload_cat,$w_array[$j],$h_array[$j]);?></a>
			<h<?php echo $i?>><a href="<?php echo url_sid($s_guid);?>"><?php echo $s_titlecat?> <img src="<?php echo $urlsite;?>/images/w_next_right.png"></a></h><?php echo $i;?>>
		</div>
	<?php
		if($i==2){echo '</div>';}
		$j++;
	}
}
	?>
	<div class="cl"></div>
	</div>
</div>
<?php

// hien thi noi dung danh sach cac bai viet theo chuyen
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;
$query = "SELECT COUNT(*) FROM {$prefix}_document WHERE alanguage='$currentlang' AND (";
$query .= "catid=$catid OR ";
for ($i = 0; $i < count($kList); $i++) $query .= "catid={$kList[$i]} OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ')';
$result = $db->sql_query($query);
list($total) = $db->sql_fetchrow($result);

$query = "SELECT n.id, n.title, n.images, n.time, n.price, u.fullname, u.folder FROM ".$prefix."_document AS n,".$prefix."_user AS u WHERE n.alanguage='$currentlang' AND n.active=1  AND n.user_id=u.id AND (";
$query .= "n.catid=$catid OR ";
for ($i = 0; $i < count($kList); $i++) $query .= "n.catid={$kList[$i]} OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ") ORDER BY n.time DESC LIMIT $offset, $perpage";
//die($query);
$resultn = $db->sql_query($query);
if($db->sql_numrows($resultn) > 0) {
	$i=0;
	$paddingright="ui-paddingright";
	echo"<div class=\"document-boxca\"><div class=\"document-boxca-group\">\n";
	while(list($id, $title, $images, $time, $price, $fullname, $folder) = $db->sql_fetchrow($resultn)) {
		$i++;
		if($i==3){$paddingright=""; $i=0;}
		else{$paddingright="ui-paddingright";}
		$url_detail =url_sid("index.php?f=".$module_name."&do=detail&id=$id");
		$path_upload_img = "$path_upload/document/$folder";
		$path_upload_img2 = "$path_upload/document";
		if(file_exists("$path_upload_img/$images") && $images !="") {
			$news_img = resize_image($title,$images,$path_upload_img,$path_upload_img2,122,177);

			
		} else {
			$news_img = resize_image($titlenewind,'no_image.gif','images',$path_upload_imgnewind,122,177);
		}
		echo "<div class=\"ui-box-content $paddingright fl\">\n";
		echo "<div class=\"ui-box-img fl\"><a href=\"$url_detail\">$news_img</a></div>\n";
		echo "<div class=\"ui-box-title fl\"><a href=\"$url_detail\">$title</a></div>\n";
		echo "<div class=\"cl\"></div>\n";
		echo "</div>\n";
	}
	if($total > $perpage) {
		$pageurl = "index.php?f=$module_name&do=categories&id=$catid";
		echo paging($total,$pageurl,$perpage,$page);
	}
	echo "</div>";
	echo "</div>";
}

//phan hien thi danh sach chuyen muc con cho chuyen muc cap 1 
$result_catindex = $db->sql_query("SELECT catid, title, parent, homelinks FROM {$prefix}_document_cat WHERE parent=$catid AND active=1 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($result_catindex) > 0) 
{
		//OpenContent($catname);
		$i=0;
		$paddingright="ui-paddingright";
		while(list($catidcat, $titlecat, $parentcat, $homelinks) = $db->sql_fetchrow($result_catindex)) 
		{
			
			$url_cat =url_sid("index.php?f=".$module_name."&do=categories&id=$catidcat");
			$margin_left="";
			
			$result_newsindex = $db->sql_query("SELECT n.id, n.title, n.images, n.time, n.price, u.fullname, u.folder FROM ".$prefix."_document AS n,".$prefix."_user AS u WHERE n.alanguage='$currentlang' AND n.active=1  AND n.user_id=u.id AND ( n.catid=$catidcat or n.catid in(SELECT catid FROM {$prefix}_document_cat WHERE parent=$catidcat)) ORDER BY n.time DESC LIMIT 9");
			$numrows = $db->sql_numrows($result_newsindex);
			if($db->sql_numrows($result_newsindex) > 0) 
			{
				echo"<div class=\"ui-box\">\n";
				echo "<div class=\"ui-box-header\"><span><a href=\"$url_cat\">$titlecat</a></span></div><div class=\"ui-box-group\">\n";
				while(list($idnewind, $titlenewind,  $imagesind, $timenewind, $price, $fullname, $folder) = $db->sql_fetchrow($result_newsindex)) 
				{
					$i++;
					if($i==3){$paddingright=""; $i=0;}
					else{$paddingright="ui-paddingright";}
					$url_detail =url_sid("index.php?f=document&do=detail&id=$idnewind");
					$path_upload_imgnewind = "$path_upload/document/$folder";
					$path_upload_imgnewind2 = "$path_upload/document";
					$news_pic_index = "";
					
					if($imagesind !="" && file_exists("$path_upload_imgnewind/$imagesind")) 
					{
					$imagesind = resize_image($titlenewind,$imagesind,$path_upload_imgnewind,$path_upload_imgnewind,60,50);
						
					}
					else
					{
					$imagesind = resize_image($titlenewind,'no_image.gif','images',$path_upload_imgnewind2,60,50);
					
					}
					echo "<div class=\"ui-box-content $paddingright fl\">\n";
					echo "<div class=\"ui-box-img fl\"><a href=\"$url_detail\">$imagesind</a></div>\n";
					echo "<div class=\"ui-box-title fl\"><a href=\"$url_detail\">$titlenewind</a></div>\n";
					echo "<div class=\"cl\"></div>\n";
					echo "</div>\n";
							
				}
				echo "</div>";
				echo "<div class=\"cl\"></div>\n";
				echo "</div>";
			}
			
		}
		//CloseContent();
}

}
else
{
OpenTab($title_home);
// hien thi noi dung danh sach cac bai viet theo chuyen
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;
$query = "SELECT COUNT(*) FROM {$prefix}_document WHERE alanguage='$currentlang' AND (";
$query .= "catid=$catid OR ";
for ($i = 0; $i < count($kList); $i++) $query .= "catid={$kList[$i]} OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ')';
$result = $db->sql_query($query);
list($total) = $db->sql_fetchrow($result);

$query = "SELECT n.id, n.title, n.images, n.time, n.price, u.fullname, u.folder FROM ".$prefix."_document AS n,".$prefix."_user AS u WHERE n.alanguage='$currentlang' AND n.active=1  AND n.user_id=u.id AND (";
$query .= "n.catid=$catid OR ";
for ($i = 0; $i < count($kList); $i++) $query .= "n.catid={$kList[$i]} OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ") ORDER BY n.time DESC LIMIT $offset, $perpage";
//die($query);
$resultn = $db->sql_query($query);
if($db->sql_numrows($resultn) > 0) {
	echo"<div class=\"document-boxca\"><div class=\"document-boxca-group\">\n";
	while(list($id, $title, $images, $time, $price, $fullname, $folder) = $db->sql_fetchrow($resultn)) {
		$url_detail =url_sid("index.php?f=".$module_name."&do=detail&id=$id");
		$path_upload_img = "$path_upload/document/$folder";
		$path_upload_img2 = "$path_upload/document";
		if(file_exists("$path_upload_img/$images") && $images !="") {
			$news_img = resize_image($title,$images,$path_upload_img,$path_upload_img,122,177);
		} else {
			$news_img = resize_image($title,'no_image.gif','images',$path_upload_img2,122,177);
		}
		temp_document_index($id, $title, $price, $news_img, $url_detail);
	}
	if($total > $perpage) {
		$pageurl = "index.php?f=$module_name&do=categories&id=$catid";
		echo paging($total,$pageurl,$perpage,$page);
	}
	echo "</div>";
	echo "</div>";
}
CloseTab();
//phan hien thi danh sach chuyen muc con cho chuyen muc cap 1 
$result_catindex = $db->sql_query("SELECT catid, title, parent, homelinks FROM {$prefix}_document_cat WHERE parent=$catid AND active=1 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($result_catindex) > 0) 
{
		//OpenContent($catname);
		$i=0;
		while(list($catidcat, $titlecat, $parentcat, $homelinks) = $db->sql_fetchrow($result_catindex)) 
		{
			
			$url_cat =url_sid("index.php?f=".$module_name."&do=categories&id=$catidcat");
			$margin_left="";
			$resultitem = $db->sql_query("SELECT COUNT(*) FROM ".$prefix."_document AS n,".$prefix."_user AS u WHERE n.alanguage='$currentlang' AND n.active=1  AND n.user_id=u.id AND ( n.catid=$catidcat or n.catid in(SELECT catid FROM {$prefix}_document_cat WHERE parent=$catidcat)) ORDER BY n.time DESC");
			list($totalitem) = $db->sql_fetchrow($resultitem);
			
			echo"<div class=\"boxca\">\n";
			echo "<div class=\"boxca-header\"><span><a href=\"$url_cat\">$titlecat ($totalitem)</a></span></div><div class=\"document-boxca-group\">\n";
			$result_newsindex = $db->sql_query("SELECT n.id, n.title, n.images, n.time, n.price, u.fullname, u.folder FROM ".$prefix."_document AS n,".$prefix."_user AS u WHERE n.alanguage='$currentlang' AND n.active=1  AND n.user_id=u.id AND ( n.catid=$catidcat or n.catid in(SELECT catid FROM {$prefix}_document_cat WHERE parent=$catidcat)) ORDER BY n.time DESC LIMIT 5");
			$numrows = $db->sql_numrows($result_newsindex);
			if($db->sql_numrows($result_newsindex) > 0) 
			{
				while(list($idnewind, $titlenewind,  $imagesind, $timenewind, $price, $fullname, $folder) = $db->sql_fetchrow($result_newsindex)) 
				{
					$url_detail =url_sid("index.php?f=document&do=detail&id=$idnewind");
					$path_upload_imgnewind = "$path_upload/document/$folder";
					$path_upload_imgnewind2 = "$path_upload/document";
					$news_pic_index = "";
					
					if($imagesind !="" && file_exists("$path_upload_imgnewind/$imagesind")) 
					{
					$imagesind = resize_image($titlenewind,$imagesind,$path_upload_imgnewind,$path_upload_imgnewind,122,177);
						
					}
					else
					{
					$imagesind = resize_image($titlenewind,'no_image.gif','images',$path_upload_imgnewind2,122,177);

					}
					echo "<div class=\"document-boxca-content fl\">\n";
					echo "<div class=\"document-boxca-img\"><a href=\"$url_detail\">$imagesind</a></div>\n";
					?><div  class="document-boxca-title">
			<p>
				<strong><a href="<?php echo $url_detail ;?>"><?php echo CutString($titlenewind,50) ;?></a></strong>
				</p></div>
               <!-- <br><?php// echo show_money($price) ?>-->
		<?php
					echo "<div class=\"cl\"></div>\n";
					echo "</div>\n";
							
				}
			}
			echo "</div></div>";
		}
		//CloseContent();
}
}
include_once("footer.php");

?>