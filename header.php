<?php
include('setting.php');
header('Access-Control-Allow-Origin: *');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="vi" xml:lang="vi">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="google-site-verification" content="<?php echo $google_verification?>" />
	<meta name="author" content='<?php echo $author?>' />
	<meta name="description" content='<?php echo $description_tag?>' />
	<meta name="keywords" content="<?php echo $keyword_tag?>">
	<meta property="fb:app_id" content="1563016677287348" />
	<meta property="og:type" content='website' />
	<meta property="og:title" content='<?php echo $author?>' />
	<meta property="og:url" content='<?php echo $urlsite.$_SERVER['REQUEST_URI']?>' />
	<meta property="og:image" content='images/default.png' />
	<meta property="og:description" content='<?php echo $description_tag?>' />

	<link type="image/x-icon" rel="shortcut icon" href="<?php echo $urlsite?>/images/favicon.png">
<!-- iPad icons -->
	<link rel="apple-touch-icon-precomposed" href="<?php echo $urlsite?>/images/favicon.png" sizes="72x72">
	<link rel="apple-touch-icon-precomposed" href="<?php echo $urlsite?>/images/favicon.png" sizes="144x144">
	<!-- iPhone and iPod touch icons -->
	<link rel="apple-touch-icon-precomposed" href="<?php echo $urlsite?>/images/favicon.png" sizes="57x57">
	<link rel="apple-touch-icon-precomposed" href="<?php echo $urlsite?>/images/favicon.png" sizes="114x114">
	<!-- Nokia Symbian -->
	<link rel="nokia-touch-icon" href="<?php echo $urlsite?>/images/favicon.png">
	<!-- Android icon precomposed so it takes precedence -->
	<link rel="apple-touch-icon-precomposed" href="<?php echo $urlsite?>/images/favicon.png" sizes="1x1">

<title><?php echo $title_tag?></title>
<meta name="description" content="<?php echo $description_tag?>"/>
<meta name="robots" content="noodp"/>
<link rel="canonical" href="<?php echo $urlsite.$_SERVER['REQUEST_URI']?>" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:description" content="<?php echo $description_tag?>" />
<meta name="twitter:title" content="<?php echo $author?> - <?php echo $description?>" />
<script type='application/ld+json'>{"@context":"https:\/\/schema.org","@type":"WebSite","@id":"#website","url":"https:\/\/www.toyota.quangninh.vn\/","name":"Toyota Vi\u1ec7t Nam","alternateName":"NVL","potentialAction":{"@type":"SearchAction","target":"https:\/\/www.toyota.quangninh.vn\/?s={search_term_string}","query-input":"required name=search_term_string"}}</script>
<script type='application/ld+json'>{"@context":"https:\/\/schema.org","@type":"Organization","url":"https:\/\/www.toyota.quangninh.vn\/","sameAs":["https:\/\/www.facebook.com\/toyotaquangninhdaily","https:\/\/www.youtube.com\/user\/toyotavietnam"],"@id":"#organization","name":"Toyota VN\u1ec7t Nam Co.,Ltd.","logo":"http:\/\/www.toyota.quangninh.vn\/images\/toyota-logo-880x625.png"}</script>
<!-- / Yoast SEO plugin. -->

