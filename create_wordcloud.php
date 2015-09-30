<?php 

	session_start();
		
	if (!isset($_SESSION['sessionid']))
	{
		$_SESSION['sessionid'] = session_id();
	}
	
    if (!isset(	$_SESSION["width_user"]))
	{
		$_SESSION["width_user"] = 1024;
	}
    
	if (!isset(	$_SESSION["height_user"]))
	{
		$_SESSION["height_user"] = 768;
	}
    
    if (!isset($_SESSION['from']))
    {
        $_SESSION['from'] = 1;
    }
    
	if (!isset($_SESSION['genes']))
	{
		$_SESSION['genes'] = "KLF4";
	}
	
	if (!isset($_SESSION['generif']))
	{
		$_SESSION['generif'] = 0;
	}
    
	if (!isset($_SESSION['pubmed']))
	{
		$_SESSION['pubmed'] = 0;
	}
    
	if (!isset($_SESSION['go']))
	{
		$_SESSION['go'] = 0;
	}
    
	if (!isset($_SESSION['mp']))
	{
		$_SESSION['mp'] = 0;
	}
	
    if (!isset($_SESSION['mesh_terms']))
	{
		$_SESSION['mesh_terms'] = 0;
	}
	
	
    
	if (!isset( $_SESSION["text"]["text"]))
	{
		$_SESSION["text"]["text"] = "The Ma'ayan Laboratory applies computational and mathematical methods to study the complexity of regulatory networks in mammalian cells. We apply graph-theory algorithms, machine-learning techniques and dynamical modeling to study how intracellular regulatory systems function as networks to control cellular processes such as differentiation, de-differentiation, apoptosis and proliferation. We develop software systems to help experimental biologists form novel hypotheses from high-throughput data, and develop theories about the structure and function of regulatory networks in mammalian systems. We apply our tools and other computational methods for the analysis of a variety of projects including: high-dimensional time-series data collected from differentiating mES cells and differentiating neuro2A cells, multi-layered experimental data collected from kidneys of Tg26 mice, a mouse model of HIV associated nephropathy (HIVAN), as well as proteomics and phosphoproteomics experiments applied to profile components downstream of stimulated G-protein coupled receptors. These results from our analyses produce concrete suggestions and predictions for further functional experiments. The predictions are tested by our collaborators and our analyses methods are delivered as software tools and databases for the systems biology research community.";
	}
    
	if (!isset(	$_SESSION["text"]["stopwords"]))
	{
		$_SESSION["text"]["stopwords"] = 1;
	}
    
	if (!isset(	$_SESSION["text"]["biostopwords"]))
	{
		$_SESSION["text"]["biostopwords"] = 0;
	}
    
    
	if (!isset(	$_SESSION["url"]["url"]))
	{
		$_SESSION["url"]["url"] = "http://en.wikipedia.org/wiki/Biology";
	}
	if (!isset(	$_SESSION["url"]["stopwords"]))
	{
		$_SESSION["url"]["stopwords"] = 1;
	}
	if (!isset(	$_SESSION["url"]["biostopwords"]))
	{
		$_SESSION["url"]["biostopwords"] = 0;
	}
    
    
	if (!isset($_SESSION["author"]))
	{
		$_SESSION["author"] = "Newson AJ";
	}
    
    
	if (!isset($_SESSION["keyword"]))
	{
		$_SESSION["keyword"] = "p38 pathway";
	}
	
    
	if (!isset(	$_SESSION["date"]))
	{
		$_SESSION["date"] = "30days";
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
        <link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/index.css"/>
        <link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/create_wordcloud.css"/>
        <link rel="stylesheet" type="text/css" href="Style/jquery.autocomplete.css" />
	</head>
    
    <body style="margin:auto;" onload="change_form(<?php if ($_SESSION['from'] == "list of genes") echo "1";
   																	else if ($_SESSION['from'] == "text") echo "2";
   																	else if ($_SESSION['from'] == "url") echo "3";
   																	else if ($_SESSION['from'] == "author") echo "4";
   																	else if ($_SESSION['from'] == "Pubmed search") echo "5";
   																	else if ($_SESSION['from'] == "bmc") echo "6";
   																	else echo "1";
   															?>);">
   <script type="text/javascript" src="js/jscolor/jscolor.js"></script>
   
   <script type="text/javascript" src="js/jquery.js"></script>
        <script type='text/javascript' src='js/jquery.bgiframe.min.js'></script>
        <script type='text/javascript' src='js/jquery.ajaxQueue.js'></script>
        <script type='text/javascript' src='js/thickbox-compressed.js'></script>
        <script type='text/javascript' src='js/jquery.autocomplete.js'></script>
        
   <script type="text/javascript">
       $(document).ready(function() {
            $("#genes").autocomplete('autocomplete.php?', {
                autoFill: true,
                delay: 200,
                max: 0,
                multiple: true, 
                mustMatch: false,
            });
        });
    </script>
    
   	<script language="JavaScript" type="text/javascript">
		function checkgene ( form )
		{
		  if (form.genes.value == "") {
		    alert( "Please enter at least a gene." );
		    form.genes.focus();
		    return false ;
		  }
		  return true ;
		}

		function checktext ()
		{
			var text = document.getElementById("text[text]");
		  if (text.value == "") {
		    alert( "Please enter at least a word." );
		    text.focus();
		    return false ;
		  }
		  return true ;
		}
		
		function checkurl ()
		{
			var url = document.getElementById("url[url]");
		  if (url.value == "") {
		    alert( "Please enter a url." );
		    url.focus();
		    return false ;
		  }
		  return true ;
		}
		
		function change_form( form_number )
		{
			for (i=1;i<7;i++)
			{
				//alert(document.getElementById('form'+i).style.display);
				if (i==form_number)
				{
					document.getElementById('form'+i).style.display = 'block';
                    document.getElementById('link'+i).style.color = '#2D0100';
				}
				else
				{
					document.getElementById('form'+i).style.display = 'none';
                    document.getElementById('link'+i).style.color = '#A10812';
				}
			}
		}
        
        function change_color( span )
		{  
            var i = (span.id).substring(4,5);
			if (document.getElementById('form'+i).style.display == 'block')
            {
                document.getElementById('link'+i).style.color = '#2D0100';
            }
			else
			{
                    document.getElementById('link'+i).style.color = '#A10812';
			}
		}
		
	</script>
    
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
                    <li class="select"> CREATE</li>
                    <li <?php if (!isset($_SESSION['from'])||$_SESSION['from']==1) echo "class=\"select\">VIEW"; 
                    else echo "> <a href=\"WordCloud.php\">VIEW</a>";?>  </li>
                    <li> <a href="help.php">HELP</a> </li>
                </ul>
            </div>
        </div>
        
        
		<div id = "corpus">
            <p>
            Generate WordClouds from a list of genes, single genes, free text, URLs, or find the themes of the currently most popular articles in BMC Bioinformatics.<br/>
			<i>Please be patient, sometimes it may take several minutes to generate a WordCloud.</i><br/><br/>
			</p>
			<div id="menu2">
				<p>
                    <span id ="link1" class="link" onClick="change_form(1);" OnMouseOver="this.style.cursor='pointer'; this.style.color='white'; this.style.backgroundColor='#A10812'"; onMouseOut="change_color(this); this.style.backgroundColor='#FFFFFF'">Genes</span>
                    <span id ="link2" class="link" onClick="change_form(2);" OnMouseOver="this.style.cursor='pointer'; this.style.color='white'; this.style.backgroundColor='#A10812';" onMouseOut="change_color(this);this.style.backgroundColor='#FFFFFF'">Free Text</span>
                    <span id ="link3" class="link" onClick="change_form(3);" OnMouseOver="this.style.cursor='pointer'; this.style.color='white'; this.style.backgroundColor='#A10812';" onMouseOut="change_color(this);this.style.backgroundColor='#FFFFFF'"> URL</span>
                    <span id ="link4" class="link" onClick="change_form(4);" OnMouseOver="this.style.cursor='pointer'; this.style.color='white'; this.style.backgroundColor='#A10812';" onMouseOut="change_color(this);this.style.backgroundColor='#FFFFFF'">Author</span>
                    <span id ="link5" class="link" onClick="change_form(5);" OnMouseOver="this.style.cursor='pointer'; this.style.color='white'; this.style.backgroundColor='#A10812';" onMouseOut="change_color(this);this.style.backgroundColor='#FFFFFF'">Pubmed Search</span>
                    <span id ="link6" class="link" onClick="change_form(6);" OnMouseOver="this.style.cursor='pointer'; this.style.color='white'; this.style.backgroundColor='#A10812';" onMouseOut="change_color(this);this.style.backgroundColor='#FFFFFF'">BMC Bioinformatics</span>
                </p>
            </div>
			<div class = "form" id="form1">
				<h4>Generate a WordCloud for a specific gene, or a list of genes:</h4>
				<form method="post" action="generate_keywords.php" onsubmit="return checkgene(this);">
					<p>
	  				 	<label for="genes">Paste Mammalian Entrez Gene symbols in the text area below:</label><br/>
                        (separated by either : ; , - \r \t \n or spaces)  </label><br/>
	      			 	<textarea name="genes" id="genes" cols="20" rows="10"><?php echo ($_SESSION["genes"]); ?></textarea><br/><br/>
                        <label for="source">Source for the WordCloud: </label><br/>
       					<select name="source" id="source">
          					<option value="generif" <?php if (($_SESSION["generif"]==1)&&($_SESSION["pubmed"]==0)) echo "selected=\"selected\"";?>>Generif annotations</option>
           					<option value="pubmed" <?php if (($_SESSION["generif"]==0)&&($_SESSION["pubmed"]==1)) echo "selected=\"selected\"";?>>Abstracts of Pubmed articles</option>
       						<option value="go" <?php if ($_SESSION["go"]==1) echo "selected=\"selected\"";?>>Gene Ontology annotations</option>
       						<option value="mp" <?php if ($_SESSION["mp"]==1) echo "selected=\"selected\"";?>>Mammalian Phenotype Ontology annotations</option>
							<option value="mesh_terms" <?php if ($_SESSION["mesh_terms"]==1) echo "selected=\"selected\"";?>>Mesh Terms from Pubmed</option>
       					</select>
       					<br/>
       					<br/>
	  				 	<input type="submit" value="" />
	  				 	</p>
				</form>
			</div>
			<div class = "form" id="form2">
				<h4>Create a WordCloud from free text</h4>
				<form method="post" action="generate_wordcloud_from_text.php" onsubmit="return checktext();">
					<p>
	  				 	<label for="text[text]">Paste free text in the text box below. Words will be stemmed and common English words will be removed.</label><br/>
	      			 	<textarea name="text[text]" id="text[text]" cols="80" rows="15"><?php echo ($_SESSION["text"]["text"]); ?></textarea><br/>
	  				 	<input type="checkbox" name="text[stopwords]" id="text[stopwords]" <?php if ($_SESSION["text"]["stopwords"]==1) echo "checked=\"checked\"";?>/> <label for="text[stopwords]">Remove common English words</label><br/>
	 	  				<input type="checkbox" name="text[biostopwords]" id="text[biostopwords]" <?php if ($_SESSION["text"]["biostopwords"]==1) echo "checked=\"checked\"";?> /> <label for="text[biostopwords]">Remove common biological terms</label><br/><br/>
	  				 	<input type="submit" value="" />
	  				 	</p>
				</form>
			</div>
			<div class = "form" id="form3">
				<h4>Create a WordCloud for text extracted from a given URL</h4>
				<form method="post" action="generate_wordcloud_url.php" onsubmit="return checkurl();">
					<p>
	  				 	<label for="url[url]">Paste in a valid URL. Words will be stemmed and common English words will be removed.</label><br/>
	      			 	<input type="text" name="url[url]" id="url[url]" size="80" value="<?php echo ($_SESSION["url"]["url"]); ?>"/><br/><br/>
	  				 	<input type="checkbox" name="url[stopwords]" id="url[stopwords]" <?php if ($_SESSION["url"]["stopwords"]==1) echo "checked=\"checked\"";?>/> <label for="url[stopwords]">Remove common English words</label><br/>
	 	  				<input type="checkbox" name="url[biostopwords]" id="url[biostopwords]" <?php if ($_SESSION["url"]["biostopwords"]==1) echo "checked=\"checked\"";?> /> <label for="text[biostopwords]">Remove common biological terms</label><br/><br/>
	  				 	<input type="submit" value="" />
	  				 	</p>
				</form>
			</div>
			<div class = "form" id="form4">
				<h4>Create a WordCloud from all publications for an author</h4>
				<form method="post" action="generate_wordcloud_author.php">
					<p>
						Paste in the author's last name and initials separated by space. Do not use punctuations. 
						<br/>For example, Newson Ainsley James should be entered as Newson AJ.
	  				 	<br/>
	  				 	<input type="text" id="author" name="author" value="<?php echo $_SESSION["author"];?>"/>
	  				 	<br/>
	  				 	<br/>
       					<input type="submit" value="" />
	  				 </p>
				</form>
			</div>
			<div class = "form" id="form5">
				<h4>Create a WordCloud from a Pubmed Search</h4>
				<form method="post" action="generate_wordcloud_keyword.php">
					<p>
						Paste keyword(s) as if you were using a regular Pubmed search. 
	  				 	<br/>
	  				 	<input type="text" id="keyword" name="keyword" value="<?php echo $_SESSION["keyword"];?>"/>
	  				 	<br/>
	  				 	<br/>
       					<input type="submit" value="" />
	  				 </p>
				</form>
			</div>
			<div class = "form" id="form6">
				<h4>Create a WordCloud from the currently most popular abstracts in BMC Bioinformatics</h4>
				<form method="post" action="generate_bmc_wordcloud.php">
					<p>
						Generate a WordCloud made of a collection of abstracts that are from the mostly viewed BMC Bioinformatics articles
	  				 	<br/>
	  				 	<br/>
	  				 	<label for="date">Most viewed articles in: </label>
       					<select name="date" id="date">
          					<option value="30days" <?php if ($_SESSION["date"]=="30days") echo "selected=\"selected\"";?>>past 30 days</option>
           					<option value="pastyear" <?php if ($_SESSION["date"]=="pastyear") echo "selected=\"selected\"";?>>past year</option>
       						<option value="ever" <?php if ($_SESSION["date"]=="ever") echo "selected=\"selected\"";?>>all times</option>
       					</select>
       					<br/>
       					<br/>
       					<input type="submit" value="" />
	  				 </p>
				</form>
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
<p style="font-size:12px;">
Try these other tools developed by the <a href="http://icahn.mssm.edu/labs/maayan">Ma'ayan Lab</a>:<br>
<a href="http://amp.pharm.mssm.edu/Enrichr">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/enrichr.png" alt="Enrichr- Analyze Gene Lists with over 30 Gene Set Libraries">
</a>

