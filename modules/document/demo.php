<?php
global $dfjsdhfjkd;
$error='';
$err=0;
$where="";
if (!file_exists("../../config.php")) die();
define('CMS_SYSTEM', true);
@require_once("../../config.php");
//global $urlsite;
	$filePath='../../download/files/ho_so_mau_ban_ve_thiet_ke_cap_thoat_nuoc_nha_biet_thu_6qz87K_73dke7KC.rar';
					/* Figure out the MIME type (if not specified) */
				$known_mime_types=array(
				   "pdf" => "application/pdf",
				   "txt" => "text/plain",
				   "html" => "text/html",
				   "htm" => "text/html",
				   "exe" => "application/octet-stream",
				   "zip" => "application/zip",
				   "rar" => "application/x-rar-compressed",
				   "doc" => "application/msword",
				   "xls" => "application/vnd.ms-excel",
				   "ppt" => "application/vnd.ms-powerpoint",
				   "gif" => "image/gif",
				   "png" => "image/png",
				   "jpeg"=> "image/jpg",
				   "jpg" =>  "image/jpg",
				   "php" => "text/plain"
				);
					  
				if($mime_type==''){
					$file_extension = strtolower(substr(strrchr($filePath,"."),1));
					if(array_key_exists($file_extension, $known_mime_types)){
					   $mime_type=$known_mime_types[$file_extension];
					} else {
					   $mime_type="application/force-download";
					};
				};
				// required for IE, otherwise Content-Disposition may be ignored
					//if(ini_get('zlib.output_compression'))
					//ini_set('zlib.output_compression', 'Off');
					ob_clean();
					header('Pragma: public');
					header('Expires: 0');
					header('Content-Type:' . $mime_type);
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header("Cache-Control: public");
					header('Content-Description: File Transfer');
					header('Content-Disposition: attachment; filename="ho_so_mau_ban_ve_thiet_ke_cap_thoat_nuoc_nha_biet_thu_6qz87K_73dke7KC-3.rar"');
					header("Content-Transfer-Encoding: binary");
					//header('Accept-Ranges: bytes');
					header('Content-Length: ' . filesize($filePath));
					ob_clean();
					flush();
					readfile($filePath);
					//output_file($filePath,$row['fattach'],'');
					exit;
					echo "fdshfjdskhfkdjfskdj";
?>