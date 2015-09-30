<?php
	// This is to get the script execution time.
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $starttime = $mtime;
?> 


<?php 

include_once '../textmining.php';
include("../database_connection.php");
include_once '../progress_bar.php';

$query = "SELECT generif FROM generif ORDER BY RAND() LIMIT 10000";

	$result = mysql_query($query);
	
	if (!$result) 
	{
   		echo('Invalid query: ' . mysql_error());
	}
	
	$fr = fopen("../data/generifs_basic_portion.txt", 'w');
	while ($row = mysql_fetch_assoc($result)) {
		fwrite($fr, $row["generif"]."\n");
	}
	mysql_free_result($result);


$text = file_get_contents("../data/generifs_basic_portion.txt");

$keywordCounts = textmining_text_e($text, "../data/stopwords.txt", 0, 100);

$fw = fopen ("../data/generifs_frequency_word.txt", "w");
foreach ($keywordCounts as $key => $val)
{
	fwrite($fw,$key."\t".$val."\n");
}

fclose ($fw);


$query = "SELECT go FROM go ORDER BY RAND() LIMIT 10000";

	$result = mysql_query($query);
	
	if (!$result) 
	{
   		echo('Invalid query: ' . mysql_error());
	}
	
	$fr = fopen("../data/go_basic_portion.txt", 'w');
	while ($row = mysql_fetch_assoc($result)) {
		fwrite($fr, $row["go"]."\n");
	}
	mysql_free_result($result);



$text = file_get_contents("../data/go_basic_portion.txt");

$keywordCounts = textmining_text_e($text, "../data/stopwords.txt", 0, 100);

$fw = fopen ("../data/go_frequency_word.txt", "w");
foreach ($keywordCounts as $key => $val)
{
	fwrite($fw,$key."\t".$val."\n");
}

fclose ($fw);

$query = "SELECT mp FROM mp ORDER BY RAND() LIMIT 10000";

	$result = mysql_query($query);
	
	if (!$result) 
	{
   		echo('Invalid query: ' . mysql_error());
	}
	
	$fr = fopen("../data/mp_basic_portion.txt", 'w');
	while ($row = mysql_fetch_assoc($result)) {
		fwrite($fr, $row["mp"]."\n");
	}
	mysql_free_result($result);



$text = file_get_contents("../data/mp_basic_portion.txt");

$keywordCounts = textmining_text_e($text, "../data/stopwords.txt", 0, 100);

$fw = fopen ("../data/mp_frequency_word.txt", "w");
foreach ($keywordCounts as $key => $val)
{
	fwrite($fw,$key."\t".$val."\n");
}

fclose ($fw);

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