<?php 
	session_start();
	if (file_exists("data/session_files/text_".$_SESSION["sessionid"].".txt")) 
		unlink("data/session_files/text_".$_SESSION["sessionid"].".txt");
	session_destroy(); 
	//echo ("logout");
?>
<html>
	<meta http-equiv="cache-control" content="no-cache"/>
	<meta http-equiv="PRAGMA" CONTENT="NO-CACHE">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="Genes2WordCloud"/>
	<meta name="keywords" content="gene, wordcloud, visualization tool, mount sinai, systems biology, genetics, genes, protein, web application."/>
	<meta name="author" content="Caroline Baroukh"/>
	<meta name="location" content="Mount Sinai School of Medicine,New York"/>
	<title>Genes2WordCloud</title>
 	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="Style/index.css" />
   <body style="margin:auto;" onload="setTimeout('load_index()', 5000);">
   		<script language="JavaScript" type="text/javascript">
		function load_index()
		{
			location.href= ('index.php');
		}
	</script>
	<p id="logout">
		You were automatically logged-out of your session since you have been inactive for more than an hour. <br/>
		You will be automatically redirected to the index page in a few seconds.
	</p>
   </body>
 </html>
