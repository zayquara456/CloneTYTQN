<?php
/*
 Filter Vnexpress.net
 Create by vinhquangvip
 26-12-2012
 v 1.0
*/
$source_view = "http://vnexpress.net/gl/xa-hoi/";
$begin	=	'#(.*)<div class="bgLeftWhite">#is';
$end	= 	'#<div class="divpage">(.*)#is';
$reg["begin"]	=	'#(.*)<div class="content">#is';
$reg["end"]	= 	'#<div class="content-left fl" style="padding-top:10px">(.*)#is';

//$reg["page"] = '#<h2 class="h2Title-14"><a href="(.*?)" class="link-title14">(.*?)</a></h2>#is';
$reg["page"] = '#<a href="([^>]*)" class="link-title14">([^>]*)</a>#is';
$reg["description"] = '#<H2 class=Lead>(.*?)<\/H2>#is';
$reg["title"] = '#<h1 class="Title">(.*?)</h1>#is';
$reg["content"] = '#<H2 class=Lead>(.*?)</H2>(.*?)<div class="likesubject fl">#is';
$reg["tag"] = '';
$reg["image"] = '#<img(.*)src="(.*?)"(.*)>#is';
$replace["title"] = array();
$replace["description"] = array();
$replace["content"] = array();
$page["title"]=2;
$page["link"]=1;
$page["description"]=1;
$page["tag"]=1;
$page["content"]=2;
$page["image"]=2;
$page["url_image"]="http://vnexpress.net";
?>