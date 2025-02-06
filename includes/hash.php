<?php
	class Hash
	{
		function sha256($msg)
		{
			$msg = Hash::utf8Encode($msg);
			$msg .= chr(128);
			$l = ceil(strlen($msg) / 4) + 2;
			$N = ceil($l / 16);
			$M = array();
			for ($i = 0; $i < $N; $i++)
			{
				$M[] = array();
				for ($j = 0; $j < 16; $j ++)
				{
					$M[$i][] = (ord(substr($msg, $i * 64 + $j * 4, 1)) << 24) |
							(ord(substr($msg, $i * 64 + $j * 4 + 1, 1)) << 16) |
							(ord(substr($msg, $i * 64 + $j * 4 + 2, 1)) << 8) |
							(ord(substr($msg, $i * 64 + $j * 4 + 3, 1)));
				}
			}
			$M[$N-1][14] = Hash::SHR((strlen($msg) - 1) * 8, 32);
			$M[$N-1][15] = ((strlen($msg) - 1) * 8) & 0xffffffff;
			$H = array(
						0x6a09e667, 0xbb67ae85,
						0x3c6ef372, 0xa54ff53a,
						0x510e527f, 0x9b05688c,
						0x1f83d9ab, 0x5be0cd19
					);
			$K = array(
						0x428a2f98, 0x71374491, 0xb5c0fbcf, 0xe9b5dba5, 0x3956c25b, 0x59f111f1, 0x923f82a4, 0xab1c5ed5,
						0xd807aa98, 0x12835b01, 0x243185be, 0x550c7dc3, 0x72be5d74, 0x80deb1fe, 0x9bdc06a7, 0xc19bf174,
						0xe49b69c1, 0xefbe4786, 0x0fc19dc6, 0x240ca1cc, 0x2de92c6f, 0x4a7484aa, 0x5cb0a9dc, 0x76f988da,
						0x983e5152, 0xa831c66d, 0xb00327c8, 0xbf597fc7, 0xc6e00bf3, 0xd5a79147, 0x06ca6351, 0x14292967,
						0x27b70a85, 0x2e1b2138, 0x4d2c6dfc, 0x53380d13, 0x650a7354, 0x766a0abb, 0x81c2c92e, 0x92722c85,
						0xa2bfe8a1, 0xa81a664b, 0xc24b8b70, 0xc76c51a3, 0xd192e819, 0xd6990624, 0xf40e3585, 0x106aa070,
						0x19a4c116, 0x1e376c08, 0x2748774c, 0x34b0bcb5, 0x391c0cb3, 0x4ed8aa4a, 0x5b9cca4f, 0x682e6ff3,
						0x748f82ee, 0x78a5636f, 0x84c87814, 0x8cc70208, 0x90befffa, 0xa4506ceb, 0xbef9a3f7, 0xc67178f2
					);
			$W = array();
			for ($i = 0; $i < $N; $i++)
			{
				for ($t = 0; $t < 16; $t++) $W[$t] = $M[$i][$t];
				for ($t = 16; $t < 64; $t++)
					$W[$t] = Hash::sum(Hash::gamma1($W[$t - 2]), $W[$t - 7], Hash::gamma0($W[$t - 15]), $W[$t - 16]);
				$a = $H[0];
				$b = $H[1];
				$c = $H[2];
				$d = $H[3];
				$e = $H[4];
				$f = $H[5];
				$g = $H[6];
				$h = $H[7];
				for ($t = 0; $t < 64; $t++)
				{
					$T1 = Hash::sum($h, Hash::sigma1($e), Hash::Ch($e, $f, $g), $K[$t], $W[$t]);
					$T2 = Hash::sum(Hash::sigma0($a), Hash::Maj($a, $b, $c));
					$h = $g;
					$g = $f;
					$f = $e;
					$e = Hash::sum($d, $T1);
					$d = $c;
					$c = $b;
					$b = $a;
					$a = Hash::sum($T1, $T2);
				}
				$H[0] = Hash::sum($a, $H[0]);
				$H[1] = Hash::sum($b, $H[1]);
				$H[2] = Hash::sum($c, $H[2]);
				$H[3] = Hash::sum($d, $H[3]);
				$H[4] = Hash::sum($e, $H[4]);
				$H[5] = Hash::sum($f, $H[5]);
				$H[6] = Hash::sum($g, $H[6]);
				$H[7] = Hash::sum($h, $H[7]);
			}
			$hash = "";
			for ($i = 0; $i < 8; $i++)
			{
				$H[$i] = dechex($H[$i]);
				while (strlen($H[$i]) < 8)
				{
					$H[$i] = '0'.$H[$i];
				}
				$hash .= $H[$i];
			}
			return $hash;
		}
		function gamma0($x)
		{
			return (Hash::ROTR($x, 7) ^ Hash::ROTR($x, 18) ^ (Hash::SHR($x, 3)));
		}
		function gamma1($x)
		{
			return (Hash::ROTR($x, 17) ^ Hash::ROTR($x, 19) ^ (Hash::SHR($x, 10)));
		}
		function sigma0($x)
		{
			return (Hash::ROTR($x, 2) ^ Hash::ROTR($x, 13) ^ Hash::ROTR($x, 22));
		}
		function sigma1($x)
		{
			return (Hash::ROTR($x, 6) ^ Hash::ROTR($x, 11) ^ Hash::ROTR($x, 25));
		}
		function Ch($x, $y, $z)
		{
			return (($x & $y) ^ (~$x & $z));
		}
		function Maj($x, $y, $z)
		{
			return (($x & $y) ^ ($x & $z) ^ ($y & $z));
		}
		function ROTR($x, $n)
		{
			return (Hash::SHR($x, $n) | ($x << (32 - $n)));
		}
		function SHR($a, $b)
		{
			return ($a >> $b) & (pow(2, 32 - $b) - 1);
		}
		// This one is by Fyed
		function sum()
		{
			$T = 0;
			for($x = 0, $y = func_num_args(); $x < $y; $x++)
			{
				$a = func_get_arg($x);
				$c = 0;
				for($i = 0; $i < 32; $i++)
				{
					$j = (($T >> $i) & 1) + (($a >> $i) & 1) + $c;
					$c = ($j >> 1) & 1;
					$j &= 1;
					$T &= ~(1 << $i);
					$T |= $j << $i;
				}
			}
			return $T;
		}
		// This function is based on the code written by Angel Martin & Paul Johnston
		function utf8Encode($msg)
		{
			$utfText = "";
			for ($n = 0; $n < strlen($msg); $n++)
			{
				$c = ord(substr($msg, $n, 1));
				if ($c < 128)
				{
					$utfText .= chr($c);
				}
				else if (($c > 127) && ($c < 2048))
				{
					$utfText .= chr(($c >> 6) | 192);
					$utfText .= chr(($c & 63) | 128);
				}
				else
				{
					$utfText .= chr(($c >> 12) | 224);
					$utfText .= chr((($c >> 6) & 63) | 128);
					$utfText .= chr(($c & 63) | 128);
				}
			}
			return $utfText;
		}
	}
?>