<?php
try
{
	session_start();

	if(isset($_GET['tipo'],$_GET['quantita']))
	{
		$tipo = $_GET['tipo'];
		$quantita = $_GET['quantita'];
		
		if(isset($_SESSION['cart'][$tipo]))
		{
			if($quantita > 0)
			{
				$_SESSION['cart'][$tipo]['quantita'] = $quantita;
			}
			else if($quantita==0)
			{
				unset($_SESSION['cart'][$tipo]);
				if(empty($_SESSION['cart']))
				{
					unset($_SESSION['cart']);
				}
			}
		}
	}
}
catch(PDOException $e)
	{
		if(isset($dbh))
		{
			$dbh = null;
		}
		echo "errore: " . $e -> getMessage();
	}
	catch(Exception $e)
	{
		if(isset($dbh))
		{
			$dbh = null;
		}
		echo "errore: " . $e -> getMessage();
	}
?>