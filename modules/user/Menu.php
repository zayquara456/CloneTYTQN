<?php
if (!defined('CMS_SYSTEM')) die();
	if (defined('iS_USER') && isset($userInfo))
	{
	?>
	<div class="menu-user" style="text-align: right; font-size:12px">
		<a href="index.php?f=user&do=noibo" title="Thông tin nội bộ">Thông tin nội bộ</a> | 
		<a href="index.php?f=user&do=edit_profile" title="Thay đổi thông tin cá nhân">Thay đổi thông tin cá nhân</a> | 
		<a href="index.php?f=user&do=logout" title="Thoát">Thoát</a>
	</div>
	<?php
	}
?>