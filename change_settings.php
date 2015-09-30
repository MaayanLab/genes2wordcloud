<?php 

session_start();

if (isset($_POST["font_name"]))
	$_SESSION["font_name"] = $_POST["font_name"];
if (isset($_POST["angler"]))
	$_SESSION["angler"] = $_POST["angler"];
if (isset($_POST["placer"]))
	$_SESSION["placer"] = $_POST["placer"];
if (isset($_POST["background_color"]))
	$_SESSION["background_color"] = $_POST["background_color"];
if (isset($_POST["color"]["mode"]))
	$_SESSION["color"]["mode"] = $_POST["color"]["mode"];
if (isset($_POST["color"]["nb_colors"]))
	$_SESSION["color"]["nb_colors"] = $_POST["color"]["nb_colors"];	
if (isset($_POST["color"][1]))
	$_SESSION["color"][1] = $_POST["color"][1];
if (isset($_POST["color"][2]))
	$_SESSION["color"][2] = $_POST["color"][2];
if (isset($_POST["color"][3]))
	$_SESSION["color"][3] = $_POST["color"][3];
if (isset($_POST["ulf_case"]))
	$_SESSION["ulf_case"] = $_POST["ulf_case"];	

?>
<?php if ($_SESSION["keywords"]=="") echo ("<h4>Warning !!!!! no keywords were found with your search.</h4>")?>
<applet  name = "WordCloud" id = "WordCloud" mayscript="true" width="<?php echo $_SESSION["width"];?>px" height="<?php echo $_SESSION["height"];?>px" codebase="applet/" code="WordCloud.class" archive="WordCloud.jar, jsoup-1.3.3.jar,WordCram.jar,core.jar,plugin.jar" hspace="10px" vspace="10px">
	<param name="font_name" value="<?php echo $_SESSION["font_name"];?>"/>
	<param name="keywords" value="<?php 
									if ($_SESSION["ulf_case"]=="UpperCase")
									{
										echo strtoupper($_SESSION["keywords"]);
									}
									else if ($_SESSION["ulf_case"]=="FirstCase")
									{
										echo ucwords($_SESSION["keywords"]);
									}
									else 
										echo ($_SESSION["keywords"]);
									?>"/>
	<param name="angler" value="<?php echo $_SESSION["angler"];?>"/>
	<param name="placer" value="<?php echo $_SESSION["placer"];?>"/>
	<param name="background_color" value="<?php echo $_SESSION["background_color"];?>"/>
	<param name="colorer" value="<?php echo $_SESSION["color"]["mode"];
										if ($_SESSION["color"]["mode"] == "colors")
										{
											for ($i = 1; $i < $_SESSION["color"]["nb_colors"] +1; $i++)
											{
												echo " ".$_SESSION["color"][$i];
											}
										}?>"/>
	<param name="width" value="<?php echo $_SESSION["width"];?>"/>
	<param name="height" value="<?php echo $_SESSION["height"];?>"/>
</applet>