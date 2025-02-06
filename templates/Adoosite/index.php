<?php
$imgFold = "$urlsite/templates/{$Default_Temp}/images";
function show_menu()
{
	global $db, $currentlang, $prefix, $home, $urlsite, $userInfo, $path_upload;
	$bucmenu = isset($_GET['c']) ? $_GET['c'] : "";
	$taobucmenu = isset($_GET['t']) ? $_GET['t'] : "";
	$result_menu = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='main_menu' AND  parentid='0' AND active='1' and alanguage='$currentlang' Order by weight asc");
	if($db->sql_numrows($result_menu) > 0) 
	{
		$i=1;
		echo "<div class=\"menu-main\">\n";
		echo "<ul id=\"menungang\" class=\"sf-menu\">\n";
		while (list($mid, $title_menu, $url_menu) = $db->sql_fetchrow($result_menu)) 
		{
			$result_check = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='main_menu' AND parentid=$mid and active=1 and alanguage='$currentlang' order by weight asc");
			if($_SERVER["REQUEST_URI"]==url_sid($url_menu))
				$current='class="current"';
			else
				$current='';
			echo "<li ".$current."><a href=\"".url_sid($url_menu)."\" class=\"nav_link\" title=\"$title_menu\">$title_menu</a>\n";
			$result_sub = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='main_menu' AND parentid=$mid and active=1 and alanguage='$currentlang' order by weight asc");
		if($db->sql_numrows($result_sub) > 0) 
		{
			echo "<ul>\n";
			while (list($mid_sub, $title_sub, $url_sub) = $db->sql_fetchrow($result_sub)) 
			{
				echo "<li  id=\"navh-2\"><a title=\"$title_sub\" href=\"".url_sid($url_sub)."\">$title_sub</a>\n";
				$result_sub2 = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='main_menu' AND parentid=$mid_sub and active=1 and alanguage='$currentlang' order by weight asc");
				if($db->sql_numrows($result_sub2) > 0) 
				{
					echo "<ul>\n";
					while (list($mid_sub2, $title_sub2, $url_sub2) = $db->sql_fetchrow($result_sub2)) 
					{
						echo "<li><a title=\"$title_sub2\" href=\"".url_sid($url_sub2)."\">$title_sub2</a></li>\n";
					}
					echo "</ul>\n";
				}
				echo "</li>\n";
			}
			echo "</ul>\n";
		}
		$i++;
		echo "</li>\n";
		
		}
		
	}
	echo "</ul>\n";
	echo "</div>";
	echo '<div class="cl"></div>';
	echo "<div class=\"thirdMenu\">";
	echo "<div class=\"header fl\">"._NEWS_NEW."</div>";
	echo "<div class=\"title fl\">";
	echo '<div class="nav-news"><a href="#" id="prev1"><span>Prev</span></a> <a href="#" id="next1"><span>Next</span></a></div>';
	?>
	<div class="cynewbox" id="cynewbox"><ul>
		<?php
		$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE active='1' AND alanguage='$currentlang' ORDER BY RAND() DESC LIMIT 8");
		$numrows = $db->sql_numrows($result_lastnew);
		if($numrows > 0) {
			?>
			<?php
			while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
			{
			?>
					<li><a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo $titlelast?></a></li>
					
			<?php
			}
		}
		?>
		</ul></div>		
	<?php
	echo "</div>";
	echo "<div id=\"header-search\" class=\"fr\">";
	echo "<form name=\"formSearch\" enctype=\"application/x-www-form-urlencoded\" action=\"".url_sid("search.php")."\" method=\"POST\" onsubmit=\"return validate_search(this);\">";
	echo "<div class=\"header-search\"><input type=\"submit\" class=\"button-search\" value=\"&nbsp;\" class=\"search-block\"  /><input type=\"text\" name=\"q\" class=\"inputkey\" id=\"q\" value=\""._SEARCH."\" onclick = \"selectText_b()\" onblur=\"addText_b()\" /></div></form>";
	echo "</div>";
	echo '<div class="nav-time fr"><span id="clock"></span></div>';
	echo '<div  style="padding:6px 0px 0px 0px; height:20px; width:106px" class="fr"><div class="fb-like" data-href="'.$urlsite.'/'.$_SERVER['REQUEST_URI'].'" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div></div>';
	echo '<div class="cl"></div>';
	echo "</div>";
	echo "<div class=\"cl of\"></div>";	
}
function show_menu_footer()
{
global $db, $currentlang, $prefix, $userInfo;
$result_menu = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='footer_menu' AND active='1' and alanguage='$currentlang' Order by weight asc");
if($db->sql_numrows($result_menu) > 0) 
{
	echo "<div id=\"navbottom\">\n";
	echo "<ul>\n";
	
	while (list($mid, $title_menu, $url_menu) = $db->sql_fetchrow($result_menu)) 
	{
		echo "<li><a title=\"$title_menu\" href=\"".url_sid($url_menu)."\">$title_menu</a></li>\n";
	}
	echo "</ul>\n";
	echo "</div>\n";		
	}	
}
// ham hien thi noi dung tin noi bat
function show_newhot()
{
	global $db, $currentlang, $prefix, $home, $urlsite, $userInfo, $path_upload;
?>
<div class="hotnews">
	<div class="hotnews-content fl">
		<div class="style1" id="style1"><ul>
		<?php
		$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE special=1 AND active='1' AND alanguage='$currentlang' ORDER BY time DESC LIMIT 5");
		$numrows = $db->sql_numrows($result_lastnew);
		if($numrows > 0) {
			$a=0;
			?>
			<?php
			while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
			{
				$hometext = preg_replace("/<.*?>/", "", $hometext);
				$a=$idlast;
				$get_path = get_path($time);
				$path_upload_img = "$path_upload/news/$get_path";
				$path_upload_img2 = "$path_upload/news";
				if($imageslast !="" && file_exists("$path_upload_img/$imageslast")) 
				{
					$imageslast= resize_image($titlelast, $imageslast, $path_upload_img, $path_upload_img2, 400,290);
				}
				else
				{
					$imageslast= resize_image($titlelast, 'no_image.gif', 'images', $path_upload_img2, 400,290);
				}
				?>
					<li>
						<a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo $imageslast?></a>
						<h1><a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo $titlelast?></a></h1>
					</li>
					
		<?php
			}
		}
		?>
		</ul></div>
		<div class="style2" id="style2"><ul>
		<?php
		$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE special=1 AND active='1' AND  id < $a  AND alanguage='$currentlang' ORDER BY time DESC LIMIT 5");
		$numrows = $db->sql_numrows($result_lastnew);
		if($numrows > 0) {
			?>
			<?php
			while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
			{
				$a=$idlast;
				$get_path = get_path($time);
				$path_upload_img = "$path_upload/news/$get_path";
				$path_upload_img2 = "$path_upload/news";
				if($imageslast !="" && file_exists("$path_upload_img/$imageslast")) 
				{
					$imageslast= resize_image($titlelast, $imageslast, $path_upload_img, $path_upload_img2, 270,160);
				}
				else
				{
					$imageslast= resize_image($titlelast, 'no_image.gif', 'images', $path_upload_img2, 270,160);
				}
				?>
					<li>
						<a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo $imageslast?></a>
						<h2><a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo $titlelast?></a></h2>
					</li>
					
		<?php
			}
		}
		?>
		</ul></div>
		<div class="style3" id="style3"><ul>
		<?php
		$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE special=1 AND active='1' AND id < $a  AND alanguage='$currentlang' ORDER BY RAND() DESC LIMIT 5");
		$numrows = $db->sql_numrows($result_lastnew);
		if($numrows > 0) {
			?>
			<?php
			while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
			{
				$get_path = get_path($time);
				$path_upload_img = "$path_upload/news/$get_path";
				$path_upload_img2 = "$path_upload/news";
				if($imageslast !="" && file_exists("$path_upload_img/$imageslast")) 
				{
					$imageslast= resize_image($titlelast, $imageslast, $path_upload_img, $path_upload_img2, 270,123);
				}
				else
				{
					$imageslast= resize_image($titlelast, 'no_image.gif', 'images', $path_upload_img2, 270,123);
				}
				?>
					<li>
						<a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo $imageslast?></a>
						<h3><a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><?php echo $titlelast?></a></h3>
					</li>
					
		<?php
			}
		}
		?>
		</ul></div>		
	<div class="cl"></div>
	</div>
	<div class="adv-hotnew fl">
	<?php
	$result_advlogo = $db->sql_query("SELECT id, target, images, imgtext, module FROM ".$prefix."_advertise WHERE bnid='8' AND alanguage='$currentlang' AND active='1' ORDER BY weight");
$numrows = $db->sql_numrows($result_advlogo);
if($numrows > 0) {
	$a=0;
	echo '<div style="margin-bottom:8px">';
	while(list($id, $target, $images, $imgtext, $module) = $db->sql_fetchrow($result_advlogo)) {
		$path_upload_img = "$path_upload/adv/$images";
		$a++;
		if(file_exists("$path_upload_img") && $images !="") {
			echo "<a href=\"".url_sid("$urlsite/click.php?id=$id")."\" target=\"$target\" title=\"$imgtext\"><img border=\"0\" src=\"$urlsite/$path_upload_img\"></a>";
		}
	}
	echo "</div>";
}

	?>
	<div class="block-event">
<div class="style4" id="style4"><ul>
<?php
$result_lastnew = $db->sql_query("SELECT id, title, images, time, hometext FROM ".$prefix."_news WHERE special=1 AND active='1' AND alanguage='$currentlang' AND ( catid=22 or catid in(SELECT catid FROM {$prefix}_news_cat WHERE parent=22)) ORDER BY time DESC LIMIT 5");
$numrows = $db->sql_numrows($result_lastnew);
if($numrows > 0) {
	$a=0;
	?>
	<?php
	while(list($idlast, $titlelast, $imageslast, $time, $hometext) = $db->sql_fetchrow($result_lastnew)) 
	{
		$hometext = preg_replace("/<.*?>/", "", $hometext);
		$a=$idlast;
		$get_path = get_path($time);
		$path_upload_img = "$path_upload/news/$get_path";
		$path_upload_img2 = "$path_upload/news";
		if($imageslast !="" && file_exists("$path_upload_img/$imageslast")) 
		{
			$imageslast= resize_image($titlelast, $imageslast, $path_upload_img, $path_upload_img2, 300,173);
		}
		else
		{
			$imageslast= resize_image($titlelast, 'no_image.gif', 'images', $path_upload_img2, 300,173);
		}
		?>
			<li>
				<span><a href="<?php echo url_sid("index.php?f=news&do=categories&id=22");?>"><?php echo _EVENTS;?></a></span>
				
				<h4># <?php echo ext_time($time,1)?><br><a href="<?php echo url_sid("index.php?f=news&do=detail&id=$idlast")?>"><strong><?php echo $titlelast?><p></strong><?php echo CutString($hometext,220)?></p></a></h4>
			</li>
			
<?php
	}
}
?>
</ul></div></div>
	</div>
	<div class="cl"></div>
</div>
<?php	
}

