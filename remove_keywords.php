<?php 

session_start();

if (isset($_POST["forbidden_keywords"]["action"]))
	$_SESSION["forbidden_keywords"]["action"] = $_POST["forbidden_keywords"]["action"];
if (isset($_POST["forbidden_keywords"]["words"]))
	$_SESSION["forbidden_keywords"]["words"] = $_POST["forbidden_keywords"]["words"];

	
if (!isset($_SESSION["keywords"]))
{
	$_SESSION["keywords"] = "The Ma'ayan Laboratory applies computational and mathematical methods to study the complexity of regulatory networks in mammalian cells. We apply graph-theory algorithms, machine-learning techniques and dynamical modeling to study how intracellular regulatory systems function as networks to control cellular processes such as differentiation, de-differentiation, apoptosis and proliferation. We develop software systems to help experimental biologists form novel hypotheses from high-throughput data, and develop theories about the structure and function of regulatory networks in mammalian systems. We apply our tools and other computational methods for the analysis of a variety of projects including: high-dimensional time-series data collected from differentiating mES cells and differentiating neuro2A cells, multi-layered experimental data collected from kidneys of Tg26 mice, a mouse model of HIV associated nephropathy (HIVAN), as well as proteomics and phosphoproteomics experiments applied to profile components downstream of stimulated G-protein coupled receptors. These results from our analyses produce concrete suggestions and predictions for further functional experiments. The predictions are tested by our collaborators and our analyses methods are delivered as software tools and databases for the systems biology research community.";
}	

$forbidden_words = preg_split("#[-,;:\s]+#",strtolower($_SESSION["forbidden_keywords"]["words"]));
//print_r($forbidden_words);
	
if ($_SESSION["forbidden_keywords"]["action"] == "redraw")
{
	$words_and_occurences = explode(" ", strtolower($_SESSION["keywords"]));
	//print_r($words_and_occurences);
	
	$string_output = "";
	for($i=0;$i<sizeof($words_and_occurences)/2;$i++)
	{
		if (!in_array($words_and_occurences[2*$i], $forbidden_words))
		{
			if (!preg_match("#[\s]+#",$words_and_occurences[2*$i])&& $words_and_occurences[2*$i] !="")
			$string_output .=$words_and_occurences[2*$i]." ".$words_and_occurences[2*$i+1]." ";
		}
	}
	$string_output = substr($string_output, 0,-1);
	$_SESSION["keywords"] = $string_output;
	
}

else if ($_SESSION["forbidden_keywords"]["action"] == "suggest")
{
	include("database_connection.php");
	
	foreach ($forbidden_words as $word)
	{
		if ($_SESSION["from"]=="list of genes")
		{
			if ($_SESSION["generif"]==1)
				$query = "INSERT INTO remove_keywords (word, type_wordcloud, query, ip) VALUES ('".mysql_escape_string($word)."','list of genes','".mysql_escape_string($_SESSION["name_genes"]."\tgenerif")."','".$_SERVER['REMOTE_ADDR']."')";
			else if ($_SESSION["pubmed"]==1)
				$query = "INSERT INTO remove_keywords (word, type_wordcloud, query, ip) VALUES ('".mysql_escape_string($word)."','list of genes','".mysql_escape_string($_SESSION["name_genes"]."\tpubmed")."','".$_SERVER['REMOTE_ADDR']."')";
			else if ($_SESSION["go"]==1)
				$query = "INSERT INTO remove_keywords (word, type_wordcloud, query, ip) VALUES ('".mysql_escape_string($word)."','list of genes','".mysql_escape_string($_SESSION["name_genes"]."\tgo")."','".$_SERVER['REMOTE_ADDR']."')";
			else 
				$query = "INSERT INTO remove_keywords (word, type_wordcloud, query, ip) VALUES ('".mysql_escape_string($word)."','list of genes','".mysql_escape_string($_SESSION["name_genes"])."','".$_SERVER['REMOTE_ADDR']."')";
			
		}
		else if ($_SESSION["from"]=="text")
		{
			$query = "INSERT INTO remove_keywords (word, type_wordcloud, query, ip) VALUES ('".mysql_escape_string($word)."','text','".mysql_escape_string($_SESSION["text"]["text"]."\tenglish ".$_SESSION["text"]["stopwords"]."\tbiology ".$_SESSION["text"]["biostopwords"])."','".$_SERVER['REMOTE_ADDR']."')";
		}
		else if ($_SESSION["from"]=="bmc")
		{
			$query = "INSERT INTO remove_keywords (word, type_wordcloud, query, ip) VALUES ('".mysql_escape_string($word)."','bmc','".mysql_escape_string($_SESSION["date"])."','".$_SERVER['REMOTE_ADDR']."')";
		}
		else if ($_SESSION["from"]=="author")
		{
			$query = "INSERT INTO remove_keywords (word, type_wordcloud, query, ip) VALUES ('".mysql_escape_string($word)."','author','".mysql_escape_string($_SESSION["author"])."','".$_SERVER['REMOTE_ADDR']."')";
		
		}
		else if ($_SESSION["from"]=="Pubmed search")
		{
			$query = "INSERT INTO remove_keywords (word, type_wordcloud, query, ip) VALUES ('".mysql_escape_string($word)."','Pubmed search','".mysql_escape_string($_SESSION["keyword"])."','".$_SERVER['REMOTE_ADDR']."')";
		
		}
		else if ($_SESSION["from"]=="url")
		{
			$query = "INSERT INTO remove_keywords (word, type_wordcloud, query, ip) VALUES ('".mysql_escape_string($word)."','url','".mysql_escape_string($_SESSION["url"]["url"]."\tenglish ".$_SESSION["url"]["stopwords"]."\tbiology ".$_SESSION["url"]["biostopwords"])."','".$_SERVER['REMOTE_ADDR']."')";
		
		}
		else 
		{
			$query = "INSERT INTO remove_keywords (word, type_wordcloud, query, ip) VALUES ('".mysql_escape_string($word)."','','','".$_SERVER['REMOTE_ADDR']."')";
		
		}
		
		//echo $_SESSION["from"];
		//echo $query;
		$result = mysql_query($query);
	
		if (!$result) 
		{
   			echo('Invalid query: ' . mysql_error());
		}
	}
	mysql_close($link);
}

else 
{
	$forbidden_words = preg_split("#[-,;:\s]+#",$_SESSION["forbidden_keywords"]["words"]);
	//print_r($forbidden_words);

	$words_and_occurences = explode(" ", $_SESSION["keywords"]);
	//print_r($words_and_occurences);
	
	$string_output = "";
	for($i=0;$i<sizeof($words_and_occurences)/2;$i++)
	{
		if (!in_array($words_and_occurences[2*$i], $forbidden_words))
		{
			if (!preg_match("#[\s]+#",$words_and_occurences[2*$i])&& $words_and_occurences[2*$i] !="")
			$string_output .=$words_and_occurences[2*$i]." ".$words_and_occurences[2*$i+1]." ";
		}
	}
	$string_output = substr($string_output, 0,-1);
	$_SESSION["keywords"] = $string_output;
}

//echo $_SESSION["keywords"];

header( 'Location: WordCloud.php' ) ;

?>