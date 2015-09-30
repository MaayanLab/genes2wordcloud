<?php
	// This is to get the script execution time.
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $starttime = $mtime;
?> 


<?php
   set_time_limit(600);
   ini_set("memory_limit","1024M");
//General parameters (filenames etc.)

$filename_mp = "../data/VOC_MammalianPhenotype.rpt";
$filename_genes = "../data/HMD_HumanPhenotype.rpt";
$output_filename = "../data/mp_name_annotation.tsv";

//Associative array containing the go defs according to their ids.
$mp = array();

//Hmap with the id and def of each mp
$fr = fopen ($filename_mp, "r") or die("Problem opening go files.");

while ($line= fgets ($fr))
{
	if ($line===FALSE) print ("FALSE\n");
	else 
	{
		$splitline = explode("\t", trim($line));
		if (isset($splitline[2])&&(trim($splitline[2])!=""))
		{
			$mp[trim($splitline[0])] = trim($splitline[2]);
		}
		else 
		{
			$mp[trim($splitline[0])] = trim($splitline[1]);
		}
	} 
}

fclose($fr);


//Generation of mp_name_annotation.tsv file.
$fw = fopen($output_filename, "w");

//To avoid duplicates, we create a hmap with MP id and geneName.
$mp_gene = array();

$fr = fopen($filename_genes, "r");
$ln = 0;
while ($line= fgets ($fr))
{
	++$ln;
	if ($line===FALSE) print ("FALSE\n");
	else 
	{
		$splitline = explode("\t", trim($line));
		if (isset($splitline[4]))
		{
			$gene = trim($splitline[0]);
			$mp_list = trim($splitline[4]);
			$mps = explode(" ", $mp_list);
			
			if (isset($mp_gene[$gene]))
			{
				foreach ($mps as $mp2)
				{
					if (!in_array(trim($mp2),$mp_gene[$gene]))
					{
						array_push($mp_gene[$gene],trim($mp2));
						fwrite($fw,$gene."\t".$mp[trim($mp2)]."\t".trim($mp2)."\n");
					}
				}
			}
			else
			{
				$mp_gene[$gene] = array();
				foreach ($mps as $mp2)
				{
					array_push($mp_gene[$gene],trim($mp2));
					fwrite($fw,$gene."\t".$mp[trim($mp2)]."\t".trim($mp2)."\n");
				}
			}
		}
	} 
}

//print_r($mp);
echo("<br/>");
//print_r($mp_gene);
fclose($fr);	
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