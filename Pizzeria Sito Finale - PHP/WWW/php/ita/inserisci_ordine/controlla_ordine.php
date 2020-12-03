<?php
	session_start();
	header('content-Type: text/html;charset=UTF-8');

	require_once("../shared/credenziali_db.php");
?>
<?php
	if(isset($_SESSION['utente']['login'],$_SESSION['utente']['nome']) && !empty($_SESSION['utente']['login']) && !empty($_SESSION['utente']['nome']))
	{
			if(isset($_SESSION['cart'],$_POST['giorno'],$_POST['ora'],$_POST['via'],$_POST['ncivico'],$_POST['cap'],$_POST['citta'])
			  && !empty($_SESSION['cart']) && !empty($_POST['giorno']) && !empty($_POST['ora']) && !empty($_POST['via']) && !empty($_POST['ncivico']) && !empty($_POST['cap']) && !empty($_POST['citta']))
			{
				$_SESSION['order'] = $_SESSION['cart'];
				$_SESSION['consegna']['giorno'] = $_POST['giorno'];
				$_SESSION['consegna']['ora'] = $_POST['ora']; 
				$_SESSION['consegna']['via'] = $_POST['via'];
				$_SESSION['consegna']['ncivico'] = $_POST['ncivico'];
				$_SESSION['consegna']['cap'] = $_POST['cap'];
				$_SESSION['consegna']['citta'] = $_POST['citta'];
			}


		if(isset($_SESSION['order'],$_SESSION['consegna']) && !empty($_SESSION['order']) && !empty($_SESSION['consegna']) && !empty($_SESSION['consegna']['giorno']) && !empty($_SESSION['consegna']['ora']))
		{
?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
			<head>
				<meta http-equiv="content-type" content="text/html; charset=utf-8" />
				<meta name="author" content="Your Company" />
				<meta name="keywords" content="Your Company" />
				<meta name="description" content="Your Company" />
				<meta name="robots" content="all" />
			<!-- inzio script aggiunti -->	
				<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
				<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
				<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/ordini/formvalidate_accessoAR.js"></script>
				<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/ordini/ordine.js"></script>
				<script>
					$(document).ready(function(){
					$("#div_ordine").load("ordine.php");
				});
				</script>
				<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/global-2.css">
				<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/tabella.css">
				

			<!-- fine script aggiunti -->	
				<title>Chalk Rock</title>
			</head>

			<body>
			<div id="wrapper">
				<div id="header">
					<h1><span class="black">Pizzeria</span> <span class="orange">Online</span></h1>
					<h2>Your Slogan</h2>
				</div>
				
			<div id="navi">
					<ul>
						<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/home/">Home</a></li>
						<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/lista-pizze/">Lista Pizze</a></li>
						<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/carrello/">Carrello</a></li>
						<?php
						if(isset($_SESSION['utente']['login'],$_SESSION['utente']['nome']) && !empty($_SESSION['utente']['nome']) && !empty($_SESSION['utente']['login']))

						{	
							echo "<li><a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/ordini/\">Ordini</a></li>";
						}
						?>

						<?php
						if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
						{	
							echo "<li><a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_pizze_amm.php\">Amministratore</a></li>";
						}
						?>

						<li style="float:right;">
						<?php 
						if(isset($_GET['ordina']))
						{
							switch($_GET['ordina'])
							{
							case "tipo":
								$ordina = "tipo";
							break;
							case "prezzo":
								$ordina = "prezzo";
							break;
							default: $ordina = "tipo";			
							}
						}
						else
						{
							$ordina = "tipo";
						}
						echo "
						<form action=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/lista-pizze/\" method=\"get\">
							Cerca Pizza: <input type=\"search\" name=\"string\">
							<input type=\"hidden\" name=\"ordina\" value=\"$ordina\" />
							<input type=\"submit\">
						</form>
						";
						?>
						</li>
					</ul>
				</div>
				<div id="subnavi">
					<ul>
						<!-- <li class="active"><a href="#">Item</a></li> -->
					<?php
					if(isset($_SESSION['utente']['login'],$_SESSION['utente']['nome']) && !empty($_SESSION['utente']['nome']) && !empty($_SESSION['utente']['login']))
					{	
						echo "<li  class=\"active\"><a href=\"../shared/log_out.php?doLogout=true\">Log out {$_SESSION['utente']['nome']}</a></li>";
					}
					else
					{
						echo "
							<li><a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso/\">Accedi</a></li>
						";
					}
					?>
					<li style="float:right;"><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/registrazione/">Registrati</a></li>

					</ul>
				</div>
				
				<div id="main">
				 
					<div id="content" style="width:95%">
						<h1><span> Ordine </span></h1>
						<div id="div_ordine">
							<!-- inserisco odine.php con la funzione jquery load -->
						</div>
						<a style="float:right" href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/home/">back to Home</a>
					</div>
				</div>
				<div id="footer">
					<p>© Copyright 2012 yourname.com. All Rights Reserved.  |  Design by <a href="http://www.schlafzimmerkomplett.net/" target="_blank">Schlafzimmer komplett</a></p>
				</div>
				<div class="cleaner"></div>
			</div>
			</body>
			</html>
<?php
		}
		else
		{
			header("Location: http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/carrello/opzioni_consegna.php");
		}
	}
	else
	{
		require("../shared/form_accesso.php");
	}
?>