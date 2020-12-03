<?php
	session_start();

	//echo'prima:';print_r($_SESSION['cart']);
	if(isset($_GET['tipo']))
	{
		echo'<br>';
		$tipo = $_GET['tipo'];
		
		if(isset($_SESSION['cart'][$tipo]))
		{
			unset($_SESSION['cart'][$tipo]);
			//echo'dopo:';print_r($_SESSION['cart']);
			if(empty($_SESSION['cart']))
			{
				unset($_SESSION['cart']);
			}
			
		}
	}

?>