<?php

/* Database config */

$db_host		= 'mysql10.gigahost.dk';
$db_user		= 'CLEAN';
$db_pass		= 'CLEAN';
$db_database	= 'mrmeee_bumblebee-clean'; 

/* End config */


$link = @mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');

mysql_set_charset('utf8');
mysql_select_db($db_database,$link);

?>