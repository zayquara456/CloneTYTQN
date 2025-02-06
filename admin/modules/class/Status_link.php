<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_POST['id']);
$status = intval($_POST['stat']);
$load_hf = isset($_POST['load_hf']) ? 1 : 0;
$linkpage = $_SESSION['linkpage'];
$table = $prefix.'_link_report';
global $sitename;
$db->sql_query("UPDATE $table SET status=$status WHERE id=$id");
//if($status==1){
// $result = $db->sql_query("SELECT id, docid, time, name, email, url, url_replace, title, content, status FROM {$prefix}_link_report WHERE id=$id");
//if($db->sql_numrows($result) > 0) {
//    list($id, $docid, $time, $name, $email, $url, $url_replace, $title, $content, $status) = $db->sql_fetchrow($result);
//    if($url_replace==""){$url=$url;}else{$url=$url_replace;}
//    $message = "<html><body style=\"font-family: Arial; font-size: 12px\">Xin chào, $name:<br/>";
//    $message .= "".nl2br("Xin cảm ơn bạn đã gửi thông báo lỗi đến cho chúng tôi.<br/>Lỗi $title đã được chúng tôi khắc phục xong, bạn có thể tại lại tài liệu tại đường dẫn dưới đây<br/> $url <br/>")."<br/>TVXD Team.<br/></body></html>";
//    $subject = "Lỗi tài liệu tại website $sitename";
//    sendmail($subject, $email, $adminmail, $message);
//}
/*$message = preg_replace("/<.*?>/", "", $message);*/


header("Location: modules.php?f=document&do=views_link&id=$id");
//include("modules/".$adm_modname."/news_active.php");*/
?>

<!--echo "</script language=\"javascript\" type=\"text/javascript\">";
//    echo "alert('Thư báo lỗi đã được gửi cho $email<br>$message');";
//    echo "window.location.href=\"modules.php?".$linkpage."\"";
//    echo "</script>";   
//}-->