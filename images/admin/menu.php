<?php
//define('CMS_ADMIN', true);
require_once("../config.php");
require_once("language/".$currentlang."/menu.php");
if(@in_array($listmenus,$file_menu)) {
		$seld =" checked";
	}
	
function show_menu()
{
	global $db, $currentlang, $prefix, $home;
	$result_menu = $db->sql_query("SELECT mid, title, url FROM {$prefix}_adminmenus WHERE menu_type='admin_menu' AND  parentid='0' AND active='1' Order by weight asc");
	if($db->sql_numrows($result_menu) > 0) 
	{
		$i=1;
		//echo "<ul class=\"sf-menu sf-navbar sf-js-enabled sf-shadow\">\n";
		while (list($mid, $title_menu, $url_menu) = $db->sql_fetchrow($result_menu)) 
		{
			$result_check = $db->sql_query("SELECT mid, title, url FROM {$prefix}_adminmenus WHERE menu_type='admin_menu' AND parentid=$mid and active=1  order by weight asc");
			echo "<li><a href=\"".url_sid($url_menu)."\" class=\"nav_link\" title=\"$title_menu\">$title_menu</a>\n";
			$result_sub = $db->sql_query("SELECT mid, title, url FROM {$prefix}_adminmenus WHERE menu_type='admin_menu' AND parentid=$mid and active=1  order by weight asc");
		if($db->sql_numrows($result_sub) > 0) 
		{
			echo "<ul>\n";
			while (list($mid_sub, $title_sub, $url_sub) = $db->sql_fetchrow($result_sub)) 
			{
				echo "<li  id=\"navh-2\"><a title=\"$title_sub\" href=\"".url_sid($url_sub)."\">$title_sub</a>\n";
				$result_sub2 = $db->sql_query("SELECT mid, title, url FROM {$prefix}_adminmenus WHERE menu_type='admin_menu' AND parentid=$mid_sub and active=1  order by weight asc");
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
	//echo "</ul>\n";
}

function show_menu_for_user()
{
	global $db, $currentlang, $prefix, $home, $admin_ar;
	//lay tat ca permission cua nguoi dung dang nhap
	$resultchauthor= $db->sql_query("SELECT permission  FROM ".$prefix."_admingroup where id in(SELECT permission FROM ".$prefix."_admin where adacc='$admin_ar[0]')");
	list($permissionchgroup) = $db->sql_fetchrow($resultchauthor);
	$auth_menusch = @explode("|",$permissionchgroup);
	//lay menu co parentid=0
	$a =0;
	for($l=0;$l <= sizeof($auth_menusch);$l++) 
	{
		if (isset($auth_menusch[$l]))
		{
			$resultmenuf= $db->sql_query("SELECT parentid  FROM ".$prefix."_adminmenus where mid=$auth_menusch[$l]");
			list($mparentid) = $db->sql_fetchrow($resultmenuf); 
			//{
				//echo $mparentid."+";
				if(!@in_array($mparentid,$auth_menuf))
					$auth_menuf[]=$mparentid;
			//}
		}
	}
	//echo count($auth_menuf);
	//
	//
	//show mang menu theo quyen nguoi dung
	//
	$i=0;
	for($l=0;$l <= sizeof($auth_menuf);$l++) 
	{
		if (isset($auth_menuf[$l]))
		{
		$resultmenushow= $db->sql_query("SELECT mid,url,title  FROM ".$prefix."_adminmenus where menu_type='admin_menu' AND active = '1' AND mid=$auth_menuf[$l] AND parentid=0 ORDER BY weight");
		list($midm2,$urlm2,$titlem2) = $db->sql_fetchrow($resultmenushow); 
		if ($db->sql_numrows($resultmenushow) > 0)
		{
			echo "<li><a href=\"".url_sid($urlm2)."\" class=\"nav_link\" title=\"$titlem2\">$titlem2</a>\n";
			echo "<ul>\n";
		//echo "<li><a href=\"$urlm2\"  rel=\"ddsubmenu$l\">$titlem2</a></li>\n";
				//echo "<ul id=\"ddsubmenu$l\" class=\"ddsubmenustyle\">\n";
		$resultmenu2 = $db->sql_query("SELECT mid, title,url, weight, active, parentid FROM {$prefix}_adminmenus WHERE menu_type='admin_menu' AND active=1 AND parentid=$auth_menuf[$l] order by weight asc");
			while(list($mid2, $title2,$url2, $weight2, $active2, $parentid2) = $db->sql_fetchrow($resultmenu2)) 
			{
				if ($db->sql_numrows($resultmenu2) > 0)
				{
					if(@in_array($mid2,$auth_menusch)) 
					{
						//echo "<li><a href=\"$url2\">$title2</a></li>\n";
						echo "<li  id=\"navh-2\"><a title=\"$title2\" href=\"".url_sid($url2)."\">$title2</a>\n";
						$result_sub2 = $db->sql_query("SELECT mid, title, url FROM {$prefix}_adminmenus WHERE menu_type='admin_menu' AND parentid=$mid2 and active=1  order by weight asc");
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
					
				}
				
			}
			echo "</ul>\n";
			echo "</li>\n";
		}
			//show mang menu con 
		}	
			
	}
}
if(defined('iS_ADMIN')) 
{
	echo "<div class=\"div-header\">";
	///echo "<div class=\"div-banner\"><span class=\"version\">"._VERSION_ONECMS." | "._HELLO.": <b><a href=\"modules.php?f=authors&do=change&acc=$admin_ar[0]\" target=\"_top\" class=\"catmenu1\">$admin_ar[0]</a></b> - <a href=\"logout.php\" target=\"_top\" title=\""._LOGOUT."\" onclick=\"return confirm('"._LOGOUTASK."');\" class=\"catmenu1\">"._LOGOUT."</a></span></div>";
	//echo "<div id=\"ddtopmenubar\" class=\"mattblackmenu\">\n";
	//echo "<ul>\n";
	//$resultchuser= $db->sql_query("SELECT * FROM ".$prefix."_adminmenus");
	
	//tra ve mang menu duoc cap quyen truy cap
	echo "<div class=\"menu-left\">";
	echo "<ul class=\"sf-menu sf-navbar sf-js-enabled sf-shadow\">\n";
		echo "<li class=\"menupop\"><a class=\"menu-item\" href=\"index.php\"><span class=\"menu-logo\">&nbsp;</span></a></li>";
	if(defined('iS_RADMIN')) 
	{
		
		show_menu();
	}
	else
	{
		show_menu_for_user();
	}	
	
	echo "</ul>";
	echo "</div >";
	echo "<div class=\"menu-right fr\">";
	echo "<ul>";
	echo "<li class=\"menu-help fr\"><a class=\"fancybox fancybox.iframe\" href=\"help/index.html\" title=\""._HELP."\">"._HELP."</a></li>";
	echo "<li class=\"menu-viewsite fr\"><a href=\"".url_sid("index.php",1)."\" target=\"_blank\">"._VIEW_WEBSITE."</a></li>";
	echo "<li class=\"menu-admin fr\"><a href=\"modules.php?f=authors&do=change&acc=$admin_ar[0]\" target=\"_top\">$admin_ar[0]</a></li>";
	echo "<li class=\"menu-logout fr\"><a target=\"_top\" href=\"logout.php\" title=\""._LOGOUT."\" onclick=\"return confirm('"._LOGOUTASK."');\">"._LOGOUT."</a></li>";
	echo "</ul>";
	echo "</div>";
	echo "</div>";
	echo "<div class=\"div-content\">\n";
}else{
	header("Location: login.php");
}
?>

<div id="abouts" style="width:450px;display: none;">
	<div class="fl"><img border="0" src="images/login/lion.png" align="baseline"></div>
    <div class="fl" style="width:300px">
		<h3><?php echo _TITLE_ABOUT_ONECMS?></h3>
        <p><?php echo _VERSION_ONECMS?><br /><?php echo _CONTENT_ABOUT_ONECMS?></p>
        </div>
        <div class="cl"></div>
	</div>
<?php
//<span class=\"version\">"._VERSION_ONECMS." | 
?>
