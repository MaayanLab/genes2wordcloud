<?php

include_once 'PorterStemmer.php';
include_once 'strip_text.php';

function textmining($input_filename, $stopwords_filename, $bio_stopwords_filename, $other_stopwords_filename, $cutoff)
{

	//Get the text
	$text = file_get_contents($input_filename);
	
	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	//Split the text into words
	$words = explode(" ", $text);
	
	//Stem the words using PorterStemmer algorithm
	$words_non_stemmed= array();
	foreach ( $words as $key => $word )
	{
		$stem = PorterStemmer::Stem( $word, true );
	    $words[$key] = $stem;
	    if (isset ($words_non_stemmed[$stem]))
	    {
	    	if (strlen($words_non_stemmed[$stem])>strlen($word))
	    	{
	    		$words_non_stemmed[$stem]=$word;
	    	}
	    }
	    else 
	    {
	    	$words_non_stemmed[$stem] = $word;
	    }
	}
	
	//Remove stop words
	$stopwords_string = file_get_contents($stopwords_filename);
	$stopwords_string = strtolower($stopwords_string);
	$stopwords = preg_split("#[\s]+#",$stopwords_string);
	foreach ( $stopwords as $key => $word )
	{
	    $stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	
	$words = array_diff( $words, $stopwords );
	
	//Remove other unwanted words
	$bio_stopwords_string = file_get_contents($bio_stopwords_filename);
	$bio_stopwords_string = strtolower($bio_stopwords_string);
	$bio_stopwords = preg_split("#[\s]+#",$bio_stopwords_string);
	
	foreach ( $bio_stopwords as $key => $word )
	{
	    $bio_stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	 
	$words = array_diff( $words, $bio_stopwords );

	//Remove the other unwanted words
	$other_stopwords_string = file_get_contents($other_stopwords_filename);
	$other_stopwords_string = strtolower($other_stopwords_string);
	$other_stopwords = preg_split("#[\s]+#",$other_stopwords_string);
	
	foreach ( $other_stopwords as $key => $word )
	{
	    $other_stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	$words = array_diff( $words, $other_stopwords );
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	//Remove the keywords where the value of occurence is below cutoff
	$keywordCounts_filtered = array();
	$compt = 0;
	foreach($keywordCounts as $key => $val)
	{
		if ($val>$cutoff)
		{
			$keywordCounts_filtered[$key] = $val;
			$compt++;
		}
	}
	
	//Generate the applet parameter keywords
	$string_output = "";
	foreach ($keywordCounts_filtered as $word => $count)
	{
		if (!preg_match("#[\s]+#", $word)&& $word !="")
			$string_output .=$word." ".$count." ";
	}
	
	//remove the last space
	$string_output = substr($string_output, 0,-1);
	
	return $string_output;
}


//echo(textmining("data/text_to_textmine.txt", "data/stopwords.txt", "data/bio-stopwords.txt", "data/other-stopwords.txt"));

?>