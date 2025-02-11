<div class="main">
	<div id="home_slider" class="home-slider">
		<ul class="slides">

        <li class="flex-active-slide" style="width: 100%; float: left; margin-right: -100%; position: relative; opacity: 1; display: block; z-index: 2;">
		    <div><a href="#"><img class="owl-lazy" src="<?php echo $urlsite?>/images/slide/slide campain 1.25.jpg"/></a></div>
		</li>
		
        <li class="flex-active-slide" style="width: 100%; float: left; margin-right: -100%; position: relative; opacity: 1; display: block; z-index: 2;">
		    <div><a href="#"><img class="owl-lazy" src="<?php echo $urlsite?>/images/slide/vf3banner.jpg"/></a></div>
		</li>
		
		<li class="flex-active-slide" style="width: 100%; float: left; margin-right: -100%; position: relative; opacity: 1; display: block; z-index: 2;">
		    <div><a href="#"><img class="owl-lazy" src="<?php echo $urlsite?>/images/slide/vf5banner.jpg"/></a></div>
		</li>
		
		
        <li class="flex-active-slide" style="width: 100%; float: left; margin-right: -100%; position: relative; opacity: 1; display: block; z-index: 2;">
		    <div><a href="#"><img class="owl-lazy" src="<?php echo $urlsite?>/images/slide/vf7banner.jpg"/></a></div>
		</li>
        
        
     	<li class="flex-active-slide" style="width: 100%; float: left; margin-right: -100%; position: relative; opacity: 1; display: block; z-index: 2;">
			<div><a href="#"><img class="owl-lazy" src="<?php echo $urlsite?>/images/slide/vf9banner.jpg"/></a></div>
		</li>
		<!--<li style="background-image:url(<?php echo $urlsite?>/images/slide/khuyen_mai_112019.jpg);">
				<div class="container"><a class="button" href="#" target="blank" style="left:80%; top:77%;" >TÌM HIỂU THÊM</a></div></li>
			<li style="background-image:url(<?php echo $urlsite?>/images/slide/khuyenmaithang112019.png?v=29102016);">
				<div class="container"><a class="button" href="http://toyota.quangninh.vn/bang-gia-xe/" target="blank" style="left:80%; top:77%;" >TÌM HIỂU THÊM</a></div></li>
				<li style="background-image:url(<?php echo $urlsite?>/images/slide/BN_DEALER-WEBSITE_7.9.jpg);"><div class="container"><a class="button" href="http://facebook.com/toyotaquangninhdaily" target="blank" style="left:75%; top:85%;" >TÌM HIỂU THÊM</a></div></li>
		<li style="background-image:url(<?php echo $urlsite?>/images/slide/innova_slide.jpg?v=29102016);">
				<div class="container"><a class="button" href="http://toyota.quangninh.vn/innova/" target="blank" style="left:9%; top:77%;" >TÌM HIỂU THÊM</a></div></li>
				<li style="background-image:url(<?php echo $urlsite?>/images/slide/vios_slide.jpg?v=29102016);">
				<div class="container"><a class="button" href="http://toyota.quangninh.vn/vios/" target="blank" style="left:9%; top:77%;" >TÌM HIỂU THÊM</a></div></li>
				<li style="background-image:url(<?php echo $urlsite?>/images/slide/corolla_slide.jpg?v=29102016);">
				<div class="container"><a class="button" href="http://toyota.quangninh.vn/corolla-altis/" target="blank" style="left:9%; top:77%;" >TÌM HIỂU THÊM</a></div></li>
			<li style="background-image:url(<?php echo $urlsite?>/images/slide/Homepage-VIMS-2017.jpg?v=29102016);">
				<div class="container"><a class="button" href="http://toyota.quangninh.vn/fortuner/" target="blank" style="left:84%; top:87%;" >TÌM HIỂU THÊM</a></div></li>-->
	</div>
	<div class="quick-button">
		<ul class="container clearfix">
			<li class="bang-gia-xe"><a href="<?php echo $urlsite?>/bang-gia-xe/">Bảng giá xe</a></li>
			<li class="tim-duong-di"><a href="<?php echo $urlsite?>/tim-duong-di/">Tìm đường đi</a></li>
			<li class="du-toan-chi-phi"><a target="_blank" href="<?php echo $urlsite?>/du-toan-chi-phi/">Dự toán chi phí</a></li>
			<li class="dang-ky-lai-thu"><a target="_blank" href="<?php echo $urlsite?>/cach-tinh-lai-suat-cho-vay-mua-xe-oto-tra-gop/">Mua xe trả góp</a></li>
			<!--<li class="dat-lich-sua-chua"><a href="#">Đặt lịch sửa chữa</a></li>-->
		</ul>
	</div>
	<div class="container home-news">
		<ul class="categories clearfix">
			<li class="current"><a>Tất cả</a></li>
			<li class="normal"><a target="_blank" href="<?php echo $urlsite;?>/news/khuyen-mai/">Khuyến mãi</a></li><li class="normal"><a target="_blank"href="<?php echo $urlsite;?>/news/tin-tuc-su-kien/">Tin tức &amp; Sự kiện</a></li>
			<!--<li class="normal"><a href="<?php echo $urlsite;?>/news/tuyen-dung">Tuyển dụng</a></li>-->
			</ul>
