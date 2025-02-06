<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$status = intval($_GET['status']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
$linkpage = $_SESSION['linkpage'];
$table = $prefix.'_link_report';
global $sitename;
$db->sql_query("UPDATE $table SET status=$status WHERE id=$id");
$result = $db->sql_query("SELECT id, docid, time, name, email, url, title, content, status FROM {$prefix}_link_report WHERE id=$id");
if($db->sql_numrows($result) > 0) {
    list($id, $docid, $time, $name, $email, $url, $title, $content, $status) = $db->sql_fetchrow($result);
    $message = "<html><body style=\"font-family: Arial; font-size: 12px\">Xin chào, $name:<br/>";
    $message .= "".nl2br("Xin cảm ơn bạn đã gửi thông báo lỗi đến cho chúng tôi.<br/>Lỗi $title đã được chúng tôi khắc phục xong, bạn có thể tại lại tài liệu tại đường dẫn dưới đây<br/> $url <br/>")."<br/>TVXD Team.<br/></body></html>";
    $subject = "Lỗi tài liệu tại website $sitename";
    sendmail($subject, $email, $adminmail, $message);
}
header("Location: modules.php?".$linkpage."");
//include("modules/".$adm_modname."/news_active.php");
?>