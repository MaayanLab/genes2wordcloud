<?php
session_start();

//Max script execution time is 600 seconds.
set_time_limit(600);

include_once 'textmining.php';
include_once 'progress_bar.php';

//Parameters
$cutoff = 0;
$rank_cutoff_value = 70;
$keywords_max = 100;

// Function which permits to cut-off the number of genes
function cutoff($var) 
{
	global $cutoff;
	return ($var > $cutoff);
}

//Get the text list pasted by the user.
if (isset($_POST["keyword"]))
{
	$_SESSION["keyword"] = $_POST["keyword"];
}
else
{
	$_SESSION["keyword"] = "p38 pathway";
}
$_SESSION['from'] = "Pubmed search";
$_SESSION["forbidden_keywords"]["words"] = "";

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

//Settings E-Utilities removal
$batch = 150;
$max_pmids = 150;

//Create Progress Bar
create_progress_bars(1);

//Get the Pubmed Ids
update_progress(1 , 0, "Extraction Pubmed Ids.");

$query_url = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=".$_SESSION["keyword"]."&retmax=10000";
//echo $query_url;

$doc = new DOMDocument();
$doc->load($query_url);

$pubmed_ids = $doc->getElementsByTagName("Id");

$compt = 0;

foreach ($pubmed_ids as $pubmed_id)
{
	$pmids[$compt] = $pubmed_id->nodeValue;
    $compt++;
}


if (sizeof($pmids)>$max_pmids)
{
	$pmids_rand_ind = array_rand($pmids,$max_pmids);
	for ($i=0;$i<$max_pmids;$i++)
	{
		$pmids_rand[$i] = $pmids[$pmids_rand_ind[$i]];
	}
	unset($pmids);
	$pmids = $pmids_rand;
}

//print_r($pmids);

//Get the Abstracts
update_progress(1 , 30, "Extraction abstracts");

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
		
	$percent = 30;
	//print_r($pmid_list_array);
	for ($i = 0 ; $i<sizeof($pmid_list_array); $i++)
	{
		//URL query
		$query_url = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&rettype=Abstract&retmode=xml&id=".$pmid_list_array[$i];
		
		//echo $query_url;
		
		//Parse and save the abstracts lists from the answer query of PubMed databases into a text file.
		$doc = new DOMDocument();
		$doc->load($query_url);
		
		$percent += (60-30)/(2*sizeof($pmid_list_array));
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
					$percent += (60-30)/($PubmedArticles->length*2*sizeof($pmid_list_array));
					update_progress(1, floor($percent), "Extraction abstracts.");
					
				}
			}
			fwrite($fr,"\n");
			$compt++;			
		}
		fclose($fr);
	}

update_progress(1 , 60, "Text-mining.");

//Get all the words with their occurences from the generated file
$keywords = preg_split("#[-,;:\s]+#",$_SESSION["keyword"]);
//print_r($keywords);

$keywordCounts = textmining_keyword("data/session_files/text_".$_SESSION['sessionid'].".txt", "data/stopwords.txt", "data/bio-stopwords.txt", $keywords, 60, 80);
update_progress(1, 80, "Extracting keywords.");
	
	//print_r($keywordCounts);
	
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

	//print_r ($keywordCounts_filtered);
	
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
	update_progress(1, 100, "Pubmed Search WordCloud created.");


?>
		<form>
			<p>
			<input type="text" id="cache" name="cache" size="1" value="" onfocus="window.location='WordCloud.php';">
			</p>
		</form>
		</div>
	</body>
</html>