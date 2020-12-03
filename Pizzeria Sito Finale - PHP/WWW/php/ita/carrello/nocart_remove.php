<?php
session_start();
?>
<?php

if(isset($_GET['indice']))
{
	$indice = $_GET['indice'];
	
	if(isset($_SESSION['nocart'][$indice]))
	{
		unset($_SESSION['nocart'][$indice]);
		//echo'dopo:';print_r($_SESSION['cart']);
		if(empty($_SESSION['nocart']))
		{
			unset($_SESSION['nocart']);
		}
		
	}
}

?>

<?php
/*
echo'prima:';print_r($_SESSION['cart']);
if(isset($_GET['indice'],$_GET['tipo']))
{
	echo'<br>';
	$indice = $_GET['indice'];
	$tipo = $_GET['tipo'];
	
	if(isset($_SESSION['nocart'][$indice][$tipo]))
	{
		unset($_SESSION['nocart'][$indice]);
		//echo'dopo:';print_r($_SESSION['cart']);
		if(empty($_SESSION['nocart']))
		{
			unset($_SESSION['nocart']);
		}
		
	}
}
*/
?>