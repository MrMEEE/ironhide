<?php
/**
 * XXX: check which characters are allowed!
 * - fix data containing pipes (|) from breaking the output of search=showall
 */
require "connect.php";

if(isset($_GET["createreport"])){
  if(isset($_GET["github_id"])){
$github_id=filter_input(INPUT_GET, "github_id");
$sql = sprintf('INSERT into bugreport(github_id) VALUES("%s")', mysql_real_escape_string($github_id));
$query=mysql_query($sql);
echo "New ID is:".mysql_insert_id()."";
  }
}
if(isset($_POST["updatereport"]) && isset($_POST["update"]) && isset($_POST["value"]) ){
    $update = filter_input(INPUT_POST, "update");
    $value = filter_input(INPUT_POST, "value");
    $updatereport = filter_input(INPUT_POST, "updatereport");
    $sql = "UPDATE bugreport set `".$update."`='".$value."' WHERE `id`='".$updatereport."'";
    mysql_query($sql);
    echo "Bug Report Updated";
}

if(!isset($_GET["createreport"])){

echo "<script src=\"sorttable.js\"></script>";
echo "<LINK href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";

$query = mysql_query("SELECT * FROM `bugreport`");

echo "<table class=\"sortable\">";
echo "<thead>";
echo " <tr><th width=9%>Github ID</th><th width=9%>nVidia Log</th><th width=9%>nVidia Startup</th><th width=9%>nVidia Shutdown</th><th width=8%>xorg.conf</th><th width=8%>xorg.conf.nvidia</th><th width=8%>System Info</th><th width=8%>lspci</th><th width=8%>Libraries</th><th width=8%>lsmod</th><th width=8%>alternatives</th><th width=8%>User</th></tr>";
echo "</thead>";
echo "<tbody>";
while($row = mysql_fetch_assoc($query)){
    echo "<tr>";
    echo "<td>";
    echo '<a href="https://github.com/MrMEEE/ironhide/issues/'. htmlspecialchars($row['github_id']) . '" target="_blank">' . htmlspecialchars($row['github_id']) . '</a>';
    echo "</td>";
    echo "<td>";
    echo '<a href="showinfo.php?show=1&id='. htmlspecialchars($row['id']) . '&info=nvidia_log">Show</a>';
    echo "</td>";
    echo "<td>";  
    echo '<a href="showinfo.php?show=1&id='. htmlspecialchars($row['id']) . '&info=enablescript">Show</a>';       
    echo "</td>";
    echo "<td>";  
    echo '<a href="showinfo.php?show=1&id='. htmlspecialchars($row['id']) . '&info=disablescript">Show</a>';       
    echo "</td>";
    echo "<td>";  
    echo '<a href="showinfo.php?show=1&id='. htmlspecialchars($row['id']) . '&info=xorg_conf">Show</a>';       
    echo "</td>";
    echo "<td>";  
    echo '<a href="showinfo.php?show=1&id='. htmlspecialchars($row['id']) . '&info=xorg_conf_nvidia">Show</a>';       
    echo "</td>";
    echo "<td>";  
    echo '<a href="showinfo.php?show=1&id='. htmlspecialchars($row['id']) . '&info=system_info">Show</a>';       
    echo "</td>";
    echo "<td>";  
    echo '<a href="showinfo.php?show=1&id='. htmlspecialchars($row['id']) . '&info=lspci">Show</a>';       
    echo "</td>";
    echo "<td>";  
    echo '<a href="showinfo.php?show=1&id='. htmlspecialchars($row['id']) . '&info=libraries">Show</a>';       
    echo "</td>";
    echo "<td>";  
    echo '<a href="showinfo.php?show=1&id='. htmlspecialchars($row['id']) . '&info=modules">Show</a>';       
    echo "</td>";
    echo "<td>";  
    echo '<a href="showinfo.php?show=1&id='. htmlspecialchars($row['id']) . '&info=alternatives">Show</a>';       
    echo "</td>";
    echo "<td>";
    echo htmlspecialchars($row['username']);
    echo "</td>";
}
echo "</tbody>";
echo "</table>";

}

?>        