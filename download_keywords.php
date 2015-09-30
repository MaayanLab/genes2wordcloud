<?php 
	session_start();
	
	if (!isset($_SESSION['sessionid']))
	{
		$_SESSION['sessionid'] = session_id();
	}
	
	include_once("database_connection.php");
	
	$fw = fopen("download/keywords/keywords_list_".$_SESSION["sessionid"].".csv","w");
	
    
    if (isset($_SESSION["keywords"]))
    {
        $keywords = explode(" ",$_SESSION["keywords"]);
        for($i=0;$i<floor(sizeof($keywords)/2);$i= $i+2)
        {
            fwrite($fw,$keywords[$i]."\t".$keywords[$i+1]."\n");
        }
    }
    fclose($fw);
    
  
	 
	 header( 'Location: download/keywords/keywords_list_'.$_SESSION["sessionid"].'.csv') ;
	
?>