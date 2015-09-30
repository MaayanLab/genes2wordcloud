<?php

include_once("database_connection.php");

if(isset($_GET['q']) and $_GET['q'] != '')
{
    $query = "SELECT DISTINCT geneName FROM genes WHERE geneName REGEXP '^".$_GET['q']."'";

   // echo $query;
    
    $result = mysql_query($query);

    while ($row = mysql_fetch_assoc($result)) 
    {
       echo $row["geneName"]."\n";
    }
    mysql_free_result($result);
}

?>