<?php
$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE active=1 AND  alanguage='$currentlang' ORDER BY time DESC LIMIT 8");
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
$a=0;
?>
	<div class="post clearfix">
	<?php
	while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) {
		$hometext = preg_replace("/<.*?>/", "", $hometext);
			$get_path = get_path($time);
			$path_upload_img = "$path_upload/news/$get_path";
			$path_upload_img2 = "$path_upload/news";
			$css='';
			if($imageslast !="" && file_exists("$path_upload_img/$imageslast"))
			{
				$imageslast= resize_image($titlelast, $imageslast, $path_upload_img, $path_upload_img2, $css, 540,270);
			}
			else
			{
				$imageslast= resize_image($titlelast, 'no_image.gif', 'images', $path_upload_img2, $css, 540,270);
			}
			if($a==1) $style_padding_bottom='style="overflow: hidden;"';
			else $style_padding_bottom='style="padding-bottom:8px;overflow: hidden"';
		?>
		<div class="item">
			<a target="_blank" href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo $imageslast?>
			<!--img class="attachment-banner size-banner wp-post-image"--></a>
				<time><?php echo date("d/m/Y",$time);?></time>
				<h2><a title="<?php echo $titlelast?>" href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo CutString($titlelast,150)?></a></h2>
	</div>
		<?php
		$a++;
	}
	?>
	</div>
<?php
}?>
	</div>
	<div class="home-social">
		<div class="container clearfix">
			<div class="column video">
				<div class="video-wrapper">
					<iframe width="560" height="315" src="https://www.youtube.com/embed/SsKIHKKm8fw?si=NCZc9gxcC1jjRf99" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
				</div>
			</div>
			<div class="column feeds">
				<div class="item facebook">
					<a class="favicon" target="blank" href="https://www.facebook.com/yentoyotaquangninh" title="Toyota Vietnam on Facebook">Facebook</a>
					<h3><a href="https://www.facebook.com/yentoyotaquangninh" target="blank">Vinfast Bãi Cháy's fanpage</a></h3>
					<p>Facebook chính thức của Vinfast Bãi Cháy. Dành cho mọi người yêu và quan tâm đến xe hơi và thương hiệu Vinfast.</p>
					<div class="plugin">
						<div class="fb-like" data-href="https://www.facebook.com/yentoyotaquangninh" data-width="100%" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
					</div>
				</div>
				<div class="item youtube">
					<a class="favicon" target="blank" href="https://www.youtube.com/channel/UCOufMvS1vslunVPW1qPxlNQ?view_as=subscriber" title="Toyota Vietnam on YouTube">YouTube</a>
					<h3><a href="https://www.youtube.com/channel/UCOufMvS1vslunVPW1qPxlNQ?view_as=subscriber" target="blank">Vinfast Bãi Cháy's channel</a></h3>
					<p>Tìm hiểu thêm về sản phẩm của Vinfast Bãi Cháy qua các video giới thiệu của chúng tôi.</p>
					<div class="plugin">
						<script src="https://apis.google.com/js/platform.js"></script>
						<!--<div class="g-ytsubscribe" data-channel="toyotavietnam"></div>-->
					</div>
				</div>
				<div class="item twitter">
					<a class="favicon" target="blank" href="https://x.com/VinFastofficial/" title="Toyota Vietnam on Twitter">Twitter</a>
					<h3><a href="https://x.com/VinFastofficial/" target="blank">@VinFastofficial/</a></h3>
					<p>Theo dõi Vinfast trên Twitter.</p>
					<div class="plugin">
						<a class="twitter-follow-button" href="http://twitter.com/toyotavietnam" data-show-count="false" data-show-screen-name="false" data-lang="en"></a>
						<script>window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));</script>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
