<?php
include_once 'PorterStemmer.php';
include_once 'strip_text.php';

function textmining($input_filename, $stopwords_filename, $bio_stopwords_filename, $name_genes, $percent_min, $percent_max)
{
	
	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/12;
	$percent = $percent_min;

	//Get the text
	$text = file_get_contents($input_filename);
	
	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($words_non_stemmed);
	
	//Remove stop words
	$stopwords = explode("\n" ,file_get_contents($stopwords_filename));
	foreach ( $stopwords as $key => $word )
	{
	    $stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	
	$words = array_diff( $words, $stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Remove other unwanted words
	$bio_stopwords = explode("\n" ,file_get_contents($bio_stopwords_filename));
	
	foreach ( $bio_stopwords as $key => $word )
	{
	    $bio_stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	 
	$words = array_diff( $words, $bio_stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Remove the name of the genes
	$genes_names_list = explode(" ",$name_genes);
	
	foreach ( $genes_names_list as $key => $word )
	{
	    $genes_names_list[$key] = PorterStemmer::Stem( $word, true );
	}
	 
	$words = array_diff( $words, $genes_names_list );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}


function textmining_text_e($text, $stopwords_filename, $percent_min, $percent_max)
{
	
	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/10;
	$percent = $percent_min;

	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	//print_r($words_non_stemmed);
	
	//Remove stop words
	$stopwords = explode("\n" ,file_get_contents($stopwords_filename));
	foreach ( $stopwords as $key => $word )
	{
	    $stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	
	$words = array_diff( $words, $stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}

function textmining_text_b($text, $bio_stopwords_filename , $percent_min, $percent_max)
{	

	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/10;
	$percent = $percent_min;

	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($words_non_stemmed);
	
	//Remove other unwanted words
	$bio_stopwords = explode("\n" ,file_get_contents($bio_stopwords_filename));
	
	foreach ( $bio_stopwords as $key => $word )
	{
	    $bio_stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	 
	$words = array_diff( $words, $bio_stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}

function textmining_text_b_e($text, $stopwords_filename, $bio_stopwords_filename, $percent_min, $percent_max)
{	

	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/11;
	$percent = $percent_min;	

	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($words_non_stemmed);
	
	//Remove stop words
	$stopwords = explode("\n" ,file_get_contents($stopwords_filename));
	foreach ( $stopwords as $key => $word )
	{
	    $stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	
	$words = array_diff( $words, $stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Remove other unwanted words
	$bio_stopwords = explode("\n" ,file_get_contents($bio_stopwords_filename));
	
	foreach ( $bio_stopwords as $key => $word )
	{
	    $bio_stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	 
	$words = array_diff( $words, $bio_stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}

function textmining_text($text, $percent_min, $percent_max)
{	
	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/9;
	$percent = $percent_min;
	
	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($words_non_stemmed);
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );

	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}


function textmining_url($url, $percent_min, $percent_max)
{	
	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/13;
	$percent = $percent_min;
	
	//Get the html webpage
	
	$encodedText = file_get_contents($url);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Get the encoding and convert it to utf-8
	
	$headers = get_headers($url,1);
	$contentType = $headers["Content-Type"];
	$charset = explode("charset=", $contentType);
	if (isset($charset[1]))
	{
		$encoding = trim($charset[1]);
		$text = iconv($encoding, "UTF-8", $encodedText);
	}
	else
	{
		$text = $encodedText;
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Strip the html tags
	$text = strip_html_tags( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Remove the special html characters (e.g &nsbp)
	$text = html_entity_decode( $text, ENT_QUOTES, "utf-8" );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($words_non_stemmed);
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );

	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}

function textmining_url_e($url, $stopwords_filename, $percent_min, $percent_max)
{
	
	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/10;
	$percent = $percent_min;

	//Get the html webpage
	
	$encodedText = file_get_contents($url);
		
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Get the encoding and convert it to utf-8
	
	$headers = get_headers($url,1);
	$contentType = $headers["Content-Type"];
	$charset = explode("charset=", $contentType);
	if (isset($charset[1]))
	{
		$encoding = trim($charset[1]);
		$text = iconv($encoding, "UTF-8", $encodedText);
	}
	else
	{
		$text = $encodedText;
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Strip the html tags
	$text = strip_html_tags( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Remove the special html characters (e.g &nsbp)
	$text = html_entity_decode( $text, ENT_QUOTES, "utf-8" );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	//print_r($words_non_stemmed);
	
	//Remove stop words
	$stopwords = explode("\n" ,file_get_contents($stopwords_filename));
	foreach ( $stopwords as $key => $word )
	{
	    $stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	
	$words = array_diff( $words, $stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}

function textmining_url_b($url, $bio_stopwords_filename , $percent_min, $percent_max)
{	

	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/10;
	$percent = $percent_min;
	
	//Get the html webpage
	
	$encodedText = file_get_contents($url);
		
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Get the encoding and convert it to utf-8
	
	$headers = get_headers($url,1);
	$contentType = $headers["Content-Type"];
	$charset = explode("charset=", $contentType);
	if (isset($charset[1]))
	{
		$encoding = trim($charset[1]);
		$text = iconv($encoding, "UTF-8", $encodedText);
	}
	else
	{
		$text = $encodedText;
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Strip the html tags
	$text = strip_html_tags( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Remove the special html characters (e.g &nsbp)
	$text = html_entity_decode( $text, ENT_QUOTES, "utf-8" );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		

	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($words_non_stemmed);
	
	//Remove other unwanted words
	$bio_stopwords = explode("\n" ,file_get_contents($bio_stopwords_filename));
	
	foreach ( $bio_stopwords as $key => $word )
	{
	    $bio_stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	 
	$words = array_diff( $words, $bio_stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}

function textmining_url_b_e($url, $stopwords_filename, $bio_stopwords_filename, $percent_min, $percent_max)
{	

	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/11;
	$percent = $percent_min;	
	
	//Get the html webpage
	
	$encodedText = file_get_contents($url);
		
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Get the encoding and convert it to utf-8
	
	$headers = get_headers($url,1);
	$contentType = $headers["Content-Type"];
	$charset = explode("charset=", $contentType);
	if (isset($charset[1]))
	{
		$encoding = trim($charset[1]);
		$text = iconv($encoding, "UTF-8", $encodedText);
	}
	else
	{
		$text = $encodedText;
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Strip the html tags
	$text = strip_html_tags( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
		
	//Remove the special html characters (e.g &nsbp)
	$text = html_entity_decode( $text, ENT_QUOTES, "utf-8" );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );

	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($words_non_stemmed);
	
	//Remove stop words
	$stopwords = explode("\n" ,file_get_contents($stopwords_filename));
	foreach ( $stopwords as $key => $word )
	{
	    $stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	
	$words = array_diff( $words, $stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Remove other unwanted words
	$bio_stopwords = explode("\n" ,file_get_contents($bio_stopwords_filename));
	
	foreach ( $bio_stopwords as $key => $word )
	{
	    $bio_stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	 
	$words = array_diff( $words, $bio_stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}

function textmining_author($input_filename, $stopwords_filename, $bio_stopwords_filename, $author, $percent_min, $percent_max)
{
	
	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/12;
	$percent = $percent_min;

	//Get the text
	$text = file_get_contents($input_filename);
	
	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($words_non_stemmed);
	
	//Remove stop words
	$stopwords = explode("\n" ,file_get_contents($stopwords_filename));
	foreach ( $stopwords as $key => $word )
	{
	    $stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	
	$words = array_diff( $words, $stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Remove other unwanted words
	$bio_stopwords = explode("\n" ,file_get_contents($bio_stopwords_filename));
	
	foreach ( $bio_stopwords as $key => $word )
	{
	    $bio_stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	 
	$words = array_diff( $words, $bio_stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Remove the name of the author
	$authors = array(PorterStemmer::Stem( $author, true ));
	 
	$words = array_diff( $words, $authors);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}

function textmining_keyword($input_filename, $stopwords_filename, $bio_stopwords_filename, $keywords, $percent_min, $percent_max)
{
	
	//Define the percent step
	$percent_step = ($percent_max-$percent_min)/12;
	$percent = $percent_min;

	//Get the text
	$text = file_get_contents($input_filename);
	
	//Strip ponctuation
	$text = strip_punctuation( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip symbol characters
	$text = strip_symbols( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Strip numbers characters
	$text = strip_numbers( $text );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Convert to lower case
	$text = strtolower( $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Split the text into words
	$words = explode(" ", $text);
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
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
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($words_non_stemmed);
	
	//Remove stop words
	$stopwords = explode("\n" ,file_get_contents($stopwords_filename));
	foreach ( $stopwords as $key => $word )
	{
	    $stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	
	$words = array_diff( $words, $stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Remove other unwanted words
	$bio_stopwords = explode("\n" ,file_get_contents($bio_stopwords_filename));
	
	foreach ( $bio_stopwords as $key => $word )
	{
	    $bio_stopwords[$key] = PorterStemmer::Stem( $word, true );
	}
	 
	$words = array_diff( $words, $bio_stopwords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Remove the keywords
	foreach ( $keywords as $key => $word )
	{
	    $keywords[$key] = PorterStemmer::Stem( $word, true );
	}
	 
	$words = array_diff( $words, $keywords );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Replace the stemwords by the word of the shortest length for better readibility
	foreach ($words as $key => $word)
	{
		$words[$key] = $words_non_stemmed[$word];
	}
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//Count Keyword usage
	$keywordCounts = array_count_values( $words );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	arsort( $keywordCounts, SORT_NUMERIC );
	
	$percent += $percent_step;
	update_progress(1,floor($percent),"Text-mining." );
	
	//print_r($keywordCounts);
	
	return $keywordCounts;
}

?>