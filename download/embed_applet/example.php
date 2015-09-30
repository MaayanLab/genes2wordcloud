<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
   <head>
       <title>Welcome!</title>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   </head>
   <body>
	<h1> Example of a WordCloud!</h1>
	<p>The text is taken from the file text_to_textmine.txt.</p>
    
	<?php
		include ('WordCloud.php');
		create_wordcloud("1", "", "data/text_to_textmine.txt", "data/stopwords.txt", "data/bio-stopwords.txt", "data/other-stopwords.txt", 3);
    ?>
    <?php
        //create_wordcloud_weights('2', '', 'Genes2WordCloud 80 Genes 30 Word 30 WordCloud 30 ');
    ?> 
   </body>
</html>

