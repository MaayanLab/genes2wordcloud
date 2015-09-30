<?php
session_start();

//Max script execution time is 600 seconds.
set_time_limit(600);

include_once 'textmining.php';
include_once 'progress_bar.php';

//Parameters
$cutoff = 1;
$rank_cutoff_value = 70;
$keywords_max = 100;
$batch = 150;
$max_pmids = 150;
$max_generifs = 500;
$max_go = 500;
$max_mp = 500;
$nb_batch = $max_pmids/$batch;

//Get the POST parameters
if (isset($_POST["width_user"]))
	$_SESSION["width_user"] = $_POST["width_user"];
if (isset($_POST["height_user"]))
	$_SESSION["height_user"] = $_POST["height_user"];
	
if ($_POST["source"]=="generif")
{
	$_SESSION["generif"]=1;
	$_SESSION["pubmed"]=0;
	$_SESSION["go"]=0;
	$_SESSION["mp"]=0;
	$_SESSION["mesh_terms"]=0;
	
}
else if ($_POST["source"]=="pubmed")
{
	$_SESSION["generif"]=0;
	$_SESSION["pubmed"]=1;
	$_SESSION["go"]=0;
	$_SESSION["mp"]=0;
	$_SESSION["mesh_terms"]=0;
}
else if ($_POST["source"]=="go")
{
	$_SESSION["generif"]=0;
	$_SESSION["pubmed"]=0;
	$_SESSION["go"]=1;
	$_SESSION["mp"]=0;
	$_SESSION["mesh_terms"]=0;
}
else if ($_POST["source"]=="mp")
{
	$_SESSION["generif"]=0;
	$_SESSION["pubmed"]=0;
	$_SESSION["go"]=0;
	$_SESSION["mp"]=1;
	$_SESSION["mesh_terms"]=0;
}
else if ($_POST["source"]=="mesh_terms")
{
	$_SESSION["generif"]=0;
	$_SESSION["pubmed"]=0;
	$_SESSION["go"]=0;
	$_SESSION["mp"]=0;
	$_SESSION["mesh_terms"]=1;
}
else
{
	$_SESSION["generif"]=1;
	$_SESSION["pubmed"]=0;
	$_SESSION["go"]=0;
	$_SESSION["mp"]=0;
	$_SESSION["mesh_terms"]=0;
}

$_SESSION['from'] = "list of genes";
$_SESSION["forbidden_keywords"]["words"] = "";

// Function which permits to cut-off the number of genes
function cutoff($var) 
{
	global $cutoff;
	return ($var > $cutoff);
}

//Get the gene list paste by the user and clean it to avoid \n or \r or \t... problems. Also create the name_genes param for the applet.
$genes = $_POST["genes"];
//Split by - , ; : \t \n \r \f " "
$gene_list = preg_split("#[-,;:\s]+#",$genes);

$gene_list_clean = array();
$gene_rejected = array();
$name_genes = "";

$compt = 0;
$compt2 = 0;
foreach ($gene_list as $val)
{
	if (preg_match("#^[a-zA-Z0-9]+$#",trim($val))==1)
	{
		$gene_list_clean[$compt] = strtoupper(trim($val));
		$name_genes .= " ".strtolower(trim($val));
		$compt++;
	}
	else {
		$gene_rejected[$compt2] = trim($val);
		$compt2++;
	}
}

//$gene_list_clean = array(KLF4);
//print_r($gene_list_clean);
//print_r($gene_rejeted);

//Connection database. To comment if not using.
include("database_connection.php");

$query_ending = "";
foreach ($gene_list_clean as $geneName)
{
	$query_ending = $query_ending." geneName = '".$geneName."' OR";
}

