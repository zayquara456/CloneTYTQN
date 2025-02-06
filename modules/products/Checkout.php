<?php
if (!defined('CMS_SYSTEM')) die();

include_once("header.php");

echo "<script language=\"javascript\" type=\"text/javascript\">";
echo "function check(f) {";
echo "var Email = f.email;";
echo "var Name = f.name;";
echo "var Address = f.address;";
echo "var Phone = f.phone;";
echo "var err = 0;";
echo "if (!isEmail(Email.value)) {";
echo "alert('"._PRODUCT_ERROR_EMAIL.": ' + Email.value);";
echo "Email.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if (isEmpty(Name.value)) {";
echo "alert('"._PRODUCT_ERROR_NAME."');";
echo "Name.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
echo "if (isEmpty(Address.value)) {";
echo "alert('"._PRODUCT_ERROR_ADDRESS."');";
echo "Address.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
echo "if (isEmpty(Phone.value)) {";
echo "alert('"._PRODUCT_ERROR_PHONE."');";
echo "Phone.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
echo "if(!err) {";
echo "if(f.submit) f.submit.disabled = true; }";
echo "return true; ";
echo "}";
echo "</script>";

OpenTab(_PRODUCT_CHECKOUT);

$mrSelected = '';
$mrsSelected = '';
$fullname = '';
$address = '';
$phone = '';
$email = '';
if (defined('iS_USER') && isset($userInfo)) {
	if ($userInfo['title'] == '0') $mrSelected = ' selected="selected"';
	elseif ($userInfo['title'] == '1') $mrsSelected = ' selected="selected"';
	$fullname = $userInfo['fullname'];
	$address = $userInfo['address'];
	$phone = $userInfo['phone'];
	$email = $userInfo['email'];
}

echo "<table border=\"0\" align=\"center\">";
echo "<form method=\"POST\" action=\"".url_sid("index.php?f=products&do=checkout2")."\" onsubmit=\"return check(this);\">";
echo "<tr>";
echo "<td><font size=\"2\">"._PRODUCT_CUSTOMER_TITLE."</font></td>";
echo "<td style=\"padding-left: 10px\"><select name=\"title\">";
echo "<option value=\"0\"$mrSelected>"._PRODUCT_MR."</option>";
echo "<option value=\"1\"$mrsSelected>"._PRODUCT_MRS."</option>";
echo "</select></td>";
echo "</tr>";
echo "<tr><td><font size=\"2\">"._PRODUCT_FULLNAME."</font></td><td style=\"padding-left: 10px\"><input name=\"name\" id=\"name\" value=\"$fullname\" maxlength=\"150\" size=\"40\" type=\"text\"></td></tr>";
echo "<tr><td><font size=\"2\">"._PRODUCT_ADDRESS."</font></td><td style=\"padding-left: 10px\"><input name=\"address\" id=\"address\" value=\"$address\" maxlength=\"250\" size=\"40\" type=\"text\"></td></tr>";
echo "<tr><td><font size=\"2\">"._PHONE."</font></td><td style=\"padding-left: 10px\"><input name=\"phone\" id=\"phone\" value=\"$phone\" maxlength=\"50\" size=\"40\" type=\"text\"></td></tr>";
echo "<tr><td><font size=\"2\">"._PRODUCT_EMAIL."</font></td><td style=\"padding-left: 10px\"><input name=\"email\" id=\"email\" value=\"$email\" maxlength=\"150\" size=\"40\" type=\"text\"></td></tr>";
echo "<tr><td><font size=\"2\">"._PRODUCT_MESSAGE."</font></td>";
echo "<td style=\"padding-left: 10px\"><textarea name=\"message\" id=\"message\" rows=\"12\" cols=\"40\" wrap=\"virtual\"></textarea></td></tr>";
echo "<tr><td></td><td style=\"padding-left: 10px\"><input type=\"submit\" name=\"submit\" value=\""._PRODUCT_CHECKOUT."\" class=\"sb_but1\"></td></tr>";
echo "</form>";
CloseTab();
echo "<tr><td colspan=\"2\"><br />";

include_once("Cart.php");

echo "</td></tr></table>";

include_once("footer.php");
?>