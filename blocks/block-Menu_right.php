<?php
if (!defined('CMS_SYSTEM')) exit;

global $Default_Temp;

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
$content = '';
function show_right_menu()
{
	global $db, $currentlang, $prefix ;
		$content="";
		$content .= "<div id=\"accordion\" class=\"ui-accordion1 ui-widget1 ui-helper-reset1\">\n";
		$result_sub = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='right_menu' AND parentid=0 and active=1 and alanguage='$currentlang' order by weight asc");
		if($db->sql_numrows($result_sub) > 0) 
		{
			$i=1;
			while (list($mid_sub, $title_sub, $url_sub) = $db->sql_fetchrow($result_sub)) 
			{
				$content .= "<div><h3><a href=\"".url_sid($url_sub)."\">$title_sub</a></h3>";
				$content .= "<div>\n";
				$result_sub2 = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='right_menu' AND parentid=$mid_sub and active=1 and alanguage='$currentlang' order by weight asc");
				if($db->sql_numrows($result_sub2) > 0) 
				{
					while (list($mid_sub2, $title_sub2, $url_sub2) = $db->sql_fetchrow($result_sub2)) 
					{
						if($i==1 && $mid_sub==158)
						{
							$content .= "<p><a class=\"btn_login\" href=\"".url_sid($url_sub2)."\">$title_sub2</a></p>\n";
						}
						elseif($i==2 && $mid_sub==158)
							$content .= "<span  class=\"btn_login2\"><a href=\"".url_sid($url_sub2)."\">$title_sub2</a></span>\n";
						elseif($i==3 && $mid_sub==158)
							$content .= "<span  class=\"btn_login3\"><a href=\"".url_sid($url_sub2)."\">$title_sub2</a></span>\n";
						else
							$content .= "<a class=\"bm_right\" href=\"".url_sid($url_sub2)."\">$title_sub2</a>\n";
					$i++;
					}
				}
				
				$content .="</div></div>";
			}
		}
		$content .= "</div>\n";
		return $content;
}
echo show_right_menu();
?>
<br />
<div id="tabs_right" class="ui-tabs">
	<ul>
		<li><a href="#tabs-1"><?php echo _NGOAI_TE ?></a></li>
		<li><a href="#tabs-2"><?php echo _GIA_VANG ?></a></li>
		<li><a href="#tabs-3" style=" padding:0.5em 0.8em 0.5em 0.8em;"><?php echo _TIET_KIEM ?></a></li>
	</ul>
	<div id="tabs-1" class="ui-tabs">
	<table class="tbl-price">
	<tr>
		<th style="font-size:9px"><?php echo _LOAI_TIEN ?></th>
		<th colspan="2" style="text-align:center;  font-size:9px"><?php echo _MUA ?></th>
		<th style="font-size:9px"><?php echo _BAN ?></th>
	</tr>
	<tr>
		<td  style="width:44px; font-size:9px"><?php echo _CODE ?></td>
		<td  style="font-size:9px"><?php echo _TIEN_MAT_SEC ?></td>
		<td  style="font-size:9px"><?php echo _CHUYEN_KHOAN ?></td>
		<td>&nbsp;</td>
	</tr>
	<?php
	$bngoaite = $db->sql_query("SELECT id, tieude, mua, mua2, ban, time FROM {$prefix}_ngoaite WHERE status=1 ORDER BY weight DESC LIMIT 4");
if($db->sql_numrows($bngoaite) > 0) 
{
	while(list($ntid, $nttieude, $ntmua, $ntmua2, $ntban, $nttime) = $db->sql_fetchrow($bngoaite))
	{
		//$url_news_detail =url_sid("index.php?f=news&do=detail&id=$nid");
		echo "<tr><td  style=\"font-weight:bold\">$nttieude</td><td>$ntmua</td><td>$ntmua2</td><td>$ntban</td></tr>";
	}
}
	
	
	?></table>
	<div class="tab-footer"><a class="lbutton" href="">Chi tiết</a></div>
	</div>
	<div id="tabs-2"><table class="tbl-price">
	<tr>
		<th style="font-size:9px"><?php echo _LOAI_VANG ?></th>
		<th style="font-size:9px"><?php echo _MUA ?></th>
		<th style="font-size:9px"><?php echo _BAN ?></th>
	</tr>
	<?php
	
	$bngoaite = $db->sql_query("SELECT id, tieude, mua, ban, time FROM {$prefix}_giavang WHERE status=1 ORDER BY weight DESC LIMIT 4");
if($db->sql_numrows($bngoaite) > 0) 
{
	while(list($ntid, $nttieude, $ntmua, $ntban, $nttime) = $db->sql_fetchrow($bngoaite))
	{
		//$url_news_detail =url_sid("index.php?f=news&do=detail&id=$nid");
		echo "<tr><td  style=\"font-weight:bold\">$nttieude</td><td>$ntmua</td><td>$ntban</td></tr>";
	}
	
}
	
	
	?>
	</table>
	<div class="tab-footer"><a class="lbutton" href="">Chi tiết</a></div>
	</div>
	<div id="tabs-3"><table class="tbl-price">
	<tr>
		<th style="font-size:9px">Kỳ hạn </th>
		<th style="font-size:9px">VND</th>
		<th style="font-size:9px">USD</th>
		<th style="font-size:9px">EUR</th>
	</tr>
	<tr><td  style="font-weight:bold">1 Tháng</td><td>54354</td><td>42343</td><td>42343</td></tr>
	<tr><td  style="font-weight:bold">2 Tháng</td><td>54354</td><td>42343</td><td>42343</td></tr>
	<tr><td  style="font-weight:bold">3 Tháng</td><td>54354</td><td>42343</td><td>42343</td></tr>
	<tr><td  style="font-weight:bold">4 Tháng</td><td>54354</td><td>42343</td><td>42343</td></tr>
	<tr><td  style="font-weight:bold">5 Tháng</td><td>54354</td><td>42343</td><td>42343</td></tr>
	
	<?php
	
	/*$bngoaite = $db->sql_query("SELECT id, tieude, mua, ban, time FROM {$prefix}_giavang WHERE status=1 ORDER BY weight DESC LIMIT 4");
if($db->sql_numrows($bngoaite) > 0) 
{
	while(list($ntid, $nttieude, $ntmua, $ntban, $nttime) = $db->sql_fetchrow($bngoaite))
	{
		//$url_news_detail =url_sid("index.php?f=news&do=detail&id=$nid");
		echo "<tr><td  style=\"font-weight:bold\">$nttieude</td><td>$ntmua</td><td>$ntban</td></tr>";
	}
	
}*/
	
	
	?>
	</table>
	<div class="tab-footer"><a class="lbutton" href="">Chi tiết</a></div></div>
</div>
