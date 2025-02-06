<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Xem tài liệu trên thuvienxaydung.net</title>
<style type="text/css">
/* CSS Document */
body {
	font-family:Arial, Helvetica, sans-serif;
	margin: 0px;
	padding: 0px;
}
.wrap {
    clear: both;
    width: 100%;
}

#ja-header {
    position: relative;
    z-index: 10;
}
#ja-header .main {
    height: 80px;
    padding: 0;
}
.main {
    background: none repeat scroll 0 0 #FFFFFF;
}
.main {
    margin: 0 auto;
    position: relative;
    width: 1000px;
}
.bar {
	margin: 0 auto;
    position: relative;
    width: 1000px;
	margin-top:5px;
}
.audio {
	margin: 0 auto;
    width: 1000px;
	margin-top:5px;
	position:relative;
}
.clearfix:after {
    clear: both;
    content: ".";
    display: block;
    height: 0;
    visibility: hidden;
}
.main .inner {
    padding-left: 15px;
    padding-right: 15px;
}

h1.logo {
    height: 80px;
    margin: 0;
    width: 160px;
}
h1.logo, div.logo-text {
    float: left;
}
h1.logo, div.logo-text h1 {
    font-size: 300%;
    line-height: 1;
}

h1.logo a {
    display: block;
    height: 62px;
    margin-left: 5px;

    width: 70px;
}
a {
    color: #333333;
    text-decoration: none;
}

#top_banner {
    float: left;
    font-size: 92%;
    width: 580px;
}
#ja-login {
    float: right;

    width: 180px;
}
.download {
    position: fixed;
    right: 0;
	top:0;
	width:120px;
	height:30px;
	z-index:9999;
}
</style>
</head>
<?php
$file=$_GET['url'];
?>
<body >

<div id="pdf" style="width:100%"  align="center">
<object data="<?php echo $file?>" type="application/pdf" width="100%" height="500px">
 
  <p>Trình duyệt của bạn không hỗ trợ đọc file PDF.<a href="<?php echo $file?>"> Nhấn vào đây để tải file về.</a></p>
  
</object>

</div>
</body>
</html>