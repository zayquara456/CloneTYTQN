<?php
if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {
    Header("Location: ../index.php");
    exit;
}

   global $prefix, $db;

function select_pic() {
   global $prefix, $db;
   $pic_dir = "".INCLUDE_PATH."uploads/Shop/pic";
   if ($handle = opendir($pic_dir)) {
   $a = 1;
   while (false !== ($file = readdir($handle))) {
   if ($file=='.' || $file=='..') continue;
   $f_name[$a] = $file;
   $a++;
   }
   closedir($handle);
   }
   $number = rand(1, $a-1);
   $image = $f_name[$number];
   return $image;
   }
$gtotal2 = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_shop where status='1' AND pic1!=''"));
if ($gtotal2 > 0) {
$gtotal2 = $gtotal2-1;
mt_srand((double)microtime()*1000000);
$pidrand2 = mt_rand(0, $gtotal2);
   list($pidbl, $titlebl, $pricebl, $picbl) = $db->sql_fetchrow($db->sql_query("SELECT pid, title, price, pic1 FROM ".$prefix."_shop WHERE status='1' AND pic1!='' LIMIT $pidrand2,1"));
   $content = "<center><a href=".INCLUDE_PATH."modules.php?name=Shop&go=goods&pid=$pidbl>";
   if (file_exists("".INCLUDE_PATH."uploads/Shop/trumb_pic/$picbl")) {
   $content .= "<img border=\"0\" src=\"".INCLUDE_PATH."uploads/Shop/trumb_pic/$picbl\">";
   }
   else {
   $content .= "<img border=\"0\" src=\"".INCLUDE_PATH."modules/Shop/pic/$picbl\" width=\"100\">";
   }
   $content .= "</a><br><b>$titlebl<br><font color=red>".number_format($pricebl, 0, '.', ' ')." VNĐ </font></b></center>";
}
?>