$query_ending =  substr($query_ending, 0, -3);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="description" content="Genes2WordCloud"/>
        <meta name="keywords" content="gene, wordcloud, visualization tool, mount sinai, systems biology, genetics, genes, protein, web application."/>
		<meta name="author" content="Caroline Baroukh"/>
		<meta name="location" content="Mount Sinai Medical School,New York"/>
		<title>Genes2WordCloud</title>
        <link rel="icon" type="image/png" href="images/favicon.png"/>
        <link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/progress_bar.css" />
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/index.css" />
	</head>
    
   <body onload="document.getElementById('cache').focus();">
		<div id="header">
            <div id="logo">
                <a href="index.php">
                    <img class="logo" border="0" src="images/logoG2W.png" height="60px">
                    <h1> Genes2WordCloud </h1> 
                </a>
            </div>
		</div>
		<div id="top">
            <div id="menu">
                <ul>
                    <li><a href="create_wordcloud.php">CREATE</a></li>
                    <li><a href="WordCloud.php">VIEW</a> </li>
                    <li><a href="help.php">HELP</a> </li>
                </ul>
            </div>
        </div>
		<div id = "corpus">
			<p>Be patient, sometimes it takes several minutes to create the WordCloud(s)!</p>

<?php

//Create Progress Bar
create_progress_bars(1);
$percent = 0;

//Generif WordCloud
if ($_SESSION["generif"]==1)
{
	$filename_generif = "data/generif_names_annotation.tsv";
	$delimiter= "\t";
	$filename_ouput = "data/session_files/text_".$_SESSION['sessionid'].".txt";
	
	update_progress(1,floor($percent),"Extraction generif annotations." );
	
	/*
	//File version
	$fr = fopen($filename_ouput, 'w');
	$fd = fopen ($filename_generif, "r");
	while ($line= fgets ($fd))
	{
		if ($line===FALSE) print ("FALSE\n");
		else 
		{
			$splitline = explode($delimiter,$line);
			if (in_array(strtoupper(trim($splitline[0])), $gene_list_clean))
			{
				fwrite($fr, trim($splitline[1])."\n");
			}
		} 
	}
	fclose ($fd);
	fclose ($fr);
	//*/
	
	//*
	//Database version
	$query = "SELECT generif, pmid FROM generif WHERE".$query_ending."ORDER BY RAND(),pmid LIMIT ".$max_generifs;

	$result = mysql_query($query);
	
	if (!$result) 
	{
   		echo('Invalid query: ' . mysql_error());
	}
	
	$fr = fopen($filename_ouput, 'w');
	$compt = 0;
	$pmid = "";
	while ($row = mysql_fetch_assoc($result)) {
		if ($pmid != $row["pmid"])
		{
			if ($compt ==0)
			{
				fwrite($fr,$row["pmid"]."\n");
			}
			else
			{
				fwrite($fr,"\n".$row["pmid"]."\n");
			}
			$compt++;
		}
		fwrite($fr, $row["generif"]."\n");
	}
	mysql_free_result($result);
	fclose ($fr);
	
	//*/
	$percent = 40;
	update_progress(1 , floor($percent), "Text-mining.");

	//Get all the words with their occurences from the generated file
	$keywordCounts = textmining("data/session_files/text_".$_SESSION['sessionid'].".txt", "data/stopwords.txt", "data/bio-stopwords.txt", $name_genes, 40, 80);
	
	$percent = 80;
	update_progress(1, floor($percent), "Extracting keywords.");
	
	//Determine the value of cutoff to know how many keywords to remove
	if (sizeof($keywordCounts) > $keywords_max)
	{
		$compt = 0;
	
		foreach($keywordCounts as $key =>$val)
		{
			$compt++;
			if ($compt == $rank_cutoff_value)
			{
				$cutoff = $val-1;
				break;
			}
		}
	}
	else 
	{
		$cutoff = 1;
	}

	//Remove the keywords where the value of occurence is below cutoff
	$keywordCounts_filtered = array_filter($keywordCounts,"cutoff");

	//Generate the applet parameter keywords
	$string_output = "";
	foreach ($keywordCounts_filtered as $word => $count)
	{
		if (!preg_match("#[\s]+#", $word)&& $word !="")
			$string_output .=$word." ".$count." ";
	}
	//remove the last space
	$string_output = substr($string_output, 0,-1);

	$_SESSION["keywords"] = $string_output;
	$percent = 100;
	update_progress(1, floor($percent), "Generif WordCloud created.");
}

	//Abstracts WordCloud
