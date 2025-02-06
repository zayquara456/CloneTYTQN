<?php
define('CMS_SYSTEM', true);
@require_once("config.php");

$id = intval($_GET['id']);
$poz = intval($_GET['poz']);
$poz =isset($_GET['poz'])?$_GET['poz']:"";
if ($poz=="")
{
    $result_adv_click = $db->sql_query("SELECT links FROM ".$prefix."_advertise WHERE id=$id AND alanguage='$currentlang' AND active=1");
}
else
{
    $result_adv_click = $db->sql_query("SELECT links FROM ".$prefix."_advertise_scroll WHERE id=$id AND alanguage='$currentlang' AND active=1");
}
if(empty($id) || $db->sql_numrows($result_adv_click) != 1) die();
list($links) = $db->sql_fetchrow($result_adv_click);
$db->sql_query("UPDATE ".$prefix."_advertise SET hits=hits+1 WHERE id='$id'");
header("Location: $links");

?>
