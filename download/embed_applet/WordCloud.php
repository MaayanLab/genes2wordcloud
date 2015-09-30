<?php 

include ('textmining.php');

function create_wordcloud($name, $path = "embed_applet/", $input_filename = "embed_applet/data/text_to_textmine.txt", $stopwords_filename = "embed_applet/data/stopwords.txt", $bio_stopwords_filename = "embed_applet/data/bio-stopwords.txt", $other_stopwords_filename = "embed_applet/data/other-stopwords.txt", $cutoff = 0)
{
	//create the XML file of settings.
	if (!file_exists($path."data/settings_".$name.".xml"))
	{	
		$dom = new DOMDocument('1.0');
	
		$root = $dom->createElement('root');
		$dom->appendChild($root);
	
		$element = $dom->createElement('keywords', 'Hello 2 World 2');
		$root->appendChild($element);
	
		$element = $dom->createElement('forbidden_keywords', '');
		$root->appendChild($element);
	
		$element = $dom->createElement('font_name', 'Minyn.ttf');
		$root->appendChild($element);
	
		$element = $dom->createElement('angler', 'mostlyHoriz');
		$root->appendChild($element);
	
		$element = $dom->createElement('placer', 'horizLine');
		$root->appendChild($element);
	
		$element = $dom->createElement('background_color', 'FFFFFF');
		$root->appendChild($element);
	
		$color = $dom->createElement('color_settings');
		$root->appendChild($color);
	
		$element = $dom->createElement('mode', 'twoHuesRandomSats');
		$color->appendChild($element);
	
		$element = $dom->createElement('nb_colors', '3');
		$color->appendChild($element);
	
		$colors = $dom->createElement('colors');
		$color->appendChild($colors);
	
		$element = $dom->createElement('color', 'B82D11');
		$colors->appendChild($element);
	
		$element = $dom->createElement('color', '2616B8');
		$colors->appendChild($element);
	
		$element = $dom->createElement('color', '000000');
		$colors->appendChild($element);
	
		$element = $dom->createElement('ulf_case', 'LowerCase');
		$root->appendChild($element);
	
		$element = $dom->createElement('width', '800');
		$root->appendChild($element);
	
		$element = $dom->createElement('height', '600');
		$root->appendChild($element);
	
		$dom->save($path."data/settings_".$name.".xml");
	
		//echo $dom->saveXML();
	}

	//Text-mining and store keywords in the settings.xml file.
	$dom = new DomDocument();
	$dom->load($path."data/settings_".$name.".xml");

	$keywords = textmining($input_filename, $stopwords_filename, $bio_stopwords_filename, $other_stopwords_filename, $cutoff);
	$dom->getElementsByTagName("keywords")->item(0)->nodeValue = $keywords;
	
	$dom->getElementsByTagName("forbidden_keywords")->item(0)->nodeValue = "";
	
	$dom->save($path."data/settings_".$name.".xml");
	
?>
	<div id="<?php echo $name;?>" name="<?php echo $name;?>">
		<script type="text/javascript" src="<?php echo $path;?>js/jscolor/jscolor.js"></script>
   		<script type="text/javascript" src="<?php echo $path;?>js/loadapplet.js"></script>
  		<script language="JavaScript" type="text/javascript">
			function selected_colors_<?php echo $name;?>(elem)
			{
				if (elem.value=="colors")
				{
					document.getElementById("color_<?php echo $name;?>").style.display = 'inline';
				}
				else 
				{
					document.getElementById("color_<?php echo $name;?>").style.display = 'none';
				}
			}
		</script>
            <div class="form" id="form_applet_<?php echo $name;?>" style="float: left;">
				<form method="post">
					<p>
						<label for="font_name">Font: </label>
       					<select name="font_name" id="font_name" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       						<?php	 
								if ($handle = opendir($path.'applet/data')) 
								{
									while (false !== ($file = readdir($handle))) 
									{
										if (eregi("\.ttf",$file))
										{
											?> <option value="<?php echo $file;?>" <?php if ($dom->getElementsByTagName("font_name")->item(0)->nodeValue==$file) echo "selected=\"selected\"";?>><?php echo substr($file, 0,-4);?></option>
								  <?php }
   									 }
   									 closedir($handle);
								} 
       						?>
          					</select>
       				 	<br/>
       					<label for="angler">Angler: </label>
       					<select name="angler" id="angler" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       					<?php $angler = $dom->getElementsByTagName("angler")->item(0)->nodeValue;?>
          					<option value="mostlyHoriz" <?php if ($angler=="mostlyHoriz") echo "selected=\"selected\"";?>>mostlyHoriz</option>
          					<option value="heaped" <?php if ($angler=="heaped") echo "selected=\"selected\"";?>>heaped</option>
           					<option value="hexes" <?php if ($angler=="hexes") echo "selected=\"selected\"";?>>hexes</option>
           					<option value="horiz" <?php if ($angler=="horiz") echo "selected=\"selected\"";?>>horiz</option>
           					<option value="random" <?php if ($angler=="random") echo "selected=\"selected\"";?>>random</option>
           					<option value="updAndDown" <?php if ($angler=="updAndDown") echo "selected=\"selected\"";?>>updAndDown</option>
       					</select>
       					<br/>
       					<label for="placer">Placer: </label>
       					<select name="placer" id="placer" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       					<?php $placer = $dom->getElementsByTagName("placer")->item(0)->nodeValue;?>
           					<option value="centerClump" <?php if ($placer=="centerClump") echo "selected=\"selected\"";?>>centerClump</option>
           					<option value="horizBandAnchoredLeft" <?php if ($placer=="horizBandAnchoredLeft") echo "selected=\"selected\"";?>>horizBandAnchoredLeft</option>
           					<option value="horizLine" <?php if ($placer=="horizLine") echo "selected=\"selected\"";?>>horizLine</option>
           					<option value="swirl" <?php if ($placer=="swirl") echo "selected=\"selected\"";?>>swirl</option>
           					<option value="upperLeft" <?php if ($placer=="upperLeft") echo "selected=\"selected\"";?>>upperLeft</option>
           					<option value="wave" <?php if ($placer=="wave") echo "selected=\"selected\"";?>>wave</option>
       					</select>
					<br/><br/>
       					Background Color: <br/><input class="color {pickerMode:'HSV'}" id="background_color" name="background_color" value="<?php echo $dom->getElementsByTagName("background_color")->item(0)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       					<br/>
       					<label for="color[mode]">Words' Colors: </label><br/>
       					<select name="color[mode]" id="color[mode]" onChange="selected_colors_<?php echo $name;?>(this); display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
           					<option value="twoHuesRandomSats" <?php if ($dom->getElementsByTagName("mode")->item(0)->nodeValue=="twoHuesRandomSats") echo "selected=\"selected\"";?>>twoHuesRandomSats</option>
       						<option value="twoHuesRandomSatsOnWhite" <?php if ($dom->getElementsByTagName("mode")->item(0)->nodeValue=="twoHuesRandomSatsOnWhite") echo "selected=\"selected\"";?>>twoHuesRandomSatsOnWhite</option>
       						<option value="colors" <?php if ($dom->getElementsByTagName("mode")->item(0)->nodeValue=="colors") echo "selected=\"selected\"";?>>Choose colors</option>
       					</select><br/>
       					<span class="color" id ="color_<?php echo $name;?>" style="display: <?php if ($dom->getElementsByTagName("mode")->item(0)->nodeValue=="colors") echo "inline"; else echo "none"?>;">
       						<br/>Color 1: <input class="color {pickerMode:'HSV'}" id="color[0]" name="color[0]" value="<?php echo $dom->getElementsByTagName("color")->item(0)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       						<br/>Color 2: <input class="color {pickerMode:'HSV'}" id="color[1]" name="color[1]" value="<?php echo $dom->getElementsByTagName("color")->item(1)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       						<br/>Color 3: <input class="color {pickerMode:'HSV'}" id="color[2]" name="color[2]" value="<?php echo $dom->getElementsByTagName("color")->item(2)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       						<input type="hidden" id="color[nb_colors]" name = "color[nb_colors]" value="<?php echo $dom->getElementsByTagName("nb_colors")->item(0)->nodeValue;?>"/>
       					</span>
       					<br/>
       					<label for="ulf_case">Upper, lower, first case: </label><br/>
       					<select name="ulf_case" id="ulf_case" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
           					<option value="UpperCase" <?php if ($dom->getElementsByTagName("ulf_case")->item(0)->nodeValue=="UpperCase") echo "selected=\"selected\"";?>>UpperCase</option>
       						<option value="LowerCase" <?php if ($dom->getElementsByTagName("ulf_case")->item(0)->nodeValue=="LowerCase") echo "selected=\"selected\"";?>>LowerCase</option>
       						<option value="FirstCase" <?php if ($dom->getElementsByTagName("ulf_case")->item(0)->nodeValue=="FirstCase") echo "selected=\"selected\"";?>>First letter case</option>
       					</select>
       					<br/><br/>
       					Width: <input class="text" id="width" name="width" size="3" maxlength="4" value="<?php echo $dom->getElementsByTagName("width")->item(0)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       					Height: <input class="text" id="height" name="height" size="3" maxlength="4" value="<?php echo $dom->getElementsByTagName("height")->item(0)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
					</p>
					<p>
						<label for="forbidden_keywords_<?php echo $name;?>">Wants to remove keywords? <br/>type them below seperated by space, commas or return:</label><br/><br/>
						<textarea name="forbidden_keywords_<?php echo $name;?>" id="forbidden_keywords_<?php echo $name;?>" cols="20" rows="5"><?php echo $dom->getElementsByTagName("forbidden_keywords")->item(0)->nodeValue;?></textarea>
       					<br/><br/>
       					<input type="button" value="Remove words" onclick="display_applet(document.getElementById('forbidden_keywords_<?php echo $name;?>').name, document.getElementById('forbidden_keywords_<?php echo $name;?>').value,'<?php echo $path;?>','<?php echo $name;?>');"/>
					</p>
				</form>
			</div>
		
			<div id="applet_<?php echo $name;?>">
                <?php if ($keywords=="") echo ("<h4>Warning !!!!! no keywords are to display.</h4>")?>
				<applet  name = "WordCloud_<?php echo $name;?>" width  = "<?php echo $dom->getElementsByTagName("width")->item(0)->nodeValue;?>px" height = "<?php echo $dom->getElementsByTagName("height")->item(0)->nodeValue;?>px" codebase="<?php echo $path;?>applet/" code="WordCloud.class" archive="WordCloud.jar, jsoup-1.3.3.jar,wordcram.jar,core.jar" hspace="10px" vspace="10px">
					<param name="font_name" value="<?php echo $dom->getElementsByTagName("font_name")->item(0)->nodeValue;?>"/>
					<param name="keywords" value="<?php 
													$ulf_case = $dom->getElementsByTagName("ulf_case")->item(0)->nodeValue;
													$keywords = $dom->getElementsByTagName("keywords")->item(0)->nodeValue;
													if ($ulf_case=="UpperCase")
													{
														echo strtoupper($keywords);
													}
													else if ($ulf_case=="FirstCase")
													{
														echo ucwords($keywords);
													}
													else 
														echo strtolower($keywords);
													?>"/>
					<param name="angler" value="<?php echo $dom->getElementsByTagName("angler")->item(0)->nodeValue;?>"/>
					<param name="placer" value="<?php echo $dom->getElementsByTagName("placer")->item(0)->nodeValue;?>"/>
					<param name="background_color" value="<?php echo $dom->getElementsByTagName("background_color")->item(0)->nodeValue;?>"/>
					<param name="colorer" value="<?php echo $dom->getElementsByTagName("mode")->item(0)->nodeValue;
														if ($dom->getElementsByTagName("mode")->item(0)->nodeValue == "colors")
														{
															for ($i = 0; $i < $dom->getElementsByTagName("nb_colors")->item(0)->nodeValue; $i++)
															{
																echo " ".$dom->getElementsByTagName("color")->item($i)->nodeValue;
															}
														}?>"/>
					<param name="width" value="<?php echo $dom->getElementsByTagName("width")->item(0)->nodeValue;?>"/>
					<param name="height" value="<?php echo $dom->getElementsByTagName("height")->item(0)->nodeValue;?>"/>
				</applet>
			</div>
		</div>

<?php 
}
?>


