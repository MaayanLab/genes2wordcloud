<?php
	// This is to get the script execution time.
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $starttime = $mtime;
?> 


<?php

//General parameters (filenames etc.)

$filename_pmids = "../data/generifs_basic";
$filename_name_genes = array("../data/Rattus_norvegicus.gene_info", "../data/Mus_musculus.gene_info", "../data/Homo_sapiens.gene_info");
$output_filename = "../data/generif_names_annotation_temp.tsv";
$delimiter = "\t";
$delimiter2 = "\t";

//Associative array containing the gene names according to their Ids.
$gene_names = array();

//The Taxonomy Id wil be extracted from the same file.
$tax_ids = array(); 

foreach($filename_name_genes as $filename)
{
	$fr = fopen ($filename, "r") or die("Problem opening gene_info files.");
	$ln= 0;
	while ($line= fgets ($fr))
	{
		++$ln;
		if ($line===FALSE) print ("FALSE\n");
		else 
		{
			$splitline = explode($delimiter, trim($line,"\n"));
		
			$gene_names[$splitline[1]] = $splitline[2];
			if (! in_array($splitline[0],$tax_ids))
				array_push($tax_ids, $splitline[0]);	
		} 
	}
	fclose ($fr);
}

//Generation of generif_names_annotation.csv file from the gene_names associative array and the generif_basic file.
$fr = fopen($filename_pmids, "r");
$fw = fopen($output_filename, "w");
$ln = 0;

while ($line= fgets ($fr))
	{
		++$ln;
		if ($line===FALSE) print ("FALSE\n");
		else 
		{
			$splitline = explode($delimiter, trim($line,"\n"));
			if (in_array($splitline[0], $tax_ids))
			{
				if (isset($gene_names[$splitline[1]]))
				{
					$pmids = explode(",",$splitline[2]);
					$output_line = trim($gene_names[$splitline[1]]).$delimiter2.trim($splitline[4]).$delimiter2.trim($pmids[0])."\n";
					fwrite($fw,$output_line);
				}
			}
		} 
	}
fclose ($fr);
fclose ($fw);
?>

<?php
	// This is to get the script execution time.
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   echo "The names_annotation.tsv file was created in ".$totaltime." seconds.<br/>";
?>