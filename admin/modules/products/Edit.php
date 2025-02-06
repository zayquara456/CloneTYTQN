<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result_prd = $db->sql_query("SELECT catid, title, text, description, active, ptops, psale, images, priceold, price FROM {$prefix}_products WHERE id=$id");
if ($db->sql_numrows($result_prd) != 1) header("Location: modules.php?f=$adm_modname");

list($catid, $title, $text, $description, $active, $ptops, $psale, $images, $priceold, $price) = $db->sql_fetchrow($result_prd);

include_once("page_header.php");

$path_upload_img = "$path_upload/$adm_modname";
$err_title = $err_cat ="";
if (isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = nospatags($_POST['title']);
	$text = trim(stripslashes(resString($_POST['text'])));
	$description = trim(stripslashes(resString($_POST['description'])));
	$active = intval($_POST['active']);
	$ptops = intval($_POST['ptops']);
	$psale = intval($_POST['psale']);
	$catid = intval($_POST['catid']);
	$delpic = intval($_POST['delpic']);
	$priceold = floatval(str_replace(',', '.', $_POST['priceold']));
	$price = floatval(str_replace(',', '.', $_POST['price']));

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1_1."</font><br/>";
		$err = 1;
	}
	else
	{
		$permalink=url_optimization(trim($title));
	}
	if($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR2."</font><br/>";
		$err = 1;
	}

	if($delpic == 1) {
		@unlink("../$path_upload_img/$images");
		@unlink("../$path_upload_img/thumb_".$images);
		$images = "";
	}

	if(!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up);
			$images_up = $upload->send();
			if($images_up) {
				resizeImg($images_up, $path_upload_img, $prd_thumbsize);
				@unlink("../$path_upload_img/$images");
				@unlink("../$path_upload_img/thumb_".$images);
			} else {
				$images_up = $images;
			}
		} else {
			$images_up = $images;
		}
		$result = $db->sql_query("UPDATE {$prefix}_products SET catid=$catid, title='$title', permalink='$permalink', text='$text', description='$description', active=$active, ptops=$ptops, psale=$psale, images='$images_up', priceold=$priceold, price=$price WHERE id=$id");
		fixcount_cat();
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADDNEWS);
		//header("Location: modules.php?f=".$adm_modname."");
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

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._EDITNEWS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"70\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
echo "<td class=\"row2\"><select name=\"catid\">\n";
$result_cat = $db->sql_query("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"catid\" value=\"0\">"._INCAT0."</option>";
$listcat = "";
while(list($cat_id, $titlecat) = $db->sql_fetchrow($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">|- $titlecat</option>";
	$listcat .= subcat($cat_id,"|",$catid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SHOW."</b></td>\n";
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
echo "<td align=\"right\" class=\"row1\"><b>"._PRODUCT_TOP."</b></td>\n";
if($ptops == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"ptops\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"ptops\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"ptops\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"ptops\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PRODUCT_SALE."</b></td>\n";
if($psale == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"psale\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"psale\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"psale\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"psale\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}
echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._TEXT_DESC."</b></td>\n";
echo "<td class=\"row2\">";
editorbasic("text",$text,"",150);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._DESCRIPTION."</b></td>\n";
echo "<td class=\"row2\">";
editor("description",$description,"",400);
echo "</td>\n";
echo "</tr>\n";
if(!empty($images) && file_exists("../$path_upload_img/$images")) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._DEL_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delpic\" value=\"1\"> <a href=\"../$path_upload_img/$images\" target=\"_blank\"><img border=\"0\" src=\"../images/img.gif\" align=\"absmiddle\"></a></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._CHANGE_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"30\"></td>\n";
	echo "</tr>\n";
} else {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"30\"></td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._PRODUCT_PRICE_OLD."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"price\" name=\"price\" value=\"$priceold\">&nbsp;("._THOUSAND_VND.")</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._PRODUCT_PRICE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"price\" name=\"price\" value=\"$price\">&nbsp;("._THOUSAND_VND.")</td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");
?>