<?php
function create_wordcloud_weights($name, $path = "embed_applet/", $input_keywords_weights = "Hello 2 World 2")
{
	//create the XML file of settings.
	if (!file_exists($path."data/settings_".$name.".xml"))
	{	
		$dom = new DOMDocument('1.0');
	
		$root = $dom->createElement('root');
		$dom->appendChild($root);
	
		$element = $dom->createElement('keywords', 'Hello 2 World 2');
		$root->appendChild($element);
	
		$element = $dom->createElement('forbidden_keywords', '');
		$root->appendChild($element);
	
		$element = $dom->createElement('font_name', 'Minyn.ttf');
		$root->appendChild($element);
	
		$element = $dom->createElement('angler', 'mostlyHoriz');
		$root->appendChild($element);
	
		$element = $dom->createElement('placer', 'horizLine');
		$root->appendChild($element);
	
		$element = $dom->createElement('background_color', 'FFFFFF');
		$root->appendChild($element);
	
		$color = $dom->createElement('color_settings');
		$root->appendChild($color);
	
		$element = $dom->createElement('mode', 'twoHuesRandomSats');
		$color->appendChild($element);
	
		$element = $dom->createElement('nb_colors', '3');
		$color->appendChild($element);
	
		$colors = $dom->createElement('colors');
		$color->appendChild($colors);
	
		$element = $dom->createElement('color', 'B82D11');
		$colors->appendChild($element);
	
		$element = $dom->createElement('color', '2616B8');
		$colors->appendChild($element);
	
		$element = $dom->createElement('color', '000000');
		$colors->appendChild($element);
	
		$element = $dom->createElement('ulf_case', 'LowerCase');
		$root->appendChild($element);
	
		$element = $dom->createElement('width', '800');
		$root->appendChild($element);
	
		$element = $dom->createElement('height', '600');
		$root->appendChild($element);
	
		$dom->save($path."data/settings_".$name.".xml");
	
		//echo $dom->saveXML();
	}

	//Text-mining and store keywords in the settings.xml file.
	$dom = new DomDocument();
	$dom->load($path."data/settings_".$name.".xml");

	$dom->getElementsByTagName("keywords")->item(0)->nodeValue = $input_keywords_weights;
	
	$dom->getElementsByTagName("forbidden_keywords")->item(0)->nodeValue = "";
	
	$dom->save($path."data/settings_".$name.".xml");
	
?>
	<div id="<?php echo $name;?>" name="<?php echo $name;?>">
		<script type="text/javascript" src="<?php echo $path;?>js/jscolor/jscolor.js"></script>
   		<script type="text/javascript" src="<?php echo $path;?>js/loadapplet.js"></script>
  		<script language="JavaScript" type="text/javascript">
			function selected_colors_<?php echo $name;?>(elem)
			{
				if (elem.value=="colors")
				{
					document.getElementById("color_<?php echo $name;?>").style.display = 'inline';
				}
				else 
				{
					document.getElementById("color_<?php echo $name;?>").style.display = 'none';
				}
			}
		</script>
		<?php if ($input_keywords_weights=="") echo ("Warning !!!!! no keywords are to display.")?>
			<div id="applet_<?php echo $name;?>">
				<applet  name = "WordCloud_<?php echo $name;?>" width  = "<?php echo $dom->getElementsByTagName("width")->item(0)->nodeValue;?>px" height = "<?php echo $dom->getElementsByTagName("height")->item(0)->nodeValue;?>px" codebase="<?php echo $path;?>applet/" code="WordCloud.class" archive="WordCloud.jar, jsoup-1.3.3.jar,wordcram.jar,core.jar" hspace="10px" vspace="10px">
					<param name="font_name" value="<?php echo $dom->getElementsByTagName("font_name")->item(0)->nodeValue;?>"/>
					<param name="keywords" value="<?php 
													$ulf_case = $dom->getElementsByTagName("ulf_case")->item(0)->nodeValue;
													$keywords = $dom->getElementsByTagName("keywords")->item(0)->nodeValue;
													if ($ulf_case=="UpperCase")
													{
														echo strtoupper($keywords);
													}
													else if ($ulf_case=="FirstCase")
													{
														echo ucwords($keywords);
													}
													else 
														echo strtolower($keywords);
													?>"/>
					<param name="angler" value="<?php echo $dom->getElementsByTagName("angler")->item(0)->nodeValue;?>"/>
					<param name="placer" value="<?php echo $dom->getElementsByTagName("placer")->item(0)->nodeValue;?>"/>
					<param name="background_color" value="<?php echo $dom->getElementsByTagName("background_color")->item(0)->nodeValue;?>"/>
					<param name="colorer" value="<?php echo $dom->getElementsByTagName("mode")->item(0)->nodeValue;
														if ($dom->getElementsByTagName("mode")->item(0)->nodeValue == "colors")
														{
															for ($i = 0; $i < $dom->getElementsByTagName("nb_colors")->item(0)->nodeValue; $i++)
															{
																echo " ".$dom->getElementsByTagName("color")->item($i)->nodeValue;
															}
														}?>"/>
					<param name="width" value="<?php echo $dom->getElementsByTagName("width")->item(0)->nodeValue;?>"/>
					<param name="height" value="<?php echo $dom->getElementsByTagName("height")->item(0)->nodeValue;?>"/>
				</applet>
			</div>
				<div class="form" id="form_applet_<?php echo $name;?>">
				<form method="post">
					<p>
						<label for="font_name">Font: </label>
       					<select name="font_name" id="font_name" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       						<?php	 
								if ($handle = opendir($path.'applet/data')) 
								{
									while (false !== ($file = readdir($handle))) 
									{
										if (eregi("\.ttf",$file))
										{
											?> <option value="<?php echo $file;?>" <?php if ($dom->getElementsByTagName("font_name")->item(0)->nodeValue==$file) echo "selected=\"selected\"";?>><?php echo substr($file, 0,-4);?></option>
								  <?php }
   									 }
   									 closedir($handle);
								} 
       						?>
          					</select>
       				 	<br/>
       					<label for="angler">Angler: </label>
       					<select name="angler" id="angler" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       					<?php $angler = $dom->getElementsByTagName("angler")->item(0)->nodeValue;?>
          					<option value="mostlyHoriz" <?php if ($angler=="mostlyHoriz") echo "selected=\"selected\"";?>>mostlyHoriz</option>
          					<option value="heaped" <?php if ($angler=="heaped") echo "selected=\"selected\"";?>>heaped</option>
           					<option value="hexes" <?php if ($angler=="hexes") echo "selected=\"selected\"";?>>hexes</option>
           					<option value="horiz" <?php if ($angler=="horiz") echo "selected=\"selected\"";?>>horiz</option>
           					<option value="random" <?php if ($angler=="random") echo "selected=\"selected\"";?>>random</option>
           					<option value="updAndDown" <?php if ($angler=="updAndDown") echo "selected=\"selected\"";?>>updAndDown</option>
       					</select>
       					<br/>
       					<label for="placer">Placer: </label>
       					<select name="placer" id="placer" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       					<?php $placer = $dom->getElementsByTagName("placer")->item(0)->nodeValue;?>
           					<option value="centerClump" <?php if ($placer=="centerClump") echo "selected=\"selected\"";?>>centerClump</option>
           					<option value="horizBandAnchoredLeft" <?php if ($placer=="horizBandAnchoredLeft") echo "selected=\"selected\"";?>>horizBandAnchoredLeft</option>
           					<option value="horizLine" <?php if ($placer=="horizLine") echo "selected=\"selected\"";?>>horizLine</option>
           					<option value="swirl" <?php if ($placer=="swirl") echo "selected=\"selected\"";?>>swirl</option>
           					<option value="upperLeft" <?php if ($placer=="upperLeft") echo "selected=\"selected\"";?>>upperLeft</option>
           					<option value="wave" <?php if ($placer=="wave") echo "selected=\"selected\"";?>>wave</option>
       					</select>
					<br/><br/>
       					Background Color: <br/><input class="color {pickerMode:'HSV'}" id="background_color" name="background_color" value="<?php echo $dom->getElementsByTagName("background_color")->item(0)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       					<br/>
       					<label for="color[mode]">Words' Colors: </label><br/>
       					<select name="color[mode]" id="color[mode]" onChange="selected_colors_<?php echo $name;?>(this); display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
           					<option value="twoHuesRandomSats" <?php if ($dom->getElementsByTagName("mode")->item(0)->nodeValue=="twoHuesRandomSats") echo "selected=\"selected\"";?>>twoHuesRandomSats</option>
       						<option value="twoHuesRandomSatsOnWhite" <?php if ($dom->getElementsByTagName("mode")->item(0)->nodeValue=="twoHuesRandomSatsOnWhite") echo "selected=\"selected\"";?>>twoHuesRandomSatsOnWhite</option>
       						<option value="colors" <?php if ($dom->getElementsByTagName("mode")->item(0)->nodeValue=="colors") echo "selected=\"selected\"";?>>Choose colors</option>
       					</select><br/>
       					<span class="color" id ="color_<?php echo $name;?>" style="display: <?php if ($dom->getElementsByTagName("mode")->item(0)->nodeValue=="colors") echo "inline"; else echo "none"?>;">
       						Color 1: <input class="color {pickerMode:'HSV'}" id="color[0]" name="color[0]" value="<?php echo $dom->getElementsByTagName("color")->item(0)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       						<br/>Color 2: <input class="color {pickerMode:'HSV'}" id="color[1]" name="color[1]" value="<?php echo $dom->getElementsByTagName("color")->item(1)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       						<br/>Color 3: <input class="color {pickerMode:'HSV'}" id="color[2]" name="color[2]" value="<?php echo $dom->getElementsByTagName("color")->item(2)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       						<input type="hidden" id="color[nb_colors]" name = "color[nb_colors]" value="<?php echo $dom->getElementsByTagName("nb_colors")->item(0)->nodeValue;?>"/>
       					</span>
       					<br/><br/>
       					<label for="ulf_case">Upper, lower, first case: </label><br/>
       					<select name="ulf_case" id="ulf_case" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
           					<option value="UpperCase" <?php if ($dom->getElementsByTagName("ulf_case")->item(0)->nodeValue=="UpperCase") echo "selected=\"selected\"";?>>UpperCase</option>
       						<option value="LowerCase" <?php if ($dom->getElementsByTagName("ulf_case")->item(0)->nodeValue=="LowerCase") echo "selected=\"selected\"";?>>LowerCase</option>
       						<option value="FirstCase" <?php if ($dom->getElementsByTagName("ulf_case")->item(0)->nodeValue=="FirstCase") echo "selected=\"selected\"";?>>First letter case</option>
       					</select>
       					<br/><br/>
       					Width: <input class="text" id="width" name="width" size="3" maxlength="4" value="<?php echo $dom->getElementsByTagName("width")->item(0)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
       					Height: <input class="text" id="height" name="height" size="3" maxlength="4" value="<?php echo $dom->getElementsByTagName("height")->item(0)->nodeValue; ?>" onchange="display_applet(this.name,this.value,'<?php echo $path;?>','<?php echo $name;?>');">
					</p>
					<p>
						<label for="forbidden_keywords_<?php echo $name;?>">Wants to remove keywords? <br/>type them below seperated by space, commas or return:</label><br/>
						<textarea name="forbidden_keywords_<?php echo $name;?>" id="forbidden_keywords_<?php echo $name;?>" cols="20" rows="5"><?php echo $dom->getElementsByTagName("forbidden_keywords")->item(0)->nodeValue;?></textarea>
       					<br/><br/>
       					<input type="button" value="Remove words" onclick="display_applet(document.getElementById('forbidden_keywords_<?php echo $name;?>').name, document.getElementById('forbidden_keywords_<?php echo $name;?>').value,'<?php echo $path;?>','<?php echo $name;?>');"/>
					</p>
				</form>
			</div>
		</div>

<?php 
}
?>