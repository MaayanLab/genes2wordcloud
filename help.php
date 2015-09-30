<?php 

	session_start();
	
	if (!isset($_SESSION['sessionid']))
	{
		$_SESSION['sessionid'] = session_id();
	}
	
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
        <link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/help.css" />
        <link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/index.css" />
	</head>
 	
   <body style="margin:auto;">
   		
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
                    <li <?php if (!isset($_SESSION['from'])||$_SESSION['from']==1) echo "class=\"select\">VIEW"; 
                    else echo "> <a href=\"WordCloud.php\">VIEW</a>";?>  </li>
                    <li class="select">HELP</li>
                </ul>
            </div>
        </div>
        
        <div id="corpus">
        
            <div id="menu2">
                <p>
                    <span class="submenu"><a href="#presentation">Introduction</a></span>
                     <span class="submenu"><a href="#structure">Structure</a></span>
                     <span class="submenu"><a href="#examples">Examples</a></span>
                     <span class="submenu"><a href="#troubleshooting">Troubleshooting</a></span>
                     <span class="submenu"><a href="#embed_applet">Use the applet on your website</a></span>
                     <span class="submenu"><a href="#FAQ">FAQ</a></span>
                     <span class="submenu"><a href="#contacts">Contact</a></span>
                </p>
            </div>
            
            <div id="presentation">
This page is translated to <a href="http://science.webhostinggeeks.com/Genes2WordCloud">Serbo-Croatian</a> language by Anja Skrba from <a href="http://webhostinggeeks.com/"> Webhostinggeeks.com</a>.

                <h3>What is Genes2WordCloud?</h3>
                <p>
                Genes2WordCloud is a web-based server application and Java Applet that enables users to create <strong>biologically-relevant-content WordClouds</strong>.
                </p>
                <p>
                A WordCloud is a visual display of a set of words where the font, size, color or angle can represent some underlying information. A WordCloud is an effective way to visually summarize information about a specific topic of interest. The WordCloud is optimized to maximize the display of the most important terms about a specific topic in the minimum amount of space.
                </p>
                <p class="center">
                <img alt="Wordcloud" src="images/wordcloud1.png">
                </p>
                <p>As researchers are faced with the daunting amount of new and growing data and text, methods to quickly summarize knowledge about a specific topic from large bodies of text or data are critical. WordClouds  are emerging as a method of choice on the web to accomplish this task.
                </p>
                <p>
                    Genes2WordCloud generates WordClouds from the following sources:
                    <ul>
                        <li><em>A single gene, or a list of genes.</em> For that, three different resources are used. Either the gene(s) are matched to:
                        <ul>
                            <li> their generifs annotations;</li>
                            <li> their gene onthology annotations;</li>
                            <li> abstracts on Pubmed articles linked to the gene(s) through generifs;</li>
                            <li> their mammalian phenotype annotations from MGI;</li>
                        </ul></li>
                        <li><em>Free text or text extracted from a URL of a website</em>. Free text or text extracted from a URL is used to generate a WordCloud.</li>
                        <li><em>An author's name.</em>WordClouds can be created from Pubmed articles returned for a specific author.</li>
                        <li><em>General Pubmed search.</em>A WordCloud can be generated from any Pubmed search based on returned abstracts.</li>
                        <li><em>BMC Bioinformatics most viewed articles.</em>Displays a WordCloud created from the most viewed BMC Bioinformatics articles for different time periods.</li>
                    </ul>
                </p>
            </div>
            <div id = "structure">
            <h3>How does it work?</h3>
                <p>
                There are two tasks for creating WordClouds: first,  <strong>generating the keywords</strong> to display; and secondly,  <strong>displaying the keywords</strong>.
                </p>
                <h4>Generating the keywords</h4>
                <p>
                The keywords are generated in several ways depending on the source chosen. In each case the process can be divided into two main tasks: obtain the text related to the user input, and text-mine the text. 
                </p>
                <p class="center">
                    <br/>
                    <img alt="Diagram 1" width="800px" src="images/Diagram1algo.png">
                    <br/>
                    <span class="caption">Diagram 1 - Main task 1: obtain text from the user input</span>
                    <br/>
                    <br/>
                    <img alt="Diagram 2" width="600px" src="images/Diagram2algo.png">
                    <br/>
                    <span class="caption">Diagram 2 - Main task 2: text-mining</span>
                    <br/>
                    <br/>
                    <img alt="Diagram 3"  width="300px" src="images/Diagram3algo.png">
                    <br/>
                    <span class="caption">Diagram 3 -Text-mining task details</span>
                    <br/>
                    <br/>
                </p>
                <p>
                The Porter stemming algorithm is a common stemming algorithm which works well for English.
                It reduces words such as "stem", "stems", "stemming" to a single root, e.g., "stem". 
                The root is not always a real English word. Therefore, to obtain a more readable WordCloud, after the stemming of all the words, each stemmed-word is replaced by the shortest word of its family. 
                </p>
                <p>It should be noted that some words are removed from the text before finding the keywords. 
