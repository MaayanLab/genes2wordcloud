<?php
	// This is to get the script execution time.
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $starttime = $mtime;
?> 


<?php

//General parameters (filenames etc.)

$filename_pmids = "../data/gene2pubmed";
$filename_name_genes = array("../data/Rattus_norvegicus.gene_info", "../data/Mus_musculus.gene_info", "../data/Homo_sapiens.gene_info");
$output_filename = "../data/symbol-pmid_temp.csv";
$delimiter = "\t";
$delimiter2 = ",";

//Associative array containing the gene names according to their Ids.
$gene_names = array();

//The Taxonomy Id wil be extracted from the same file.
$tax_ids = array(); 

foreach($filename_name_genes as $filename)
{
	$fr = fopen ($filename, "r");
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

//Generation of symbol-pmid.txt file from the gene_names associative array and the gene2pubmed file.
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
				$output_line = trim($splitline[2]).$delimiter2.trim($gene_names[$splitline[1]])."\n";
				fwrite($fw,$output_line);
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
   echo "The symbol-pmid.csv file was created in ".$totaltime." seconds. <br/>";
?>