<?php
    session_start();
    if (isset($_GET["width"]))
    {
        $_SESSION["width"] = round($_GET["width"]*0.70*0.75-30);
        $_SESSION["height"] = round($_SESSION["width"]*0.85);
    }
    else
    {
        $_SESSION["width"] = 700;
        $_SESSION["height"] = 600;
    }
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