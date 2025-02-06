<?php
if (!defined('CMS_SYSTEM')) header("Location: index.php");
if(file_exists("data/config_news.php")) require("data/config_news.php");
global $path_upload, $mod_name, $id, $Default_Temp, $urlsite;

$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) {
    for ($h = 0; $h < count($bl_arr[$i]); $h++) {
        $temp = explode("@", $bl_arr[$i][$h]);
        if (($temp[5] == $currentlang) && ($temp[6] == $basename)) {
            $correctArr = $temp;
            break;
        }
    }
}
$margin="padding: 7px;";
$content ="";
$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news  WHERE (catid=67) AND  active=1 AND alanguage='$currentlang' ORDER BY id DESC LIMIT 1");
$numrows = $db->sql_numrows($result_lastnew);

$a=0;
$content .= "<div class=\"div-block\">";
$content .= "<div class=\"div-tblock\" style=\"padding-left: 0; padding-bottom: 0;\"><div class=\"fl\" style=\"background: #6a0000;color: #fff;text-shadow: none;padding: 5px 20px;\">Story</div><div class=\"pagination fr\" id=\"foo222_pag\"></div><div class=\"cl\"></div></div>";
$content .= "<div class=\"div-cblock\">";
if($db->sql_numrows($result_lastnew) > 0)  {
    while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew))
    {
        $rwtitlelast = utf8_to_ascii(url_optimization($titlelast));
        $url_news_detail =url_sid("index.php?f=news&do=detail&id=$idlast");
        $get_path = get_path($time);
        $path_upload_img = "$path_upload/news/$get_path";
        $path_upload_noimg = "$path_upload/news";
        $a++;
        if(file_exists("$path_upload_img/$imageslast") && $imageslast !="") {
            $imageslast = resize_image($titlelast,$imageslast,$path_upload_img,$path_upload_img,285,250);
        }
        else
        {
            $imageslast = resize_image($titlelast,'no_image.gif','images',$path_upload_noimg,285,250);
        }
        $content .= "<div class=\"document-item fl\" style=\"$margin\">";
        $content .= "<div class=\"document-title \"><a href=\"$url_news_detail\" title=\"$titlelast\" style=\"margin-bottom: 7px; font-weight: bold; color: #0066CC; display: inherit;\">".$titlelast."</a></div>";
        $content .= "<div class=\"document-img\" ><a href=\"$url_news_detail\">$imageslast</a></div>";
        $content .= "<div class=\"document-content \" style=\"text-align: justify; padding: 10px 0;\">".$hometext."</div>";
        $content .= "<div class=\"cl\"></div></div>";
        if($a==6 || $a==12){$content .= "</div><div class=\"document-group\">";}

    }
    $content .= "<div class=\"cl\"></div>";
}
else
{
    $content .= "&#272;ang c&#7853;p nh&#7853;t";
}
$content .= "</div></div>";

///////////=== chuyen muc guong mat

?>