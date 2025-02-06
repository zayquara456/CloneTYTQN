<?php
$page = $_GET['page'] ? $_GET['page'] : $_POST['page'];
$author = 'Toyota Quảng Ninh';
$title 	= 'Toyota Quảng Ninh';
$company = 'CÔNG TY TNHH MTV TOYOTA QUẢNG NINH';
$address = 'Tổ 94 - Khu Đồn Điền - Phường Hà Khẩu - TP. Hạ Long - Quảng Ninh';
$hotline = '0971.54.2222';
$email = 'kinhdoanh@toyota.quangninh.vn';
$urlsite = "http://toyota.quangninh.vn";
$website = 'http://toyota.quangninh.vn';
$home_description ='Trang chủ chính thức của Toyota Quảng Ninh.';
date_default_timezone_set('asia/ho_chi_minh');
//cau hinh website
$google_verification = "qKLRTANXDHakEmR0H7RxSoxNU7WOiYbUTFC-ibUTyQ4";
$folder_site = '';

if ($folder_site!=""){
    $urlsite=$urlsite."/".$folder_site;
}
//lua chon description
$description = "home";

switch ($description) {
    case "red":
        echo "Your favorite color is red!";
        break;
    case "blue":
        echo "Your favorite color is blue!";
        break;
    case "green":
        echo "Your favorite color is green!";
        break;
    default:
        $description= $home_description;
}
$vehicle_price_list='<div class="vehicle-call-to-action pricing">
			<a download href="'.$urlsite.'/document/bang-gia-xe-toyota.pdf"><small>Tải về </small> Bảng giá</a>

		</div>';
