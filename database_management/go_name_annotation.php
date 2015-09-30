<?php
	// This is to get the script execution time.
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $starttime = $mtime;
   set_time_limit(600);
?> 


<?php

//General parameters (filenames etc.)

$filename_go = "../data/gene_ontology.obo";
$filename_genes = array("../data/gene_association.goa_human", "../data/gene_association.mgi", "../data/gene_association.rgd");
$output_filename = "../data/go_name_annotation.tsv";

//Associative array containing the go defs according to their ids.
$go = array();

//Hmap with the id and def of each go
$fr = fopen ($filename_go, "r") or die("Problem opening go files.");

$contents = fread($fr, filesize($filename_go));
//echo $contents;
$contents_exploded = explode("[Term]\n",$contents);
unset($contents);

for ($i = 1; $i < sizeof($contents_exploded); $i++)
{
	$lines = explode("\nname: ",$contents_exploded[$i]);
	//print_r($lines);
	$id = explode("id: ",$lines[0]);
	$def1 = explode("\ndef: \"", $lines[1]);
	$def2 = explode("\" [",$def1[1]);
	$go[trim($id[1],"\n")] = trim($def2[0],"\n");
}
//print_r($go);
unset ($contents_exploded);
fclose($fr);


//Generation of go_name_annotation.tsv file.
$fw = fopen($output_filename, "w");

//To avoid duplicates, we create a hmap with GO id and geneName.
$go_gene = array();

foreach ($filename_genes as $filename)
{
	$fr = fopen($filename, "r");
	$ln = 0;
	while ($line= fgets ($fr))
	{
		++$ln;
		if ($line===FALSE) print ("FALSE\n");
		else 
		{
			if (substr($line,0,1)!="!")
			{
				$splitline = explode("\t", trim($line,"\n"));
				//print_r($splitline);
				if (isset($go_gene[trim($splitline[2])]))
				{
					if (!(in_array(trim($splitline[4]),$go_gene[trim($splitline[2])])))
					{
						$output_line = trim($splitline[2])."\t".$go[trim($splitline[4])]."\t".trim($splitline[4])."\n";
						//echo $output_line;
						fwrite($fw,$output_line);
						array_push($go_gene[trim($splitline[2])], trim($splitline[4]));
					}
				}
				else 
				{
					$output_line = trim($splitline[2])."\t".$go[trim($splitline[4])]."\t".trim($splitline[4])."\n";
					//echo $output_line;
					fwrite($fw,$output_line);
					$go_gene[trim($splitline[2])] = array(trim($splitline[4]));
				}
			}
		} 
	}
	fclose($fr);	
}
fclose($fw);

?>

<?php
	// This is to get the script execution time.
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   echo "The go_name_annotation.tsv file was created in ".$totaltime." seconds.<br/>";
?>