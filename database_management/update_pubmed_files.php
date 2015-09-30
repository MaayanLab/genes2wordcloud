<?php
#!/usr/local/php5/bin/php-cgi -q
set_time_limit(3600);

$batch = 10000;

function gz_extract($filename_input, $filename_output, $batch)
{
	$gz = gzopen($filename_input , "r");
	$fw = fopen($filename_output, "w");

	while (!gzeof($gz)) {
		$contents = gzread($gz, $batch);
		fwrite($fw, $contents);
	}
	gzclose($gz);
	fclose ($fw);
}


//*

//Get the gene2pubmed file and uncompress it.
copy("ftp://ftp.ncbi.nih.gov/gene/DATA/gene2pubmed.gz", "../data/gene2pubmed.gz");
gz_extract("../data/gene2pubmed.gz","../data/gene2pubmed", $batch);
unlink("../data/gene2pubmed.gz");

//Get the generifs_basic file and uncompress it.
copy("ftp://ftp.ncbi.nih.gov/gene/GeneRIF/generifs_basic.gz", "../data/generifs_basic.gz");
gz_extract("../data/generifs_basic.gz","../data/generifs_basic", $batch);
unlink("../data/generifs_basic.gz");


//Get the Mus_musculus.gene_info file and uncompress it.
copy("ftp://ftp.ncbi.nih.gov/gene/DATA/GENE_INFO/Mammalia/Mus_musculus.gene_info.gz", "../data/Mus_musculus.gene_info.gz");
gz_extract("../data/Mus_musculus.gene_info.gz","../data/Mus_musculus.gene_info", $batch);
unlink("../data/Mus_musculus.gene_info.gz");


//Get the Homo_sapiens.gene_info file and uncompress it.
copy("ftp://ftp.ncbi.nih.gov/gene/DATA/GENE_INFO/Mammalia/Homo_sapiens.gene_info.gz", "../data/Homo_sapiens.gene_info.gz");
gz_extract("../data/Homo_sapiens.gene_info.gz","../data/Homo_sapiens.gene_info", $batch);
unlink("../data/Homo_sapiens.gene_info.gz");

//Get the Rattus_norvegicus.gene_info file and uncompress it.
copy("ftp://ftp.ncbi.nih.gov/gene/DATA/GENE_INFO/Mammalia/Rattus_norvegicus.gene_info.gz", "../data/Rattus_norvegicus.gene_info.gz");
gz_extract("../data/Rattus_norvegicus.gene_info.gz","../data/Rattus_norvegicus.gene_info", $batch);
unlink("../data/Rattus_norvegicus.gene_info.gz");
//*/

/*
//Get the gene_association.goa_human file and uncompress it
copy("ftp://ftp.geneontology.org/pub/go/gene-associations/gene_association.goa_human.gz", "../data/gene_association.goa_human.gz");
gz_extract("../data/gene_association.goa_human.gz","../data/gene_association.goa_human", $batch);
unlink("../data/gene_association.goa_human.gz");

//Get the gene_association.goa_rat file and uncompress it
copy("ftp://ftp.geneontology.org/pub/go/gene-associations/gene_association.goa_rat.gz", "../data/ggene_association.goa_rat.gz");
gz_extract("../data/gene_association.goa_rat.gz","../data/gene_association.goa_rat", $batch);
unlink("../data/gene_association.goa_rat.gz");

//Get the gene_association.goa_mouse file and uncompress it
copy("ftp://ftp.geneontology.org/pub/go/gene-associations/gene_association.goa_mouse.gz", "../data/gene_association.goa_mouse.gz");
gz_extract("../data/gene_association.goa_mouse.gz","../data/gene_association.goa_mouse", $batch);
unlink("../data/gene_association.goa_mouse.gz");

//Get the gene_ontology.obo file and uncompress it
copy("ftp://ftp.geneontology.org/pub/go/ontology/gene_ontology.obo", "../data/gene_ontology.obo.gz");
gz_extract("../data/gene_ontology.obo.gz","../data/gene_ontology.obo", $batch);
unlink("../data/gene_ontology.obo.gz");

 */

 //*
 
//Generate the symbol-pmid.csv file.
include("generate_symbol-pmid.php");

//Generate the generif_names_annotation.tsv file.
include("generif_names_annotation.php");
//*/

//Delete all the files not needed
//*
unlink("../data/gene2pubmed");
unlink("../data/generifs_basic");
unlink("../data/Mus_musculus.gene_info");
unlink("../data/Homo_sapiens.gene_info");
unlink("../data/Rattus_norvegicus.gene_info");
//*/

//*
//Delete the file otherwise won't be replace when rename takes place
unlink("../data/generif_names_annotation.tsv");
unlink("../data/symbol-pmid.csv");
//*/

//Rename the files
rename("../data/generif_names_annotation_temp.tsv","../data/generif_names_annotation.tsv");
rename("../data/symbol-pmid_temp.csv","../data/symbol-pmid.csv");

//Update the database
//*
include("updatedatas_database.php");
//*/

?>