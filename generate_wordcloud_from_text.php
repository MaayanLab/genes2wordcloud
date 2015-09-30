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
if (isset($_POST["text"]["text"]))
{
	$text = stripslashes($_POST["text"]["text"]);
	$_SESSION["text"]["text"]= htmlspecialchars($text);
	file_put_contents("data/session_files/text_".$_SESSION['sessionid'].".txt", htmlspecialchars($text));
}
else
{
	$text = "The Ma'ayan Laboratory applies computational and mathematical methods to study the complexity of regulatory networks in mammalian cells. We apply graph-theory algorithms, machine-learning techniques and dynamical modeling to study how intracellular regulatory systems function as networks to control cellular processes such as differentiation, de-differentiation, apoptosis and proliferation. We develop software systems to help experimental biologists form novel hypotheses from high-throughput data, and develop theories about the structure and function of regulatory networks in mammalian systems. We apply our tools and other computational methods for the analysis of a variety of projects including: high-dimensional time-series data collected from differentiating mES cells and differentiating neuro2A cells, multi-layered experimental data collected from kidneys of Tg26 mice, a mouse model of HIV associated nephropathy (HIVAN), as well as proteomics and phosphoproteomics experiments applied to profile components downstream of stimulated G-protein coupled receptors. These results from our analyses produce concrete suggestions and predictions for further functional experiments. The predictions are tested by our collaborators and our analyses methods are delivered as software tools and databases for the systems biology research community.";
	$_SESSION["text"]["text"]= "The Ma'ayan Laboratory applies computational and mathematical methods to study the complexity of regulatory networks in mammalian cells. We apply graph-theory algorithms, machine-learning techniques and dynamical modeling to study how intracellular regulatory systems function as networks to control cellular processes such as differentiation, de-differentiation, apoptosis and proliferation. We develop software systems to help experimental biologists form novel hypotheses from high-throughput data, and develop theories about the structure and function of regulatory networks in mammalian systems. We apply our tools and other computational methods for the analysis of a variety of projects including: high-dimensional time-series data collected from differentiating mES cells and differentiating neuro2A cells, multi-layered experimental data collected from kidneys of Tg26 mice, a mouse model of HIV associated nephropathy (HIVAN), as well as proteomics and phosphoproteomics experiments applied to profile components downstream of stimulated G-protein coupled receptors. These results from our analyses produce concrete suggestions and predictions for further functional experiments. The predictions are tested by our collaborators and our analyses methods are delivered as software tools and databases for the systems biology research community.";
}

if (isset($_POST["text"]["stopwords"]))
{
	$_SESSION["text"]["stopwords"]="1";
}
else
{
	$_SESSION["text"]["stopwords"]="0";
}

if (isset($_POST["text"]["biostopwords"]))
{
	$_SESSION["text"]["biostopwords"]="1";
}
else
{
	$_SESSION["text"]["biostopwords"]="0";
}

$_SESSION['from'] = "text";
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

//Create Progress Bar
create_progress_bars(1);
update_progress(1 , 0, "Text-mining.");

//echo $_SESSION["text"]["stopwords"];
//echo $_SESSION["text"]["biostopwords"];
//echo $text;

//Get all the words with their occurences from the generated file
	if ($_SESSION["text"]["stopwords"]==1 && $_SESSION["text"]["biostopwords"]==0)
	{
		$keywordCounts = textmining_text_e($text,"data/stopwords.txt", 0, 80);
	}
	else if ($_SESSION["text"]["stopwords"]==1 && $_SESSION["text"]["biostopwords"]==1)
	{
		$keywordCounts = textmining_text_b_e($text, "data/stopwords.txt", "data/bio-stopwords.txt" , 0, 80);
	}
	else if ($_SESSION["text"]["stopwords"]==0 && $_SESSION["text"]["biostopwords"]==1)
	{
		$keywordCounts = textmining_text_b($text, "data/bio-stopwords.txt", 0, 80);
	}
	else 
	{
		$keywordCounts = textmining_text($text, 0, 80);
	}
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
	update_progress(1, 100, "Text WordCloud created.");


?>
		<form>
			<p>
				<input type="text" id="cache" name="cache" size="1" value="" onfocus="window.location='WordCloud.php';">
			</p>
		</form>
		</div>
	</body>
</html>