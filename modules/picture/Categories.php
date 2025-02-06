<?php
if (!defined('CMS_SYSTEM')) die();
global $url_site;
$catid = intval($_GET['catid']);

$result = $db->sql_query("SELECT catid, parent FROM {$prefix}_picture_cat WHERE 1 ORDER BY weight, catid ASC");
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
if ($searchArray === false) header("Location: index.php");
$kList = '';
if (is_array($searchArray[$catid])) Common::findAllKeys($searchArray[$catid], $kList);
else $kList = strval($catid);
if (substr($kList, -1) == ':') $kList = substr($kList, 0, strlen($kList) - 1);
$kList = explode(':', $kList);

$result = $db->sql_query("SELECT title, startid FROM ".$prefix."_picture_cat WHERE catid=$catid");
if($db->sql_numrows($result) != 1) header("Location: index.php");

list($catname, $startid) = $db->sql_fetchrow($result);

$page_title = $catname;

include_once("header.php");
include("blocks/Menu_Left.php");
OpenTab("<a href=\"".url_sid("index.php")."\" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &gt; <a href=\"".url_sid("index.php?f=picture")."\"  title=\"Hình ảnh\">Hình ảnh</a> &gt;".$catname."");
?>
<script type="text/javascript" src="<?php echo $urlsite;?>/js/highslide/highslide-with-gallery.js"></script><link rel="stylesheet" type="text/css" href="<?php echo $urlsite;?>/js/highslide/highslide.css" /><script type="text/javascript">
	hs.graphicsDir = '<?php echo $urlsite ?>/js/highslide/graphics/';
	hs.align = 'center';
	hs.transitions = ['expand', 'crossfade'];
	hs.fadeInOut = true;
	hs.dimmingOpacity = 0.8;
	hs.outlineType = 'rounded-white';
	hs.captionEval = 'this.thumb.alt';
	hs.marginBottom = 105; 
	hs.numberPosition = 'caption';
	
	
	hs.addSlideshow({
		interval: 5000,
		repeat: false,
		useControls: true,
		overlayOptions: {
			className: 'text-controls',
			position: 'bottom center',
			relativeTo: 'viewport',
			offsetY: -60
		},
		thumbstrip: {
			position: 'bottom center',
			mode: 'horizontal',
			relativeTo: 'viewport'
		}
	});
 </script>
<?php
echo "<div class=\"content\" >";
echo "<h1 class=\"posttitle\">$catname</h1>";
$perpage = $pic_per_page;

$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;

$query = "SELECT COUNT(*) FROM {$prefix}_picture WHERE catid=$catid";

$result = $db->sql_query($query);
list($total) = $db->sql_fetchrow($result);

$query = "SELECT id, title,images FROM ".$prefix."_picture WHERE id!=$startid AND (";
$query .= "catid=$catid OR ";
for ($i = 0; $i < count($kList); $i++) $query .= "catid={$kList[$i]} OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ") ORDER BY time DESC LIMIT $offset, $perpage";
$resultn = $db->sql_query($query);
if($db->sql_numrows($resultn) > 0) {
	while(list($id, $title, $images) = $db->sql_fetchrow($resultn)) {
		$path_upload_img = "$path_upload/pictures";
		if(file_exists("$path_upload_img/$images") && $images !="") {
				$images2 = resizeImages("$path_upload_img/$images", "$path_upload_img/130x100_$images" ,130,100);	
			//echo "<div class=\"picture-detail\">";
			//	echo "<a rel=\"picture_group\" title=\"".$title."\" href=\"$urlsite/$path_upload_img/$images\"><img border=\"0\" src=\"".$images2."\" alt=\"".$title."\"></a>";	
			//echo "</div>";
			echo "<div class=\"picture-detail\">";
				echo "<a class='highslide' href='$path_upload_img/$images' onclick=\"return hs.expand(this)\"><img border=\"0\" src=\"".$images2."\" alt=\"".$title."\"\"></a>";	
			echo "</div>";
			
		
	}
}	
	if($total > $perpage) {
		echo "<div style=\"float:left;\">";
		$pageurl = "index.php?f=$module_name&do=categories&catid=$catid";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</div>";
	}
}
echo "</div>";
CloseTab();
include_once("footer.php");
?>