else if ($_SESSION["pubmed"]==1)
{
	//Get the pmids
	
	/* From the file
	$filename_symbolpmid = "data/symbol-pmid.csv";
	$delimiter_symbolpmid = ",";

	$fd = fopen ($filename_symbolpmid, "r");
	$pmids = array();
	$compt = 1;
	while ($line= fgets ($fd))
	{
		if ($line===FALSE) print ("FALSE\n");
		else 
		{
			$splitline = explode($delimiter_symbolpmid,$line);
			if (in_array(strtoupper(trim($splitline[1])), $gene_list_clean))
			{
				$pmids[$compt-1] = trim($splitline[0]);
				$compt ++;
			}
		} 
	}
	fclose ($fd);
	//*/
	$percent = 0;
	update_progress(1, floor($percent), "Extraction PubMed ids.");
	//* From the database
	$query = "SELECT pmid FROM pmid_symbol WHERE".$query_ending." ORDER BY RAND() LIMIT ".$max_pmids;
	$result = mysql_query($query);
	
	if (!$result) 
	{
   		echo('Invalid query: ' . mysql_error());
	}
	
	$compt = 0;
	while ($row = mysql_fetch_assoc($result)) {
    	$pmids[$compt] = $row["pmid"];
    	$compt++;
	}

	mysql_free_result($result);
	//*/

	//print_r($pmids);
	$percent = 30;
	update_progress(1, floor($percent), "Extraction abstracts.");
	
	//Delete content of the file
	$fr = fopen("data/session_files/text_".$_SESSION['sessionid'].".txt", "w");
	fclose($fr);
	
	$pmid_list_array = array();
	$pmid_list = $pmids[0];
	for ($i = 1; $i<sizeof($pmids); $i++)
	{
		if ($i % $batch == 0)
		{
			array_push($pmid_list_array, $pmid_list);
			$pmid_list = $pmids[$i];
		}
		else 
		{
			$pmid_list .=",".$pmids[$i];
		}
		
	}
	//Don't forget the last batch...
	array_push($pmid_list_array, $pmid_list);
		
		
	//print_r($pmid_list_array);
	for ($i = 0 ; $i<sizeof($pmid_list_array); $i++)
	{
		//URL query
		$query_url = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&rettype=Abstract&retmode=xml&id=".$pmid_list_array[$i];
		
		//echo $query_url;
		
		//Parse and save the abstracts lists from the answer query of PubMed databases into a text file.
		$doc = new DOMDocument();
		$doc->load($query_url);
		
		$percent += (70-30)/(2*sizeof($pmid_list_array));
		update_progress(1, floor($percent), "Extraction abstracts.");
		
		$fr = fopen("data/session_files/text_".$_SESSION['sessionid'].".txt", "a");
		$compt = 0;
		
		$PubmedArticles = $doc->getElementsByTagName("PubmedArticle");
		foreach ( $PubmedArticles as $PubmedArticle)
		{
			fwrite($fr,$pmids[$compt]."\n");
			$ArticleTitles = $PubmedArticle -> getElementsByTagName("ArticleTitle");
			foreach ($ArticleTitles as $ArticleTitle)
			{
				fwrite($fr,$ArticleTitle->nodeValue."\n");
			}
			$Abstracts = $PubmedArticle -> getElementsByTagName("Abstract");
			foreach ($Abstracts as $Abstract)
			{
				$AbstractTexts = $Abstract -> getElementsByTagName("AbstractText");
				foreach ($AbstractTexts as $AbstractText)
				{
					fwrite($fr, $AbstractText->nodeValue."\n");
					$percent += (70-30)/($PubmedArticles->length*2*sizeof($pmid_list_array));
					update_progress(1, floor($percent), "Extraction abstracts.");
					
				}
			}
			fwrite($fr,"\n");
			$compt++;			
		}
		fclose($fr);
	}
	
	$percent = 70;
	update_progress(1, floor($percent), "Text-mining.");
	//*
	$keywordCounts = textmining("data/session_files/text_".$_SESSION['sessionid'].".txt", "data/stopwords.txt", "data/bio-stopwords.txt", $name_genes, 70 ,90);
	//Determine the value of cutoff to know how many keywords to remove
	
	$percent = 90;
	update_progress(1, floor($percent), "Extracting keywords.");
	if (sizeof($keywordCounts) > $keywords_max)
	{
		$compt = 0;
	
		foreach($keywordCounts as $key =>$val)
		{
			$compt++;
			if ($compt == $rank_cutoff_value)
			{
				$cutoff = $val-1;
				break;
			}
		}
	}
	else 
	{
		$cutoff = 1;
	}

	//Remove the keywords where the value of occurence is below cutoff
	$keywordCounts_filtered = array_filter($keywordCounts,"cutoff");

	//Generate the applet parameter keywords
	$string_output = "";
	foreach ($keywordCounts_filtered as $word => $count)
	{
		if (!preg_match("#[\s]+#", $word)&& $word !="")
			$string_output .=$word." ".$count." ";
	}
	//remove the last space
	$string_output = substr($string_output, 0,-1);

	$_SESSION["keywords"] = $string_output;
	$percent = 100;
	update_progress(1, floor($percent), "Abstracts WordCloud created.");
}

