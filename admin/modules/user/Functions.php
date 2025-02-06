<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

function AccCheck($nick,$acc="") {
	global $db, $prefix;
	if ((!$nick) || ($nick=="") || (preg_match("![^a-zA-Z0-9_-]!",$nick))) { $stop = ""._ERROR1.""; }
	elseif (strlen($nick) > 10) { $stop = ""._ERROR1.""; }
	elseif (strlen($nick) < 3) { $stop = ""._ERROR1.""; }
	elseif (strrpos($nick,' ') > 0) { $stop = ""._ERROR1.""; }
	elseif ($db->sql_numrows($db->sql_query("SELECT adacc FROM ".$prefix."_admin WHERE adacc='$nick' AND adacc!='$acc'")) > 0) { $stop = ""._ERROR1_1.""; }
	else { $stop = ""; }
    return($stop);
}
function show_groupauthor($id)
{
	global $db, $prefix;

	$result = $db->sql_query("SELECT id,title FROM ".$prefix."_admingroup WHERE id='$id'");
	if($db->sql_numrows($result) > 0 ) 
	{
		list($id, $title) = $db->sql_fetchrow($result);
		return $title;
	}		
}
function show_bank($code){
	global $bankcode_arr;
	$bank="";
	foreach($bankcode_arr as $key => $value)
	{
		if($code==$value){$bank=$key;}
	}
	return $bank;
}
function show_telecom($id){
	global $telecom_arr;
	$telecom="";
	foreach($telecom_arr as $key => $value)
	{
		if($id==$value){$telecom=$key;}
	}
	return $telecom;
}

function show_user($id)
{
	global $db, $prefix;

	$result = $db->sql_query("SELECT fullname FROM ".$prefix."_user WHERE id='$id'");
	if($db->sql_numrows($result) > 0 ) 
	{
		list($fullname) = $db->sql_fetchrow($result);
		return "<strong>$fullname</strong>";
	}
	else{return "<strong>*</strong>";}
}	
function show_status($status){
	switch ($status){
		case 0:
			return "Chưa xử lý";
			break;
		case 1:
			return "<font color='#2d7700'>Đang chờ xử lý</font>";
			break;
		case 2:
			return "<font color='#9f0000'>Đã xử lý</font>";
			break;
		case 3:
			return "<font color='#9f0000'>Giao dịch bị hủy</font>";
			break;
	}
}
function show_action_status($id,$status){
	global $adm_modname;
	switch ($status){
		case 0:
			return "<a href=\"?f=".$adm_modname."&do=status_abstract&id=$id&status=1\" info=\"Đang chờ xử lý\"><img border=\"0\" src=\"images/view.png\"></a> <a href=\"?f=".$adm_modname."&do=status_abstract&id=$id&status=3\" info=\"Hủy giao dịch\"><img border=\"0\" src=\"images/viewo.png\"></a>";
			break;
		case 1:
			return "<a href=\"?f=".$adm_modname."&do=status_abstract&id=$id&status=2\" info=\"Đã xử lý\"><img border=\"0\" src=\"images/clock.png\"></a> <a href=\"?f=".$adm_modname."&do=status_abstract&id=$id&status=3\" info=\"Hủy giao dịch\"><img border=\"0\" src=\"images/viewo.png\"></a>";
			break;
		case 2:
			return "<img border=\"0\" src=\"images/tick.png\">";
			break;
		case 3:
			return "<img border=\"0\" src=\"images/viewo.png\">";
			break;
	}
}
function show_groupuser($id)
{
	global $db, $prefix;

	$result = $db->sql_query("SELECT id,title, sale FROM ".$prefix."_usergroup WHERE id='$id'");
	if($db->sql_numrows($result) > 0 ) 
	{
		list($id, $title, $sale) = $db->sql_fetchrow($result);
		return "<strong>$title + ($sale%)</strong>";
	}
	else
	{
		return "Khách hàng thường + (0%)";
	}
}
function show_giaodich($id,$fullname)
{
	global $db, $prefix;

	$result = $db->sql_query("SELECT  COUNT(id) FROM ".$prefix."_user_log WHERE user_id=$id");
	if($db->sql_numrows($result) > 0 ) 
	{
		list($total) = $db->sql_fetchrow($result);
		return "<strong><a target=\"_blank\" href=\"modules.php?f=user&do=history&s_name=".$fullname."&s_quantity=1000\" >$total giao dịch</a></strong>";
	}
	else
	{
		return "Không có";
	}
}
function show_thecao($id,$fullname)
{
	global $db, $prefix;

	$result = $db->sql_query("SELECT  COUNT(id) FROM ".$prefix."_thecao_buy WHERE userid=$id");
	if($db->sql_numrows($result) > 0 ) 
	{
		list($total) = $db->sql_fetchrow($result);
		return "<strong><a  target=\"_blank\" href=\"modules.php?f=thecao&do=history&s_name=".$fullname."&s_quantity=1000\" >$total thẻ</a></strong>";
	}
	else
	{
		return "Không có";
	}
}

function telecom_name($id)
{
	global $telecom_arr;
	$telecom_list="";
	foreach($telecom_arr as $_key => $_value)
	{
			if($_value==$id){$telecom_list=$_key;}
			else{$telecom="";}
			
	}
	return $telecom_list;
}
function subnoibocat($mid, $text="", $mcheck="", $mseld="", $css) {
	
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query("SELECT catid, title FROM ".$prefix."_noibo_cat WHERE parent='$mid' AND catid!='$mseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		$i=0;
		while(list($mid2, $title) = $db->sql_fetchrow($result)) {
			//if($catcheck) {
			//if($mid2) {
				if(@in_array($mid2,$mcheck)) {
					$seld = " checked ";
				}else{
					$seld ="";

				}	
			//}
			$treeTemp .= "<td><input type=\"checkbox\" name=\"auth_menus[]\" value=\"$mid2\"$seld $css> ".$title."</td>";
			if($i==3){$treeTemp .= "</td></tr><tr><td></td>";}
			$treeTemp .= subnoibocat($mid2,$text, $mcheck, $mseld, $css);
			$i++;
		}	
	}
	return $treeTemp;	
}


?>