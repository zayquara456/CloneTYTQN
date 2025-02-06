<?php
if(!defined('CMS_ADMIN')) {
	die();
}
//$sqlup="SELECT ul.id, ul.user_id, ul.dateline, ul.action, ul.money, d.user_id, d.id FROM ".$prefix."_user_log as ul, ".$prefix."_document as d WHERE TRIM(ul.action)=d.title AND ul.title='Tải tài liệu'";
//
//$resultup = $db->sql_query($sqlup);
//$i=1;
//if($db->sql_numrows($resultup) > 0) {
//	while(list($id, $user_id, $dateline, $action, $money, $duser_id, $documentid) = $db->sql_fetchrow($resultup)){
//		updatedocumentorder($user_id, $duser_id, $documentid, $money, $dateline);
//		$i++;
//	}
//	echo "update $i ban ghi";
//	
//}
//$i=1;
//$sqlup="SELECT user_sale, documentid, SUM(price) as sumprice, COUNT(documentid) AS count_documentid FROM ".$prefix."_document_order WHERE user_buy<>1 AND user_buy<>94 AND user_buy<>167 AND user_buy<>116 GROUP BY user_sale order by sumprice desc";
//$resultup = $db->sql_query($sqlup);
//if($db->sql_numrows($resultup) > 0) {
//	while(list($user_sale, $documentid, $sumprice, $count_documentid) = $db->sql_fetchrow($resultup)){
//	$db->sql_query("UPDATE ".$prefix."_user SET mep='$sumprice' WHERE id='$user_sale'");
//	$i++;
//	}
//	echo "update $i ban ghi";
//}

$sqlup="SELECT id, title FROM ".$prefix."_question";

$resultup = $db->sql_query($sqlup);
$i=1;
if($db->sql_numrows($resultup) > 0) {
	while(list($id, $title) = $db->sql_fetchrow($resultup)){
		$permalink=url_optimization(trim($title));
		$guid="index.php?f=question&do=detail&id=$id";
		echo $permalink."---".$guid."<br>";
		$db->sql_query("UPDATE ".$prefix."_question SET permalink='$permalink' , guid='$guid' WHERE id='$id'");
	//	die("UPDATE ".$prefix."_question SET permalink='$permalink' , guid='$guid' WHERE id='$id'");
		$i++;
	}
	echo "update $i ban ghi";
}
$sqlup="SELECT catid, title FROM ".$prefix."_question_cat";

$resultup = $db->sql_query($sqlup);
$i=1;
if($db->sql_numrows($resultup) > 0) {
	while(list($id, $title) = $db->sql_fetchrow($resultup)){
		$permalink=url_optimization(trim($title));
		$guid="index.php?f=question&do=categories&id=$id";
		echo $permalink."---".$guid."<br>";
		$db->sql_query("UPDATE ".$prefix."_question_cat SET permalink='$permalink' , guid='$guid' WHERE catid='$id'");
		$i++;
	}
	echo "update $i ban ghi";
}
?>