else if ($_SESSION["go"]==1)
{
	$filename_generif = "data/go_name_annotation.tsv";
	$delimiter= "\t";
	$filename_ouput = "data/session_files/text_".$_SESSION['sessionid'].".txt";
	
	update_progress(1,floor($percent),"Extraction go annotations." );
		
	//*
	//Database version
	$query = "SELECT go, goID FROM go WHERE".$query_ending."ORDER BY RAND(), goID LIMIT ".$max_go;

	$result = mysql_query($query);
	
	if (!$result) 
	{
   		echo('Invalid query: ' . mysql_error());
	}
	
	$fr = fopen($filename_ouput, 'w');
	$compt = 0;
	$goID = "";
	while ($row = mysql_fetch_assoc($result)) {
		if ($goID != $row["goID"])
		{
			if ($compt ==0)
			{
				fwrite($fr,substr($row["goID"],3)."\n");
			}
			else
			{
				fwrite($fr,"\n".substr($row["goID"],3)."\n");
			}
			$compt++;
		}
		fwrite($fr, $row["go"]."\n");
	}
	mysql_free_result($result);
	fclose ($fr);
	//*/
	$percent = 40;
	update_progress(1 , floor($percent), "Text-mining.");

	//Get all the words with their occurences from the generated file
	$keywordCounts = textmining("data/session_files/text_".$_SESSION['sessionid'].".txt", "data/stopwords.txt", "data/bio-stopwords.txt", $name_genes, 40, 80);
	
	$percent = 80;
	update_progress(1, floor($percent), "Extracting keywords.");
	
	//Determine the value of cutoff to know how many keywords to remove
	if (sizeof($keywordCounts) > $keywords_max)
	{
		$compt = 0;
	
		foreach($keywordCounts as $key =>$val)
		{
			$compt++;
			if ($compt == $rank_cutoff_value)
			{
				$cutoff = $val-1;
				break;
			}
		}
	}
	else 
	{
		$cutoff = 1;
	}

	//Remove the keywords where the value of occurence is below cutoff
	$keywordCounts_filtered = array_filter($keywordCounts,"cutoff");

	//Generate the applet parameter keywords
	$string_output = "";
	foreach ($keywordCounts_filtered as $word => $count)
	{
		if (!preg_match("#[\s]+#", $word)&& $word !="")
			$string_output .=$word." ".$count." ";
	}
	//remove the last space
	$string_output = substr($string_output, 0,-1);

	$_SESSION["keywords"] = $string_output;
	$percent = 100;
	update_progress(1, floor($percent), "Gene Ontology WordCloud created.");
}

