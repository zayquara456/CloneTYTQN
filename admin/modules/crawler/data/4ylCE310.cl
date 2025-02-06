<?php
/*
 Filter Vnexpress.net
 Create by vinhquangvip
 26-12-2012
 v 1.0
*/
$source_view = "http://dantri.com.vn/xa-hoi.htm";
$begin	=	'#(.*)<div class="fl wid470">#is';
$end	= 	'#<div class="fl wid310 box5 admicro">(.*)#is';
$reg["begin"]	=	'#(.*)<div class="fl wid470">#is';
$reg["end"]	= 	'#<div class="fl wid310 box5 admicro">(.*)#is';
$reg["page"] = '#<h2><a title="([^>]*)" class="fon6" href="([^>]*)">([^>]*)</a></h2>#is';
$reg["description"] = '#<h2 class="fon33 mt1">(.*)</h2>#is';
$reg["title"] = '#<H1 class=Title(.*)>(.*)</H1>#i';
$reg["content"] = '#<div class="fon34 mt3 mr2 fon43">(.*)<input type=\'hidden\' value=\'(.*)\' id=\'hidNextUsing\'/>#is';
$reg["tag"] = '';
$reg["image"] = '#<img(.*)src="(.*?)"(.*)>#is';
$replace["title"] = array();
$replace["description"] = array();
$replace["content"] = array();
$page["title"] = 3;
$page["link"] = 2;
$page["description"]=1;
$page["tag"]=1;
$page["content"]=1;
$page["image"]=1;
$page["url_image"]="";
?>