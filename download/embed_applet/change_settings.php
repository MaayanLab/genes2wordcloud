<?php 

$path = $_POST["path"];
$name = $_POST["name"];

$dom = new DomDocument();
$dom->load("data/settings_".$name.".xml");

if (isset($_POST["font_name"]))
	$dom->getElementsByTagName("font_name")->item(0)->nodeValue = $_POST["font_name"];
if (isset($_POST["angler"]))
	$dom->getElementsByTagName("angler")->item(0)->nodeValue = $_POST["angler"];
if (isset($_POST["placer"]))
	$dom->getElementsByTagName("placer")->item(0)->nodeValue = $_POST["placer"];
if (isset($_POST["background_color"]))
	$dom->getElementsByTagName("background_color")->item(0)->nodeValue = $_POST["background_color"];
if (isset($_POST["color"]["mode"]))
	$dom->getElementsByTagName("mode")->item(0)->nodeValue = $_POST["color"]["mode"];
if (isset($_POST["color"]["nb_colors"]))
	$dom->getElementsByTagName("nb_colors")->item(0)->nodeValue = $_POST["color"]["nb_colors"];	
if (isset($_POST["color"][0]))
	$dom->getElementsByTagName("color")->item(0)->nodeValue = $_POST["color"][0];
if (isset($_POST["color"][1]))
	$dom->getElementsByTagName("color")->item(1)->nodeValue = $_POST["color"][1];
if (isset($_POST["color"][2]))
	$dom->getElementsByTagName("color")->item(2)->nodeValue = $_POST["color"][2];
if (isset($_POST["ulf_case"]))
	$dom->getElementsByTagName("ulf_case")->item(0)->nodeValue = $_POST["ulf_case"];
if (isset($_POST["width"]))
	$dom->getElementsByTagName("width")->item(0)->nodeValue = $_POST["width"];
if (isset($_POST["height"]))
	$dom->getElementsByTagName("height")->item(0)->nodeValue = $_POST["height"];
if (isset($_POST["forbidden_keywords_".$name]))
	$dom->getElementsByTagName("forbidden_keywords")->item(0)->nodeValue .= "\t".strtolower($_POST["forbidden_keywords_".$name]);

//Remove the unwanted words
$forbidden_words = preg_split("#[;:\s]+#",strtolower($dom->getElementsByTagName("forbidden_keywords")->item(0)->nodeValue));
	
$words_and_occurences = explode(" ", strtolower($dom->getElementsByTagName("keywords")->item(0)->nodeValue));

$string_output = "";
for($i=0;$i<sizeof($words_and_occurences)/2;$i++)
{
	if (!in_array($words_and_occurences[2*$i], $forbidden_words))
	{
		if (!preg_match("#[\s]+#",$words_and_occurences[2*$i])&& $words_and_occurences[2*$i] !="")
		$string_output .=$words_and_occurences[2*$i]." ".$words_and_occurences[2*$i+1]." ";
	}
}
$string_output = substr($string_output, 0,-1);

$dom->getElementsByTagName("keywords")->item(0)->nodeValue = $string_output;

$dom->save("data/settings_".$name.".xml");

?>

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