function show_menu_header()
{
	global $db, $currentlang, $prefix ;
	$result_menu = $db->sql_query("SELECT mid, title, url FROM {$prefix}_mainmenus WHERE menu_type='top_menu' AND active='1' and alanguage='$currentlang' Order by weight asc");
	if($db->sql_numrows($result_menu) > 0) 
	{
		echo "<div id=\"navheader\">\n";
		echo "<ul>\n";
		
		while (list($mid, $title_menu, $url_menu) = $db->sql_fetchrow($result_menu)) 
		{
			echo "<li><span><a title=\"$title_menu\" href=\"".url_sid($url_menu)."\">$title_menu</a></span></li>\n";
		}
		echo "</ul>\n";
		echo "</div>\n";		
	}	
}
function header_bar()
{
	global $imgFold, $currentlang, $prefix, $urlsite, $db, $userInfo;
		echo "<div class=\"div-header\">";
	?>
	<div class="header-menu">
	<div class="support fl">
	<a href="ymsgr:SendIM?acudvn" title=""><img src="<?php echo $urlsite;?>/images/yahoo.png" alt="" title="" align="baseline"></a>&nbsp;
	<a href="skype:acud.vn?chat" title=""><img src="<?php echo $urlsite;?>/images/skype.png" alt="" title="" align="baseline"></a>&nbsp;
	<a href="#" title=""><img src="<?php echo $urlsite;?>/images/hotline.png" alt="" title="" align="baseline"></a>		
			</div>
	<?php
	
	if (!defined('iS_USER') || !isset($userInfo)) {
		
		echo "<div class=\"header-bar\"><div class=\"menu-header\">";
	?>
	<div class="menu-login">
			<div class="login-item fr">
			<div id="login_header">
			<form method="POST" autocomplete="off"  action="<?php echo url_sid('index.php?f=user&do=login')?>">
			<input class="ip_text" type="text" value="<?php echo _USER_FULLNAME;?>" name="email" id="email" onfocus="if(this.value=='<?php echo _USER_FULLNAME ?>') this.value=''" onblur="if(this.value=='') this.value='<?php echo _USER_FULLNAME ?>'">
			
			<input class="ip_text" type="password" value="<?php echo _PASSWORD;?>" name="password" id="password"  onfocus="if(this.value=='<?php echo _PASSWORD ?>') this.value=''" onblur="if(this.value=='') this.value='<?php echo _PASSWORD ?>'">
			<a href="<?php echo url_sid('index.php?f=user&do=recover');?>" title=""><img src="<?php echo $urlsite;?>/images/help.png" alt="" title="<?php echo _USER_RECOVER_PASSWORD?>" align="baseline"></a>
			<input type="hidden" name="url" value="<?php echo url_sid('index.php?f=user&do=login')?>">
			<input class="sb_but1" type="submit" value="<?php echo _LOGIN?>" name="submit" id="btn_login"/>
			<input class="sb_but1" type="button" value="<?php echo _REGISTER?>" name="btn_register" onclick="location.href='<?php echo url_sid('index.php?f=user&do=register')?>'" id="btn_regiser" />
			</form>
	</div>
			</div>
		</div>
	<div class="cl"></div>
	</div>
	<?php
	}
	else
	{
	?>
	<div class="fr">
	<ul id="menu">
	<li class="menu-li menu_right"><a href="#" class="drop"><?php echo $userInfo['fullname']; ?></a>
		<div class="dropdown_1column align_right">
                <div class="col_1">
                    <ul class="simple">
                        <li><a href="<?php echo url_sid('index.php?f=user&do=edit_profile')?>"><?php echo _USER_EDIT_PROFILE?></a></li>
                        <li><a href="<?php echo url_sid('index.php?f=user&do=logout')?>"><?php echo _USER_LOGOUT?></a></li>
                    </ul>   
                     
                </div>
		</div>
	</li>
	<!--<li class="menu-li menu_right"><a href="#" class="drop"><?php echo _DOCUMENT; ?></a>
		<div class="dropdown_1column align_right">
                <div class="col_1">
                    <ul class="simple">
                        <li><a href="<?php echo $urlsite?>/index.php?f=user&do=document_add"><?php echo _DOCUMENT_ADD ?></a></li>
                        <li><a href="<?php echo $urlsite?>/index.php?f=user&do=document_favorite"><?php echo _DOCUMENT_FAVORITE ?></a></li>
                        <li><a href="<?php echo $urlsite?>/index.php?f=user&do=document_list"><?php echo _DOCUMENT_LIST ?></a></li>
						<li><a href="<?php echo $urlsite?>/index.php?f=user&do=document_downloaded"><?php echo _DOCUMENT_DOWNLOADED ?></a></li>
                    </ul>   
                </div>
		</div>
	</li>-->
	<!--<li class="menu-li menu_right"><a href="#" class="drop">Thanh toán</a>
		<div class="dropdown_1column align_right">
                <div class="col_1">
                    <ul class="simple">
                        <li><a href="<?php echo url_sid('index.php?f=user&do=history')?>">Lịch sử thanh toán</a></li>
                        <li><a href="<?php echo url_sid('index.php?f=napthe&do=create')?>">Nạp tiền tài khoản</a></li>
                    </ul>   
                </div>
		</div>
	</li>
	<li class="menu-lii menu_right">Số dư: <?php echo bsVndDot($userInfo['money'])?> <?php echo _XU?></li>-->
	<li class="menu-lii menu_right"></li>
</ul>
	</div>
	<div class="cl"></div>
</div>
	<?php
	}
	?>
	</div></div></div>
	<?php
}
function upcount_udownload()
{
	global $prefix, $db;
	$result = $db->sql_query("SELECT config, value FROM ".$prefix."_config WHERE config='".date('d-m-Y')."'");
	list($today,$value) = $db->sql_fetchrow($result);
	if($value != date('d-m-Y'))
	{
		$query = "UPDATE ".$prefix."_user,".$prefix."_config SET downloads_free=3, value='".date('d-m-Y')."'WHERE value <> '".date('d-m-Y')."'";
		$db->sql_query($query);
	}
	
}
function show_banner()
{
	global $imgFold, $currentlang, $prefix, $path_upload, $db, $urlsite; 	
	$bnid = 9;	
	$result_flash = $db->sql_query("SELECT a.images,b.bwidth,b.bheight FROM ".$prefix."_advertise AS a INNER JOIN ".$prefix."_advertise_banners AS b ON a.bnid=b.bnid  WHERE a.bnid='$bnid' AND a.active=1 AND a.alanguage='$currentlang' ORDER BY a.id DESC LIMIT 1");
	list($images,$width,$height) = $db->sql_fetchrow($result_flash);	
	//== kiem tra xem la anhr hay la flash 
	$check = Common::getExt($images);	
 	if($check=="swf"){show_flash("FlashID_banner","$urlsite/".$path_upload."/adv/".$images."","".$width."px","".$height."px");}
	else{echo "<img src=\"$urlsite/".$path_upload."/adv/".$images."\" width=\"".$width."px\" height=\"".$height."px\" />";}

}
function show_slide()
{
	global $imgFold, $currentlang, $prefix, $urlsite ;
    /*echo"<div class=\"slide-home\">\n";
    echo"<img src=\"$urlsite/$imgFold/slide.jpg\" alt=\"Western Bank\"/>\n";
    echo"</div>\n";*/
}

