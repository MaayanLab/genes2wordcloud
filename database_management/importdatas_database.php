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
/*
$table_name = 'pmid_symbol';

$query_create_table = "CREATE TABLE ".$table_name." (
   pmid INT NOT NULL,
   geneName VARCHAR(10) NOT NULL
  ) CHARACTER SET UTF8";	

$result = mysql_query($query_create_table);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}


$query_index = "CREATE UNIQUE INDEX dpc ON pmid_symbol (pmid,geneName);";

$result = mysql_query($query_index);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}

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

$query_index = "CREATE INDEX gene ON pmid_symbol (geneName);";

$result = mysql_query($query_index);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}

//*/

//------------------------------------------------------------------
//generif_names_annotation.tsv
//------------------------------------------------------------------

//*
$table_name = 'generif';

$query_create_table = "CREATE TABLE ".$table_name." (
   geneName VARCHAR(50) NOT NULL,
   generif VARCHAR(255) NOT NULL,
   pmid INT NOT NULL
  ) CHARACTER SET UTF8";	

$result = mysql_query($query_create_table);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}

$query_index = "CREATE UNIQUE INDEX dpc ON generif (geneName,generif);";

$result = mysql_query($query_index);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}

$filename = "../data/generif_names_annotation.tsv";

$fd = fopen ($filename, "r");
while ($line= fgets ($fd))
{
	if ($line===FALSE) print ("FALSE\n");
	else 
	{
		$splitline = explode("\t",$line);
		$query = "INSERT IGNORE INTO ".$table_name." VALUES ('".trim($splitline[0])."','".mysql_real_escape_string(trim($splitline[1]))."','".trim($splitline[2])."')";
		$result = mysql_query($query);
		if (!$result) 
		{
    		die('Invalid query: ' . mysql_error());
		}		
	} 
}
fclose ($fd);

$query_index = "CREATE INDEX gene2 ON generif (geneName);";

$result = mysql_query($query_index);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}
//*/
//------------------------------------------------------------------
//go_name_annotation.tsv
//------------------------------------------------------------------
//*
$table_name = 'go';

$query_create_table = "CREATE TABLE ".$table_name." (
   geneName VARCHAR(50) NOT NULL,
   go TEXT NOT NULL,
   goID VARCHAR(50) NOT NULL
  ) CHARACTER SET UTF8";	

$result = mysql_query($query_create_table);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}

$query_index = "CREATE INDEX gene3 ON go (geneName);";

$result = mysql_query($query_index);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}

$filename = "../data/go_name_annotation.tsv";

$fd = fopen ($filename, "r");
while ($line= fgets ($fd))
{
	if ($line===FALSE) print ("FALSE\n");
	else 
	{
		$splitline = explode("\t",$line);
		$query = "INSERT INTO ".$table_name." VALUES ('".trim($splitline[0])."','".mysql_real_escape_string(trim($splitline[1]))."','".trim($splitline[2])."')";
		$result = mysql_query($query);
		if (!$result) 
		{
    		die('Invalid query: ' . mysql_error());
		}		
	} 
}
fclose ($fd);

//*/

//------------------------------------------------------------------
//remove_keywords
//------------------------------------------------------------------
/*
$table_name = 'remove_keywords';

$query_create_table = "CREATE TABLE ".$table_name." (
   id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
   word VARCHAR(255) NOT NULL,
   type_wordcloud VARCHAR(255),
   query TEXT,
   ip VARCHAR(255),
   time TIMESTAMP
  ) CHARACTER SET UTF8";	

$result = mysql_query($query_create_table);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}
//*/


//------------------------------------------------------------------
//mp_name_annotation.tsv
//------------------------------------------------------------------
//*
$table_name = 'mp';

$query_create_table = "CREATE TABLE ".$table_name." (
   geneName VARCHAR(50) NOT NULL,
   mp TEXT NOT NULL,
   mpID VARCHAR(50) NOT NULL
  ) CHARACTER SET UTF8";	

$result = mysql_query($query_create_table);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}

$query_index = "CREATE INDEX gene4 ON mp (geneName);";

$result = mysql_query($query_index);
if (!$result) 
{
   echo('Invalid query: ' . mysql_error());
}

$filename = "../data/mp_name_annotation.tsv";

$fd = fopen ($filename, "r");
while ($line= fgets ($fd))
{
	if ($line===FALSE) print ("FALSE\n");
	else 
	{
		$splitline = explode("\t",$line);
		$query = "INSERT INTO ".$table_name." VALUES ('".trim($splitline[0])."','".mysql_real_escape_string(trim($splitline[1]))."','".trim($splitline[2])."')";
		$result = mysql_query($query);
		if (!$result) 
		{
    		die('Invalid query: ' . mysql_error());
		}		
	} 
}
fclose ($fd);

//*/


mysql_close($link);

?>

<?php
	// This is to get the script execution time.
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   echo "This page was created in ".$totaltime." seconds";
?>
