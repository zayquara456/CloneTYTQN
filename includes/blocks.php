<?php
if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) { die(); }
function blist() {
	global $prefix, $db;
	@chmod(RPATH.DATAFOLD."/blist.php", 0666);
	@$file = fopen("".RPATH."".DATAFOLD."/blist.php", "w");
	$content = "<?php\n\n";
	$content .= "if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) {\n";
	$content .= "die('Stop!!!');\n";
	$content .= "}\n";
	$content .= "\n";
	$bp_ar = array("l","r","c","d");
	for($t=0;$t < sizeof($bp_ar);$t++) {
		$fsql = "SELECT * FROM ".$prefix."_blocks WHERE bposition='".$bp_ar[$t]."' ORDER BY weight";
		$fresult = $db->sql_query($fsql);
		while($frow = $db->sql_fetchrow($fresult)) {
			$content .= "\$bl_".$bp_ar[$t]."[] = \"".$frow['bkey']."@".$frow['title']."@".$frow['url']."@".$frow['refresh']."@".$frow['time']."@".$frow['blanguage']."@".$frow['blockfile']."@".$frow['view']."@".$frow['expire']."@".$frow['action']."@".$frow['link']."@".$frow['module']."@".$frow['bid']."@".$frow['active']."@".$frow['showtitle']."\";\n";
		}
		$content .= "\n";
	}
	$content .= "?>";
	@$writefile = fwrite($file, $content);
	@fclose($file);
	@chmod(RPATH.DATAFOLD."/blist.php", 0644);
}
function blocks($side, $name) {
	global $prefix, $currentlang, $db, $block_side, $home;
    $cont = 0;
    $bl_l = array(); $bl_r = array(); $bl_c = array(); $bl_d = array(); $now = time();
    @include(RPATH.DATAFOLD."/blist.php");
    $side = strtolower($side[0]);
    $block_side = $side;
    if (strtolower($side[0]) == "l") {
        $bl = $bl_l;
    } elseif (strtolower($side[0]) == "r") {
        $bl = $bl_r;
    }  elseif (strtolower($side[0]) == "c") {
        $bl = $bl_c;
    } elseif  (strtolower($side[0]) == "d") {
        $bl = $bl_d;
    }
    
    for($bli=0;$bli < sizeof($bl); $bli++) {
    	if($bl[$bli]!="") {
    	$bl_ar = explode("@",$bl[$bli]);
    	if($bl_ar[13]=='1') {
    	if ($bl_ar[5]=="$currentlang") {
    		$bl_mod_ar = @explode("|",$bl_ar[11]);
    		if($bl_ar[11]=="all" || $bl_ar[11]=="" || ($home==1 && @in_array("home",$bl_mod_ar)) || ($home!=1 AND @in_array($name,$bl_mod_ar))) {
    			if (intval($bl_ar[8]) != 0 AND intval($bl_ar[8]) <= $now) {
    				if ($bl_ar[9] == "d") {
					$db->sql_query("UPDATE {$prefix}_blocks SET active=0, expire=0 WHERE bid='".intval($bl_ar[12])."'");
					blist();
					header("Location: index.php"); exit();
				} elseif ($bl_ar[9] == "r") {
					$db->sql_query("DELETE FROM ".$prefix."_blocks WHERE bid='".intval($bl_ar[12])."'");
					$db->sql_query("OPTIMIZE TABLE ".$prefix."_blocks");
					@unlink("".RPATH."".DATAFOLD."/".$bl_ar[6]."");
					fixweight();
					blist();
					header("Location: ".RPATH."index.php"); exit();
				}
			}
			$bl_acc = 0;
			if ($bl_ar[7] == 0) { $bl_acc = 1; } 
			elseif ($bl_ar[7] == 1 && defined('iS_ADMIN')) { $bl_acc = 1; }
			if($bl_acc == 1) {
				if ($bl_ar[0] == 0) { $block_path = RPATH."blocks/"; } else { $block_path = RPATH.DATAFOLD."/blocks/"; }
				$file = @file($block_path.$bl_ar[6]);
				if (!$file) { $content = _BLOCKPROBLEM; }
				else { include($block_path.$bl_ar[6]); }
				///////////////////////////////headlines
				if ($bl_ar[0] == 2) {
					$siteurl = ereg_replace("http://","",$bl_ar[2]);
					$siteurl = explode("/",$siteurl);
					$bl_ar[10] = "http://".$siteurl[0]."";
					$btime = time();
					if($bl_ar[4] < $btime - $bl_ar[3]) {
						$rdf = $fp ="";
						$rdf = parse_url($bl_ar[2]);
						$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
						if ($fp) {
							if ($rdf['query'] != '') { 	$rdf['query'] = "?" . $rdf['query']; }
							fputs($fp, "GET " . $rdf['path'] . $rdf['query'] . " HTTP/1.0\r\n");
							fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
							$string        = "";
							while(!feof($fp)) {
								$pagetext = fgets($fp,300);
								$string .= chop($pagetext);
							}
							fputs($fp,"Connection: close\r\n\r\n");
							fclose($fp);
							$items = explode("</item>",$string);
							$content = "<font class=\"content\">";
							for ($i=0;$i < 10;$i++) {
								$link = ereg_replace(".*<link>","",$items[$i]);
								$link = ereg_replace("</link>.*","",$link);
								$title2 = ereg_replace(".*<title>","",$items[$i]);
								$title2 = ereg_replace("</title>.*","",$title2);
								$title2 = stripslashes($title2);
								if ($items[$i] == "" AND $cont != 1) {
								} else {
									if (strcmp($link,$title2) AND $items[$i] != "") {
										$cont = 1;
										$content .= "<img border=\"0\" src=\"images/arrow2.gif\" width=\"10\" height=\"5\">&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br/>\n";
									}
                								}
            							}
							$sql = "UPDATE ".$prefix."_blocks SET time='$btime' WHERE bid='".$bl_ar[12]."'";
							$db->sql_query($sql);
							@chmod("".$block_path."".$bl_ar[6]."", 0777);
							@$file = fopen("".$block_path."".$bl_ar[6]."", "w");
							$content2 = "<?php\n\n";
							$fctime = date("d-m-Y H:i:s",filectime ("".$block_path."".$bl_ar[6].""));
							$fmtime = date("d-m-Y H:i:s");
							$content2 .= "// File: ".$bl_ar[6].".\n// Created: $fctime.\n// Modified: $fmtime.\n// Do not change anything in this file!\n\n";
							$content2 .= "if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) {\n";
							$content2 .= "die('Stop!!!');\n";
							$content2 .= "}\n";
							$content2 .= "\n";
							$content2 .= "\$content = \"".htmlspecialchars(stripslashes($content))."\";\n";
							$content2 .= "\n";
							$content2 .= "?>";
							@$writefile = fwrite($file, $content2);
							@fclose($file);
							@chmod("".$block_path."".$bl_ar[6]."", 0604);
							blist();
						}
					}
					if (($cont == 1) OR ($content != "")) {
						$content .= "<br/><a href=\"http://".$siteurl[0]."\" target=\"blank\"><b>"._HREADMORE."</b></a></font>";
					}
				}
				/////////////////////////////////END
				if ($bl_ar[0] != 0) {
					$content = html_entity_decode($content);
				}
				if ($content != "") {
					if ($side == "c") {
						blocks_center_up($bl_ar[1], $content, $bl_ar[10], $bl_ar[12], $bl_ar[14]);
					} elseif ($side == "d") {
						blocks_center_down($bl_ar[1], $content, $bl_ar[10], $bl_ar[12], $bl_ar[14]);
					} elseif ($side == "l") {
						temp_blocks_left($bl_ar[1], $content, $bl_ar[10], $bl_ar[12], $bl_ar[14]);
					} elseif ($side == "r") {
						temp_blocks_right($bl_ar[1], $content, $bl_ar[10], $bl_ar[12], $bl_ar[14]);
					}	
				}
				
				unset($content);
			}
    		}
    	}
	}	
	}
    }
}
if (!file_exists(RPATH.DATAFOLD."/blist.php")) {
	blist();
	header("Location: ".RPATH."index.php"); exit();
}
?>