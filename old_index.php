<?php 
	session_start();
    
	if (!isset($_SESSION['sessionid']))
	{
		$_SESSION['sessionid'] = session_id();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" > 
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="description" content="Genes2WordCloud"/>
        <meta name="keywords" content="gene, wordcloud, visualization tool, mount sinai, systems biology, genetics, genes, protein, web application."/>
		<meta name="author" content="Caroline Baroukh"/>
		<meta name="location" content="Mount Sinai Medical School,New York"/>
		<title>Genes2WordCloud</title>
        <link rel="icon" type="image/png" href="images/favicon.png"/>
	 	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/index.css"/>
	 	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/index2.css"/>
        <link rel="stylesheet" type="text/css" href="Style/jquery.autocomplete.css" />
        <link rel="stylesheet" type="text/css" href="Style/search_bar.css" />
	</head>
    
	<body> 
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
                    <li class="select">HOME</li>
                    <li> <a href="create_wordcloud.php">CREATE</a> </li>
                    <li <?php if (!isset($_SESSION['from'])||$_SESSION['from']==1) echo "class=\"select\">VIEW"; 
                    else echo "> <a href=\"WordCloud.php\">VIEW</a>";?></li>
                    <li> <a href="help.php">HELP</a> </li>
                </ul>
            </div>
        </div>
         
        <div id="head-img">
        </div>
            
        <div id="corpus">
            <div id="description">
                <h3>Genes2WordCloud: a quick way to identify biological themes from gene lists and free text</h3>
                <div id="description_text">
                <p> Genes2WordCloud is a web-based server application and Java Applet that enables users to create <strong>biologically-relevant-content WordClouds.</strong>
                    A WordCloud is a visual display of a set of words where the font, size, color or angle can represent some underlying information. 
                    A WordCloud is an effective way to visually summarize information about a specific topic of interest. 
                    The WordCloud is optimized to maximize the display of the most important terms about a topic in the minimum amount of space. <br/>
                    As researchers are faced with the daunting amount of new and growing data and text, methods to quickly summarize knowledge about a specific topic from
                    large bodies of text or data are critical. WordClouds  are rising as a method of choice on the web to accomplish this task.
                </p>
                <p> Genes2WordCloud generates WordClouds from the following sources:</p>
                    <ul>
                        <li><em>a single gene, or a list of genes.</em> For that, three different resources are used. Either the gene(s) are matched to:
                            <ul>
                                <li> their generifs annotations;</li>
                                <li> their gene onthology annotations;</li>
                                <li> abstracts on Pubmed articles linked to the gene(s) through generifs;</li>
                                <li> their mammalian phenotype annotations from MGI;</li>
                            </ul>
                        </li>
                        <li><em>free text or URL of a website</em>. Allows for pasting a bunch of text or providing a url to generate a WordCloud.</li>
                        <li><em>an author's name.</em> Generates the WordCloud from Pubmed articles of the author.</li>
                        <li><em>a general Pubmed search.</em> Generates a WordCloud from any Pubmed search based on returned abstracts.</li>
                        <li><em>BMC most viewed articles.</em> Displays a WordCloud created from the most viewed BMC Bioinformatics articles for different time periods. </li>
                    </ul>
                
                </div>
            </div>
            
            <div id="separation_lines">
            </div>
            
            <div id="news">
				<h3> NEWS </h3>
				<div class="news">
                    <h4> The origin of the words is now displayed! </h4>
                     <p> Now you can know how the words were obtained by clicking on them. It will show the PubMed Id or the GO Id or MP Id linked to the word clicked. </p>
                 </div>
                <div class="news">
                    <h4> Embed Genes2WordCloud in your own website! </h4>
                     <p> You can embed Genes2WordCloud in your own website! All you need is a php server... <br/>
                     More details on how to do it in the <a href="help.php">help section</a>. </p>
                 </div>
                  <div class="news">
                    <h4> New design for the website! </h4>
                     <p> A new design for the website is now available! Hope you enjoy it! </p>
                 </div>
            </div>
        </div>
		
        <div id="sidebar">
            <div id="papers">
                <h3> Related Publications </h3>
                <ul>
                    <li> <a href="http://www.springerlink.com/content/v855r11764133052/">Visual Presentation as a Welcome Alternative to Textual Presentation of Gene Annotation Information.</a><br/>
                        <span class="authors">Jairav Desai, Jared M. flatow, Jie Song, Lihua J. Zhu, Pan Du, Chiang-Ching Huang, Hui Lu, Simon M. Lin, and Warren A. Kibbe, Advances in computational biology, 2010, pages 709-715, Springer.</span><br/>
                        <span class="abstract">The functions of a gene are traditionally annotated textually using either free text (Gene Reference Into Function or GeneRIF) or controlled vocabularies (e.g., Gene Ontology or Disease Ontology). Inspired by the latest word cloud tools developed by... </span> 
                    </li>
                    <li> <a href="http://www.uni-due.de/~s400268/Lohmann09-interact.pdf">Comparison of Tag Cloud Layouts: Task-Related Performance and Visual Exploration.</a><br/>
                        <span class="authors">Lohmann, S., Ziegler, J., Tetzlaff, L., T. Gross et al. (Eds.): INTERACT 2009, Part I, LNCS 5726, 2009, pages 392-404.</span><br/>
                        <span class="abstract">Tag clouds have become a popular visualization and navigation
                                                interface on the Web. Despite their popularity, little is known about tag cloud
                                                perception and performance with respect to different user goals. This paper
                                                presents results from a comparative study of several tag cloud layouts.</span>
                   </li>
                </ul>
            </div>
            <div id="stats">
                <h3> Links </h3>
                <ul>
                    <li><a href="http://wordcram.wordpress.com/">WordCram</a>, Dan Bernier</a></li>
                    <li> <a href="http://www.wordle.net/">Wordle</a>, Jonathan Feinberg, 2009 </li>
                    <li><a href="http://en.wikipedia.org/wiki/Tag_cloud">Wikipedia</a>, article on Tag Clouds</li>
                </ul>
            </div>
        </div>
        <div class="clear">
        </div>
        
		<div id="footer">
            <div id="contact">
                <a href="mailto:avi.maayan@mssm.edu"> <img src="images/enveloppe.jpg" height="12px;" border="0" /> Contact us</a>
            </div>
            <div id="links">
                <ul> 
                    <li> <a href="http://www.mountsinai.org/Research/Centers%20Laboratories%20and%20Programs/Maayan%20Laboratory">Ma'ayan Laboratory</a> </li>
                    <li> <a href="http://www.sbcny.org">Systems Biology Center New York</a> </li>
                    <li> <a href="http://www.mountsinai.org/Education/School%20of%20Medicine">Mount Sinai School of Medicine</a> </li>
                </ul>
            </div>
		</div>
        
	</body>
</html>