function themeheader() 
{
	global $home, $module_name, $imgFold, $Default_Temp, $do, $db, $prefix, $currentlang, $module_name, $key_words, $urlsite;
	
	echo "<body onLoad=\"goforit()\">\n";
	echo '<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>';
	ajaxload_content();
	//cap nhat luot download cho thanh vien
	upcount_udownload();
	//header_bar();

	echo "<div id=\"maincontainer\">\n";
	echo "	<div id=\"topsection\">\n";
	echo "	    <div id=\"banner\"><a style=\"cursor:pointer;\" href=\"http://acud.vn\">\n";
				show_banner();
	echo "		</a></div>\n";
	echo "		<div id=\"navtop\">\n";
				show_menu();
	echo "		</div>\n";
	echo "	</div>\n";
	if($home==1)
	{
		show_newhot();
	}
	//echo "	<div id=\"leftcolumn\">\n";
	//			blocks("left",$module_name);
	//echo "	</div>\n";
	if($module_name == 'user' || $module_name == 'napthe' || $module_name == 'video')
	{
		//echo "	<div id=\"leftcolumn\">\n";
		//blocks("left",$module_name);
		//echo "	</div>\n";
		$contentcolumn = 'module-'.$module_name;
	}
	else
	{
		$contentcolumn='contentcolumn';
	}
	echo "	<div id=\"$contentcolumn\">\n";
	
}

