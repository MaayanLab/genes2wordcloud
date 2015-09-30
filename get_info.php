<?php

include_once 'strip_text.php';
include_once 'PorterStemmer.php';		

function get_pmids($word, $input_text_file)
{
	$pmids_match = array();
	$text = file_get_contents($input_text_file);
	$articles = explode("\n\n",$text);
	for($i=0;$i<sizeof($articles)-1;$i++)
	{
		$pmid = explode("\n",$articles[$i]);
		$pmid = $pmid[0];
		$text = strip_punctuation($articles[$i]);
		$text = strip_symbols( $text );
		$text = strip_numbers( $text );
		$text = strtolower( $text);
		$words = explode(" ",$text);
		$compt = 0;
		$stem_word = PorterStemmer::Stem( $word, true );
		foreach ($words as $val)
		{
			$stem = PorterStemmer::Stem( $val, true );
			if ($stem_word == $stem)
			{
				$compt++;
			}
		}
		$pmids_match[$pmid] = $compt;
	}
	arsort( $pmids_match, SORT_NUMERIC );
	
	$compt = 0;

	$pmids_match_filtered = array();
	foreach ($pmids_match as $key => $val)
	{
		if ($compt>4)
		{
			break;
		}
		if ($val!=0)
		{
			$pmids_match_filtered[$key] = $val;
		}
		$compt++;
	}

	//print_r($pmids_match_filtered);
	return $pmids_match_filtered;
}

function get_pmids_quick($word, $input_text_file)
{
	$pmids_match = array();
	$text = file_get_contents($input_text_file);
	$articles = explode("\n\n",$text);
	for($i=0;$i<sizeof($articles)-1;$i++)
	{
		$pmid = explode("\n",$articles[$i]);
		$pmid = $pmid[0];
		$stem_word = PorterStemmer::Stem( $word, true );
		$pmids_match[$pmid] = preg_match_all("#".$stem_word."#i",$articles[$i],$arr);
	}
	arsort( $pmids_match, SORT_NUMERIC );
	
	
	$compt = 0;
	$cutoff = 0;
	$pmids_match_filtered = array();
	foreach ($pmids_match as $key => $val)
	{
		if ($compt==3)
		{
			$cutoff= $val;
		}
		if ($val<$cutoff)
		{
			break;
		}
		if ($val!=0)
		{
			$pmids_match_filtered[$key] = $val;
		}
		$compt++;
	}

	//print_r($pmids_match_filtered);
	return $pmids_match_filtered;
}

function get_pmids_quick2($word, $input_text_file)
{
	$pmids_match = array();
	$text = file_get_contents($input_text_file);
	$articles = explode("\n\n",$text);
	for($i=0;$i<sizeof($articles)-1;$i++)
	{
		$pmid = explode("\n",$articles[$i]);
		$pmid = $pmid[0];
		$stem_word = $word;
		$pmids_match[$pmid] = preg_match_all("#".$stem_word."#i",$articles[$i],$arr);
	}
	arsort( $pmids_match, SORT_NUMERIC );
	
	
	$compt = 0;
	$cutoff = 0;
	$pmids_match_filtered = array();
	foreach ($pmids_match as $key => $val)
	{
		if ($compt==3)
		{
			$cutoff= $val;
		}
		if ($val<$cutoff)
		{
			break;
		}
		if ($val!=0)
		{
			$pmids_match_filtered[$key] = $val;
		}
		$compt++;
	}

	//print_r($pmids_match_filtered);
	return $pmids_match_filtered;
}

session_start();
	
if (!isset($_SESSION['sessionid']))
{
	$_SESSION['sessionid'] = session_id();
}

