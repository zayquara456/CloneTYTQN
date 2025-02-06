<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
$resultinfo = $db->sql_query("SELECT c.id, c.title, c.newsid, n.title FROM {$prefix}_comments AS c, {$prefix}_news AS n WHERE c.newsid=n.id AND id='$id'");
if ($db->sql_numrows($resultinfo) > 0) {
	list($id, $title, $newsid, $newstitle) = $db->sql_fetchrow($resultinfo);
}
	$db->sql_query("DELETE FROM {$prefix}_comments WHERE id=$id");
        updateadmlog($admin_ar[0], _MODTITLE, 'Xóa bình luận', 'Xóa bình luận '.$title.' | ID-'.$id.' Thuộc bài viết '.$newstitle.' | ID-'.$newsid);
	//include("modules/".$adm_modname."/index.php");
	header("Location: modules.php?f=".$adm_modname."&do=comments");
?>