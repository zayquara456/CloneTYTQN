<?php

if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) { die(); }

function errorMess($ds, $id, $content) {
	return "<span style=\"display:".$ds.";\" id=\"".$id."_err\" class=\"error_msg\">".$content."<br/></span>";
}
$bankcode_arr = array(
				 'Vietcombank'	=> 'VCB',
				 'Đông Á Bank'	=> 'DAB',
				 'MaritimeBank'	=> 'MSB',
				 'Vietinbank'	=> 'VTB',
				 'BIDV'			=> 'BIDV',
				 'Agribank'		=> 'AGR',
				 'MilitariBank'	=> 'MILI'
				 );
$telecom_arr = array(
				'Viettel'	=> '3',
				'Mobifone'	=> '1',
				'Vinaphone'	=> '2'
				 );
$chieukhau_arr = array(
				'80'	=> 'Viettel',
				'81.5'	=> 'Mobifone',
				'80.5'	=> 'Vinaphone'
				 );
$menhgia_arr = array(
				 '10000'	=> '1',
				 '20000'	=> '2',
				 '50000'	=> '3',
				 '100000'	=> '4',
				 '200000'	=> '5',
				 '500000'	=> '6'
				 );
//Lay ra mot doan trong chuoi van ban
function CutString($string, $num){
        if(strlen($string) > $num)
        {
            $result = substr($string,0,$num); //cut string with limited number
            $position = strrpos($result," "); //find position of last space
            if($position)
                $result = substr($result,0,$position); //cut string again at last space if there are space in the result above
            $result .= '...';
        }
        else {
            $result = $string;
        }
        return $result;
}
function show_money($price)
{
	if($price==0)
	{
		$price	= _FREE;
	}
	else
	{
		$price	= bsVndDot($price).'đ';
	}
	return $price;
}
//convert vnd to ep and ep to vnd
function convert_ep($money,$type){
	if($type==0)
	{
		$result = $money/1000;
	}
	elseif($type==1)
	{
		$result	= $money*1000;
	}
	return $result;
}
//convert monney
function bsVndDot($strNum)
{
    $len = strlen($strNum);
    $counter = 3;
    $result = "";
    while ($len - $counter >= 0)
    {
        $con = substr($strNum, $len - $counter , 3);
        $result = '.'.$con.$result;
        $counter+= 3;
    }
    $con = substr($strNum, 0 , 3 - ($counter - $len) );
    $result = $con.$result;
    if(substr($result,0,1)=='.'){
        $result=substr($result,1,$len+1);   
    }
    return $result;
}
function GetMonthsFromDate($myDate) {
  $year = (int) date('Y',$myDate);
  $months = (int) date('m', $myDate);
  $dateAsMonths = 12*$year + $months;
  return $dateAsMonths;
}

function GetDateFromMonths($months) {
  $years = (int) $months / 12;
  $month = (int) $months % 12;
  $myDate = strtotime("$years/$month/01"); //makes a date like 2009/12/01
  return $myDate;
}
function sortBy($url, $s) {
	if (empty($url)) { $url = "?"; } else { $url = "$url&"; }
	$s1 = $s + 1;
	return "<a href=\"{$url}sort=$s\" title=\""._SORTUP."\"><img border=\"0\" src=\"".RPATH."images/sup.gif\"></a> <a href=\"".$url."sort=".$s1."\" title=\""._SORTDOWN."\"><img border=\"0\" src=\"".RPATH."images/sdown.gif\"></a>";
}

function updateadmlog($adname, $area, $title, $action) {
	global $db, $prefix, $currentlang, $client_ip;
	$db->sql_query("INSERT INTO {$prefix}_admin_log (id, adname, dateline, area, ip_add, alanguage, action, title) VALUES (NULL, '$adname', '".TIMENOW."', '$area', '$client_ip', '$currentlang', '$action', '$title')");
}
function updateuserlog($user_id, $title,$money_old, $money, $money_new, $status, $action) {
	global $db, $prefix, $currentlang, $client_ip;
	$db->sql_query("INSERT INTO {$prefix}_user_log (id, user_id, title, money_old, money, money_new, status, dateline, ip_add, action) VALUES (NULL, '$user_id', '$title', '$money_old', '$money', '$money_new', '$status', '".TIMENOW."', '$client_ip','$action')");
}
function updatenapthelog($user_id, $title,$money_old, $money, $money_new, $status, $action) {
	global $db, $prefix, $currentlang, $client_ip;
	$db->sql_query("INSERT INTO {$prefix}_napthe_log (id, user_id, title, money_old, money, money_new, status, dateline, ip_add, action) VALUES (NULL, '$user_id', '$title', '$money_old', '$money', '$money_new', '$status', '".TIMENOW."', '$client_ip','$action')");
}
function updatedocumentorder($user_buy, $user_sale, $documentid, $price, $time) {
	global $db, $prefix, $currentlang, $client_ip;
	$db->sql_query("INSERT INTO {$prefix}_document_order (id, user_buy, user_sale, documentid, price, time) VALUES (NULL, '$user_buy', '$user_sale', '$documentid', '$price', '$time')");
}
function createsession($s) {
	$sescode = time();
	$sescode .= generate_code(8);
	$sescode = md5($sescode);
	if(!isset($_SESSION[$s])) {
		session_register ($s);
		$_SESSION[$s] = $sescode;
		return $sescode;
	} else {
		return false;
	}
}