First, all common English words such as: <i>the, is, or are</i> are removed. The list of these words can be found in the <a href="download/stopwords.txt">following file</a>. 
Then common biological terms such as: <i>experiments, abstracts, contributes </i> are removed. These terms are available <a href="download/bio-stopwords.txt">here</a>. These terms were chosen by hand curation after experimenting with many WordClouds. Text-mining of generifs and gene ontology annotations also contains removed common terms.
Finally, other terms such as the input gene names, the name of the author, or the keywords of the Pubmed search are also removed to avoid self-referencing. 
                    </p>
                    <p>
                    The source files used to create the database for processing lists of genes to create WordClouds were taken from:
                        <ul>
                            <li> NCBI for generating a reference of Entrez gene names. Only mouse, rat and human genes were used (<a href="ftp://ftp.ncbi.nih.gov/gene/DATA/GENE_INFO/Mammalia/Homo_sapiens.gene_info.gz">file1</a>, <a href="ftp://ftp.ncbi.nih.gov/gene/DATA/GENE_INFO/Mammalia/Rattus_norvegicus.gene_info.gz">file2</a>, <a href="ftp://ftp.ncbi.nih.gov/gene/DATA/GENE_INFO/Mammalia/Mus_musculus.gene_info.gz">file3</a>)</li>
                            <li> NCBI file for linking PMIDs to genes. (<a href="ftp://ftp.ncbi.nih.gov/gene/DATA/gene2pubmed.gz">file4</a>) </li>
                            <li> NCBI's GeneRifs annotations. (<a href="ftp://ftp.ncbi.nih.gov/gene/GeneRIF/generifs_basic.gz">file5)</a></li> 
                            <li> Gene Ontology annotations. Only mouse, rat and human genes were used. (<a href="http://cvsweb.geneontology.org/cgi-bin/cvsweb.cgi/go/gene-associations/gene_association.goa_human.gz?rev=HEAD">file6</a>, <a href="http://cvsweb.geneontology.org/cgi-bin/cvsweb.cgi/go/gene-associations/gene_association.mgi.gz?rev=HEAD">file7</a>, <a href ="http://cvsweb.geneontology.org/cgi-bin/cvsweb.cgi/go/gene-associations/gene_association.rgd.gz?rev=HEAD">file8</a>, <a href="http://www.geneontology.org/ontology/obo_format_1_2/gene_ontology_ext.obo">file9</a>)</li>
                        </ul>
                    </p>
                    <p>
                        The different methods to obtain text from the user input and the text-mining algorithms consume a lot of CPU time and memory. For each query we only use a maximum of 150 abstracts or 500 annotations picked randomly when the queries return more than these limits.
                    </p>
                <h4>Displaying the WordCloud</h4>
                <p>
                There are currently two main web-based applications to create WordClouds from weighted lists of keywords: <a href="http://www.wordle.net/">Wordle</a>, developed by Jonathan Feinberg and indirectly IBM, and <a href="http://wordcram.wordpress.com/">WordCram</a> developed by Dan Bernier. Wordle cannot be used outside of the web application since its source code is protected, whereas WordCram is an open-source Java library using the Java libraries of <a href="http://processing.org/">Processing</a>. Processing is a scripting language that uses Java Applets for creating web-based applications enriched with graphics. Genes2WordCloud is a WordCould viewer that is based on <a href="http://wordcram.wordpress.com/">WordCram</a>.
                </p>
                <p>
                    A web-based user-interface was added to Genes2WordCloud where several parameters such as the font or the background-color can be changed.
                </p>
            </div>
            <div id="examples">
                <h3>Examples</h3>
                <p> In this section we provide some examples of using Genes2WordCloud.
                </p>
                <h4>A generif based Wordcloud for NANOG and SOX2</h4>
                <p class="center"><img alt="wordcloud" src="images/wordcloud5.png"></p>
                <p>NANOG and SOX2 are both genes encoding transcription factors involved in embryonic stem cells self-renewal and pluripotency maintenance. The WordCloud automatically obtained relevant terms such as <i>stem</i> (the word <i>cell</i> was automatically removed as it is considered a biological common term), <i>differentiate, pluripotent, self-renewal 
