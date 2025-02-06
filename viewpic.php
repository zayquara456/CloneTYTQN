<?php
if ((!file_exists("config.php")) || (!isset($_GET['image']))) die();
define('CMS_SYSTEM', true);
@require_once("config.php");

echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<title>"._VIEWIMG."</title>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
echo "</head>";
?>
<script language="JavaScript">
function resizeOuterTo(w,h) {
	if (parseInt(navigator.appVersion)>3) {
		if (navigator.appName=="Netscape") {
			top.outerWidth=w+8;
			top.outerHeight=h+29;
		}
		else
		{
			top.resizeTo(400,300);
			wd = 400-document.body.clientWidth;
			hd = 300-document.body.clientHeight;
			top.resizeTo(w+wd,h+hd);
		}
	}
}

function init()
{
	resizeOuterTo(document.images['LargeImg'].width, document.images['LargeImg'].height);
}
</script>
<?php
echo "<body marginheight=\"0\" marginwidth=\"0\" topmargin=\"0\" leftmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\" onLoad=\"init();\">";
echo "<a href=\"\" onclick=\"window.close();\"><img name=\"LargeImg\" src=\"{$_GET['image']}\" border=\"0\" info=\"Close\"></a>";
echo "</body>";
echo "</html>";
?>