<link rel='dns-prefetch' href='//fonts.googleapis.com' />
<link rel='dns-prefetch' href='//s.w.org' />
		<script type="text/javascript">
			window._wpemojiSettings = {"baseUrl":"https:\/\/s.w.org\/images\/core\/emoji\/2\/72x72\/","ext":".png","svgUrl":"https:\/\/s.w.org\/images\/core\/emoji\/2\/svg\/","svgExt":".svg","source":{"concatemoji":"https:\/\/toyota.quangninh.vn\/js\/wp-emoji-release.min.js?ver=4.6.1"}};
			!function(a,b,c){function d(a){var c,d,e,f,g,h=b.createElement("canvas"),i=h.getContext&&h.getContext("2d"),j=String.fromCharCode;if(!i||!i.fillText)return!1;switch(i.textBaseline="top",i.font="600 32px Arial",a){case"flag":return i.fillText(j(55356,56806,55356,56826),0,0),!(h.toDataURL().length<3e3)&&(i.clearRect(0,0,h.width,h.height),i.fillText(j(55356,57331,65039,8205,55356,57096),0,0),c=h.toDataURL(),i.clearRect(0,0,h.width,h.height),i.fillText(j(55356,57331,55356,57096),0,0),d=h.toDataURL(),c!==d);case"diversity":return i.fillText(j(55356,57221),0,0),e=i.getImageData(16,16,1,1).data,f=e[0]+","+e[1]+","+e[2]+","+e[3],i.fillText(j(55356,57221,55356,57343),0,0),e=i.getImageData(16,16,1,1).data,g=e[0]+","+e[1]+","+e[2]+","+e[3],f!==g;case"simple":return i.fillText(j(55357,56835),0,0),0!==i.getImageData(16,16,1,1).data[0];case"unicode8":return i.fillText(j(55356,57135),0,0),0!==i.getImageData(16,16,1,1).data[0];case"unicode9":return i.fillText(j(55358,56631),0,0),0!==i.getImageData(16,16,1,1).data[0]}return!1}function e(a){var c=b.createElement("script");c.src=a,c.type="text/javascript",b.getElementsByTagName("head")[0].appendChild(c)}var f,g,h,i;for(i=Array("simple","flag","unicode8","diversity","unicode9"),c.supports={everything:!0,everythingExceptFlag:!0},h=0;h<i.length;h++)c.supports[i[h]]=d(i[h]),c.supports.everything=c.supports.everything&&c.supports[i[h]],"flag"!==i[h]&&(c.supports.everythingExceptFlag=c.supports.everythingExceptFlag&&c.supports[i[h]]);c.supports.everythingExceptFlag=c.supports.everythingExceptFlag&&!c.supports.flag,c.DOMReady=!1,c.readyCallback=function(){c.DOMReady=!0},c.supports.everything||(g=function(){c.readyCallback()},b.addEventListener?(b.addEventListener("DOMContentLoaded",g,!1),a.addEventListener("load",g,!1)):(a.attachEvent("onload",g),b.attachEvent("onreadystatechange",function(){"complete"===b.readyState&&c.readyCallback()})),f=c.source||{},f.concatemoji?e(f.concatemoji):f.wpemoji&&f.twemoji&&(e(f.twemoji),e(f.wpemoji)))}(window,document,window._wpemojiSettings);
		</script>
<!--<script type='text/javascript' src='//f.fff.com.vn/aui.js?_key=9HNCd4Da9eB3s' async='async' > </script>-->
<style type="text/css">
img.wp-smiley,
img.emoji {
	display: inline !important;
	border: none !important;
	box-shadow: none !important;
	height: 1em !important;
	width: 1em !important;
	margin: 0 .07em !important;
	vertical-align: -0.1em !important;
	background: none !important;
	padding: 0 !important;
}
</style>
<link rel='stylesheet' id='styles-css'  href='<?php echo $urlsite?>/css/style.css?ver=4.6.1' type='text/css' media='all' />
<link rel='stylesheet' id='styles-css'  href='<?php echo $urlsite?>/css/nvldealer/style.css?ver=4.4.1' type='text/css' media='all' />

<link rel='stylesheet' id='fonts-css'  href='https://fonts.googleapis.com/css?family=Open+Sans%3A300%2C300italic%2C400%2C400italic%2C700%2C700italic%2C800&#038;subset=latin%2Ccyrillic%2Cvietnamese&#038;ver=4.6.1' type='text/css' media='all' />
<script type='text/javascript' src='<?php echo $urlsite?>/js/jquery/jquery3.6.js'></script>
<script type='text/javascript' src='<?php echo $urlsite?>/js/jquery/jquery-migrate.min.js?ver=1.4.1'></script>
<script type='text/javascript' src='<?php echo $urlsite?>/js/addons.js?ver=1.0'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var wp_vars = {"ajaxurl":"\/slide-p\/admin-ajax.php","homeurl":"/","themeurl":"\/images"};
/* ]]> */
</script>
<script type='text/javascript' src='<?php echo $urlsite?>/js/scripts.js?ver=3.0'></script>
<link rel='https://api.w.org/' href='<?php echo $urlsite?>/wp-json/' />
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php echo $urlsite?>/xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php echo $urlsite?>/wlwmanifest.xml" />
<meta name="generator" content="OneCMS 3.0" />
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MDQVJGN');</script>
<!-- End Google Tag Manager -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-176552192-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-176552192-1');
</script>