else if ($_SESSION["mp"]==1)
{
	$filename_generif = "data/mp_name_annotation.tsv";
	$delimiter= "\t";
	$filename_ouput = "data/session_files/text_".$_SESSION['sessionid'].".txt";
	
	update_progress(1,floor($percent),"Extraction go annotations." );
		
	//*
	//Database version
	$query = "SELECT mp, mpID FROM mp WHERE".$query_ending."ORDER BY RAND(), mp LIMIT ".$max_mp;

	$result = mysql_query($query);
	
	if (!$result) 
	{
   		echo('Invalid query: ' . mysql_error());
	}
	
	$fr = fopen($filename_ouput, 'w');
	$compt = 0;
	$mpID = "";
	while ($row = mysql_fetch_assoc($result)) {
		if ($mpID != $row["mpID"])
		{
			if ($compt ==0)
			{
				fwrite($fr,substr($row["mpID"],3)."\n");
			}
			else
			{
				fwrite($fr,"\n".substr($row["mpID"],3)."\n");
			}
			$compt++;
		}
		fwrite($fr, $row["mp"]."\n");
	}
	fclose ($fr);
	//*/
	$percent = 40;
	update_progress(1 , floor($percent), "Text-mining.");

	//Get all the words with their occurences from the generated file
	$keywordCounts = textmining("data/session_files/text_".$_SESSION['sessionid'].".txt", "data/stopwords.txt", "data/bio-stopwords.txt", $name_genes, 40, 80);
	
	$percent = 80;
	update_progress(1, floor($percent), "Extracting keywords.");
	
	//Determine the value of cutoff to know how many keywords to remove
	if (sizeof($keywordCounts) > $keywords_max)
	{
		$compt = 0;
	
		foreach($keywordCounts as $key =>$val)
		{
			$compt++;
			if ($compt == $rank_cutoff_value)
			{
				$cutoff = $val-1;
				break;
			}
		}
	}
	else 
	{
		$cutoff = 0;
	}

	//Remove the keywords where the value of occurence is below cutoff
	$keywordCounts_filtered = array_filter($keywordCounts,"cutoff");

	//Generate the applet parameter keywords
	$string_output = "";
	foreach ($keywordCounts_filtered as $word => $count)
	{
		if (!preg_match("#[\s]+#", $word)&& $word !="")
			$string_output .=$word." ".$count." ";
	}
	//remove the last space
	$string_output = substr($string_output, 0,-1);

	$_SESSION["keywords"] = $string_output;
	$percent = 100;
	update_progress(1, floor($percent), "Gene Ontology WordCloud created.");
}

