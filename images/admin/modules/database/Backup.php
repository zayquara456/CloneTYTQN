<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
$filename = RPATH."$path_upload/data/backup.sql";
$file = @fopen($filename, 'wb');
$content = '';
$tableResult = $db->sql_query("SHOW TABLES");
while (list($tableName) = $db->sql_fetchrow($tableResult)) {
	$createTableResult = $db->sql_query("SHOW CREATE TABLE $tableName");
	list($tableName, $query) = $db->sql_fetchrow($createTableResult);
	$query = explode("\n", $query);
	for ($i = 0; $i < count($query); $i++) $query[$i] = trim($query[$i]);
	$query = implode(' ', $query);
	$query = str_replace("CHARSET=latin1", "CHARSET=UTF8", $query);
	$query .= ";\n";
	$content .= $query;
	
	$dbInfoResult = $db->sql_query("SELECT * FROM $tableName");
	$insertQueryPrefix = "INSERT INTO $tableName (";
	$fullInsertQuery = '';
	$start = true;
	while ($dbInfo = $db->sql_fetchrow($dbInfoResult)) {
		if ($start) {
			for ($i = 0; $i < $db->sql_numfields($dbInfoResult); $i++) {
				$insertQueryPrefix .= $db->sql_fieldname($i, $dbInfoResult).',';
			}
			$insertQueryPrefix = substr($insertQueryPrefix, 0, strlen($insertQueryPrefix) - 1);
			$insertQueryPrefix .= ') VALUES (';
			$start = false;
		}
		$insertQuery = $insertQueryPrefix;
		for ($i = 0; $i < $db->sql_numfields($dbInfoResult); $i++) {
			$fieldType = $db->sql_fieldtype($i, $dbInfoResult);
			if (($fieldType != 'int') && ($fieldType != 'null') && ($fieldType != 'real'))
				$insertQuery .= "'".$escape_mysql_string($dbInfo[$i])."',";
			else
				$insertQuery .= "{$dbInfo[$i]},";
		}
		$insertQuery = substr($insertQuery, 0, strlen($insertQuery) - 1);
		$insertQuery = explode("\r\n", $insertQuery);
		$insertQuery = implode("\n", $insertQuery);
		$insertQuery = explode("\n", $insertQuery);
		$insertQuery = implode(' ', $insertQuery);
		$insertQuery .= ");\n";
		$fullInsertQuery .= $insertQuery;
	}
	$content .= $fullInsertQuery;
}

$content = "\xEF\xBB\xBF".$content;
@fwrite($file, $content);

if ($smtp_mail == 1) $m = new Mail($adminmail, $adminmail, _DATABASE_BACKUP_SUBJECT, '', "SMTP", $smtp_host, $smtp_username, $smtp_password, $smtp_port);
else $m = new Mail($adminmail, $adminmail, _DATABASE_BACKUP_SUBJECT, _DATABASE_BACKUP_MESSAGE);
$m->attach($filename, 'backup.sql', 'text/plain');
$m->send();
@fclose($file);
@unlink($filename);

header("Location: modules.php?f=$adm_modname&stat=done");
?>