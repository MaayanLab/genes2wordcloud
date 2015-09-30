<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
   <head>
       <title>Bienvenue sur mon site !</title>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   </head>
   <body>
	<h1> Example of a WordCloud!</h1>
	<p>The text is taken from the file text_to_textmine.php.</p>
	<?php
		include ('embed_applet/WordCloud.php');
		//create_wordcloud("aa", "", "data/text_to_textmine.txt", "data/stopwords.txt", "data/bio-stopwords.txt", "data/other-stopwords.txt", 3);
		//create_wordcloud("bb", "", "data/text_to_textmine2.txt", "data/stopwords.txt", "data/bio-stopwords.txt", "data/other-stopwords2.txt", 2);
		create_wordcloud("cc");
		?>
   </body>
</html>

