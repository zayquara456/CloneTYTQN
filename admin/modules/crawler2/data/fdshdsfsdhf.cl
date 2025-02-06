<?php
/*
 Filter Vnexpress.net
 Create by vinhquangvip
 26-12-2012
 v 1.0
*/
$begin	=	'#(.*)<div class="content">#is';
$end	= 	'#<div class="Div_OtherNews" style="margin-left:10px;width: 490px;">(.*)#is';
$title	=	'/<a href=\"([^>]*)\" class="link-title14">([^>]*)<\/a>/i';
$reg["description"] = "/<H2 class=Lead(.*?)>(.*?)<\/H2>/";
$reg["title"] = '#<H1 class=Title(.*)>(.*)</H1>#i';
$reg["content"] =	"";
$reg["tag"] = "";
$reg["image"] = '#<IMG src="(.*?)"(.*)\/>#i';
$arr_replace = array(
	// remove doan tren
	'#(.*)<div class="rightEP">#is',
	// Remove doan duoi
	'#<div style="width:500px;display:block;overflow:hidden;float:left;height:35px;padding-top:8px">(.*)#is',
	//Bo div du thua
	'#<div class="Div_OtherNews1 fl">(.*)#is',
	'#<div class="box-item" style="margin-top:5px;margin-bottom:5px;">(.*)#is',
	'#<div style="width:500px;display:block;float:left;height:35px;padding-top:8px">(.*)#is',
	'#<script language="javascript">(.*)#is',
	'#<a href="(.*?)" class="Lead">(.*?)<\/a>#is',
	'#<a (.*?)">#is',
	'#<\/a>#',
	'#<BR>>#'
);
?>