<?php
if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) { die(); }
///============ Ham resize IMG

class Image
{
var $SrcFile = false;//File nguồn
var $DestFile = false;//File đích nếu được lưu
var $Quality = 100; //Chất lượng ảnh sẽ được tạo
var $NewWidth = 0; //Độ rộng của ảnh sẽ được tạo
var $NewHeight = 0;//Độ cao của ảnh sẽ được tạo
var $WidthPercent = 0;//chiều rộng của ảnh cần tạo dùng khi muốn resize ảnh nhưng giữ nguyên tỷ lệ dài/rộng
var $HeightPercent = 0;//chiều cao của ảnh cần tạo dùng khi muốn resize ảnh nhưng giữ nguyên tỷ lệ dài/rộng
function GetType()//Hàm lấy kiểu của file nguồn - chỉ hỗ trợ jpg(1), gif(2), png(3)
{
$arr['mime'] = false;
$arr = getimagesize($this->SrcFile);
$type = 0;
switch($arr['mime'])
{
case 'image/jpeg':
$type = 1;
break;
case 'image/gif':
$type = 2;
break;
case 'image/png':
$type = 3;
break;
case 'image/bmp':
$type = 4;
break;
default:
$type = 0;
break;
}
if($type > 0)
return $type;
else
die("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">File nguồn không tồn tại hoặc không phải định dạng cho phép !. Lỗi tại Image->GetType()");
}
function GetWidth() //Hàm lấy chiều rộng của ảnh gốc
{
$arr[0] = 0;
$arr = getimagesize($this->SrcFile);
if(intval($arr[0]) > 0)
return intval($arr[0]);
else
die("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">File nguồn không tồn tại hoặc không phải định dạng cho phép !. Lỗi tại Image->GetWidth()");
}
function GetHeight() //Hàm lấy chiều cao của ảnh gốc
{
$arr[1] = 0;
$arr = getimagesize($this->SrcFile);
if(intval($arr[1]) > 0)
return intval($arr[1]);
else
die("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">File nguồn không tồn tại hoặc không phải định dạng cho phép !. Lỗi tại Image->GetHeight()");
}
function LoadImageFromFile()//Ham tạo một ảnh vào trong bộ nhớ từ file nguồn - trả về địa chị vùng nhớ chứa anh dc tạo
{
$type = $this->GetType();
$img = false;
switch($type)
{
case 1:
$img = imagecreatefromjpeg($this->SrcFile);
break;
case 2:
$img = imagecreatefromgif($this->SrcFile);
break;
case 3:
$img = imagecreatefrompng($this->SrcFile);
break;
case 4:
$img = imagecreatefromwbmp($this->SrcFile);
break;
}
return $img;
}
function NewImage($W, $H) //Hàm tạo 1 ảnh mới trong bộ nhớ - trả về địa chỉ của nó trong bộ nhớ
{
	if($this->GetType() != 2)
	$imgNew = imagecreatetruecolor($W, $H);//Dung cho gif - chua ho tro gif nen gif se khong transfer
	else
	
	$imgNew = imagecreatetruecolor($W, $H);
	$white = imagecolorallocate($imgNew, 0, 0, 0);//Dung cho PNG
	imagefilledrectangle( $imgNew, 0, 0, $W, $H, $white);//Dung cho PNG
	//imagecolortransparent($imgNew, $white);
	//imagecopyresampled($imgNew, $image_old, $xpos, $ypos, 0, 0, $new_width, $new_height, $this->info['width'], $this->info['height']);
	return $imgNew;
}
function CopyImage($Src, $Dest, $Width, $Height) //Hàm copy và resize từ ảnh có địa chỉ trong bộ nhớ $Src tới ảnh có địa chỉ $Dest
{
	$scale = min($Width / $this->GetWidth(), $Height / $this->GetHeight());
	$new_width = $this->GetWidth() * $scale;
	$new_height = $this->GetHeight() * $scale;			
    $xpos =($Width - $new_width) / 2;
   	$ypos = ($Height - $new_height) / 2;
	imagecopyresampled($Dest, $Src,$xpos,$ypos,0,0, $new_width, $new_height, $this->GetWidth(), $this->GetHeight());
//imagecopyresampled($Dest, $Src,25,0,0,0, 50, 100, 100,200);
}
function SaveFile($Src, $Dest)//Hàm ghi thành file nếu cần
{
$type = $this->GetType();
switch($type)
{
case 1:
ImageJPEG($Dest, $this->DestFile, $this->Quality);
break;
case 2:
if(function_exists('imagegif')) //PHP < 5 no support
ImageGif($Dest, $this->DestFile, $this->Quality);
else
ImageJPEG($Dest, $this->DestFile, $this->Quality);
break;
case 3:
@ImagePNG($Dest, $this->DestFile, $this->Quality);
break;
case 4:
@ImageBMP($Dest, $this->DestFile, $this->Quality);
break;
}
}
function FreeMemory($Src, $Dest)//Hàm giải phóng bộ nhớ chứa hình ảnh nguồn và đích
{
ImageDestroy($Src);
ImageDestroy($Dest);
}

//Hàm được gọi
function SaveFileWH()//Hàm trả về file ảnh được resize với Width và Height do ta chỉ định
{
$img = false;
$imgNew = false;
$img = $this->LoadImageFromFile();
$imgNew = $this->NewImage($this->NewWidth, $this->NewHeight);
$this->CopyImage($img, $imgNew, $this->NewWidth, $this->NewHeight);
$this->SaveFile($img, $imgNew);
$this->FreeMemory($img, $imgNew);
}
//Hàm được gọi
function SaveFileW()//Resize voi Width do ta chi dinh va Height lay theo ti le cua Width
{
$oldW = $this->GetWidth();
$oldH = $this->GetHeight();
$newW = $this->WidthPercent;
$newH = $newW*($oldH/$oldW);
$img = false;
$imgNew = false;
$img = $this->LoadImageFromFile();
$imgNew = $this->NewImage($newW, $newH);
$this->CopyImage($img, $imgNew, $newW, $newH);
$this->SaveFile($img, $imgNew);
$this->FreeMemory($img, $imgNew);
}
//Hàm được gọi
function SaveFileH()//Resize voi Height do ta chi dinh va Width lay theo ti le cua Height
{
$oldW = $this->GetWidth();
$oldH = $this->GetHeight();
$newH = $this->HeightPercent;
$newW = $newH*($oldW/$oldH);
$img = false;
$imgNew = false;
$img = $this->LoadImageFromFile();
$imgNew = $this->NewImage($newW, $newH);
$this->CopyImage($img, $imgNew, $newW, $newH);
$this->SaveFile($img, $imgNew);
$this->FreeMemory($img, $imgNew);
}
}

//Mot so ham phu tro
function getImageWidth($FileName)
{
if(!file_exists($FileName)) return false;
$arr = getimagesize($FileName);
return $arr[0];
}
function getImageHeight($FileName)
{
if(!file_exists($FileName)) return false;
$arr = getimagesize($FileName);
return $arr[1];
}
?>