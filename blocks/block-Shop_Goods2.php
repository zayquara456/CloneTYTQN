<?php
/********************************************************/
/* Giới thiệu công ty PCCC T-M - www.tm-pccc.com     	*/
/* ============================================         */
/* Module Shop NukeViet RC1 - http://nukeviet.vn		*/
/********************************************************/

if ((!defined('NV_SYSTEM')) AND (!defined('NV_ADMIN'))) {
    Header("Location: ../index.php");
    exit;
}

global $prefix, $db, $ThemeSel;
include("".INCLUDE_PATH."includes/data/config_shop.php");


/*Phần khai báo*/
$Scroll = 1;//Có cho nội dung của chạy từ dưới lên trên hay không. 0 - không, 1 - đồng ý
$pic_dir = "".INCLUDE_PATH."uploads/Shop/block_pic"; //Noi chứa thư viện hình ảnh cho bài viết
/*Hết phần khai báo*/

    $f_name = "";
   if ($handle = opendir($pic_dir)) {
   $a = 0;
   while (false !== ($file = readdir($handle))) {
   if ($file=='.' || $file=='..') continue;
   $f_name[$a] = $file;
   $a++;
   }
   closedir($handle);
   }
   if ($f_name != "") {
   $content = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td>";
   if ($Scroll) {
    $content .= "<marquee behavior= \"scroll\" align= \"center\" direction= \"up\" height=\"250\" scrollamount= \"2\" scrolldelay= \"100\" onmouseover='this.stop()' onmouseout='this.start()'>"; 
  }
  $content .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">"; 
   for ($e=0; $e < sizeof($f_name); $e++) {
   if ($f_name[$e] != "index.html") {
    $cresult = $db->sql_query("SELECT * FROM ".$prefix."_shop WHERE status = '1' AND pic1='$f_name[$e]' AND pic1!=''");
    $row = $db->sql_fetchrow($cresult);
        if ($e > 0) {
    $content .= "<tr><td><img border=\"0\" src=\"".INCLUDE_PATH."modules/Shop/themes/$ThemeSel/images/spacer.gif\"  width=\"1\" height=\"10\"></td></tr>";
    }
    $content .= "<tr><td valign=\"top\"><a href=\"".INCLUDE_PATH."modules.php?name=Shop&go=goods&pid=$row[pid]\"><img border=\"0\" src=\"$pic_dir/$f_name[$e]\"  width=\"$width_block\" style=\"float: right\"></a><b><a href=\"".INCLUDE_PATH."modules.php?name=Shop&go=goods&pid=$row[pid]\">$row[title]</a></b><br>$row[addition]</td></tr>";
   }
   }
   $content .= "</table>"; 
   if ($Scroll) { $content .= "</marquee>"; }
   $content .= "</td></tr></table>"; 
   }

?> 