$title_tag 	= 'TOYOTA QUẢNG NINH - BẢNG GIÁ, KHUYẾN MẠI 2020 TOYOTA VIỆT NAM';
$description_tag = 'Toyota Quảng Ninh là Đại lý của Toyota Việt Nam Chuyên cung cấp dòng xe Toyota Vios, Innova, Camry, Corolla dai ly toyota, gia xe toyota quang ninh.';
$keyword_tag = 'Toyota quảng ninh, toyota vios, toyota innova, toyota quang ninh, dai ly toyota quang ninh, dai ly toyota, giá xe toyota vios';
// meta data news
$news_title = '';
$news_keyword = '';
$new_description = '';
if ($page=="news_detail")
{
	$where="";
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$t = isset($_GET['t']) ? $_GET['t'] : "";
	$c = isset($_GET['c']) ? $_GET['c'] : "";
	if($id!=0)
		$where.="n.id=$id AND ";
	if($t!="")
		$where.="n.permalink='$t' AND ";
	if($c!="")
		$where.="c.permalink='$c' AND ";
	$result = $db->sql_query("SELECT n.id, n.catid, c.title, c.parent, n.title, n.othershow, n.time, n.hometext, n.bodytext, n.seo_title, n.seo_description, n.seo_keyword, n.seo_tag, n.fattach, n.news_type, n.images, n.imgtext, n.source, n.imgshow, n.hits FROM ".$prefix."_news AS n,".$prefix."_news_cat AS c WHERE $where n.catid=c.catid AND n.alanguage='$currentlang'");

	if($db->sql_numrows($result) != 1) {
		die('ssssssssssss');
	}

	list($nid, $ncatid, $ncatname, $nparent, $ntitle, $nothershow, $ntime, $nhometext, $nbodytext, $ntitle_seo, $ndescription_seo, $nkeyword_seo, $ntag_seo, $nfattach, $nnews_type, $nimages, $nimgtext, $nsource, $nimgshow,$nhits ) = $db->sql_fetchrow($result);
	if($ntitle_seo=="") $news_title = $ntitle;
	else $news_title = $ntitle_seo;
	$news_keyword = $nkeyword_seo;
	if($ndescription_seo!="") $new_description = $ndescription_seo;
	else $new_description = $nhometext;
}
switch ($page) {
    case "vehicle-yaris":
        $title_tag = "GIÁ XE TOYOTA YARIS - TOYOTA QUẢNG NINH";
		$description_tag = 'Toyota Quảng Ninh Chuyên cung cấp dòng xe Yaris, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
    case "vehicle-vios":
        $title_tag = "GIÁ XE TOYOTA VIOS - TOYOTA QUẢNG NINH";
		$description_tag = 'Giá xe Toyota Vios tốt nhất Quảng Ninh. Với 100tr bạn có thể sở hữu chiếc xe có số bán tốt nhất Việt Nam.'.$hotline;
        break;
    case "vehicle-corolla-altis":
        $title_tag = "GIÁ XE TOYOTA COROLLA ALTIS - TOYOTA QUẢNG NINH";
		$description_tag = 'Xe ô tô Toyota Corolla Altis là mẫu sedan hạng C cỡ trung được khách hàng Việt rất tin cậy và yêu thích. Giá xe Toyota Altis 2020 mới tốt nhất liên hê:'.$hotline;
        break;
	case "vehicle-camry":
        $title_tag = "GIÁ XE TOYOTA CAMRY - TOYOTA QUẢNG NINH";
		$description_tag = 'Toyota Quảng Ninh Chuyên cung cấp dòng xe Camry, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
	case "vehicle-innova":
        $title_tag = "GIA XE TOYOTA INNOVA  - TOYOTA QUẢNG NINH";
		$description_tag = 'Toyota Quảng Ninh Chuyên cung cấp dòng xe Innova, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
	case "vehicle-fortuner":
        $title_tag = "GIÁ XE TOYOTA FORTUNER - TOYOTA QUẢNG NINH";
		$description_tag = 'Toyota Quảng Ninh Chuyên cung cấp dòng xe Fortuner, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
	case "vehicle-land-cruiser":
        $title_tag = "GIÁ XE TOYOTA LAND CRUISER - TOYOTA QUẢNG NINH";
		$description_tag = 'Toyota Quảng Ninh Chuyên cung cấp dòng xe Land Cruiser, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
	case "vehicle-land-cruiser-prado":
        $title_tag = "GIÁ XE TOYOTA LAND CRUISER PRADO - TOYOTA QUẢNG NINH";
		$description_tag = 'Toyota Quảng Ninh Chuyên cung cấp dòng xe Land Cruiser Prado, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
	case "vehicle-hilux":
        $title_tag = "GIÁ XE TOYOTA HILUX - TOYOTA QUẢNG NINH";
		$description_tag = 'Toyota Quảng Ninh Chuyên cung cấp dòng xe Hilux, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
	case "vehicle-hiace":
        $title_tag = "GIÁ XE TOYOTA HIACE - TOYOTA QUẢNG NINH";
		$description_tag = 'Toyota Quảng Ninh Chuyên cung cấp dòng xe Hiace, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
	case "bang-gia-xe":
        $title_tag = "BẢNG GIÁ XE MỚI NHẤT 2020 TOYOTA QUẢNG NINH";
		$description_tag = 'Giá xe Toyota Vios, Camry, Corolla Altis, Innova năm 2020 tại quảng ninh. Giá tốt nhất, mua xe chỉ cần 100tr-300tr. liên hê:'.$hotline;
        break;
	case "du-toan-chi-phi":
        $title_tag = "Bảng tính dự toán chi phí mua xe Toyota Tại Quảng Ninh 2020";
		$description_tag = 'Bảng tính dự toán chi phí mua xe Toyota Vios, Camry, Corolla Altis, Innova năm 2020 tại quảng ninh. mua xe chỉ cần 100tr-300tr. liên hê:'.$hotline;
        break;
	case "cach-tinh-lai-suat-cho-vay-mua-xe-oto-tra-gop":
        $title_tag = "Cách tính mua xe trả góp xe Toyota Tại Quảng Ninh 2020";
		$description_tag = 'Cách tính mua xe trả góp xe Toyota Vios, Camry, Corolla Altis, Innova, fortuner năm 2020 tại quảng ninh. mua xe chỉ cần 100tr-300tr. liên hê:'.$hotline;
        break;

	case "gioi-thieu-toyota-quang-ninh":
        $title_tag = "GIỚI THIỆU VỀ TOYOTA QUẢNG NINH";
		$description_tag = 'Toyota Quảng Ninh là đại lý chính thức của Toyota Việt Nam được ủy quyền duy nhất tại Quảng Ninh. Luôn luôn phục vụ khách hàng Tận tâm - chuyên nghiệp. liên hê:'.$hotline;
        break;
	case "lien-he":
        $title_tag = "LIÊN HỆ ĐẠI LÝ TOYOTA QUẢNG NINH";
		$description_tag = 'Đại lý Toyota Tại Quảng Ninh. Bạn có thể liên hệ với chúng tôi để được tư vấn các vấn đề liên quan đến xe, sửa chữa, mua xe mới. liên hê:'.$hotline;
        break;
	case "bao-duong-dinh-ky":
        $title_tag = "Bảo dưỡng định kỳ - Đại lý Toyota tại Quảng Ninh";
		$description_tag = 'Thông tin bảo dưỡng định kỳ. Bảo dưỡng nhỏ (mỗi 5000km): 5.000 – 15.000 – 25.000 – 35.000 – 45.000 km. Bảo dưỡng Trung bình (mỗi 10000km): 10.000 – 30.000 – 50.000 – 70.000 – 90.000 km. liên hê:'.$hotline;
        break;
	case "dich-vu-sua-chua":
        $title_tag = "Dịch vụ sửa chữa - Đại lý Toyota tại Quảng Ninh";
		$description_tag = 'Thông tin dịch vụ sửa chữa. Bạn có thể liên hệ với chúng tôi để được tư vấn các vấn đề liên quan đến xe, sửa chữa, mua xe mới. liên hê:'.$hotline;
        break;
	case "thu-tuc-dang-ky-va-dang-kiem-xe":
        $title_tag = "Thủ tục đăng lý và đăng kiểm xe - Đại lý Toyota tại Quảng Ninh";
		$description_tag = 'Với sự hỗ trợ tốt nhất từ Totota Quảng Ninh, khách hàng sử dụng xe Totota sẽ có trải nghiệm thú vị và tự tin khi tự thực hiện các công việc Đăng ký – Đăng kiểm xe (*). liên hê:'.$hotline;
        break;
	case "chinh-sach-va-dieu-khoan":
        $title_tag = "Chính sách và điều khoản - Đại lý Toyota tại Quảng Ninh";
		$description_tag = 'Chính sách và điều khoản. Tên, logo, tên sản phẩm, tên đặc tính, và khẩu hiệu Toyota đều là thương hiệu thuộc sở hữu hoặc đăng ký của Công ty Ô tô Toyota Việt Nam và/hoặc Toyota Việt Nam. liên hê:'.$hotline;
        break;
	case "bao-hiem-xe":
        $title_tag = "BẢO HIỂM XE TOYOTA - Đại lý Toyota tại Quảng Ninh";
		$description_tag = 'BẢO HIỂM XE TOYOTA. Khách hàng sẽ luôn an tâm và hài lòng với dịch vụ chăm sóc và bảo hiểm xe Toyota từ các đối tác Bảo hiểm uy tín hàng đầu tại Việt Nam. liên hê:'.$hotline;
        break;
	case "tim-duong-di":
        $title_tag = "Tìm đường đi - Đại lý Toyota tại Quảng Ninh";
		$description_tag = 'Tìm đường đi TOYOTA QUẢNG NINH Đại Lý Chính Thức Toyota Việt Nam Tại Quảng Ninh. liên hê:'.$hotline;
        break;
	case "news":
        $title_tag = "Tin tức & sự kiện, khuyến mại, tuyển dụng tại Đại lý Toyota Quảng Ninh";
		$description_tag = 'Tin tức & sự kiện, khuyến mại, tuyển dụng tại Đại lý Toyota Quảng Ninh. liên hê:'.$hotline;
        break;
	case "news_detail":
        $title_tag = $news_title;
		$description_tag = $new_description;
        break;
    default:
        $title_tag= $title_tag;
}
//Lay ra mot doan trong chuoi van ban
function cat_chuoi($string, $num){
        if(strlen($string) > $num)
        {
            $result = substr($string,0,$num); //cut string with limited number
            $position = strrpos($result," "); //find position of last space
            if($position)
                $result = substr($result,0,$position); //cut string again at last space if there are space in the result above
            $result .= '';
        }
        else {
            $result = $string;
        }
        return $result;
}
//$title_tag = cat_chuoi($title_tag,78);
//$description_tag = cat_chuoi($description_tag,175);