function checksession($s,$code) {
	if(isset($_SESSION[$s]) && $_SESSION[$s] == $code) {
		unset($_SESSION[$s]);
		return true;
	} else {
		return false;
	}
}

function checkmainsess($s) {
	if (isset($_SESSION[$s])) {
		unset($_SESSION[$s]);
		return true;
	} else {
		return false;
	}
}

function delsession($s) {
	if(isset($_SESSION[$s])) {
		unset($_SESSION[$s]);
	}
}

function optioncheck($name, $check="") {
	return "<input type=\"hidden\" name=\"scheck[]\" value=\"$name\"><input type=\"checkbox\" name=\"soption[$name]\" value=\"1\" ".$check." title=\""._MARKDISPLAY."\">";
}


function checkds($check) {
	if($check =="checked") {
		return true;
	} else {
		return false;
	}
}

function signsite() {
	global $db, $prefix, $currentlang;
	list($signsite) = $db->sql_fetchrow($db->sql_query("SELECT content FROM ".$prefix."_gentext WHERE textname='sign' AND alanguage='$currentlang'"));
	return $signsite;
}
//lay link cua id
function getlink($adm_modname,$cdo) {
	return "<a href=\"".RPATH."index.php?f=".$adm_modname."&do=".$cdo."\" info=\""._GETLINK."\" onclick=\"prompt('"._GETLINK."','"."index.php?f=".$adm_modname."&do=".$cdo."'); return false;\"><img border=\"0\" src=\"images/link.png\"></a>";
}
function gen_id($table)
{
	global $db, $prefix;
	list ($id) = $db->sql_fetchrow($db->sql_query("SELECT max(id) AS id FROM ".$prefix."_".$table.""));
	if ($id == '-1') {
		$id = 1;
	}
	else {
		$id = $id + 1;
	}
	return $id;
}
function gen_permalink($permalink,$table)
{
	global $db, $prefix;
	$result = $db->sql_query("SELECT id FROM ".$prefix."_".$table." WHERE permalink='$permalink'");
	if($db->sql_numrows($result) > 0) {
		list($id) = $db->sql_fetchrow($result);
		$permalink = $permalink.'_'.$id;
	}
	return $permalink;
}
function fetch_string($str,$act="") {
	$str = str_replace('"',"''",$str);
	return $str;
}

function idHexde($str) {
	global $numshex_std;
	$nums = hexdec($str);
	$id = $nums%$numshex_std;
	$id = intval($id);
	return $id;
}


function idHexen($id) {
	global $numshex_std;
	$nums = $id+$numshex_std;
	$nums = intval($nums);
	$str = dechex($nums);
	return $str;
}

function cleanPosUrl ($str) {
	global $escape_mysql_string;
	
	$nStr = $str;
	$nStr = str_replace("**am**","&",$nStr);
	$nStr = str_replace("**pl**","+",$nStr);
	$nStr = str_replace("**eq**","=",$nStr);
	return $escape_mysql_string(trim(check_html($nStr,"nohtml")));
}
function check_docdown($id) {
	global $db, $prefix;
	$docdown ="";
	$result = $db->sql_query("SELECT price, title, link_extend,fattach,price FROM ".$prefix."_document WHERE id='$id'");
	if($db->sql_numrows($result) > 0) {
		list($price,$title, $link_extend, $fattach, $price) = $db->sql_fetchrow($result);
		if($link_extend=="" && $fattach=="" && $price==0){
			$docdown="<a href=\"http://thuvienxaydung.net/contact/request_document&t=$title.html\">"._CONTACT."</a>";
		}
		else{$docdown= $price ." EP";}
	}
	return $docdown;
}
?>