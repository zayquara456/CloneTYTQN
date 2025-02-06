<?php
class CAPTCHA
{
	var $length;
	var $width;
	var $height;
	var $timeout;

	function CAPTCHA($length)
	{	
		$this->setLength($length);
		$this->setSize(($length + 1) * 40, 50);
		$this->timeout = 300;
	}

	function setTimeout($timeout) {
		$this->timeout = $timeout;
	}

	function setSize($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
	}

	function getWidth()
	{
		return $this->width;
	}

	function getHeight()
	{
		return $this->height;
	}

	function setLength($length)
	{
		$this->length = $length;
	}

	function getLength()
	{
		return $this->length;
	}

	function getImage()
	{
		global $db, $prefix;
		
		$db->sql_query("DELETE FROM {$prefix}_captcha WHERE UNIX_TIMESTAMP(NOW()) >= UNIX_TIMESTAMP(captchaTime) + {$this->timeout}");
		
		$alphabet = array('2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','J','K','L','M','N',
		'P','Q','R','S','T','U','V','W','X','Y','Z');

		$numberArray = array();
		$angleArray = array();
		for ($i = 0; $i < $this->length; $i++)
		{
			$numberArray[] = $alphabet[rand(0, 25)];
			$angleArray[] = rand(0, 45);
		}
		header("Content-Type: image/png");
		
		$im = imagecreatetruecolor($this->width, $this->height);
		$black = imagecolorallocate($im, 0, 0, 0);
		$white = imagecolorallocate($im, 255, 255, 255);
		imagefilledrectangle($im, 0, 0, 399, 99, $white);

		$x = 0;
		for ($i = 0; $i < $this->length; $i++)
		{
			$x += 40;
			imagettftext($im, 20, $angleArray[$i], $x, 35, $black, './images/ariblk.ttf', $numberArray[$i]);
		}

		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		imagefilter($im, IMG_FILTER_MEAN_REMOVAL);
		imagefilter($im, IMG_FILTER_MEAN_REMOVAL);
		imagefilter($im, IMG_FILTER_MEAN_REMOVAL);
		imagefilter($im, IMG_FILTER_MEAN_REMOVAL);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		imagefilter($im, IMG_FILTER_CONTRAST, 50);
		imagefilter($im, IMG_FILTER_EMBOSS);
		imagefilter($im, IMG_FILTER_COLORIZE, 0, 50, 100);
		
		imageline($im, 0, 0, 0, $this->height - 1, $black);
		imageline($im, 0, $this->height - 1, $this->width - 1, $this->height - 1, $black);
		imageline($im, $this->width - 1, $this->height - 1, $this->width - 1, 0, $black);
		imageline($im, 0, 0, $this->width - 1, 0, $black);

		$text = implode(null, $numberArray);
		$uniqueKey = uniqid(null, true);
		$db->sql_query("INSERT INTO {$prefix}_captcha VALUES ('$uniqueKey','$text', NOW())");
		if ($db->sql_affectedrows() > 0) {
			imagepng($im);
			imagedestroy($im);
			$_SESSION[CAPTCHA_SESS] = $uniqueKey;
			return true;
		} else {
			$_SESSION[CAPTCHA_SESS] = '';
			return false;
		}
	}

	function isValid($text)
	{
		global $db, $prefix, $escape_mysql_string;

		$db->sql_query("DELETE FROM {$prefix}_captcha WHERE UNIX_TIMESTAMP(NOW()) >= UNIX_TIMESTAMP(captchaTime) + {$this->timeout}");
		
		if (!isset($_SESSION[CAPTCHA_SESS]) || empty($_SESSION[CAPTCHA_SESS])) return false;
		$db->sql_query("SELECT text FROM {$prefix}_captcha WHERE uniqueKey='".$escape_mysql_string($_SESSION[CAPTCHA_SESS])."'");
		if ($db->sql_numrows() > 0)
		{
			list($realText) = $db->sql_fetchrow();
			if ($text != $realText) return false;
			$db->sql_query("DELETE FROM {$prefix}_captcha WHERE uniqueKey='".$escape_mysql_string($_SESSION[CAPTCHA_SESS])."'");
			return true;
		}
		else return false;
	}
}
?>