if (isset($_POST["str"]))
{
	$infos = explode(": ",$_POST["str"]);
	$word = $infos[0];
	$weight = $infos[1];
	
	if (file_exists("data/session_files/text_".$_SESSION['sessionid'].".txt"))
	{
		if (($_SESSION['from'] == "url")||($_SESSION['from'] == "text"))
		{
			echo "<p> Word: ".$word."<br/>Weight: ".$weight."</p>";
		}
		else if (($_SESSION['from'] == "bmc")||($_SESSION['from'] == "Pubmed search")||($_SESSION['from']=="author"))
		{
			$pmids = get_pmids_quick($word,"data/session_files/text_".$_SESSION['sessionid'].".txt");
			//print_r($pmids);
			//echo is_null($pmids);
			if (empty($pmids))
			{
				echo Hello;
				$pmids = get_pmids_quick2($word,"data/session_files/text_".$_SESSION['sessionid'].".txt");
			}
			$content = "<p> Word: ".$word."<br/>Weight: ".$weight."<br/><table><tr><th>PubMed Id</th><th>Occurence of the word</th></tr>";
			foreach ($pmids as $pmid => $occ)
			{
				$content .= "<tr><td><a href=\"http://www.ncbi.nlm.nih.gov/pubmed?term=".$pmid."\" target=\"_blank\">".$pmid ."</a></td><td>".$occ."</td></tr>";
			}
			$content .= "</table></p>";
			echo $content;
		}
		else if ($_SESSION['from'] == "list of genes")
		{
			if ($_SESSION["go"]==1)
			{
				$pmids = get_pmids_quick($word,"data/session_files/text_".$_SESSION['sessionid'].".txt");
				//print_r($pmids);
				$content = "<p> Word: ".$word."<br/>Weight: ".$weight."<br/><table><tr><th>GO Id</th><th>Occurence of the word</th></tr>";
				foreach ($pmids as $pmid => $occ)
				{
					$content .= "<tr><td><a href=\"http://amigo.geneontology.org/cgi-bin/amigo/term_details?term=GO:".$pmid."\" target=\"_blank\">GO:".$pmid ."</a></td><td>".$occ."</td></tr>";
				}
				$content .= "</table></p>";
				echo $content;
			}
			else if ($_SESSION["mp"]==1)
			{
				$pmids = get_pmids_quick($word,"data/session_files/text_".$_SESSION['sessionid'].".txt");
				//print_r($pmids);
				$content = "<p> Word: ".$word."<br/>Weight: ".$weight."<br/><table><tr><th>MP Id</th><th>Occurence of the word</th></tr>";
				foreach ($pmids as $pmid => $occ)
				{
					$content .= "<tr><td><a href=\"http://www.informatics.jax.org/searches/Phat.cgi?id=MP:".$pmid."\" target=\"_blank\">MP:".$pmid ."</a></td><td>".$occ."</td></tr>";
				}
				$content .= "</table></p>";
				echo $content;
			}
			else if ($_SESSION["generif"]==1)
			{
				$pmids = get_pmids_quick($word,"data/session_files/text_".$_SESSION['sessionid'].".txt");
				//print_r($pmids);
				$content = "<p> Word: ".$word."<br/>Weight: ".$weight."<br/><table><tr><th>PubMed Id</th><th>Occurence of the word</th></tr>";
				foreach ($pmids as $pmid => $occ)
				{
					$content .= "<tr><td><a href=\"http://www.ncbi.nlm.nih.gov/pubmed?term=".$pmid."\" target=\"_blank\">".$pmid ."</a></td><td>".$occ."</td></tr>";
				}
				$content .= "</table></p>";
				echo $content;
			}
			else if ($_SESSION["pubmed"]==1)
			{
				$pmids = get_pmids_quick($word,"data/session_files/text_".$_SESSION['sessionid'].".txt");
				//print_r($pmids);
				$content = "<p> Word: ".$word."<br/>Weight: ".$weight."<br/><table><tr><th>PubMed Id</th><th>Occurence of the word</th></tr>";
				foreach ($pmids as $pmid => $occ)
				{
					$content .= "<tr><td><a href=\"http://www.ncbi.nlm.nih.gov/pubmed?term=".$pmid."\" target=\"_blank\">".$pmid ."</a></td><td>".$occ."</td></tr>";
				}
				$content .= "</table></p>";
				echo $content;
			}
			else if ($_SESSION["mesh_terms"]==1)
			{
				$pmids = get_pmids_quick($word,"data/session_files/text_".$_SESSION['sessionid'].".txt");
				//print_r($pmids);
				$content = "<p> Word: ".$word."<br/>Weight: ".$weight."<br/><table><tr><th>PubMed Id</th><th>Occurence of the word</th></tr>";
				foreach ($pmids as $pmid => $occ)
				{
					$content .= "<tr><td><a href=\"http://www.ncbi.nlm.nih.gov/pubmed?term=".$pmid."\" target=\"_blank\">".$pmid ."</a></td><td>".$occ."</td></tr>";
				}
				$content .= "</table></p>";
				echo $content;
			}
		}
	}		
}

?>