<!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-XXXXX-Y', 'auto');
ga('send', 'pageview');
</script>
<!-- End Google Analytics -->

<!-- Code đặt QC Google Adsens -->
<script data-ad-client="ca-pub-5981688261434179" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

<!-- data tươi -->
<script id='ip-widget-script' type='text/javascript' src='https://taskmanagerglobal.com/ip_analytics.js?code=5a972939abc457146d7b' async></script>

</head>
<?php echo $body;?>
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MDQVJGN"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-26347818-22', 'auto');
  ga('send', 'pageview');

</script>

	<div class="header">
		<div class="container clearfix">
				<div class="logo">
				<a href="/">
					<img class="desktop" src="/images/logo1.jpg" alt="<? echo $author?>">
				<img class="mobile" src="/images/logo-mobile.png" alt="<? echo $author?>">
				</a>
			</div>

			<div class="name"><?php echo $title;?></div>
			<div class="top-head clearfix">
			<input type="text" id="searchInput" placeholder="Nhập nội dung tìm kiếm...">
			<button  class="search-submit" id="searchButton">Tìm kiếm</button>

<script>
  document.getElementById('searchButton').addEventListener('click', function() {
    var noiDungTimKiem = document.getElementById('searchInput').value;
    var urlTimKiemGoogle = "https://www.google.com/search?q=site%3A+vinfastquangninh.com.vn+" + noiDungTimKiem;
    window.location.href = urlTimKiemGoogle;
  });
</script>
				<p class="hotline clearfix">
					<a class="facebook" href="https://www.facebook.com/yentoyotaquangninh" target="blank"></a>
					<a class="cellphone" href="tel:<?php echo $hotline?>"><?php echo $hotline?></a>
				</p>
				<a id="menu_toggler" class="menu-toggler icon" href="#">
					<span></span>
					<span></span>
					<span></span>
				</a>
			</div>

			<ul id="main_menu" class="main-menu clearfix">
				<li class="menu-item-has-children no-position">
					<a href="javascript:void(0)">Sản phẩm</a>
					<ul class="sub-menu all-vehicles-menu clearfix">
			<li><a href="<?php echo $urlsite?>/vf3/"><h3>VF3</h3><span><?php echo number_format($cars[32][6])?></span> VNĐ<img src="<?php echo $urlsite?>/images/vf3360x240.png"><div class="highlight"></div></a></li>
			<li><a href="<?php echo $urlsite?>/avanza/"><h3>VF5</h3><span><?php echo number_format($cars[45][6])?></span> VNĐ<img src="<?php echo $urlsite?>/images/vf5360x240.png"><div class="highlight"></div></a></li>
            <li><a href="<?php echo $urlsite?>/veloz/"><h3>VF6</h3><span><?php echo number_format($cars[43][6])?></span> VNĐ<img src="<?php echo $urlsite?>/images/vf6360x240.png"><div class="highlight"></div></a></li>
            <li><a href="<?php echo $urlsite?>/vios/"><h3>VF7</h3><span><?php echo number_format($cars[5][6])?></span> VNĐ<img src="<?php echo $urlsite?>/images/vf7360x240.png"><div class="highlight"></div></a></li>            
            <li><a href="<?php echo $urlsite?>/raize/"><h3>VF8</h3><span><?php echo number_format($cars[40][6])?></span> VNĐ<img src="<?php echo $urlsite?>/images/vf8360x240.png"><div class="highlight"></div></a></li>
			<li><a href="<?php echo $urlsite?>/yaris/"><h3>VF9</h3><span><?php echo number_format($cars[0][6])?></span> VNĐ<img src="<?php echo $urlsite?>/images/vf9360x240.png"><div class="highlight"></div></a></li>
		    	</ul>
		</li>
	<li id="menu-item-25" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-25"><a href="#">Mua xe</a>
