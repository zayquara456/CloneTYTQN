<?php
include("../setting.php");
//include("../includes/global.php");
////mau ngoai that xe
////http://www.nissan.com.vn/wp-admin/admin-ajax.php?action=vehicle_color_select&post=15857&color=1
////mau noi that xe
////post=15857&action=vehicle_interior_360
$post = $_GET['post'] ? $_GET['post'] : $_POST['post'];
$action = $_GET['action']? $_GET['action'] : $_POST['action'];
$vehicle = $_GET['vehicle']? $_GET['vehicle'] : $_POST['vehicle'];
$color = isset($_GET['color']) ? intval($_GET['color']) : intval($_POST['color']);
////$vehicle = $_GET['vehicle'];
//$newscrawler = new Crawler("","",99999999999);
//if($color!=0){
//   $html =	$newscrawler->runBrowser("http://www.nissan.com.vn/wp-admin/admin-ajax.php?post=$post&color=$color&action=$action"); 
//}
//echo $html;

//if($action=='vehicle_interior_360')
//{
// echo'["http:\/\/www.nissan.com.vn\/wp-content\/uploads\/2016\/10\/16TDI_ALTmyu065_0001-1200x640.jpg",
// "http:\/\/www.nissan.com.vn\/wp-content\/uploads\/2016\/10\/16TDI_ALTmyu065_0008-1200x704.jpg",
// "http:\/\/www.nissan.com.vn\/wp-content\/uploads\/2016\/10\/16TDI_ALTmyu065_0007-1200x704.jpg",
// "http:\/\/www.nissan.com.vn\/wp-content\/uploads\/2016\/10\/16TDI_ALTmyu065_0006-1200x704.jpg",
// "http:\/\/www.nissan.com.vn\/wp-content\/uploads\/2016\/10\/16TDI_ALTmyu065_0005-1200x704.jpg",
// "http:\/\/www.nissan.com.vn\/wp-content\/uploads\/2016\/10\/16TDI_ALTmyu065_0004-1200x704.jpg",
// "http:\/\/www.nissan.com.vn\/wp-content\/uploads\/2016\/10\/16TDI_ALTmyu065_0003-1200x704.jpg",
// "http:\/\/www.nissan.com.vn\/wp-content\/uploads\/2016\/10\/16TDI_ALTmyu065_0002-1200x704.jpg"]';
//}
// tinh toan du toan chi phi
// hien thi ket qua tinh toan chi phi
if($action=='price_calculator_action')
{
	//gia xe
	$vehicle_price = $_GET['vehicle_price']? $_GET['vehicle_price'] : $_POST['vehicle_price'];
	//loai xe
	$vehicle_id = $_GET['vehicle_id']? $_GET['vehicle_id'] : $_POST['vehicle_id'];
	//vung
	$vehicle_location = $_GET['vehicle_location']? $_GET['vehicle_location'] : $_POST['vehicle_location'];
	//thong tin vung
	for ($row = 0; $row < 5; $row++) {
		if($locations[$row][1]== $vehicle_location){
			$truocba	= $locations[$row][2];
			$dangky		= $locations[$row][3];
		}
	}
	//thong tin xe
	for ($row = 0; $row < 33; $row++) {
		if($cars[$row][1]== $vehicle_id && $cars[$row][6]==$vehicle_price){
			$baohiemds	= $cars[$row][7];
			$loaixe		= $cars[$row][3];
		}
	}
	$dangkiem	= 340000;
	$phiduongbo	= 1560000;
	$vatchat	= 1.5*$vehicle_price/100;
	$truocba_v 	= $truocba*$vehicle_price/100;
	$total_dk	= $truocba_v+$dangky+$dangkiem+$phiduongbo+$baohiemds;
	$total_car	= $total_dk+$vehicle_price+$vatchat;
	echo '
	<p><h2>'.$loaixe.'</h2></p>
	<p class="price">Giá (VNĐ) <span>'.number_format($vehicle_price).'</span></p>
	<p>Phí trước bạ (<strong>'.$truocba.'%</strong>) <span>'.number_format($truocba_v).'</span></p>
	<p>Phí đăng ký <span>'.number_format($dangky).'</span></p>
	<p>Phí đăng kiểm <span>'.number_format($dangkiem).'</span></p>
	<p>Phí sử dụng đường bộ/Năm <span>'.number_format($phiduongbo).'</span></p>
	<p>Bảo hiểm TNDS <span>'.number_format($baohiemds).'</span></p>
	<p>Bảo hiểm Vật chất (<strong>1.5%</strong>) <span>'.number_format($vatchat).'</span></p>
	<p class="fee">Tổng chi phí đăng ký  <span>'.number_format($total_dk).'</span></p>
	<p class="total">TỔNG CỘNG <span>'.number_format($total_car).'</span></p>';
}
//du toan chi phi
if($action=='vehicle_get_version' && $vehicle!=''){
echo '<label>Phiên bản (*)</label> <select id="price_calculator_version_select" name="vehicle_price" class="select" required autocomplete="off">';
for ($row = 0; $row < 33; $row++) {
	if($cars[$row][1]== $vehicle)
		echo '<option value="'.$cars[$row][6].'">'.$cars[$row][3].' ('.number_format($cars[$row][6]).')</option>';
}
echo '</select>';
}
// tinh toan du toan vay tra gop
// hien thi ket qua tinh toan vay tra gop
if($action=='loan_calculator_action')
{
	//gia xe
	$vehicle_price = $_GET['vehicle_price']? $_GET['vehicle_price'] : $_POST['vehicle_price'];
	//loai xe
	$vehicle_id = $_GET['vehicle_id']? $_GET['vehicle_id'] : $_POST['vehicle_id'];
	//thoihanay
	$thoihanvay = $_GET['thoihanvay']? $_GET['thoihanvay'] : $_POST['thoihanvay'];
	//tientratruoc
	$tientratruoc = $_GET['tientratruoc']? $_GET['tientratruoc'] : $_POST['tientratruoc'];
	//laisuat
	$laisuat = $_GET['laisuat']? $_GET['laisuat'] : $_POST['laisuat'];
	
	//thong tin xe
	for ($row = 0; $row < 33; $row++) {
		if($cars[$row][1]== $vehicle_id && $cars[$row][6]==$vehicle_price){
			$baohiemds	= $cars[$row][7];
			$loaixe		= $cars[$row][3];
		}
	}
	$tientratruoc 	= ereg_replace("[^0-9]", "", $tientratruoc); 
	$tienvay 		= $vehicle_price-$tientratruoc;
	$kyvay 			= $thoihanvay*12;
	$tragoc 		= $tienvay/$kyvay;
	$tralai 		= $laisuat*$goclai/100;
	$duno			= $tienvay;
	$goclai			= $tralai+$tragoc;
	$laisuatnam		= $laisuat*12;
	echo '
	<p><h2>Dự toán chi phí trả góp xe '.$loaixe.'</h2></p>
	<p class="price">Giá (VNĐ) <span>'.number_format($vehicle_price).'</span></p>
	<p>Tiền trả trước <span>'.number_format($tientratruoc).'</span></p>
	<p>Tiền vay <span>'.number_format($tienvay).'</span></p>
	<p>Thời hạn vay <span>'.$thoihanvay.' Năm</span></p>
	<p>Lãi suất vay <span>'.$laisuat.'%/Tháng</span></p>
	<p class="fee">Trả gốc hàng tháng<span>'.number_format($tragoc).'</span></p>
	<p ><table style="width:100%">
	<tr><th>Kỳ</th><th>Dư nợ</th><th>Tiền lãi</th><th>Gốc+Lãi</th></tr>';
	// = '.$laisuatnam.'%/Năm
	for ($row = 1; $row <= $kyvay; $row++) {
		$tralai = $laisuat*$duno/100;
		$goclai = $tralai+$tragoc;
		echo'<tr><td>'.$row.'</td><td>'.number_format($duno).'</td><td>'.number_format($tralai).'</td><td>'.number_format($goclai).'</td></tr>';
		$duno = $duno-$tragoc;
		$total_tienlai = $total_tienlai+$tralai;
		$total_goclai = $total_goclai+$goclai;
	}
	//echo '<tr><td></td><td> </td><td>'.number_format($total_tienlai).'</td><td>'.number_format($total_goclai).'</td></tr>';
	echo'
	</table>
	</p>
	<p class="fee">Tổng lãi phải trả: <span>'.number_format($total_tienlai).'</span></p>
	<p class="total">Tổng phải trả:<span>'.number_format($total_goclai).'</span></p>';
}
// ham chon ma mau xe 
function vehicle_color_select($link, $vcolor, $imgcount){
   for ($x = 1; $x <= $imgcount; $x++) {
      if($imgcount==1){
         echo '["'.$link.$vcolor.'\/'.$vcolor.'_00'.$x.'.png"]';
      }
      else
      {
         if($x==1)
        echo '["'.$link.$vcolor.'\/'.$vcolor.'_00'.$x.'.png",';
      elseif($x==$imgcount)
        echo '"'.$link.$vcolor.'\/'.$vcolor.'_0'.$x.'.png"]';
      elseif($x<10)
        echo '"'.$link.$vcolor.'\/'.$vcolor.'_00'.$x.'.png",';
      else
        echo '"'.$link.$vcolor.'\/'.$vcolor.'_0'.$x.'.png",';
      }
  }
}
// danh sach mau xe
//vios color
if($action=="vehicle_color_select" && $post==1022 && $color==1)
{
  $vcolor='1D4';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/vios\/360\/';
  vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1022 && $color==2)
{
  $vcolor='218';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/vios\/360\/';
   vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1022 && $color==3)
{
  $vcolor='4R0';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/vios\/360\/';
   vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1022 && $color==4)
{
  $vcolor='040';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/vios\/360\/';
   vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1022 && $color==5)
{
  $vcolor='089';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/vios\/360\/';
vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1022 && $color==6)
{
  $vcolor='3R3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/vios\/360\/';
vehicle_color_select($link,$vcolor,16);
}
//yaris color
if($action=="vehicle_color_select" && $post==1021 && $color==1)
{
  $vcolor='1D6';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yaris\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1021 && $color==2)
{
  $vcolor='6W2';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yaris\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1021 && $color==3)
{
  $vcolor='3R3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yaris\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1021 && $color==4)
{
  $vcolor='040';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yaris\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1021 && $color==5)
{
  $vcolor='1G3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yaris\/360\/';
vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1021 && $color==6)
{
  $vcolor='218';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yaris\/360\/';
vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1021 && $color==7)
{
  $vcolor='4R8';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yaris\/360\/';
vehicle_color_select($link,$vcolor,1);
}
//corolla color
if($action=="vehicle_color_select" && $post==1023 && $color==1)
{
  $vcolor='1K3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/corolla\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1023 && $color==2)
{
  $vcolor='218';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/corolla\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1023 && $color==3)
{
  $vcolor='3R3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/corolla\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1023 && $color==4)
{
  $vcolor='1D6';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/corolla\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1023 && $color==5)
{
  $vcolor='089';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/corolla\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
//camry color
if($action=="vehicle_color_select" && $post==1024 && $color==1)

{
  $vcolor='1D4';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/camry\/360\/';
  vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1024 && $color==2)
{
  $vcolor='3T3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/camry\/360\/';
   vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1024 && $color==3)
{
  $vcolor='4W9';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/camry\/360\/';
   vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1024 && $color==4)
{
  $vcolor='4X7';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/camry\/360\/';
   vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1024 && $color==5)
{
  $vcolor='089';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/camry\/360\/';
   vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1024 && $color==6)
{
  $vcolor='218';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/camry\/360\/';
   vehicle_color_select($link,$vcolor,16);
}
elseif($action=="vehicle_color_select" && $post==1024 && $color==7)
{
  $vcolor='222';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/camry\/360\/';
   vehicle_color_select($link,$vcolor,16);
}
//innova color
if($action=="vehicle_color_select" && $post==1025 && $color==1)
{
  $vcolor='4V8';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/innova\/360\/';
  vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1025 && $color==2)
{
  $vcolor='1H2';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/innova\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1025 && $color==3)
{
  $vcolor='1D6';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/innova\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1025 && $color==4)
{
  $vcolor='089';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/innova\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1025 && $color==5)
{
  $vcolor='218';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/innova\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
//fortuner color
if($action=="vehicle_color_select" && $post==1026 && $color==1)
{
  $vcolor='4W9';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/fortuner\/360\/';
  vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1026 && $color==2)
{
  $vcolor='1D6';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/fortuner\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1026 && $color==3)
{
  $vcolor='218';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/fortuner\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1026 && $color==4)
{
  $vcolor='1G3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/fortuner\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1026 && $color==5)
{
  $vcolor='070';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/fortuner\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
//land cruiser prado color
if($action=="vehicle_color_select" && $post==1027 && $color==1)
{
  $vcolor='3R3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landprado\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1027 && $color==2)
{
  $vcolor='4V8';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landprado\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1027 && $color==3)
{
  $vcolor='4X4';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landprado\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1027 && $color==4)
{
  $vcolor='070';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landprado\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1027 && $color==5)
{
  $vcolor='1F7';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landprado\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1027 && $color==6)
{
  $vcolor='1G3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landprado\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1027 && $color==7)
{
  $vcolor='221';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landprado\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1027 && $color==8)
{
  $vcolor='202';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landprado\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1027 && $color==9)
{
  $vcolor='4T3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landprado\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1027 && $color==10)
{
  $vcolor='6V4';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landprado\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
//land cruiser color
if($action=="vehicle_color_select" && $post==1028 && $color==1)
{
  $vcolor='070';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landcruiser\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1028 && $color==2)
{
  $vcolor='1F7';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landcruiser\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1028 && $color==3)
{
  $vcolor='1G3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landcruiser\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1028 && $color==4)
{
  $vcolor='202';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landcruiser\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1028 && $color==5)
{
  $vcolor='4V8';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landcruiser\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1028 && $color==6)
{
  $vcolor='218';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landcruiser\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1028 && $color==7)
{
  $vcolor='8X8';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/landcruiser\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
//Hilux color
if($action=="vehicle_color_select" && $post==1029 && $color==1)
{
  $vcolor='070';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/hilux\/360\/';
  vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1029 && $color==2)
{
  $vcolor='1D6';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/hilux\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1029 && $color==3)
{
  $vcolor='1G3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/hilux\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1029 && $color==4)
{
  $vcolor='218';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/hilux\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1029 && $color==5)
{
  $vcolor='3T6';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/hilux\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
elseif($action=="vehicle_color_select" && $post==1029 && $color==6)
{
  $vcolor='4R8';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/hilux\/360\/';
   vehicle_color_select($link,$vcolor,36);
}
//Hiace color
if($action=="vehicle_color_select" && $post==1030 && $color==1)
{
  $vcolor='058';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/hiace\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1030 && $color==2)
{
  $vcolor='1E7';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/hiace\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
//wigo color
if($action=="vehicle_color_select" && $post==1032 && $color==1)//1 thứ tự đầu tiên  //post là mã trùng với với .haccess 1032 của wigo
{
  $vcolor='R79'; //'W09 là mãu màu sếp vị trí đầu tiên trong file wigo.php cũng phải đầu tiên'
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/wigo\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1032 && $color==2)
{
  $vcolor='R80';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/wigo\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1032 && $color==3)
{
  $vcolor='S28';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/wigo\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1032 && $color==4)
{
  $vcolor='W09';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/wigo\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1032 && $color==5)
{
  $vcolor='1G3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/wigo\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1032 && $color==6)
{
  $vcolor='218';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/wigo\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1032 && $color==7)
{
  $vcolor='4R8';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/wigo\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
//cross color
if($action=="vehicle_color_select" && $post==1033 && $color==1)//1 thứ tự đầu tiên  //post là mã trùng với với .haccess 1032 của wigo
{
  $vcolor='1K3'; //'1D6 là mãu màu sếp vị trí đầu tiên trong file wigo.php cũng phải đầu tiên'
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/cross\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1033 && $color==2)
{
  $vcolor='218';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/cross\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1033 && $color==3)
{
  $vcolor='3R3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/cross\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1033 && $color==4)
{
  $vcolor='089';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/cross\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1033 && $color==5)
{
  $vcolor='1K0';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/cross\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1033 && $color==6)
{
  $vcolor='4X7';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/cross\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1033 && $color==7)
{
  $vcolor='8X2';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/cross\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
//raize color 
if($action=="vehicle_color_select" && $post==1034 && $color==1)//1 thứ tự đầu tiên  //post là mã trùng với với .haccess 1034 của raize 
{
  $vcolor='r40'; //'1D6 là mãu màu sếp vị trí đầu tiên trong file wigo.php cũng phải đầu tiên'
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/raize\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1034 && $color==2)
{
  $vcolor='x13';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/raize\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1034 && $color==3)
{
  $vcolor='w25';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/raize\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1034 && $color==4)
{
  $vcolor='xj7';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/raize\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1034 && $color==5)
{
  $vcolor='xj8';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/raize\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1034 && $color==6)
{
  $vcolor='xj9';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/raize\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1034 && $color==7)
{
  $vcolor='xk1';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/raize\/360\/';
   vehicle_color_select($link,$vcolor,1);
}

//veloz color 
if($action=="vehicle_color_select" && $post==1035 && $color==1)//1 thứ tự đầu tiên  //post là mã trùng với với .haccess 1035 của veloz 
{
  $vcolor='089'; //'089 là mãu màu sếp vị trí đầu tiên trong file veloz.php cũng phải đầu tiên'
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/veloz\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1035 && $color==2)
{
  $vcolor='P20';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/veloz\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1035 && $color==3)
{
  $vcolor='S28';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/veloz\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1035 && $color==4)
{
  $vcolor='X12';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/veloz\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1035 && $color==5)
{
  $vcolor='3Q3';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/veloz\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
//avanza color 
if($action=="vehicle_color_select" && $post==1036 && $color==1)//1 thứ tự đầu tiên  //post là mã trùng với với .haccess 1035 của veloz 
{
  $vcolor='W09'; //'089 là mãu màu sếp vị trí đầu tiên trong file veloz.php cũng phải đầu tiên'
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/avanza\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1036 && $color==2)
{
  $vcolor='P20';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/avanza\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1036 && $color==3)
{
  $vcolor='S28';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/avanza\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1036 && $color==4)
{
  $vcolor='X12';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/avanza\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
//yaris cross
if($action=="vehicle_color_select" && $post==1037 && $color==1)//1 thứ tự đầu tiên  //post là mã trùng với với .haccess 1035 của veloz 
{
  $vcolor='218'; //'089 là mãu màu sếp vị trí đầu tiên trong file veloz.php cũng phải đầu tiên'
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yariscross\/360\/';
  vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1037 && $color==2)
{
  $vcolor='089';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yariscross\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1037 && $color==3)
{
  $vcolor='XM1';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yariscross\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1037 && $color==4)
{
  $vcolor='2PS';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yariscross\/360\/';
   vehicle_color_select($link,$vcolor,1);
}
elseif($action=="vehicle_color_select" && $post==1037 && $color==5)
{
  $vcolor='XM2';
  $link='http:\/\/toyota.quangninh.vn\/images\/product\/yariscross\/360\/';
   vehicle_color_select($link,$vcolor,1);
}

?>