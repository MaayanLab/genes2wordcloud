<?php 
	session_start();
	
	if (!isset($_SESSION['sessionid']))
	{
		$_SESSION['sessionid'] = session_id();
	}
	
	//Settings and keywords
	if (!isset($_SESSION["keywords"]))
	{
		$_SESSION["keywords"] = file_get_contents("data/abstracts_KLF4.txt");
	}
	if (!isset($_SESSION["forbidden_keywords"]["words"]))
	{
		$_SESSION["forbidden_keywords"]["words"] = "";
	}
	if (!isset($_SESSION["forbidden_keywords"]["action"]))
	{
		$_SESSION["forbidden_keywords"]["action"] = "redraw";
	}
		if (!isset($_SESSION["font_name"]))
	{
		$_SESSION["font_name"] = "Minyn.ttf";
	}
	if (!isset($_SESSION["angler"]))
	{
		$_SESSION["angler"] = "mostlyHoriz";
	}
	if (!isset($_SESSION["placer"]))
	{
		$_SESSION["placer"] = "horizLine";
	}
	if (!isset($_SESSION["background_color"]))
	{
		$_SESSION["background_color"] = "FAFAFA";
	}
	if (!isset($_SESSION["color"]["mode"]))
	{
		$_SESSION["color"]["mode"] = "twoHuesRandomSats";
	}
	if (!isset($_SESSION["color"]["nb_colors"]))
	{
		$_SESSION["color"]["nb_colors"] = 3;
	}
	if (!isset($_SESSION["color"][1]))
	{
		$_SESSION["color"][1] = "B82D11";
	}
		if (!isset($_SESSION["color"][2]))
	{
		$_SESSION["color"][2] = "2616B8";
	}
		if (!isset($_SESSION["color"][3]))
	{
		$_SESSION["color"][3] = "000000";
	}
	if (!isset($_SESSION["ulf_case"]))
	{
		$_SESSION["ulf_case"]="LowerCase";
	}
	if (!isset($_SESSION["width"]))
	{
		$_SESSION["width"]="700";
	}
	if (!isset($_SESSION["height"]))
	{
		$_SESSION["height"]="600";
	}
	if (!isset($_SESSION["from"])||($_SESSION["from"]==1))
	{
		header( 'Location: create_wordcloud.php') ;
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
        <link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/wordcloud.css" />
        <link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/index.css" />
	</head>
    
   <body>
   <script type="text/javascript" src="js/jscolor/jscolor.js"></script>
   <script type="text/javascript" src="js/loadapplet.js"></script>
   <script type="text/javascript" src="js/jquery.js"></script>
   <script type="text/javascript">
       $(document).ready(function() {
            $.ajax({
                url: "windows_size.php?width="+$(window).width(),
                success: function(data){
                    $("#applet").html(data);
                }
            });
        });
     </script>
	 
   <script language="JavaScript" type="text/javascript">
		function selected_colors(elem)
		{
				if (elem.value=="colors")
				{
					document.getElementById("color").style.display = 'inline';
				}
				else 
				{
					document.getElementById("color").style.display = 'none';
				}
		}
		function check_form()
		{	
			if(isNaN(document.getElementById("width").value))
			{
				alert("Width is not a number.");
				document.getElementById("width").focus();
				bool =  false;
			}	
			else if(isNaN(document.getElementById("height").value))
			{
				alert("Height is not a number.");
				document.getElementById("height").focus();
				bool = false;
			}
			else
			{
				bool = true;
			}
			return bool;
		}
		
		function affiche(str){
			get_info(str);
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
                    <li><a href="create_wordcloud.php">CREATE</a></li>
                    <li class="select">VIEW</li>
                    <li><a href="help.php">HELP</a> </li>
                </ul>
            </div>
        </div>
        
        
		<div id = "corpus">
			<div id="informations_origins">
			<?php
			if (isset($_SESSION['from'])&&($_SESSION['from']!=1)) 
			{	
				if ($_SESSION['from'] == "text")
				{
					echo "<h4> WordCloud generated from free text</h4>";
					if ($_SESSION["text"]["stopwords"]==1 && $_SESSION["text"]["biostopwords"]==0)
					{
						echo("<p><i>Common English words were removed.</i></p>");
					}
					else if ($_SESSION["text"]["stopwords"]==1 && $_SESSION["text"]["biostopwords"]==1)
					{
						echo("<p><i>Common English words and biological terms were removed.</i></p>");
					}
					else if ($_SESSION["text"]["stopwords"]==0 && $_SESSION["text"]["biostopwords"]==1)
					{
						echo("<p><i>Common biological terms were removed.</i></p>");
					}		
				}
				if ($_SESSION['from'] == "url")
				{
					echo "<h4> WordCloud for <a href=\"".$_SESSION["url"]["url"]."\">".$_SESSION["url"]["url"]."</a></h4>";
					if ($_SESSION["url"]["stopwords"]==1 && $_SESSION["url"]["biostopwords"]==0)
					{
						echo("<p><i>Common English words were removed.</i></p>");
					}
					else if ($_SESSION["url"]["stopwords"]==1 && $_SESSION["url"]["biostopwords"]==1)
					{
						echo("<p><i>Common English words and biological terms were removed.</i></p>");
					}
					else if ($_SESSION["url"]["stopwords"]==0 && $_SESSION["url"]["biostopwords"]==1)
					{
						echo("<p><i>Common biological terms were removed.</i></p>");
					}	
				}
				else if ($_SESSION['from'] == "Pubmed search")
				{
					echo "<h4> WordCloud for ".$_SESSION["keyword"]."</h4>";
				}
				else if($_SESSION['from']=="author")
				{
					echo "<h4> WordCloud of ".$_SESSION["author"]." work</h4>";
				}
				else if ($_SESSION['from'] == "bmc")
				{
					if ($_SESSION["date"]=="30days")
					{
						echo "<h4> WordCloud generated from the most read BMC Bioinformatics articles in the last 30 days </h4>";
					}
					else if ($_SESSION["date"]=="pastyear")
					{
						echo "<h4> WordCloud generated from the most read BMC Bioinformatics articles in the past year. </h4>";
					}
					else if ($_SESSION["date"]=="ever")
					{
						echo "<h4> WordCloud generated from the most read BMC Bioinformatics articles.</h4>";
					}
				}
				else if ($_SESSION['from'] == "list of genes")
				{
					if ($_SESSION["go"]==1)
					{
						echo "<h4> WordCloud generated from GO terms for gene ".strtoupper($_SESSION["name_genes"])." </h4>";
					}
					else if ($_SESSION["mp"]==1)
					{
						echo "<h4> WordCloud generated from MPI for gene ".strtoupper($_SESSION["name_genes"])." </h4>";
					}
					else if ($_SESSION["generif"]==1)
					{
						echo "<h4> WordCloud generated from generifs annotations for gene ".strtoupper($_SESSION["name_genes"])." </h4>";
					}
					else if ($_SESSION["pubmed"]==1)
					{
						echo "<h4> WordCloud generated from PubMed abstracts for gene ".strtoupper($_SESSION["name_genes"])." </h4>";
					}
					else if ($_SESSION["mesh_terms"]==1)
					{
						echo "<h4> WordCloud generated from Mesh Terms of PubMed abstracts for gene ".strtoupper($_SESSION["name_genes"])." </h4>";
					}
				}
			}
			?>
			</div>
            <div class="form" id="form_applet">
                <form method="post">
                    <p>
                        <label for="font_name">Font: </label>
                        <select name="font_name" id="font_name" onchange="display_applet(this.name,this.value);">
                            <?php	 
                                if ($handle = opendir('applet/data')) 
                                {
                                    while (false !== ($file = readdir($handle))) 
                                    {
                                        if (preg_match("#.ttf$#",$file))
                                        {
                                            ?> <option value="<?php echo $file;?>" <?php if ($_SESSION["font_name"]==$file) echo "selected=\"selected\"";?>><?php echo substr($file, 0,-4);?></option>
                                  <?php }
                                     }
                                     closedir($handle);
                                } 
                            ?>
                            </select>
                        <br/>
                        <label for="angler">Angle: </label>
                        <select name="angler" id="angler" onchange="display_applet(this.name,this.value);">
                            <option value="mostlyHoriz" <?php if ($_SESSION["angler"]=="mostlyHoriz") echo "selected=\"selected\"";?>>mostlyHoriz</option>
                            <option value="heaped" <?php if ($_SESSION["angler"]=="heaped") echo "selected=\"selected\"";?>>heaped</option>
                            <option value="hexes" <?php if ($_SESSION["angler"]=="hexes") echo "selected=\"selected\"";?>>hexes</option>
                            <option value="horiz" <?php if ($_SESSION["angler"]=="horiz") echo "selected=\"selected\"";?>>horiz</option>
                            <option value="random" <?php if ($_SESSION["angler"]=="random") echo "selected=\"selected\"";?>>random</option>
                            <option value="updAndDown" <?php if ($_SESSION["angler"]=="updAndDown") echo "selected=\"selected\"";?>>updAndDown</option>
                        </select>
                        <br/>
                        <label for="placer">Placer: </label>
                        <select name="placer" id="placer" onchange="display_applet(this.name,this.value);">
                            <option value="centerClump" <?php if ($_SESSION["placer"]=="centerClump") echo "selected=\"selected\"";?>>centerClump</option>
                    <!--        <option value="horizBandAnchoredLeft" <?php if ($_SESSION["placer"]=="horizBandAnchoredLeft") echo "selected=\"selected\"";?>>horizBandAnchoredLeft</option> -->
                            <option value="horizLine" <?php if ($_SESSION["placer"]=="horizLine") echo "selected=\"selected\"";?>>horizLine</option>
                            <option value="swirl" <?php if ($_SESSION["placer"]=="swirl") echo "selected=\"selected\"";?>>swirl</option>
                    <!--        <option value="upperLeft" <?php if ($_SESSION["placer"]=="upperLeft") echo "selected=\"selected\"";?>>upperLeft</option> -->
                            <option value="wave" <?php if ($_SESSION["placer"]=="wave") echo "selected=\"selected\"";?>>wave</option>
                        </select>
                        <br/>
                        <br/>
                        Background Color: <br/><input class="color {pickerMode:'HSV'}" id="background_color" name="background_color" value="<?php echo $_SESSION["background_color"]; ?>" onchange="display_applet(this.name,this.value);">
                        <br/>
                        <label for="color[mode]">Words' Colors: </label><br/>
                        <select name="color[mode]" id="color[mode]" onChange="selected_colors(this); display_applet(this.name,this.value);">
                            <option value="twoHuesRandomSats" <?php if ($_SESSION["color"]["mode"]=="twoHuesRandomSats") echo "selected=\"selected\"";?>>twoHuesRandomSats</option>
                            <option value="twoHuesRandomSatsOnWhite" <?php if ($_SESSION["color"]["mode"]=="twoHuesRandomSatsOnWhite") echo "selected=\"selected\"";?>>twoHuesRandomSatsOnWhite</option>
                            <option value="colors" <?php if ($_SESSION["color"]["mode"]=="colors") echo "selected=\"selected\"";?>>Choose colors</option>
                        </select>
                        <br/>
                        <span class="color" id ="color" style="display: <?php if ($_SESSION["color"]["mode"]=="colors") echo "inline"; else echo "none"?>;">
                            Color 1: <input class="color {pickerMode:'HSV'}" id="color[1]" name="color[1]" value="<?php echo $_SESSION["color"][1]; ?>" onchange="display_applet(this.name,this.value);"><br/>
                            Color 2: <input class="color {pickerMode:'HSV'}" id="color[2]" name="color[2]" value="<?php echo $_SESSION["color"][2]; ?>" onchange="display_applet(this.name,this.value);"><br/>
                            Color 3: <input class="color {pickerMode:'HSV'}" id="color[3]" name="color[3]" value="<?php echo $_SESSION["color"][3]; ?>" onchange="display_applet(this.name,this.value);"><br/>
                            <input type="hidden" id="color[nb_colors]" name = "color[nb_colors]" value="3"/>
                        </span>
                        <br/>
                        <label for="ulf_case">Upper, lower, first case: </label><br/>
                        <select name="ulf_case" id="ulf_case" onchange="display_applet(this.name,this.value);">
                            <option value="UpperCase" <?php if ($_SESSION["ulf_case"]=="UpperCase") echo "selected=\"selected\"";?>>UpperCase</option>
                            <option value="LowerCase" <?php if ($_SESSION["ulf_case"]=="LowerCase") echo "selected=\"selected\"";?>>LowerCase</option>
                            <option value="FirstCase" <?php if ($_SESSION["ulf_case"]=="FirstCase") echo "selected=\"selected\"";?>>First letter case</option>
                        </select>
                    </p>
                </form>
                 <div class="form" id="remove_keywords">
                    <form method="post" action="remove_keywords.php">
                        <p>
                            <label for="forbidden_keywords[words]">Remove keywords from the WordCloud by typing them below separated by space, commas or return:</label><br/>
                            <textarea name="forbidden_keywords[words]" id="forbidden_keywords[words]" cols="20" rows="5"><?php echo $_SESSION["forbidden_keywords"]["words"];?></textarea>
                            <br/><br/>
                            <select name="forbidden_keywords[action]" id="forbidden_keywords[action]">
                                <option value="redraw" <?php if ($_SESSION["forbidden_keywords"]["action"]=="redraw") echo "selected=\"selected\"";?>>Redraw the WordCloud</option>
                                <option value="suggest" <?php if ($_SESSION["forbidden_keywords"]["action"]=="suggest") echo "selected=\"selected\"";?>>Suggest to definitively remove</option>
                            </select>
                            <br/><br/>
                            <input type="submit" value=""/>
                        </p>
                    </form>
                </div>
                <div id="downloads">
                    <p> <a href="download_keywords.php">Download</a> the list of keywords from the WordCloud with their occurrence scores.</p>
                </div>
				<div id="appletData">
					<?php 
						if (isset($_SESSION['from'])&&($_SESSION['from']!=1)) {
							echo "<p>	Click on the words to see ";
														if (($_SESSION['from'] == "url")||($_SESSION['from'] == "text"))
															echo "the weight of each word.";
														else if (($_SESSION['from'] == "bmc")||($_SESSION['from'] == "Pubmed search")||($_SESSION['from']=="author"))
															echo "which PubMed articles the words are from.";
														else if ($_SESSION['from'] == "list of genes")
														{
															if ($_SESSION["go"]==1)
															{
																echo "in which GO terms the words appear.";
															}
															else if ($_SESSION["mp"]==1)
															{
																echo "in which MP terms the words appear.";
															}
															else if ($_SESSION["generif"]==1)
															{
																echo "in which PubMed articles the words appear.";
															}
															else if ($_SESSION["pubmed"]==1)
															{
																echo "in which PubMed articles the words appear.";
															}
															else if ($_SESSION["mesh_terms"]==1)
															{
																echo "in which mesh_terms of PubMed articles the words appear.";
															}
														}
														}
														?>
					</p>
				</div>
            </div>
			<div id="appletcontainer">
				<div id="applet">
				</div>
<b>If you can't see the clouds because of a Java security error, change the Java security settings to Medium. Complete instructions are provided <a href="http://www.maayanlab.net/G2W/applet/Genes2WordCloudQuickFix.pdf">here</a></b>.
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