<a href="http://www.maayanlab.net/DPS">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/dps.png" alt="Drug Pair Seeker- Identify Pairs of Drugs that can Reverse or Mimic Gene Expression Patterns">
</a>

<a href="http://amp.pharm.mssm.edu/lib/chea.jsp">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/chea.png" alt="ChIP-X Enrichment Analysis- Database of Transcription Factors and their Target Genes extracted from ChIP-seq and ChIP-chip studies">
</a>


<a href="http://amp.pharm.mssm.edu/lib/kea.jsp">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/kea.PNG" alt="Kinase Enrichment Analysis- Database of Literature-Based Kinase Substrate Interactions">
</a>

<a href="http://apps.lincscloud.org/LCBL/">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/canvas.jpg" alt="LINCS Canvas Browser- Query and Visualize 1000's of L1000 Experiments">
</a>

<a href="http://amp.pharm.mssm.edu/maayan-lab/gate.htm">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/gate.png" alt="GATE- Desktop Application for Gene Expression Time-Series Data Analysis">
</a>

<a href="http://actin.pharm.mssm.edu/genes2FANs/">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/G2F-icon.png" alt="Genes2FANs- Tools to Build Networks from List of Genes">
</a>

<a href="http://www.maayanlab.net/S2N/">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/S2N-icon.png" alt="Sets2Networks- Tool to Build Networks from Gene-Set Libraries">
</a>

<a href="http://www.maayanlab.net/X2K">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/X2K_icon.PNG" alt="Expression2Kinases- Tool to Infer Cell Signaling Pathways from Sets of Differentially Expressed Genes">
</a>

<a href="http://amp.pharm.mssm.edu/lachmann/upload/register.php">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/lists2networks.PNG" alt="Lists2Networks- Web-Based Platform for Performing Gene Set Enrichment Analyses">
</a>


<a href="http://actin.pharm.mssm.edu/genes2networks/">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/genes2networks.PNG" alt="Genes2Networks- Tool to Build Protein-Protein Interaction Networks from Lists of Genes">
</a>

<a href="http://www.maayanlab.net/ESCAPE/">
<img src="http://icahn.mssm.edu/static_files/MSSM/Images/Research/Labs/Maayan Laboratory/ESCAPE-icon.png" alt="ESCAPE- Database that Collects, Organizes and Visualizes High-Content Data from Embryonic Stem Cell Research">
</a>
</p>
		</div>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-6398361-7']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
   </body>
</html>