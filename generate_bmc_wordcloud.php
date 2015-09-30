<?php
session_start();

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
if (isset($_POST["date"]))
{
	$_SESSION["date"] = $_POST["date"];
}
else 
{
	$_SESSION["date"] = "30days";
}

$_SESSION['from'] = "bmc";
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
			<h4>Please be patient, sometimes it takes several minutes to create the WordCloud(s)!</h4>
            <?php

            //Create Progress Bar
            create_progress_bars(1);

            update_progress(1 , 0, "Requesting Pubmed Ids.");

            //Get the most viewed BMC articles webpage.

            if ($_SESSION["date"]=="30days")
            {
                $htmlcontents = file_get_contents("http://www.biomedcentral.com/bmcbioinformatics/mostviewed/");
            }
            else if ($_SESSION["date"]=="pastyear")
            {
                $htmlcontents = file_get_contents("http://www.biomedcentral.com/bmcbioinformatics/mostviewedbyyear/");
            }
            else if ($_SESSION["date"]=="ever")
            {
                $htmlcontents = file_get_contents("http://www.biomedcentral.com/mostviewedalltime/");
            }
            else 
            {
                $htmlcontents = file_get_contents("http://www.biomedcentral.com/bmcbioinformatics/mostviewed/");
            }

            //echo $htmlcontents;

            $pubmed_url = array();
            //Get the pubmed url links
            preg_match_all("#http://www.biomedcentral.com/pubmed/[0-9]+#",$htmlcontents,$pubmed_url);

            //print_r($pubmed_url);

            //get the pubmed ids
            preg_match("#[0-9]+#",$pubmed_url[0][0],$pmid);
			$pmids[0] = $pmid[0];
            $pmid_list = $pmid[0];

            for ($i=1; $i<sizeof($pubmed_url[0]);$i++)
            {
                preg_match("#[0-9]+#",$pubmed_url[0][$i],$pmid);
                $pmid_list .= ",".$pmid[0];
				$pmids[$i] = $pmid[0];
            }

            //echo $pmid_list;
			//print_r($pmids);

            update_progress(1 , 0, "Requesting Abstracts.");

            $query_url = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&rettype=Abstract&retmode=xml&id=".$pmid_list;
                    
            //echo $query_url;
                    
            //Parse and save the abstracts lists from the answer query of PubMed databases into a text file.
            $doc = new DOMDocument();
            $doc->load($query_url);
            
			$fr = fopen("data/session_files/text_".$_SESSION['sessionid'].".txt", "w");
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
					}
				}
				fwrite($fr,"\n");
				$compt++;			
			}
			fclose($fr);

            $percent = 70;
            update_progress(1, floor($percent), "Text-mining.");

            $keywordCounts = textmining("data/session_files/text_".$_SESSION['sessionid'].".txt", "data/stopwords.txt", "data/bio-stopwords.txt", "" , 70, 90);
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
            update_progress(1, floor($percent), "BMC WordCloud created.");

            ?>
            <form>
                <p>
                    <input type="text" id="cache" name="cache" size="1" value="" onfocus="window.location='WordCloud.php';">
                </p>
            </form>
        </div>
	</body>
</html>
