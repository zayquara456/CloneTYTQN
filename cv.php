<?php 

$db_server = 'localhost'; 
$db_user="toyotaqu_data"; 
$db_password="3J9ww45hQwM"; 
$db_name = "toyotaqu_data"; 

// Cac table can convert 
$table_names = array(" adoosite_admin",
" adoosite_admingroup",
" adoosite_adminmenus",
" adoosite_admin_log",
" adoosite_admin_menu",
" adoosite_advertise",
" adoosite_advertise_banners",
" adoosite_advertise_scroll",
" adoosite_answer",
" adoosite_blocks",
" adoosite_captcha",
" adoosite_city",
" adoosite_comments",
" adoosite_contact",
" adoosite_contact_add",
" adoosite_contact_part",
" adoosite_contents",
" adoosite_document",
" adoosite_document_cat",
" adoosite_document_comments",
" adoosite_document_favorites",
" adoosite_footermenus",
" adoosite_gentext",
" adoosite_intro",
" adoosite_intro_cat",
" adoosite_leftmenus",
" adoosite_mainmenus",
" adoosite_menus",
" adoosite_menu_types",
" adoosite_modules",
" adoosite_napthe",
" adoosite_napthe_log",
" adoosite_napthe_promotion",
" adoosite_news",
" adoosite_newsletter",
" adoosite_newsletter_send",
" adoosite_news_cat",
" adoosite_news_temp",
" adoosite_picture",
" adoosite_picture_cat",
" adoosite_picture_temp",
" adoosite_products",
" adoosite_products_cat",
" adoosite_products_order",
" adoosite_project",
" adoosite_question",
" adoosite_question_cat",
" adoosite_rss",
" adoosite_service",
" adoosite_stats",
" adoosite_survey",
" adoosite_survey_check",
" adoosite_thuchi",
" adoosite_thuchi_cat",
" adoosite_user",
" adoosite_usergroup",
" adoosite_user_log",
" adoosite_video",
" adoosite_weblink",
" thuchi");  

// Ket noi CSDL 
mysql_connect($db_server, $db_user, $db_password) or die(mysql_error()); 
mysql_select_db($db_name); 
// Thuc hien 
charset_fixer($table_names); 

function charset_fixer($table_names){ 
  foreach($table_names as $type){ 
    $ret[] = charset_fixer_fix_table($type); 
  } 
} 

function charset_fixer_fix_table($table) { 
  $ret = array(); 
  $types = array('char' => 'binary', 
                 'varchar' => 'varbinary', 
                 'tinytext' => 'tinyblob', 
                 'text' => 'blob', 
                 'mediumtext' => 'mediumblob', 
                 'longtext' => 'longblob'); 

  // du table tiep theo vao list 
  $convert_to_binary = array(); 
  $convert_to_latin1 = array(); 
  $convert_to_utf8 = array(); 

  // thuc hien convert 
  $result = mysql_query('SHOW FULL COLUMNS FROM '. $table .''); 
  while ($column = mysql_fetch_assoc($result)) { 
    list($type) = explode('(', $column['Type']); 
    if (isset($types[$type])) { 
      $names = 'CHANGE `'. $column['Field'] .'` `'. $column['Field'] .'` '; 
      $attributes = ' DEFAULT '. ($column['Default'] == 'NULL' ? 'NULL ' : 
                     "'". mysql_real_escape_string($column['Default']) ."' ") . 
                    ($column['Null'] == 'YES' ? 'NULL' : 'NOT NULL'); 
      $convert_to_binary[] = $names . preg_replace('/'. $type .'/i', $types[$type], $column['Type']) . $attributes; 
      $convert_to_latin1[] = $names . $column['Type'] .' CHARACTER SET latin1'. $attributes; 
      $convert_to_utf8[] = $names . $column['Type'] .' CHARACTER SET utf8'. $attributes; 
    } 
  } 

  if (count($convert_to_binary)) { 
    //dat collatoin table mac dinh thanh latin1 
    mysql_query('ALTER TABLE '. $table .' DEFAULT CHARACTER SET latin1'); 

    //Convert sang latin1 
    mysql_query('ALTER TABLE '. $table .' '. implode(', ', $convert_to_latin1)); 
     
   //dat collatoin table mac dinh thanh utf8 
    mysql_query('ALTER TABLE '. $table .' DEFAULT CHARACTER SET utf8'); 
     
    //Convert latin1 sang binary 
    mysql_query('ALTER TABLE '. $table .' '. implode(', ', $convert_to_binary)); 
     
    //Convert binary sang UTF-8 
    mysql_query('ALTER TABLE '. $table .' '. implode(', ', $convert_to_utf8)); 
  } 
} 
?> 
Convert thanh cong!