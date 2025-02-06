<?php

if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) { die('Stop!!!'); }

function url_sid($s,$act="",$value="")
{
	global $db,$f, $do, $rewrite_mod, $urlsite, $prefix;
	$urlin	= array();
	$urlout	= array();

	//permalink for news detail
	if(strpos($s,"f=news&do=detail")==true){
		$query_news = "SELECT n.permalink, c.permalink FROM ".$prefix."_news AS n,".$prefix."_news_cat AS c WHERE n.guid='$s' AND n.catid=c.catid";
		$result_news = $db->sql_query($query_news);
		list($permalink,$cpermalink) = $db->sql_fetchrow($result_news);
		$_urlin = array("'(?<!/)index.php\?f=news&do=detail&id=([0-9]*)'");
		$_urlout = array("$urlsite/".$cpermalink."/".$permalink.".html");
		$urlin = array_merge($urlin,$_urlin);
		$urlout = array_merge($urlout,$_urlout);
	}
	//permalink for news category
	if(strpos($s,"f=news&do=categories")==true){
		$query_newscat = "SELECT permalink, catid FROM ".$prefix."_news_cat WHERE guid='$s'";
		$result_newscat = $db->sql_query($query_newscat);
		list($permalink_cat, $catid) = $db->sql_fetchrow($result_newscat);
		$_urlin = array(
			"'(?<!/)index.php\?f=news&do=categories&id=([0-9]*)&page=([0-9]*)'",	
			"'(?<!/)index.php\?f=news&do=categories&id=([0-9]*)'"
		);
	
		$_urlout = array(
			"$urlsite/".$permalink_cat."/page/\\2/",
			"$urlsite/".$permalink_cat."/"
		);
		$urlin = array_merge($urlin,$_urlin);
		$urlout = array_merge($urlout,$_urlout);
	}
	//permalink for document detail
	if(strpos($s,"f=document&do=detail")==true){
		$query = "SELECT n.permalink, c.permalink FROM ".$prefix."_document AS n,".$prefix."_document_cat AS c WHERE n.guid='$s' AND n.catid=c.catid";
		$result = $db->sql_query($query);
		list($permalink,$cpermalink) = $db->sql_fetchrow($result);
		$_urlin = array("'(?<!/)index.php\?f=document&do=detail&id=([0-9]*)'");
		$_urlout = array("$urlsite/document/".$cpermalink."/".$permalink.".html");
		$urlin = array_merge($urlin,$_urlin);
		$urlout = array_merge($urlout,$_urlout);
	}
	//permalink for document category
	if(strpos($s,"f=document&do=categories")==true){
		$query_newscat = "SELECT permalink, catid FROM ".$prefix."_document_cat WHERE guid='$s'";
		$result_newscat = $db->sql_query($query_newscat);
		list($permalink_cat, $catid) = $db->sql_fetchrow($result_newscat);
		$_urlin = array(
			"'(?<!/)index.php\?f=document&do=categories&id=([0-9]*)&page=([0-9]*)'",	
			"'(?<!/)index.php\?f=document&do=categories&id=([0-9]*)'"
		);
	
		$_urlout = array(
			"$urlsite/document/".$permalink_cat."/page/\\2/",
			"$urlsite/document/".$permalink_cat."/"
		);
		$urlin = array_merge($urlin,$_urlin);
		$urlout = array_merge($urlout,$_urlout);
	}
	//permalink for question detail
	if(strpos($s,"f=question&do=detail")==true){
		$query = "SELECT n.permalink, c.permalink FROM ".$prefix."_question AS n,".$prefix."_question_cat AS c WHERE n.guid='$s' AND n.catid=c.catid";
		$result = $db->sql_query($query);
		list($permalink,$cpermalink) = $db->sql_fetchrow($result);
		$_urlin = array("'(?<!/)index.php\?f=question&do=detail&id=([0-9]*)'");
		$_urlout = array("$urlsite/question/".$cpermalink."/".$permalink.".html");
		$urlin = array_merge($urlin,$_urlin);
		$urlout = array_merge($urlout,$_urlout);
	}
	//permalink for video category
	if(strpos($s,"f=video&do=categories")==true){
		$query_newscat = "SELECT permalink, catid FROM ".$prefix."_video_cat WHERE guid='$s'";
		$result_newscat = $db->sql_query($query_newscat);
		list($permalink_cat, $catid) = $db->sql_fetchrow($result_newscat);
		$_urlin = array(
			"'(?<!/)index.php\?f=video&do=categories&id=([0-9]*)&page=([0-9]*)'",	
			"'(?<!/)index.php\?f=video&do=categories&id=([0-9]*)'"
		);
	
		$_urlout = array(
			"$urlsite/video/".$permalink_cat."/page/\\2/",
			"$urlsite/video/".$permalink_cat."/"
		);
		$urlin = array_merge($urlin,$_urlin);
		$urlout = array_merge($urlout,$_urlout);
	}
	//permalink for video detail
	if(strpos($s,"f=video&do=detail")==true){
		$query = "SELECT n.permalink, c.permalink FROM ".$prefix."_video AS n,".$prefix."_video_cat AS c WHERE n.guid='$s' AND n.catid=c.catid";
		$result = $db->sql_query($query);
		list($permalink,$cpermalink) = $db->sql_fetchrow($result);
		$_urlin = array("'(?<!/)index.php\?f=video&do=detail&id=([0-9]*)'");
		$_urlout = array("$urlsite/video/".$cpermalink."/".$permalink.".html");
		$urlin = array_merge($urlin,$_urlin);
		$urlout = array_merge($urlout,$_urlout);
	}
	//permalink for video category
	if(strpos($s,"f=video&do=categories")==true){
		$query_newscat = "SELECT permalink, catid FROM ".$prefix."_video_cat WHERE guid='$s'";
		$result_newscat = $db->sql_query($query_newscat);
		list($permalink_cat, $catid) = $db->sql_fetchrow($result_newscat);
		$_urlin = array(
			"'(?<!/)index.php\?f=video&do=categories&id=([0-9]*)&page=([0-9]*)'",	
			"'(?<!/)index.php\?f=video&do=categories&id=([0-9]*)'"
		);
	
		$_urlout = array(
			"$urlsite/video/".$permalink_cat."/page/\\2/",
			"$urlsite/video/".$permalink_cat."/"
		);
		$urlin = array_merge($urlin,$_urlin);
		$urlout = array_merge($urlout,$_urlout);
	}
	$_urlin = array(
		"'(?<!/)index.php\?f=user&do=(.*)'",
		"'(?<!/)index.php\?f=user&do=(.*)&id=(.*)'",
		"'(?<!/)index.php\?f=document&do=download&u=(.*)'",
		"'(?<!/)index.php\?f=document&do=download_extend&u=(.*)'",
		"'(?<!/)index.php\?f=document&do=tags&tag=(.*)'",//6
		"'(?<!/)index.php\?f=document&do=print&id=([0-9]*)'",
		"'(?<!/)index.php\?f=document&do=(.*)'",
		"'(?<!/)index.php\?f=document'",
		"'(?<!/)index.php\?f=video'",
		"'(?<!/)index.php\?f=question&do=(.*)'",
		"'(?<!/)index.php\?f=question'",
		"'(?<!/)index.php\?f=contact&do=(.*)'",
		"'(?<!/)index.php\?f=contact'",
		"'(?<!/)index.php\?f=news&do=print&id=([0-9]*)'",
		"'(?<!/)index.php\?f=news&do=tags&tag=(.*)'",//6
		"'(?<!/)index.php'",
		"'(?<!/)search.php'",
		"'(?<!/)document.php'"
	);
	$_urlout = array(
		"$urlsite/user/\\1.html",
		"$urlsite/user/\\1\\/2.html",
		"$urlsite/document/download/\\1",
		"$urlsite/document/download_extend/\\1",
		"$urlsite/document/tag/\\1",//6
		"$urlsite/document/print/\\1",//6
		"$urlsite/document/\\1",//6
		"$urlsite/document/",
		"$urlsite/video/",
		"$urlsite/question/\\1",//6
		"$urlsite/question/",
		"$urlsite/contact/\\1.html",//8
		"$urlsite/contact/",
		"$urlsite/print/\\1.html",//8
		"$urlsite/tag/\\1",//6
		"$urlsite",
		"$urlsite/search.php",
		"$urlsite/document.php"
	);
	$urlin = array_merge($urlin,$_urlin);
	$urlout = array_merge($urlout,$_urlout);
	$s=$s.$value;
	if($rewrite_mod == 1) {
		if($act == 1 || ($act != 1 && !defined('CMS_ADMIN'))) {
			$s = preg_replace($urlin, $urlout, $s);
		}
	}
	return $s;
}

?>