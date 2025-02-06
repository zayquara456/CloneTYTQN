<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$text = $description = $title = $err_title = $err_cat = $ptops = $psale="";
$active = 1;
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	
	$title = nospatags($_POST['title']);
	$text = trim(stripslashes(resString($_POST['text'])));
	$description = trim(stripslashes(resString($_POST['description'])));
	$active = intval($_POST['active']);
	$ptops = intval($_POST['ptops']);
	$psale = intval($_POST['psale']);
	$catid = intval($_POST['catid']);
	$priceold = floatval(str_replace(',', '.', $_POST['priceold']));
	$price = floatval(str_replace(',', '.', $_POST['price']));

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1_1."</font><br/>";
		$err = 1;
	}

	if($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR2."</font><br/>";
		$err = 1;
	}

	if(!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname";
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up);
			$images = $upload->send();
			resizeImg($images, $path_upload_img, $prd_thumbsize);
		}
		$result = $db->sql_query("INSERT INTO {$prefix}_thecao (catid, title, alanguage, time, text, description, active, ptops, psale, images, priceold, price, buyCount) VALUES ($catid, '$title', '$currentlang', ".time().", '$text', '$description', $active, '$ptops', '$psale', '$images', $priceold, $price, 0)");
		fixcount_cat();
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADDNEWS);
		header("Location: modules.php?f=".$adm_modname."");
	}
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1_1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		if(f.catid.value == 0) {\n";
echo "			alert('"._ERROR2."');\n";
echo "			f.catid.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
echo "<div id=\"pagecontent\">";
echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Thêm thẻ cào mới</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Chọn loại thẻ</b></td>\n";
echo "<td class=\"row2\">$err_cat<select name=\"catid\">\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_thecao_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"catid\" value=\"0\">Chọn loại thẻ</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\"$seld>$titlecat</option>";
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Kích hoạt</b></td>\n";
if($active == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Mã code</b></td>\n";
echo "<td class=\"row2\">$err_code<input type=\"text\" name=\"code\" value=\"$code\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Mã serial</b></td>\n";
echo "<td class=\"row2\">$err_serial<input type=\"text\" name=\"serial\" value=\"$serial\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._PRODUCT_PRICE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"price\" name=\"price\">&nbsp;("._THOUSAND_VND.")</td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
echo "</table></form></div>";

include_once("page_footer.php");
?>