$cars = array
  (
  // stt, loai xe, ma xe, kieu xe, ma kieu xe, xuat xu, gia tien, bao hiem ds, vat chat, phi kiem dinh,
  array(0,"yaris","YG","Yaris G (CVT)","NSP151L-AHXGKU","CBU",650000000,480700),
  array(1,"yaris","YE","Yaris E (CVT)","NSP151L-AHXRKU","CBU",592000000,480700),
  array(2,"vios","VG","Vios G (CVT)","NSP151L-BEXGKU","CKD",570000000,480700),
  array(3,"vios","VE","Vios E (CVT)","NSP151L-BEXGKU","CKD",540000000,480700),
  array(4,"vios","VK","Vios E (CVT)","NSP151L-BEMRKU","CKD",520000000,480700),
  array(5,"vios","VE","Vios E (MT)","NSP151L-BEMRKU","CKD",470000000,480700),
  array(6,"corolla","CQ","Corolla Altis 2.0V Sport","ZRE173L-GEXVKH","CKD",932000000,480700),
  array(7,"corolla","CZ","Corolla Altis 2.0V (CVT-i)","ZRE173L-GEXVKH","CKD",889000000,480700),
  array(8,"corolla","CV","Corolla Altis 1.8G (CVT)","ZRE173L-GEXVKH","CKD",791000000,480700),
  array(9,"corolla","CK","Corolla Altis 1.8E (CVT)","ZRE173L-GEXVKH","CKD",733000000,480700),
  array(10,"corolla","CG","Corolla Altis 1.8E (MT)","ZRE173L-GEXVKH","CKD",697000000,480700),
  array(11,"camry","KZ","Camry 2.5Q","ASV50L-JETEKU","CKD",1235000000,480700),
  array(12,"camry","KL","Camry 2.5G","ASV50L-JETEKU","CKD",1161000000,480700),
  array(13,"camry","KE","Camry 2.0G","ASV50L-JETEKU","CKD",1029000000,480700),
  array(14,"innova","IV","Innova 2.0V","ASV50L-JETEKU","CKD",971000000,837400),
  array(15,"innova","IG","Innova 2.0G","ASV50L-JETEKU","CKD",847000000,837400),
  array(16,"innova","IE","Innova 2.0E","ASV50L-JETEKU","CKD",771000000,837400),
  //array(16,"innova","IJ","Innova 2.0J","ASV50L-JETEKU","CKD",712000000,837400),
  array(17,"fortuner","FV","Fortuner 2.8 4x4","ASV50L-JETEKU","CKD",1354000000,837400),
  array(18,"fortuner","FX","Fortuner 2.7V 4x2","ASV50L-JETEKU","CBU",1150000000,837400),
  array(19,"fortuner","FG","Fortuner 2.4G 4x2","ASV50L-JETEKU","CKD",1096000000,837400),
  array(20,"land","LC","Land Cruiser VX","ASV50L-JETEKU","CBU",4030000000 ,837400),
  array(21,"prado","LP","Land Cruiser Prado TX-L","ASV50L-JETEKU","CBU",2342000000,837400),
  array(22,"hilux","HQ","Hilux 2.8G 4x4 AT","ASV50L-JETEKU","CBU",878000000,1026300),
  array(23,"hilux","HG","Hilux 2.4E 4x4 MT","ASV50L-JETEKU","CBU",772000000,1026300),
  array(24,"hilux","HK","Hilux 2.4E 4x2 AT","ASV50L-JETEKU","CBU",662000000,1026300),
  array(25,"hiace","HD","Hiace Động cơ dầu","ASV50L-JETEKU","CBU",1176000000,1397000),
  array(26,"hiace","HC","Hiace Động cơ xăng","ASV50L-JETEKU","CBU",1176000000,1397000),
  array(27,"alphard","AP","Alphard","ASV50L-JETEKU","CBU",4038000000,837400),
  array(28,"innova","IGM","Innova Venturer","ASV50L-JETEKU","CKD",879000000,837400),
  array(29,"fortuner","FK","Fortuner 2.4G AT 4x2","ASV50L-JETEKU","CBU",1033000000,837400),
  array(30,"fortuner","FXS","Fortuner TRD 2.7 AT 4x2","ASV50L-JETEKU","CKD",1199000000,837400),
  array(31,"hilux","HE","Hilux 2.4E 4x2 MT","ASV50L-JETEKU","CBU",622000000,1026300),
  );
 $locations = array
  (
  // stt, ten vung, phi truoc ba, phi dang ky
  array(0,"quangninh",12,1000000),
  array(0,"hanoi",12,20000000),
  array(0,"kvii",12,1000000),
  array(0,"kviii",10,1000000)
  );
?>
