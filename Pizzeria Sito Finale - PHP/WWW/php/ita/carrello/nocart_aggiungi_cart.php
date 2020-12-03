<?php
	session_start();
?>

<?php
	if(isset($_GET['indice'],$_GET['tipo']))
	{
		$indice = $_GET['indice'];
		$tipo = $_GET['tipo'];

		if(isset($_SESSION['nocart'][$indice]))
		{
			$nocart_tipo = $_SESSION['nocart'][$indice][$tipo]['tipo'];
			$nocart_prezzo = $_SESSION['nocart'][$indice][$tipo]['prezzo'];
			$nocart_prezzo_lista = $_SESSION['nocart'][$indice][$tipo]['prezzo_lista'];
			$nocart_quantita = $_SESSION['nocart'][$indice][$tipo]['quantita'];

			if(isset($_SESSION['cart'][$tipo]) && !empty($_SESSION['cart'][$tipo]))
			{ 
				$sum = $_SESSION['cart'][$tipo]['quantita'] + $nocart_quantita;
				if($sum > 100)
				{
					$_SESSION['cart'][$tipo]['quantita'] = 100;
				}
				else
				{
					$_SESSION['cart'][$tipo]['quantita'] = $_SESSION['cart'][$tipo]['quantita'] + $nocart_quantita;
				}
			}
			else
			{
				$_SESSION['cart'][$tipo]['tipo'] = $nocart_tipo;
				$_SESSION['cart'][$tipo]['prezzo'] = $nocart_prezzo;
				$_SESSION['cart'][$tipo]['prezzo_lista'] = $nocart_prezzo_lista;
				$_SESSION['cart'][$tipo]['quantita'] = $nocart_quantita;
			}
			unset($_SESSION['nocart'][$indice]);
			
			if(empty($_SESSION['nocart']))
			{
				unset($_SESSION['nocart']);
			}
		}
	}


?>