function themefooter() 
{
	global $imgFold ,$module_name, $Default_Temp,$key_words;

	echo "	</div>\n";
	if($module_name == 'user' || $module_name == 'napthe' || $module_name == 'video')
	{
		
	}
	else
	{
		echo "	<div id=\"rightcolumn\">\n";
		blocks("right",$module_name);
		echo "	</div>\n";
	}
	echo "	<div id=\"footer\">\n";
	show_menu_footer();
	echo "		<div class=\"cfooter\">\n";
			footmsg();
	echo "		</div>\n";
	echo "	</div>\n";
	echo "</div>\n";
	
	//echo "<h1 class=\"h1-title\">$key_words</h1>";
}

function OpenBox($title="") {
	echo "<div><div>$title</div>";

}

function CloseBox() {
	echo "</div>";
}
function OpenContent($title, $url="", $ret = false){
	global $imgFold;
	if(empty($url))
		$title=$title;
	else
		$title="<a href=\"".$url."\" title=\"".$title."\">".$title."</a>";
	$c = "<div class=\"content\">";//<h1 class=\"posttitle\">".$title."</h1>\n";
	if ($ret) return $c;
	else echo $c;
}

function CloseContent($ret = false) {
$c = "</div>\n";
	if ($ret) return $c;
	else echo $c;
}

function OpenTab($title, $url="", $ret = false){
	global $imgFold;
	if($url=="")
		$title=$title;
	else
		$title="<a href=\"".$url."\" title=\"".$title."\">".$title."</a>";
		
	$c = "<div class=\"tabbox\"><div class=\"breakcoup\">".$title."</div>\n";
	if ($ret) return $c;
	else echo $c;
}

function CloseTab($ret = false) {
$c = "</div>\n";
	if ($ret) return $c;
	else echo $c;
}

function temp_blocks_left($title, $content, $link, $id, $stitle) {
	echo "$content";
}

function temp_blocks_right($title, $content, $link, $id, $stitle) {
	echo "$content";
}
?>