<ul class="sub-menu">
	<li id="menu-item-26" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-26"><a href="<?php echo $urlsite?>/bang-gia-xe/">Bảng giá xe</a></li>
	<li id="menu-item-27" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-27"><a href="<?php echo $urlsite?>/tim-duong-di/">Tìm đường đi</a></li>
	<li id="menu-item-28" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-28"><a href="<?php echo $urlsite?>/du-toan-chi-phi/">Dự toán chi phí</a></li>
	<li id="menu-item-29" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-29"><a href="<?php echo $urlsite?>/cach-tinh-lai-suat-cho-vay-mua-xe-oto-tra-gop/" target="_blank">Cách tính mua xe trả góp</a></li>
	<li id="menu-item-30" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-30"><a href="<?php echo $urlsite?>/news/tuyen-dung/" target="_blank">Xe cũ đã qua sử dụng</a></li>
</ul>
</li>
<li id="menu-item-92" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-92"><a href="#">Dịch vụ</a>
<ul class="sub-menu">
	<li id="menu-item-93" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-93"><a href="<?php echo $urlsite?>/dich-vu-sua-chua/">Dịch vụ sửa chữa</a></li>
	<li id="menu-item-93" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-93"><a href="<?php echo $urlsite?>/bao-duong-dinh-ky/">Bảo dưỡng định kỳ</a></li>
	<!--<li id="menu-item-94" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-94"><a href="#">Đặt lịch sửa chữa</a></li>
	<li id="menu-item-95" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-95"><a href="<?php echo $urlsite?>/index.php?page=phu-tung">Phụ tùng chính hãng</a></li>
	<li id="menu-item-96" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-96"><a href="<?php echo $urlsite?>/index.php?page=bao-hanh">Bảo hành</a></li>
	<li id="menu-item-97" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-97"><a href="<?php echo $urlsite?>/index.php?page=hoi-dap">Hỏi đáp</a></li>-->
</ul>
</li>
<li id="menu-item-98" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-98"><a href="#">Giới thiệu</a>
<ul class="sub-menu">
	<li id="menu-item-99" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-99"><a href="<?php echo $urlsite?>/gioi-thieu-toyota-quang-ninh/">Toyota Quảng Ninh</a></li>
	<!--<li id="menu-item-100" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-100"><a href="#">Thông điệp từ Ban quản trị</a></li>
	<li id="menu-item-258" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-258"><a href="#">Hình ảnh Đại lý</a></li>-->
</ul>
</li><!--
<li id="menu-item-101" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-101"><a href="#">Công nghệ</a>
<ul class="sub-menu">
	<li id="menu-item-102" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-102"><a href="<?php echo $urlsite?>/index.php?page=an-toan">An toàn</a></li>
	<li id="menu-item-103" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-103"><a href="<?php echo $urlsite?>/index.php?page=giai-tri">Giải trí</a></li>
	<li id="menu-item-104" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-104"><a href="<?php echo $urlsite?>/index.php?page=chat-luong">Chất lượng</a></li>
</ul>
</li>-->
<li id="menu-item-105" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-105"><a href="<?php echo $urlsite?>/news/">Tin tức</a>
<ul class="sub-menu">
	<li id="menu-item-181" class="menu-item menu-item-type-taxonomy menu-item-object-news_category menu-item-181"><a target="_blank" href="<?php echo $urlsite?>/news/tin-tuc-su-kien/">Tin tức &#038; Sự kiện</a></li>
	<li id="menu-item-180" class="menu-item menu-item-type-taxonomy menu-item-object-news_category menu-item-180"><a target="_blank" href="<?php echo $urlsite?>/news/khuyen-mai/">Khuyến mãi</a></li>
	<li id="menu-item-182" class="menu-item menu-item-type-taxonomy menu-item-object-news_category menu-item-182"><a target="_blank" href="<?php echo $urlsite?>/news/tuyen-dung/">Xe cũ đã qua sử dụng</a></li>
	
</ul>
</li>
<li id="menu-item-6514" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-6514"><a href="<?php echo $urlsite?>/lien-he/">Liên hệ</a></li>
			</ul>
		</div>
	</div>
