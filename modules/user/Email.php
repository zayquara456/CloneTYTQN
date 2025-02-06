<?php
$result = $db->sql_query("SELECT email FROM {$prefix}_user");
if($db->sql_numrows($result) > 0) {
	$i=0;
	while(list($email) = $db->sql_fetchrow($result)) {
		$i++;
		echo $email."<br>";
	}
	echo $i;
}
?>