<?php
$page = $_GET['page'] ? $_GET['page'] : $_POST['page'];
$author = 'Vinfast Bãi Cháy';
$title 	= 'Vinfast Bãi Cháy';
$company = 'VINFAST PHONG NGUYÊN';
$address = 'Ô số 83 Đông Ga,Phường Giếng Đáy,Hạ Long,Quảng Ninh, Ha Long, Vietnam';
$hotline = '0569.323.456';
$email = 'khangpradoluxury@gmail.com';
$urlsite = "";
$website = '';
$home_description ='Trang chủ chính thức của Vinfast Bãi Cháy.';
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
			<a download href="#"><small>Tải về </small> Bảng giá</a>

		</div>';
$title_tag 	= 'VINFAST BÃI CHÁY - BẢNG GIÁ, KHUYẾN MẠI 2025 VINFAST VIỆT NAM';
$description_tag = 'VinFast Quảng Ninh - Showroom VinFast Bãi Cháy là Showroom VinFast chính hãng tại Quảng Ninh. Vinfast Hạ Long là Showroom chuẩn 3S với dịch vụ sau bán hàng';
$keyword_tag = 'vinfast bãi cháy, VF3, VF2, vinfast quang ninh, dai ly vinfast quang ninh, dai ly vinfast, giá xe vinfast';
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
    	case "bang-gia-xe":
        $title_tag = "BẢNG GIÁ XE MỚI NHẤT 2025";
		$description_tag = 'Giá xe Vinfast vf3, vf5, vf6, vf7, vf8, vf9 năm 2025 tại quảng ninh. Giá tốt nhất, mua xe chỉ cần 100tr-300tr. liên hê:'.$hotline;
        break;
          case "vehicle-vf3":
        $title_tag = "GIÁ XE VINFAST VF3 - VINFAST PHONG NGUYÊN BÃI CHÁY";
		$description_tag = 'Vinfast Phong Nguyên Bãi Cháy Chuyên cung cấp dòng xe VF3, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
    case "vehicle-vf5":
        $title_tag = "GIÁ XE VINFAST VF5 - VINFAST PHONG NGUYÊN BÃI CHÁY";
		$description_tag = 'Vinfast Phong Nguyên Bãi Cháy Chuyên cung cấp dòng xe VF5, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
    case "vehicle-vf6":
        $title_tag = "GIÁ XE VINFAST VF6 - VINFAST PHONG NGUYÊN BÃI CHÁY";
		$description_tag = 'Giá xe Vinfast VF6 tốt nhất Quảng Ninh. Với 200tr bạn có thể sở hữu chiếc xe có số bán tốt nhất Việt Nam.'.$hotline;
        break;
    case "vehicle-vf7":
        $title_tag = "GIÁ XE VINFAST VF7 - VINFAST PHONG NGUYÊN BÃI CHÁY";
		$description_tag = 'Xe ô tô Vinfast VF6 là mẫu CUV hạng B cỡ trung được khách hàng Việt rất tin cậy và yêu thích. Giá xe VF6 2025 mới tốt nhất liên hê:'.$hotline;
        break;
	case "vehicle-vf8":
        $title_tag = "GIÁ XE VINFAST VF8 - VINFAST PHONG NGUYÊN BÃI CHÁY";
		$description_tag = 'Vinfast Phong Nguyên Bãi Cháy Chuyên cung cấp dòng xe VF8, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
	case "vehicle-vf9":
        $title_tag = "GIÁ XE VINFAST VF9 - VINFAST PHONG NGUYÊN BÃI CHÁY";
		$description_tag = 'Vinfast Phong Nguyên Bãi Cháy Chuyên cung cấp dòng xe VF9, với nhiều khuyến mại hấp dẫn. Chi tiết xin liên hê:'.$hotline;
        break;
	case "du-toan-chi-phi":
        $title_tag = "Bảng tính dự toán chi phí mua xe Vinfast Tại Quảng Ninh 2025";
		$description_tag = 'Bảng tính dự toán chi phí mua xe Vinfast Vios, Camry, Corolla Altis, Innova năm 2025 tại quảng ninh. mua xe chỉ cần 100tr-300tr. liên hê:'.$hotline;
        break;
	case "cach-tinh-lai-suat-cho-vay-mua-xe-oto-tra-gop":
        $title_tag = "Cách tính mua xe trả góp xe Vinfast Tại Quảng Ninh 2025";
		$description_tag = 'Cách tính mua xe trả góp xe Vinfast vf3, vf5, vf6, vf7 năm 2025 tại quảng ninh. mua xe chỉ cần 100tr-300tr. liên hê:'.$hotline;
        break;

	case "gioi-thieu-toyota-quang-ninh":
        $title_tag = "GIỚI THIỆU VỀ VINFAST PHONG NGUYÊN BÃI CHÁY";
		$description_tag = 'Vinfast Phong Nguyên Bãi Cháy là đại lý chính thức của Vinfast Việt Nam được ủy quyền duy nhất tại Quảng Ninh. Luôn luôn phục vụ khách hàng Tận tâm - chuyên nghiệp. liên hê:'.$hotline;
        break;
	case "lien-he":
        $title_tag = "LIÊN HỆ ĐẠI LÝ VINFAST PHONG NGUYÊN BÃI CHÁY";
		$description_tag = 'Đại lý Vinfast Tại Quảng Ninh. Bạn có thể liên hệ với chúng tôi để được tư vấn các vấn đề liên quan đến xe, sửa chữa, mua xe mới. liên hê:'.$hotline;
        break;
	case "bao-duong-dinh-ky":
        $title_tag = "Bảo dưỡng định kỳ - Đại lý Vinfast tại Quảng Ninh";
		$description_tag = 'Thông tin bảo dưỡng định kỳ. Bảo dưỡng nhỏ (mỗi 5000km): 5.000 – 15.000 – 25.000 – 35.000 – 45.000 km. Bảo dưỡng Trung bình (mỗi 10000km): 10.000 – 30.000 – 50.000 – 70.000 – 90.000 km. liên hê:'.$hotline;
        break;
	case "dich-vu-sua-chua":
        $title_tag = "Dịch vụ sửa chữa - Đại lý Vinfast tại Quảng Ninh";
		$description_tag = 'Thông tin dịch vụ sửa chữa. Bạn có thể liên hệ với chúng tôi để được tư vấn các vấn đề liên quan đến xe, sửa chữa, mua xe mới. liên hê:'.$hotline;
        break;
	case "thu-tuc-dang-ky-va-dang-kiem-xe":
        $title_tag = "Thủ tục đăng lý và đăng kiểm xe - Đại lý Vinfast tại Quảng Ninh";
		$description_tag = 'Với sự hỗ trợ tốt nhất từ Vinfast Quảng Ninh, khách hàng sử dụng xe Totota sẽ có trải nghiệm thú vị và tự tin khi tự thực hiện các công việc Đăng ký – Đăng kiểm xe (*). liên hê:'.$hotline;
        break;
	case "chinh-sach-va-dieu-khoan":
        $title_tag = "Chính sách và điều khoản - Đại lý Vinfast tại Quảng Ninh";
		$description_tag = 'Chính sách và điều khoản. Tên, logo, tên sản phẩm, tên đặc tính, và khẩu hiệu Vinfast đều là thương hiệu thuộc sở hữu hoặc đăng ký của Công ty Ô tô Vinfast Việt Nam và/hoặc Vinfast Việt Nam. liên hê:'.$hotline;
        break;
	case "bao-hiem-xe":
        $title_tag = "BẢO HIỂM XE VINFAST - Đại lý Vinfast tại Quảng Ninh";
		$description_tag = 'BẢO HIỂM XE VINFAST. Khách hàng sẽ luôn an tâm và hài lòng với dịch vụ chăm sóc và bảo hiểm xe Vinfast từ các đối tác Bảo hiểm uy tín hàng đầu tại Việt Nam. liên hê:'.$hotline;
        break;
	case "tim-duong-di":
        $title_tag = "Tìm đường đi - Đại lý Vinfast tại Quảng Ninh";
		$description_tag = 'Tìm đường đi  VINFAST PHONG NGUYÊN BÃI CHÁY Đại Lý Chính Thức Vinfast Việt Nam Tại Quảng Ninh. liên hê:'.$hotline;
        break;
	case "news":
        $title_tag = "Tin tức & sự kiện, khuyến mại, tuyển dụng tại Đại lý Vinfast Quảng Ninh";
		$description_tag = 'Tin tức & sự kiện, khuyến mại, tuyển dụng tại Đại lý Vinfast Quảng Ninh. liên hê:'.$hotline;
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
  array(0,"VF9 ","YG","VF 9 Eco","NSP151L-AHXGKU","CBU",1380000000,837400),
  array(1,"VF9","YXH","VF 9 Plus","NSP151L-AHXRKU","CBU",1560000000,837400),
  array(2,"vios","VG","Vios G (CVT)","NSP151L-BEXGKU","CKD",545000000,480700),
  array(3,"VF7","VE","VF 7 PLUS (Trần kính toàn cảnh)","NSP151L-BEXGKU","CKD",1024000000,480700),
  array(4,"VF7","VK","VF 7 PLUS","NSP151L-BEMRKU","CKD",999000000,480700),
  array(5,"VF7","VE","VF 7 ECO","NSP151L-BEMRKU","CKD",850000000,480700),
  array(6,"corolla","CQ","Corolla Altis 2.0V Sport","ZRE173L-GEXVKH","CKD",932000000,480700),
  array(7,"corolla","CZ","Corolla Altis 2.0V (CVT-i)","ZRE173L-GEXVKH","CKD",878000000,480700),
  array(8,"corolla","CV","Corolla Altis 1.8G (CVT)","ZRE173L-GEXVKH","CKD",780000000,480700),
  array(9,"corolla","CK","Corolla Altis 1.8E (CVT)","ZRE173L-GEXVKH","CKD",725000000,480700),
  array(10,"corolla","CG","Corolla Altis 1.8E (MT)","ZRE173L-GEXVKH","CKD",697000000,480700),
  array(11,"camry","KHM","Camry 2.5HEV MID","ASV50L-JETEKU","CBU",1460000000,480700),
  array(12,"camry","KV","Camry 2.0Q","ASV50L-JETEKU","CBU",1220000000,480700),
  array(13,"camry","KE","Camry 2.0G","ASV50L-JETEKU","CBU",1105000000,480700),
  array(14,"innova","IXH","Innova Cross HEV","ASV50L-JETEKU","CBU",990000000,837400),
  array(15,"innova","IXV","Innova Cross","ASV50L-JETEKU","CBU",810000000,837400),
  array(16,"innova","IE","Innova 2.0E","ASV50L-JETEKU","CKD",755000000,837400),
  array(17,"fortuner","FVD","Fortuner 2.8AT 4x4","ASV50L-JETEKU","CKD",1434000000,837400),
  array(18,"fortuner","FX","Fortuner Legender 2.7AT 4x2","ASV50L-JETEKU","CBU",1290000000,837400),
  array(19,"fortuner","FXL","Fortuner 2.7AT 4x2","ASV50L-JETEKU","CKD",1155000000,837400),
  array(20,"land","LC","Land Cruiser VX","ASV50L-JETEKU","CBU",4286000000 ,837400),
  array(21,"prado","LP","Land Cruiser Prado TX-L","ASV50L-JETEKU","CBU",3480000000,837400),
  array(22,"hilux","HQ","Hilux 2.8G 4x4 AT","ASV50L-JETEKU","CBU",999000000,1026300),
  array(23,"hilux","HG","Hilux 2.4E 4x4 MT","ASV50L-JETEKU","CBU",668000000,1026300),
  array(24,"hilux","HK","Hilux 2.4L 4x2 AT","ASV50L-JETEKU","CBU",706000000,480700),
  array(25,"hiace","HD","Hiace Động cơ dầu","ASV50L-JETEKU","CBU",1176000000,1397000),
  array(26,"hiace","HC","Hiace Động cơ xăng","ASV50L-JETEKU","CBU",1176000000,1397000),
  array(27,"alphard","AP","Alphard","ASV50L-JETEKU","CBU",4370000000,837400),
  array(28,"innova","IGM","Innova Venturer","ASV50L-JETEKU","CKD",885000000,837400),
  array(29,"fortuner","FK","Fortuner 2.4AT 4x2","ASV50L-JETEKU","CBU",1055000000,837400),
  array(30,"fortuner","FKS","Fortuner Legender 2.4AT 4x2","ASV50L-JETEKU","CKD",1185000000,837400),
  array(31,"hilux","HE","Hilux 2.4E 4x2 MT","ASV50L-JETEKU","CBU",628000000,1026300),
  array(32,"vf3","TP","VF3 thuê pin","B101LA-GMSGF","CBU",240000000,480700),
  array(33,"vf3","MP","VF3 mua pin","B101LA-GMSGF","CBU",322000000,480700),
  array(34,"cross","CXH","Coralla Cross 1.8HV","ZVG10L-DHXEBU","CBU",905000000,837400),
  array(35,"cross","CXV","Coralla Cross 1.8V","ZSG10L-DHXEKU","CBU",820000000,837400),
  array(36,"cross","CXG","Coralla Cross 1.8G","ZSG10L-DHXNKU","CBU",760000000,837400),
  array(37,"fortuner","FVS","Fortuner Legender 2.8AT 4x4","ASV50L-JETEKU","CKD",1350000000,837400),
  array(38,"fortuner","FV","Fortuner Legender 2.7AT 4x4","ASV50L-JETEKU","CBU",1395000000,837400),
  array(39,"VF8","VF8P","VF 8 Plus","B101LA-GMSGF","CBU",1149000000,480700),
  array(40,"VF8","VF8E","VF 8 Eco","B101LA-GMSGF","CBU",970000000,480700),
  array(41,"camry","KH","Camry 2.5 Hybrid","ASV50L-JETEKU","CBU",1495000000,480700),
  array(42,"VF6","VF6P","VF6 Plus ","ASV50L-JETEKU","CBU",710000000,940000),
  array(43,"VF6","VF6S","VF6S","ASV50L-JETEKU","CBU",640000000,940000),
  array(44,"vf5","MP","VF5 mua pin","ASV50L-JETEKU","CBU",540000000,480700),
  array(45,"vf5","TP","VF5 thuê pin","ASV50L-JETEKU","CBU",480000000,480700),
  array(46,"yaris","YXV","Yaris Cross G (CVT)","NSP151L-AHXRKU","CBU",650000000,480700),
  array(47,"yaris","YXH","Yaris Cross HEV (CVT)","NSP151L-AHXRKU","CBU",765000000,480700),
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
