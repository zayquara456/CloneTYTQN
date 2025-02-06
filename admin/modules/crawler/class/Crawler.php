<?php
/**
 * www.vinhquang.net
 * Author: Vinh Quang Vip
 * Email: vinhquangvip@gmail.com
 * Class Crawler
 */
class Crawler
{	
	public function __construct($upload_image_to,$image_restrict,$image_size_restrict) 
	{
		//parent::__construct();
		$this->upload_image_to	   = $upload_image_to;
		$this->image_restrict 	   = $image_restrict;
		$this->image_size_restrict = $image_size_restrict;
	}
	// ham loc cac thanh phan cua bai viet 
	// $_url				: url chi tiet bai viet
	// $arr_replace		: mang dieu kien loc lay noi dung bai viet
	// $image_content	: dieu kien loc lay tat ca anh cua bai viet
	public function getPost($_url, array $_replace, $_reg, $_page)
	{
		$html	= $this->runBrowser($_url);
		$html	= preg_replace($_reg["begin"],'',$html);// remove doan tren
		$html	= preg_replace($_reg["end"],'',$html);// Remove doan duoi
		$html	= $this->_replace($html);
		//replace get content
		if(!empty($_reg["tag"]))
		{
			$_post["tag"]	= preg_replace('#<.*?>#is','',$this->getContent($html,$_reg["tag"],$_page["tag"]));
		}
		else
		{
			$_post["tag"]	= "";
		}
		//$_post["title"]		= $this->getContent($html,$_reg["title"],$_page["title"]);
		$_post["description"]	= $this->getContent($html,$_reg["description"],$_page["description"]);
		$_post["description"]	= $this->replace_all($_post["description"], $_replace["description"]);
		$_post["content"]		= $this->getContent($html,$_reg["content"],$_page["content"]);
		$_post["content"]		= $this->replace_all($_post["content"], $_replace["content"]);
		$_dataimage				= $this->getImage($_post["content"], $_reg["image"], $_page["url_image"]);
		$_post["images"] 		= $_dataimage["images"];
		if ($_dataimage["content"]!="")
			$_post["content"] 		= $_dataimage["content"];
		return $_post;
	}
	
	// ham doc trang
	public function runBrowser($_url)
	{
		if ( function_exists('curl_init') ) 
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $_url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			$response = curl_exec($ch);
			curl_close($ch);
		}
		else 
		{ 				
			$response = @file_get_contents($_url);
		}
		return $response;
	}
	// ham lay noi dung chi tiet
	public function getContent($_data, $_reg, $_page){
			if (preg_match($_reg, $_data, $matches))
			{
				$_data = preg_replace($_reg,'',$matches[$_page]);
			}
			else
			{
				$_data = "content not fount!";
			}
		
			return $_data;
	}
	//ham loc bo thanh phan
	public function replace_all($_data, array $_replace)
	{
		if(!empty($_replace))
		{
			foreach($_replace as $value)
			{
				$_data= preg_replace($value,'',$_data);
			}
		}
		return $_data;
	}
	//ham loc tong hop 
	public function _replace($html)
	{
		$html = preg_replace('#<a(.*?)>#is','',$html);
		$html = preg_replace('#</a>#','',$html);
		return $html;
	}
	// ham chuan hoa dia chi trang
	public function chuanhoaUrl($_url,$_page)
	{
		$_url	=	str_replace("http://www.","http://",$_url);
		
		$_url = $_page.$_url;
		return $_url;
	}

	// ham lay tat ca image
	public function getImage($_content, $_reg, $_page)
	{
		$_data = array();
		$_data["images"]=$_data["content"]="";
		preg_match_all($_reg, $_content, $images);
		$_dataImage	= $_dataImage2	=	array();
		if(!empty($images))
		{
			$k=0;
			foreach($images[2] as $image_link)
			{
				$_dataImage[$k]	=	$this->chuanhoaUrl($image_link,$_page);
				$_dataImage2[$k]	=	$this->upload_image_to.$this->uploadUrl($_dataImage[$k],$this->upload_image_to,$this->image_restrict,$this->image_size_restrict);
				// Replace image url
				$_data["images"]	= basename($_dataImage2[$k]);
				$_data["content"]	= str_replace($image_link,$_dataImage2[$k],$_content);
				$k++;
			}
		}
		else
		{
			$_data["images"] = "image not fount!";
			$_data["content"] = "";
		}
		return $_data;
	}	
	// upload hinh anh theo url
	function uploadUrl($_url,$savePath,$imageRestrict,$imageSizeRestrcit)
	{
		if(!file_exists($savePath)){
			$this->makeDir( $savePath ) ;
		}
		$type_upload	=	explode(',',$imageRestrict);
		$ext			=	strtolower(substr(basename($_url),strrpos(basename($_url),".")+1));
		$name			=	basename($_url);
		$result	=	"";
		if(!in_array($ext,$type_upload)){
			echo("not upload image ".$_url."</br>");
		}
		else{
			$fn	=	$savePath.$name;
			$fp	=	fopen($fn,"w");
			$_content	=	file_get_contents($_url);
			fwrite($fp,$_content,strlen($_content));
			fclose($fp);
			$result	=	$name;
		}
		return $result;
	}
	
	function makeDir( $target ) {
		// from php.net/mkdir user contributed notes
		$target = str_replace( '//', '/', $target );
		if ( file_exists( $target ) )
			return @is_dir( $target );
	
		// Attempting to create the directory may clutter up our display.
		if ( @mkdir( $target ) ) {
			$stat = @stat( dirname( $target ) );
			$dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
			@chmod( $target, $dir_perms );
			return true;
		} elseif ( is_dir( dirname( $target ) ) ) {
				return false;
		}
	
		// If the above failed, attempt to create the parent node, then try again.
		if ( ( $target != '/' ) && ( $this->makeDir( dirname( $target ) ) ) )
			return $this->makeDir( $target );
	
		return false;
	}

}
?>