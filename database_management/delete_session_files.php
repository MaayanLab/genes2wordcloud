<?php
#!/usr/local/php5/bin/php-cgi -q

if ($handle = opendir('../data/session_files')) 
{
	while (false !== ($file = readdir($handle))) 
	{
		if (eregi("\.txt",$file))
		{
			//If last access older than 2 days.
			if (fileatime("../data/session_files/".$file)< (time()-2*24*3600))
			//if (fileatime("../data/session_files/".$file)< (time()))
			{
				echo("File ".$file." when last access was ".date('l jS \of F Y h:i:s A',fileatime("../data/session_files/".$file)));
				unlink("../data/session_files/".$file);
				echo(" has been deleted.\n");
			}
		}
   	}
   	closedir($handle);
} 

?>
