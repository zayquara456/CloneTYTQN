<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
$err_title = $err_cat = "";
if (isset($_POST['submit'])) {
	$croname = $escape_mysql_string(trim($_POST['croname']));
	$catid = intval($_POST['catid']);
	$filter = intval($_POST['filter']);
	$cronurl = isset($_POST['cronurl']) ? $escape_mysql_string(trim($_POST['cronurl'])) : '';
	$err = 0;
	if($croname =="") {
		$err_croname = "<font color=\"red\">"._ERROR1."</font><br/>";
		$err = 1;
	}
	if(!$err) {
		$query = $db->sql_query("INSERT {$prefix}_ngrab_cron (cron_name, cron_url, cat_id, filter_id, alanguage, cdate) VALUES ('$croname', '$cronurl', $catid, $filter, '$currentlang', ".time().")");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_EDIT_NEWS);
		header("Location: modules.php?f=".$adm_modname."&do=cron&msg=insert");
	}
}



include_once("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" enctype=\"multipart/form-data\">";
echo "
<div id=\"pagecontent\">
	<div class=\"ctrl-header\">
		<div><span id=\"ctl10_lblTitle\">"._CREATE_CRON."</span></div>
	</div>
	<div class=\"ctrl-content\">
		<div class=\"ctrl-content-list\">";
echo "<table class=\"tableborder\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
echo "<td width=\"120px\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"croname\" value=\"\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SOURCE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"cronurl\" value=\"\" size=\"60\" maxlength=\"253\"></td>\n";
echo "</tr>\n";
$resultfilter= $db->sql_query("SELECT id, title FROM {$prefix}_ngrab_filter WHERE alanguage='$currentlang' ORDER BY id");
if($db->sql_numrows($resultfilter) > 0) 
{
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._FILTER."</b></td>\n";
	echo "<td class=\"row2\">";
	echo '<select id="filter" name="filter">'."\n";
	echo '<option value="0">'._SELECT_FILTER."</option>\n";
	while(list($filter_id, $filter_title) = $db->sql_fetchrow($resultfilter)) 
	{
		echo "<option value=\"$filter_id\">$filter_title</option>";
	}
	echo "</select></td></tr>";
}
echo "<tr>\n";
$resultcat = $db->sql_query("SELECT catid, title FROM {$prefix}_news_cat WHERE parent=0 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($resultcat) > 0) 
{
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._CATEGORIES_NEWS."</b></td>\n";
	echo "<td class=\"row2\">";
	echo '<select id="catid" name="catid">'."\n";
	echo '<option value="0">'._SELECT_CATEGORIES_NEWS."</option>\n";
	$listcat ="";
	while(list($cat_id, $titlecat) = $db->sql_fetchrow($resultcat)) 
	{
		$listcat .= "<option value=\"$cat_id\">--$titlecat</option>";
		$listcat .= subcat($cat_id,"-","", "");
	}
	echo $listcat;
	echo "</select></td></tr>";
}
echo '<tr><td>'._THOI_GIAN_CHAY.'</td><td><table>
<tbody><tr><td valign="top">Minute(s):<br>
<select onchange="update_crontab()" size="5" name="minute2" >
<option value="*"> Every Minute
</option><option value="*/2"> Every Other Minute
</option><option value="*/5"> Every Five Minutes
</option><option value="*/10"> Every Ten Minutes

</option><option value="*/15"> Every Fifteen Minutes
</option><option value="0"> 0
</option><option value="1"> 1
</option><option value="2"> 2
</option><option value="3"> 3
</option><option value="4"> 4
</option><option value="5"> 5
</option><option value="6"> 6
</option><option value="7"> 7

</option><option value="8"> 8
</option><option value="9"> 9
</option><option value="10"> 10
</option><option value="11"> 11
</option><option value="12"> 12
</option><option value="13"> 13
</option><option value="14"> 14
</option><option value="15"> 15
</option><option value="16"> 16

</option><option value="17"> 17
</option><option value="18"> 18
</option><option value="19"> 19
</option><option value="20"> 20
</option><option value="21"> 21
</option><option value="22"> 22
</option><option value="23"> 23
</option><option value="24"> 24
</option><option value="25"> 25

</option><option value="26"> 26
</option><option value="27"> 27
</option><option value="28"> 28
</option><option value="29"> 29
</option><option value="30"> 30
</option><option value="31"> 31
</option><option value="32"> 32
</option><option value="33"> 33
</option><option value="34"> 34

</option><option value="35"> 35
</option><option value="36"> 36
</option><option value="37"> 37
</option><option value="38"> 38
</option><option value="39"> 39
</option><option value="40"> 40
</option><option value="41"> 41
</option><option value="42"> 42
</option><option value="43"> 43

</option><option value="44"> 44
</option><option value="45"> 45
</option><option value="46"> 46
</option><option value="47"> 47
</option><option value="48"> 48
</option><option value="49"> 49
</option><option value="50"> 50
</option><option value="51"> 51
</option><option value="52"> 52

</option><option value="53"> 53
</option><option value="54"> 54
</option><option value="55"> 55
</option><option value="56"> 56
</option><option value="57"> 57
</option><option value="58"> 58
</option><option value="59"> 59
</option></select><br><br>
</td>

<td valign="top">Hour(s):<br>
<select onchange="update_crontab()" size="5" name="hour2">
<option value="*"> Every Hour
</option><option value="*/2"> Every Other Hour
</option><option value="*/4"> Every Four Hours
</option><option value="*/6"> Every Six Hours
</option><option value="0"> 0 = 12 AM/Midnight
</option><option value="1"> 1 = 1 AM
</option><option value="2"> 2 = 2 AM

</option><option value="3"> 3 = 3 AM
</option><option value="4"> 4 = 4 AM
</option><option value="5"> 5 = 5 AM
</option><option value="6"> 6 = 6 AM
</option><option value="7"> 7 = 7 AM
</option><option value="8"> 8 = 8 AM
</option><option value="9"> 9 = 9 AM
</option><option value="10"> 10 = 10 AM
</option><option value="11"> 11 = 11 AM

</option><option value="12"> 12 = 12 PM/Noon
</option><option value="13"> 13 = 1 PM
</option><option value="14"> 14 = 2 PM
</option><option value="15"> 15 = 3 PM
</option><option value="16"> 16 = 4 PM
</option><option value="17"> 17 = 5 PM
</option><option value="18"> 18 = 6 PM
</option><option value="19"> 19 = 7 PM
</option><option value="20"> 20 = 8 PM

</option><option value="21"> 21 = 9 PM
</option><option value="22"> 22 = 10 PM
</option><option value="23"> 23 = 11 PM
</option></select>
</td><td>Day(s):<br>
<select onchange="update_crontab()" size="5" name="day2">
<option value="*"> Every Day
</option><option value="1"> 1
</option><option value="2"> 2
</option><option value="3"> 3

</option><option value="4"> 4
</option><option value="5"> 5
</option><option value="6"> 6
</option><option value="7"> 7
</option><option value="8"> 8
</option><option value="9"> 9
</option><option value="10"> 10
</option><option value="11"> 11
</option><option value="12"> 12

</option><option value="13"> 13
</option><option value="14"> 14
</option><option value="15"> 15
</option><option value="16"> 16
</option><option value="17"> 17
</option><option value="18"> 18
</option><option value="19"> 19
</option><option value="20"> 20
</option><option value="21"> 21

</option><option value="22"> 22
</option><option value="23"> 23
</option><option value="24"> 24
</option><option value="25"> 25
</option><option value="26"> 26
</option><option value="27"> 27
</option><option value="28"> 28
</option><option value="29"> 29
</option><option value="30"> 30

</option><option value="31"> 31
</option></select><br><br>
</td><td valign="top">Months(s):<br>
<select onchange="update_crontab()" size="5" name="month2">
<option value="*"> Every Month
</option><option value="1"> January
</option><option value="2"> February
</option><option value="3"> March
</option><option value="4"> April
</option><option value="5"> May

</option><option value="6"> June
</option><option value="7"> July
</option><option value="8"> August
</option><option value="9"> September
</option><option value="10"> October
</option><option value="11"> November
</option><option value="12"> December
</option></select>
</td><td>Weekday(s):<br>

<select onchange="update_crontab()" size="5" name="weekday2">
<option value="*"> Every Weekday
</option><option value="0"> Sunday
</option><option value="1"> Monday
</option><option value="2"> Tuesday
</option><option value="3"> Wednesday
</option><option value="4"> Thursday
</option><option value="5"> Friday
</option><option value="6"> Saturday

</option></select>
</td></tr>
<script>
 function update_crontab(){

 document.adminForm.cron_mhdmd.value =   document.adminForm.minute2.value+" "+
                  document.adminForm.hour2.value+" "+
                  document.adminForm.day2.value+" "+
                  document.adminForm.month2.value+" "+
                  document.adminForm.weekday2.value;
 }
</script>

<script>

var croncount=2;

function updateform2() {

var ccount=2;

fieldvals=new Array("15","*/4","*","*","*");
	if ("15" == "") {
		document.adminForm.minute2.options[5].selected = true;
	}
	if ("*/4" == "") {
		document.adminForm.hour2.options[7].selected = true;
	}
	if ("*" == "") {
		document.adminForm.day2.options[0].selected = true;
	}
	if ("*" == "") {
		document.adminForm.month2.options[0].selected = true;
	}
	if ("*" == "") {
		document.adminForm.weekday2.options[0].selected = true;
	}
 
	var ft = fieldvals[0];
	var far = ft.split(",");
	for (t=0;t<document.adminForm.minute2.options.length;t++) {
		for (var loop=0; loop < far.length; loop++)
		{
			if ( document.adminForm.minute2.options[t].value == far[loop]) {
				document.adminForm.minute2.options[t].selected = true;
			}
		}
	}
	var ft = fieldvals[1];
	var far = ft.split(",");
	for (t=0;t<document.adminForm.hour2.options.length;t++) {
		for (var loop=0; loop < far.length; loop++)
		{
			if ( document.adminForm.hour2.options[t].value == far[loop]) {
				document.adminForm.hour2.options[t].selected = true;
			}
		}
	}
	var ft = fieldvals[2];
	var far = ft.split(",");
	for (t=0;t<document.adminForm.day2.options.length;t++) {
		for (var loop=0; loop < far.length; loop++)
		{
			if ( document.adminForm.day2.options[t].value == far[loop]) {
				document.adminForm.day2.options[t].selected = true;
			}
		}
	}
	var ft = fieldvals[3];
	var far = ft.split(",");
	for (t=0;t<document.adminForm.month2.options.length;t++) {
		for (var loop=0; loop < far.length; loop++)
		{
			if ( document.adminForm.month2.options[t].value == far[loop]) {
				document.adminForm.month2.options[t].selected = true;
			}
		}
	}
	var ft = fieldvals[4];
	var far = ft.split(",");
	for (t=0;t<document.adminForm.weekday2.options.length;t++) {
		for (var loop=0; loop < far.length; loop++)
		{
			if ( document.adminForm.weekday2.options[t].value == far[loop]) {
				document.adminForm.weekday2.options[t].selected = true;
			}
		}
	}

}
</script>
<script>
      	 eval ("updateform2()");
         </script>
</tbody></table></td></tr>';
echo '<tr>
            <td class="key"><strong>'._GIO_CHAY.'</strong></td>
            <td>
            <input type="text" readonly="1" value="15 */4 * * *" valign="top" size="40" name="cron_mhdmd" class="inputbox"> (*)		</td>
        </tr>';
echo "<tr><td >&nbsp;</td><td ><input type=\"hidden\"  name=\"csrf\" value=\"$key\" /><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";

echo "</table>";
echo "	</div>
		<div class=\"ctrl-footer\"></div>
</div>
<div class=\"cl\"></div>
</div>
</div>";
echo "</form>";

include_once("page_footer.php");
?>