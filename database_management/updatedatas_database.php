<?php
	// This is to get the script execution time.
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $starttime = $mtime;
?> 

<?php

set_time_limit(1800);

include '../database_connection.php';

//------------------------------------------------------------------
//Symbol_pmids
//------------------------------------------------------------------
$table_name = 'pmid_symbol';
$filename = "../data/symbol-pmid.csv";

$fd = fopen ($filename, "r");
while ($line= fgets ($fd))
{
	if ($line===FALSE) print ("FALSE\n");
	else 
	{
		$splitline = explode(",",$line);
		$query = "INSERT IGNORE INTO ".$table_name." VALUES ('".trim($splitline[0])."','".trim($splitline[1])."')";
		$result = mysql_query($query);
		if (!$result) 
		{
    		die('Invalid query: ' . mysql_error());
		}
	} 
}
fclose ($fd);


//------------------------------------------------------------------
//generif_names_annotation.tsv
//------------------------------------------------------------------

$table_name = 'generif';

$filename = "../data/generif_names_annotation.tsv";

$fd = fopen ($filename, "r");
while ($line= fgets ($fd))
{
	if ($line===FALSE) print ("FALSE\n");
	else 
	{
		$query = "INSERT IGNORE INTO ".$table_name." VALUES ('".trim($splitline[0])."','".mysql_real_escape_string(trim($splitline[1]))."','".trim($splitline[0])."')";
		$result = mysql_query($query);
		if (!$result) 
		{
    		die('Invalid query: ' . mysql_error());
		}		
	} 
}
fclose ($fd);

mysql_close($link);




?>

<?php
	// This is to get the script execution time.
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   echo "The database was updated in ".$totaltime." seconds.<br/>";
?>
