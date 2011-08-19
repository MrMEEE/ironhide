<?php
/**
 * XXX: check which characters are allowed!
 * - fix data containing pipes (|) from breaking the output of search=showall
 */
require "connect.php";

if(isset($_GET["addmachine"])){
    $manufacturer = filter_input(INPUT_GET, "manufacturer");
    $model = filter_input(INPUT_GET, "model");
    $start = filter_input(INPUT_GET, "start");
    $shutdown = filter_input(INPUT_GET, "shutdown");
    if($start==""){
     $start="UNAVAILABLE";
     $shutdown="UNAVAILABLE";
    }
    $user = filter_input(INPUT_GET, "user");
    $distro = filter_input(INPUT_GET, "distro");
    // if the host allows it, use MySQLi + prepared statements
    $sql = sprintf('SELECT * from confirmed
        WHERE Manufacturer="%s" AND Model="%s" AND `nVidia Startup`="%s" AND `nVidia Shutdown`="%s"',
        mysql_real_escape_string($manufacturer), mysql_real_escape_string($model),
        mysql_real_escape_string($shutdown));
    $query = mysql_query($sql);
    
    if($manufacturer!=""&&$model!=""&&$monitor!=""&&$nvidiabusid!=""&&$dmiproduct!=""&&
    $user!=""&&$monitor!="REPLACEWITHCONNECTEDMONITOR"&&$model!='$MODEL'){
        $sql = sprintf('INSERT into confirmed(Manufacturer, Model, `nVidia BusID`, `nVidia Startup`,
            `nVidia Shutdown`, `Confirming User`, `Users Confirming`, Distribution) VALUES
            ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")',
            mysql_real_escape_string($manufacturer), mysql_real_escape_string($model),
            mysql_real_escape_string($monitor), mysql_real_escape_string($intelbusid),
            mysql_real_escape_string($nvidiabusid), mysql_real_escape_string($start),
            mysql_real_escape_string($shutdown), mysql_real_escape_string($dmiproduct),
            mysql_real_escape_string($user), mysql_real_escape_string($distro)
        );
        mysql_query($sql);
        echo "System Added";
    }
    $row = mysql_fetch_assoc($query);
    if($row['Manufacturer'] == $manufacturer){
     $id = $row['id'];
     $confirmed = $row['Users Confirming'];
     $confirmed += 1;
     // acceptable since $confirmed and $id are both numbers
     mysql_query("UPDATE `confirmed` SET `Users Confirming`=$confirmed WHERE id=$id");
     echo "System Added to already existing profile";
    }
    else{
     if($manufacturer!=""&&$model!=""&&$user!=""&&$user!=$row['Confirming User']){
      $sql = 'INSERT into confirmed (Manufacturer, Model, `nVidia Startup`,
        `nVidia Shutdown`, `Submitting User`, `Users Confirming`, Distribution) VALUES
        ("%s", "%s", "%s", "%s", "%s", '1', "%s")',
        mysql_real_escape_string($manufacturer), mysql_real_escape_string($model),
        mysql_real_escape_string($start), mysql_real_escape_string($shutdown),
        mysql_real_escape_string($user), mysql_real_escape_string($distro)
        );
      mysql_query($sql);
      echo "System Added";
     }
    }
}
else if(isset($_GET["search"])){ // addmachine should not be combined with search
    // do not render the output as HTML
    header('Content-Type: text/plain');
  $searchitem = filter_input(INPUT_GET, 'searchitem');
  if($searchitem=="showall")
  $query = mysql_query("SELECT * FROM `confirmed` ORDER by Manufacturer");
  else
  $query = mysql_query(sprintf("SELECT * FROM confirmed WHERE Model = '%s'",
    mysql_real_escape_string($searchitem)));

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
    echo htmlspecialchars($row['Manufacturer']);
    echo "</td>";
    echo "<td>";
    echo htmlspecialchars($row['Model']);
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
    echo htmlspecialchars($row['Submitting User']);
    echo "</td>";
    echo "<td>";
    echo htmlspecialchars($row['Users Confirming']);
    echo "</td>";
    echo "<td>";
    echo htmlspecialchars($row['Distribution']);
    echo "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
}



?>        