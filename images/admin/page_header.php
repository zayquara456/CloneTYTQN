<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

require("language/$currentlang/menu.php");
//@require_once(RPATH."editor/fckeditor.php");
@require_once(RPATH."editor/ckeditor/ckeditor.php");
global $adm_pagetitle, $adm_modname, $adm_pagetitle2, $load_hf;
if($load_hf) { $noload_hf = 0; } else { $noload_hf = 1; }

$scolor1 = "#ebf3f8";
$scolor2 = "#F0F0F0";
$scolor3 = "#4399d0";

//danh sach module theo file
$modlist = "";
$testmodhandle=opendir(RPATH.'modules');
while ($file = readdir($testmodhandle)) 
{
	if (is_dir(RPATH."modules/$file") && ($file != '.') && ($file != '..')) 
	{
		$modlist[] = "$file";
	}
}
closedir($testmodhandle);
sort($modlist);
//danh sach modules bi khoa
$listmods_noaccept = array("search","home");
//danh sach modules chua khoa
$listmenus_naccept = array("");
//show list mod
$listmods = "";
$ml2result = $db->sql_query("SELECT title, custom_title FROM ".$prefix."_modules WHERE alanguage='$currentlang'");
while($rowmod = $db->sql_fetchrow($ml2result))
{
	$titlemod =  $rowmod['title'];
	$titlemod_custom = $rowmod['custom_title'];
	if(@in_array($titlemod, $modlist)) 
	{
		$listmods[] = "".$titlemod."";
		$listmods_custom[] = "".$titlemod_custom."";
		$listmods_name[$titlemod] = $titlemod_custom;
	}
	else 
	{
		$db->sql_query("delete from ".$prefix."_modules where title='$titlemod'");
		$db->sql_query("OPTIMIZE TABLE ".$prefix."_modules");
	}
}
//get all menus admin
$listmenus = "";
$menuresult = $db->sql_query("SELECT file_menu FROM ".$prefix."_admin_menu order by weight");
while($rowmenu = $db->sql_fetchrow($menuresult))
{
	if (file_exists("menus/adm_".$rowmenu['file_menu'].".php"))
	{
		$file_menu =  $rowmenu['file_menu'];
		$listmenus[] = "".$file_menu."";
		include("menus/adm_".$file_menu.".php");
			if($menu_main != "") 
			{
				$listnamemenu[]= $menu_main;
			}
	}
}
//cap nhat modules
for ($i=0; $i < sizeof($modlist); $i++) 
{
	if($modlist[$i] != "" AND !@in_array($modlist[$i],$listmods)) 
	{
		$db->sql_query("INSERT INTO " . $prefix . "_modules (mid, title, custom_title, active, view, inmenu, alanguage) VALUES (NULL, '$modlist[$i]', '$modlist[$i]', '0', '0', '1', '$currentlang')");
	}
}

function OpenDiv() {
	global $scolor1, $scolor3;
	echo "<table align=\"center\" border=\"2\" width=\"90%\" cellspacing=\"0\" cellpadding=\"10\" style=\"border-collapse: collapse\" bordercolor=\"$scolor3\">\n";
	echo "<tr><td bgcolor=\"$scolor1\">\n";
}	

function CloseDiv() {
	echo "</td></tr></table>";
}

if ($noload_hf) {
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
	echo "<html dir=\"ltr\" lang=\"en\">\n";
	echo "<head>\n";
	echo "<title>";
	if($adm_pagetitle) { 
		echo "$adm_pagetitle - ";
	}	
	if($adm_pagetitle2) { 
		echo "$adm_pagetitle2 - ";
	}
	echo "Admin Control Panel";
	echo "</title>\n";
	echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\">\n";
	echo "<link rel=\"stylesheet\" href=\"styles/styles.css\" />\n";
	echo "<script language=\"javascript\" src=\"../js/common.js\"></script>\n";
	echo "<script language=\"javascript\" src=\"js/tooltip.js\"></script>\n";
	echo "<script language=\"javascript\" src=\"js/adm_common.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"../js/mudim.packed.js?ver=1.2\"></script>\n";
	//popup menu
?>
<link rel="stylesheet" href="../js/jquery/development-bundle/themes/base/jquery.ui.all.css">
	<script src="../js/jquery/development-bundle/jquery1.4.4.js"></script>
	<script src="../js/jquery/development-bundle/ui/jquery.ui.core.js"></script>
	<script src="../js/jquery/development-bundle/ui/jquery.ui.widget.js"></script>
	<script src="../js/jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="styles/superfish.css" />
<script type="text/javascript" src="../js/fancybox/lib/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../js/jqueryui1816custommin.js"></script>
<script type="text/javascript" src="js/superfish.js"></script>
<script type="text/javascript" src="js/hoverIntent.js"></script>
<script type="text/javascript" src="js/supersubs.js"></script>

<script type="text/javascript" src="../js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="../js/fancybox/source/jquery.fancybox.js?v=2.1.0"></script>
<link rel="stylesheet" type="text/css" href="../js/fancybox/source/jquery.fancybox.css?v=2.1.0" media="screen" />
<!-- Add jQuery library -->
	
<script> 
 	
    $(document).ready(function(){ 
		$('.fancybox').fancybox({
			maxWidth	: 1000,
			maxHeight	: 500,
			fitToView	: false,
			autoSize	: true,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
		

        $("ul.sf-menu").supersubs({ 
            minWidth:    10,   // minimum width of sub-menus in em units 
            maxWidth:    27,   // maximum width of sub-menus in em units 
            extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                               // due to slight rounding differences and font-family 
        }).superfish();  // call supersubs first, then superfish, so that subs are 
                         // not display:none when measuring. Call before initialising 
                         // containing tabs for same reason. 
		
    }); 
 
</script>
<?php
	echo "<link href=\"calendar/calendar.css\" rel=\"stylesheet\" type=\"text/css\" />";
	echo "<script type=\"text/javascript\" src=\"js/ddlevelsmenu.js\"></script>";
	//end popup menu
	if (file_exists("js/".$adm_modname.".js")) {
		echo "<script type=\"text/javascript\" src=\"js/".$adm_modname.".js\"></script>\n";
	}
	echo "</head>\n";
	echo "<body onload=\"set_cp_title();\">\n";
	include_once("menu.php");
}

// check to see if $_SESSION['timeout'] is set
if(isset($_SESSION['timeout']) ) {
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{ session_destroy(); header("Location: logout.php"); }
}
$_SESSION['timeout'] = time();
?>