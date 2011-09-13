<?php
require "connect.php";

if(isset($_GET["show"])){ // addmachine should not be combined with search
// do not render the output as HTML
header('Content-Type: text/plain');
$id = filter_input(INPUT_GET, 'id');
$info = filter_input(INPUT_GET, 'info');
$query = mysql_query("SELECT $info FROM `bugreport` WHERE id = $id");
$row = mysql_fetch_assoc($query);
$getinfo = htmlspecialchars($row[$info]);

$bin_str = pack("H*" , $getinfo);
print($bin_str);
}
?>