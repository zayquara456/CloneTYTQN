<?php
if (!file_exists("config.php")) exit();
define('CMS_SYSTEM', true);

@require_once("config.php");

//lua chon trang hien thi
$pages = $_GET['page'];
$post = isset($_GET['post']) ? intval($_GET['post']) : 0;
if($post!='0'){
	if($pages=='news'){
		$body = '<body class="archive post-type-archive post-type-archive-news lang-vi">';
	}
	elseif($pages=='news_detail'){
		$body = '<body class="archive post-type-archive post-type-archive-news lang-vi">';

	}
	else{
		$body = '<body class="vehicle-template-default single single-vehicle postid-'.$post.' lang-vi">';
	}
}
elseif($post=0){
	$body = '<body class="page page-id-9 page-child parent-pageid-2 page-template page-template-pages page-template-price-calculator page-template-pagesprice-calculator-php">';

}
else{
	if($pages=='news'){
		$body = '<body class="archive post-type-archive post-type-archive-news lang-vi">';
	}
	elseif($pages=='news_detail'){
		$body = '<body class="archive post-type-archive post-type-archive-news lang-vi">';
	}
	elseif($pages=='du-toan-chi-phi'){
		$body = '<body class="page-template page-template-pages page-template-price-calculator page-template-pagesprice-calculator-php page page-id-1314 page-child parent-pageid-19845 lang-vi">';
	}
	elseif($pages=='cach-tinh-lai-suat-cho-vay-mua-xe-oto-tra-gop'){
		$body = '<body class="page-template page-template-pages page-template-price-calculator page-template-pagesloan-calculator-php page page-id-1314 page-child parent-pageid-19845 lang-vi">';
	}
	else{
		$body = '<body class="home blog lang-vi">';
	}

}
//<body class="news-template-default single single-news postid-20927 lang-vi">
//<body class="archive post-type-archive post-type-archive-news lang-vi">
include('header.php');
$linkinclude='';
$linkinclude = str_replace('vehicle-','vehicle/',$pages);
$linkinclude = $linkinclude.".php";
if (file_exists($linkinclude)) {
	include($linkinclude);
}
else{
	$linkinclude = "pages/".$pages.".php";
	if (file_exists($linkinclude)) {
		include($linkinclude);
	}
	else{
		include("pages/home.php");
	}
}
include('footer.php');


?>
