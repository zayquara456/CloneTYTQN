<?php
$link = $_GET['link'];
$color= $_GET['color'];
for ($x = 1; $x <= 36; $x++) {
	if($x<10)
		echo $link.$color.'/'.$color.'_00'.$x.'.png<br>';
	else
    echo $link.$color.'/'.$color.'_0'.$x.'.png<br>';
}
echo '<img src="'.$link.$color.'/'.$color.'_001.png"></img><br>';
?>