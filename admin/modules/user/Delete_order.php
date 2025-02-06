<?php
if (!defined('CMS_SYSTEM')) die();

if (!defined('iS_USER') || !isset()) header("Location: index.php?f=user&do=login");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$db->sql_query("DELETE FROM {$prefix}_products_order WHERE id=$id");
//updateadmlog($admin_ar[0], $module_name, _MODTITLE, _PRD_DELETE_ORDER);
header( 'index.php?f=user&do=giaodich' ) ;
?>