else if ($_SESSION["mesh_terms"]==1)
{

	$percent = 0;
	update_progress(1, floor($percent), "Extraction PubMed ids.");
	//* From the database
	$query = "SELECT pmid FROM pmid_symbol WHERE".$query_ending." ORDER BY RAND() LIMIT ".$max_pmids;
	$result = mysql_query($query);
	
	if (!$result) 
	{
   		echo('Invalid query: ' . mysql_error());
	}
	
	$compt = 0;
	while ($row = mysql_fetch_assoc($result)) {
    	$pmids[$compt] = $row["pmid"];
    	$compt++;
	}

	mysql_free_result($result);
	//*/

	//print_r($pmids);
	$percent = 30;
	update_progress(1, floor($percent), "Extraction Mesh terms.");
	
	//Delete content of the file
	$fr = fopen("data/session_files/text_".$_SESSION['sessionid'].".txt", "w");
	fclose($fr);
	
	$pmid_list_array = array();
	$pmid_list = $pmids[0];
	for ($i = 1; $i<sizeof($pmids); $i++)
	{
		if ($i % $batch == 0)
		{
			array_push($pmid_list_array, $pmid_list);
			$pmid_list = $pmids[$i];
		}
		else 
		{
			$pmid_list .=",".$pmids[$i];
		}
		
	}
	//Don't forget the last batch...
	array_push($pmid_list_array, $pmid_list);
		
		
	//print_r($pmid_list_array);
	for ($i = 0 ; $i<sizeof($pmid_list_array); $i++)
	{
		//URL query
		$query_url = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&rettype=Abstract&retmode=xml&id=".$pmid_list_array[$i];
		//echo $query_url;
		
		//Parse and save the abstracts lists from the answer query of PubMed databases into a text file.
		$doc = new DOMDocument();
		$doc->load($query_url);
		
		$percent += (70-30)/(2*sizeof($pmid_list_array));
		update_progress(1, floor($percent), "Extraction Mesh Terms.");
		
		$fr = fopen("data/session_files/text_".$_SESSION['sessionid'].".txt", "a");
		$compt = 0;
		
		$PubmedArticles = $doc->getElementsByTagName("PubmedArticle");
		foreach($PubmedArticles as $PubmedArticle)
		{
			$MeshHeadingLists = $PubmedArticle->getElementsByTagName("MeshHeadingList");
			foreach ($MeshHeadingLists as $MeshHeadingList)
			{
				fwrite($fr,$pmids[$compt]."\n");
				$MeshHeadings = $MeshHeadingList -> getElementsByTagName("MeshHeading");
				foreach ($MeshHeadings as $MeshHeading)
				{
					$DescriptorNames  = $MeshHeading -> getElementsByTagName("DescriptorName");
					foreach ($DescriptorNames as $DescriptorName )
					{
						fwrite($fr, $DescriptorName->nodeValue."\n");
					}
				}
				$percent += (70-30)/($PubmedArticles->length*2*sizeof($pmid_list_array));
				update_progress(1, floor($percent), "Extraction Mesh Terms.");
				fwrite($fr,"\n");
				$compt++;			
			}
		}
		fclose($fr);
		
	}
	
	$percent = 70;
	update_progress(1, floor($percent), "Text-mining.");
	//*
	$keywordCounts = textmining("data/session_files/text_".$_SESSION['sessionid'].".txt", "data/stopwords.txt", "data/bio-stopwords.txt", $name_genes, 70 ,90);
	//Determine the value of cutoff to know how many keywords to remove
	
	$percent = 90;
	update_progress(1, floor($percent), "Extracting keywords.");
	if (sizeof($keywordCounts) > $keywords_max)
	{
		$compt = 0;
	
		foreach($keywordCounts as $key =>$val)
		{
			$compt++;
			if ($compt == $rank_cutoff_value)
			{
				$cutoff = $val-1;
				break;
			}
		}
	}
	else 
	{
		$cutoff = 1;
	}

	//Remove the keywords where the value of occurence is below cutoff
	$keywordCounts_filtered = array_filter($keywordCounts,"cutoff");

	//Generate the applet parameter keywords
	$string_output = "";
	foreach ($keywordCounts_filtered as $word => $count)
	{
		if (!preg_match("#[\s]+#", $word)&& $word !="")
			$string_output .=$word." ".$count." ";
	}
	//remove the last space
	$string_output = substr($string_output, 0,-1);

	$_SESSION["keywords"] = $string_output;
	$percent = 100;
	update_progress(1, floor($percent), "Abstracts WordCloud created.");
}

//*

$_SESSION["genes"] = $genes;
$_SESSION["name_genes"] = $name_genes; 

/*
echo $_SESSION["keywords"];

//*/

//Close database connection. To comment if database not used.
mysql_close($link);

?>
		<form>
			<p>
			 <input type="text" id="cache" name="cache" size="1" value="" onfocus="window.location='WordCloud.php';">
			</p>
		</form>
		</div>
	</body>
</html>