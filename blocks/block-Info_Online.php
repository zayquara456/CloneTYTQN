<?php
if (!defined('CMS_SYSTEM')) exit;

global $yim_support, $Default_Temp;

$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) 
{
	for ($h = 0; $h < count($bl_arr[$i]); $h++) 
	{
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) 
		{
			$correctArr = $temp;
			break;
		}
	}
}

$content = "";
	echo "<script type=\"text/javascript\" src=\"js/System_Library2406.js\"></script>";
	//$yim_support_arr = str_replace(" ","",$yim_support_arr);
    $content .= "<div class=\"colleft\">";
    $content .= "<div><h2>"._THOI_TIET."</h2></div>";
    $content .= "<div class=\"bloc01\"> ";
    $content .= "    <div id=\"cboWeather\"> </div> ";
    $content .= "    <div id=\"zfWeContent\"> </div> ";
    $content .= "</div> ";
    $content .= "<script type=\"text/javascript\" src=\"http://zing.vn/util/weather.js\"></script> ";
    $content .= "<script type=\"text/javascript\"> zfShowWeather(); </script> ";
    $content .= "<div><h2>"._TIEN_ICH."</h2></div>";
    $content .= "<div class=\"bloc01\"> ";
    $content .= "   <div class=\"tr04\"> <a target=\"_blank\" title=\""._BANG_GIA_CHUNG_KHOAN."\" class=\"clr02\" href=\"javascript:voidnull(0)\" onclick='openWindow(\"http://news.zing.vn/utility/chungkhoan.aspx\",\"\",1024,600);'>"._BANG_GIA_CHUNG_KHOAN."</a> </div> ";
    $content .= "   <div class=\"tr04\"> <a target=\"_blank\" title=\""._KET_QUA_XO_SO."\" class=\"clr02\" href=\"javascript:voidnull(0)\" onclick='openWindow(\"http://news.zing.vn/utility/kqxs.aspx\",\"\",520,300);'>"._KET_QUA_XO_SO."</a> </div> ";
    $content .= "    <div class=\"tr04\"> <a target=\"_blank\" title=\""._LICH_PHIM_TRUYEN_HINH."\" class=\"clr02\" href=\"http://movie.zing.vn/Movie/lich-chieu-tivi.html\">"._LICH_PHIM_TRUYEN_HINH."</a> </div> ";
    $content .= "    <div class=\"tr04\"> <a target=\"_blank\" title=\""._LICH_PHIM_CHIEU_RAP."\" class=\"clr02\" href=\"http://movie.zing.vn/Movie/lich-chieu-rap.html\">"._LICH_PHIM_CHIEU_RAP."</a> </div> ";
    $content .= "</div> ";
    $content .= "<div><h2>".TY_GIA."</h2></div>";
    $content .= "<div class=\"bloc01\"> ";
    $content .= "    <div id=\"otblGold\"> </div> ";
    $content .= "    <div id=\"otblTygia\"> </div> ";
   	$content .= " </div> ";
    $content .= "<div id=\"Zad02\" style=\"margin: 0px auto; width: 154px; text-align: center;\"> </div> ";
    $content .= "<script type=\"text/javascript\" src=\"http://zing.vn/util/Rate.js\"></script> ";
    $content .= "<script type=\"text/javascript\" src=\"http://zing.vn/util/gold.js\"></script> ";
    $content .= "<script type=\"text/javascript\"> initGold(); initTyGia(); selectAd(245,'Zad02'); </script> ";
    $content .= "</div>";
?>

