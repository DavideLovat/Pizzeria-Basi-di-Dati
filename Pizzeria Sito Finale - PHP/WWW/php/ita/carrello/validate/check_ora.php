<?php
echo "true";
//print_r($_GET);	
/*if(isset($_GET['ora'],$_GET['giorno']) && !empty($_GET['ora']) && !empty($_GET['giorno']))
{
	$date = $_GET['giorno'];
	$ora = $_GET['ora'];
	$date_time = $date." ".$ora;
	//echo"<br>$date_time<br>";
	$array_date = date_parse($date_time);
	$today = getdate();
	
	//print_r($array_date);
	//print_r($today);
	
	if($array_date['year']==$today['year'])
	{
		if($array_date['month']==$today['mon'])
		{
			if( $array_date['day']==$today['mday'])
			{
				if($array_date['hour']==$today['hours'])
				{
					if($array_date['minute']>=$today['minutes'])
					{
						echo 'true';
						exit;
					}
					else
					{
						echo 'false';
						exit;
					}

				}
				else if($array_date['hour']>$today['hours'])
				{
					echo 'true';
					exit;
				}
				else
				{
					echo 'false';
					exit;

				}
			}
			else if( $array_date['day']<$today['mday'])
			{
				echo 'false';
				exit;
			}
			else if($array_date['day']>$today['mday'])
			{
				echo 'true';
				exit;
			}
			
		}
		else if($array_date['month']>$today['mon'])
		{
			echo 'true';
			exit;
		}
		else
		{
			echo 'false';
			exit;
		}
	}
	else if($array_date['year']>$today['year'])
	{
		echo 'true';
			exit;
	}
	else
	{
		echo 'false';
			exit;
	}
}
else
{
	echo 'false';
	exit;
}*/


?>