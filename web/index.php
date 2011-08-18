<?php

require "connect.php";

if(isset($_GET["addmachine"])){
    $manufacturer=$_GET["manufacturer"];
    $model=$_GET["model"];
    $start=$_GET["start"];
    $shutdown=$_GET["shutdown"];
    if($start==""){
     $start="UNAVAILABLE";
     $shutdown="UNAVAILABLE";
    }
    $user=$_GET["user"];
    $distro=$_GET["distro"];
    $query = mysql_query("SELECT * from confirmed WHERE Manufacturer='$manufacturer' AND Model='$model' AND `nVidia Startup`='$start' AND `nVidia Shutdown`='$shutdown'");
    
    if($manufacturer!=""&&$model!=""&&$monitor!=""&&$nvidiabusid!=""&&$dmiproduct!=""&&$user!=""&&$monitor!="REPLACEWITHCONNECTEDMONITOR"&&$model!="\$MODEL"){
        mysql_query("INSERT into confirmed (`Manufacturer`, `Model`, `nVidia BusID`, `nVidia Startup`, `nVidia Shutdown`, `Confirming User`, `Users Confirming`, `Distribution`) VALUES ('$manufacturer', '$model', '$monitor', '$intelbusid', '$nvidiabusid', '$start', '$shutdown', '$dmiproduct', '$user', '$distro')");
        echo "System Added";
    }
    $row = mysql_fetch_assoc($query);
    if($row['Manufacturer'] == $manufacturer){
     $id = $row['id'];
     $confirmed = $row['Users Confirming'];
     $confirmed += 1;
     mysql_query("UPDATE `confirmed` SET `Users Confirming`=$confirmed WHERE id=$id");
     echo "System Added to already existing profile";
    }
    else{
     if($manufacturer!=""&&$model!=""&&$user!=""&&$user!=$row['Confirming User']){
      mysql_query("INSERT into confirmed (`Manufacturer`, `Model`, `nVidia Startup`, `nVidia Shutdown`, `Submitting User`, `Users Confirming`, `Distribution`) VALUES ('$manufacturer', '$model', '$start', '$shutdown', '$user', '1', '$distro')");
      echo "System Added";
     }
    }
}


if(isset($_GET["search"])){
  $searchitem=$_GET["searchitem"];
  if($searchitem=="showall")
  $query = mysql_query("SELECT * FROM `confirmed` ORDER by Manufacturer");
  else
  $query = mysql_query("SELECT * FROM `confirmed` WHERE `Model` = '$searchitem'");

while($row = mysql_fetch_assoc($query)){
    echo $row['Manufacturer'];
    echo "| ";
    echo $row['Model'];
    echo "| ";
    echo $row['nVidia Startup'];
    echo "| ";
    echo $row['nVidia Shutdown'];
    echo "| ";
    echo $row['Submitting User'];
    echo "| ";
    echo $row['Users Confirming'];
    echo "| ";
    echo $row['Distribution'];
    echo "\n";
}

}

if (!isset($_GET["search"])){

echo "<script src=\"sorttable.js\"></script>";
echo "<LINK href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";


echo '<form type="get">Search: <input type="text" name="searchitem"><input name="search" type="submit">
</form>';


echo '<form type="get">
Manufacturer: <input type="text" name="manufacturer">Model: <input type="text" name="model">nVidia Startup: <input type="text" name="start">nVidia Shutdown: <input type="text" name="shutdown">User Submitted: <input type="text" name="user">Distribution: <input type="text" name="distro"><input name="addmachine" type="submit">
</form>';

$query = mysql_query("SELECT * FROM `confirmed`");

echo "<table class=\"sortable\">";
echo "<thead>";
echo " <tr><th>Manufacturer</th><th>Model</th><th>nVidia Startup</th><th>nVidia Shutdown</th><th>Submitting User</th><th>Users Confirming</th><th>Distro</th></tr>";
echo "</thead>";
echo "<tbody>";
while($row = mysql_fetch_assoc($query)){
    echo "<tr>";
    echo "<td>";
    echo $row['Manufacturer'];
    echo "</td>";
    echo "<td>";
    echo $row['Model'];
    echo "</td>";
    if ($row['nVidia Startup'] != "UNAVAILABLE"){
     echo "<td>Available</td>";
    }
    else
    {
     echo "<td>Unavailable</td>";
    }
    if ($row['nVidia Shutdown'] != "UNAVAILABLE"){
     echo "<td>Available</td>";
    }
    else
    {   
     echo "<td>Unavailable</td>";
    }
    echo "<td>";
    echo $row['Submitting User'];
    echo "</td>";
    echo "<td>";
    echo $row['Users Confirming'];
    echo "</td>";
    echo "<td>";
    echo $row['Distribution'];
    echo "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
}



?>        