<?php
function show_user($id)
{
	global $db, $prefix;

	$result = $db->sql_query("SELECT fullname FROM ".$prefix."_user WHERE id='$id'");
	if($db->sql_numrows($result) > 0 ) 
	{
		list($fullname) = $db->sql_fetchrow($result);
		return "$fullname";
	}
	else{return "<strong>*</strong>";}
}
function count_document_byuserid($id)
{
	global $db, $prefix;

	$sqltotal="SELECT COUNT(*) AS doc FROM ".$prefix."_document WHERE user_id='$id'";
		$resulttotal = $db->sql_query($sqltotal);
		if($db->sql_numrows($resulttotal) > 0) {
			list($doc) = $db->sql_fetchrow($resulttotal);
			return $doc;
		}
		else{return "<strong>*</strong>";}
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
function show_chietkhau($telecom){
	global $chieukhau_arr;
	$chietkhau="";
	foreach($chieukhau_arr as $key => $value)
	{
		if($telecom==$value){$chietkhau=$key;}
	}
	return $chietkhau;
}
function show_document($id)
{
	global $db, $prefix;

	$result = $db->sql_query("SELECT title FROM ".$prefix."_document WHERE id='$id'");
	if($db->sql_numrows($result) > 0 ) 
	{
		list($title) = $db->sql_fetchrow($result);
		return "$title";
	}
	else{return "<strong>*</strong>";}
}
?>