</i>. Also Oct4, a gene that is often associated with NANOG and SOX2 was recovered by Genes2WordCloud.
                </p>
                <h4>A WordCloud that is based on our laboratory web-page was also created as an example</h4>
				<p class="center"><img alt="wordcloud" src="images/wordcloud1.png"></p>
                <p> <a href="http://www.mountsinai.org/Research/Centers%20Laboratories%20and%20Programs/Maayan%20Laboratory">The Ma'ayan Laboratory</a> is a computational systems biology laboratory and the program correctly extracted the most relevant terms that describe the function of the lab, for example: <i>network, mammalian, software, database, compute, web-based tool</i>.
                </p>
                <h4>A WordCloud for the p38 pathway based on a PubMed search</h4>
                <p class="center"><img alt="wordcloud" src="images/wordcloud3.png"></p>
                <p> This WordCloud was obtained with the PubMed search:  <i>p38 pathway</i>. The algorithm recovered terms such as: <i>kinase, signal, MAPK, phosphorylate, apoptosis</i> which are relevant to the p38 pathway, a signaling pathway involved in cell differentiation and apoptosis.
                </p>
            </div>
            <div id="troubleshooting">
                <h3>Troubleshooting</h3>
                <h4>What to do if you don't see the WordCloud?</h4>
                <p> There are three possible explanations:
                <ul>
                <li>Java is not working on your computer or within your browser. In this case, to verify and solve the problem, go <a href="http://www.java.com/en/download/testjava.jsp">here.</a> </li>
                <li>No terms were found with your input. Normally you should receive a warning message. In some cases try to remove punctuations, symbols, or other similar characters, or verify that you entered correct gene names. </li>
                <li>Check that the color of the words is different from the background-color. White words on a white background won't be visible.</li>
                </ul>
                If it still doesn't work, you can try to figure out the error by opening the java console on your computer. To do this click <a href="http://www.java.com/en/download/help/javaconsole.xml">here</a>.
                <br/> Send <a href="mailto:avi.maayan@mssm.edu;caroline.baroukh@mssm.edu"> us</a> the content of the java console, along with the type of WordCloud you tried to display and the input you used. We will try to debug the error and get back to you.
                </p>
            </div>
            <div id="embed_applet">
                <h3>Using the WordCloud as an applet on your own website?</h3>
                <p>
                    You can use the applet with your own keywords on your own website. For doing this all you need to do is:
                    <ol>
                        <li> Download the following scripts from <a href="download/embed_applet.rar">here.</a> </li>
                        <li> Unzip the compressed file in the repository of your website. </li>
                        <li> To create a WordCloud, write the following code in the body of your HTML web-page:<br/><br/>
                        <div class="code">
                            &lt;?php <br/>
                            include ('path/embed_applet/WordCloud.php'); <br/>
                            create_wordcloud('name_of_worcloud', 'path', 'path_of_textfile_to_textmine.txt',  'path_of_english_forbidden_words_file', 'path_of_biology_forbidden_words_file', 'path_of_other_forbidden_words_file', 'cutoff'); <br/>
                            ?&gt; 
                        </div>
                        <br/> where 
                        <ul>
                            <li> <em>name_of_wordcloud</em> is the name you want to give to your WordCloud. Make sure to only use letters, digits and underscores characters.</li>
                            <li> <em>path</em> is the path to the folder that you unzipped. The default path should be "embed_applet/".</li>
                            <li> <em>path_of_textfile_to_textmine</em> is the path to the file containing the text to mine. The default file is in embed_applet/data/text_to_textmine.txt</li>
                            <li> <em>path_of_english_forbidden_words_file</em> is the path to the file containing the common English words to remove. The words need to be separated by space, tabs or returns. The default file is in embed_applet/data/stopwords.txt.</li>
                            <li> <em>path_of_biology_forbidden_words_file</em> is the path to the file containing the common biological terms you may want to remove. The words need to be separated by spaces, tabs or returns. The default file is in embed_applet/data/bio-stopwords.txt.</li>
                            <li> <em>path_of_other_forbidden_words_file</em> is the path to a file containing the other words you may want to remove. The words need to be separated by spaces, tabs or returns. The default file is in embed_applet/data/other-stopwords.txt.</li>
                            <li> <em>cutoff</em> is an integer representing the threshold for keywords. A word appearing less time than this value won't be kept. The default value is 0. </i></li>
                        </ul>
                    <br/> You need to have php installed on your server to be able to use the WordCloud generator. Therefore, your web page where you embed the WordCloud needs to have a .php extension.
                    <br/> No css-style is provided to the WordCloud, so if you want to add some css properties, we advise you to use Firebug to obtain the names of the HTML elements you want to add style to.
                    <br/>
                    <br/> If you already have keywords and weights for the keywords, you can directly use them as an input to the WordCloud. For this you need to write in your own HTML code as follows: <br/><br/>
                    <div class="code">
                        &lt;?php <br/>
                        include ('WordCloud.php');<br/>
                        create_wordcloud_weights('name_of_worcloud', 'path', 'keyword1 weight1 keyword2 weight2 ... keywordn weightn');
                        <br/>?&gt;                  
                    </div> 
                    <br/>
                    where <em>keyword1 weight1 keyword2 weight2 ... keywordn weightn</em> are the keywords and the weights associated with them. These need to be separated by the space character.
                    <br/><br/> An example of how to embed a WordCloud in a web-page is available in /embed_applet/example.php.
                        </li>
                    </ol>
                </p>
            </div>
            <div id="FAQ">
                <h3>Frequent Asked Questions</h3>
                <h4>What happens to the terms suggested to be removed for all the WordClouds?</h4>
                <p> These terms are stored in our database. If we agree that these should be indeed removed, we will add them to the common English words list or the common biological terms list.
                </p>
            </div>
            <div id="contacts">
                <h3>Contact Information</h3>
                <p>
                    Genes2WordCloud was developed by the <a href="http://www.mountsinai.org/Research/Centers%20Laboratories%20and%20Programs/Maayan%20Laboratory">Ma'ayan Laboratory</a> , at <a href="http://www.mountsinai.org/Education/School%20of%20Medicine">Mount Sinai School of Medicine</a> as part of the activities of the <a href="http://www.sbcny.org">Systems Biology Center New York (SBCNY)</a> . <br/><br/>
                    If you have any particular issues, questions, remarks or suggestions, please <a href="mailto:avi.maayan@mssm.edu;caroline.baroukh@mssm.edu">Contact us</a>.
                </p>
            </div>
            <div id="references">
                <h3>References</h3>
                <ul>
                    <li>Visual Presentation as a Welcome Alternative to Textual Presentation of Gene Annotation Information, Jairav Desai, Jared M. flatow, Jie Song, Lihua J. Zhu, Pan Du, Chiang-Ching Huang, Hui Lu, Simon M. Lin, and Warren A. Kibbe, Advances in computational biology, 2010, pages 709-715, Springer.</li>
                    <li><a href="http://www.wordle.net/">Wordle</a>, Jonathan Feinberg, 2009</li>
                    <li><a href="http://en.wikipedia.org/wiki/Tag_cloud"> Wikipedia, article on Tag Clouds</a></li>
                    <li><a href="http://processing.org/">Processing librairies</a></li>
                    <li><a href="http://wordcram.wordpress.com/">WordCram, Dan Bernier</a></li>
                    <li><a href="http://www.ncbi.nlm.nih.gov/books/NBK25500/">Pubmed e-utilities</a>
                    <li>Comparison of Tag Cloud Layouts: Task-Related Performance and Visual Exploration, Lohmann, S., Ziegler, J., Tetzlaff, L., T. Gross et al. (Eds.): INTERACT 2009, Part I, LNCS 5726, 2009, pages 392–404.</li>
                    <li><a href="http://nadeausoftware.com/articles/2008/04/php_tip_how_extract_keywords_web_page">How to extract keywords from a web page</a>, Dr. David R. Nadeau</li>
                </ul>
                </p>
            </div>
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
