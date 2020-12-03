<?php
	try
	{
		session_start();

		header('content-Type: text/html;charset=UTF-8');

		//require_once("../shared/credenziali_db.php");

		//$_SESSION['amministratore'] = true;

		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
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
				<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/accesso_amm/form_insert_pizze.js"></script>
					
				<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/form.css">
				<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/global-3.css">
				<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/tabella.css"> --> 
				<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/lista-pizze/infoaggiungi.css"> -->
				<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/lista-pizze/lettere.css"> -->
				
				<style type="text/css">

								label{float:left;width:100px;padding:0 1em;text-align:right;}
								
								div.prova label{display:block;width:200px;padding-left:5em;text-align:left;}

								label:before{content:"* ";}

								form{margin:0;padding:0;}
								fieldset{margin:1em 0;border:none;border-top:1px solid #ccc;}
								legend{margin:1em 0;padding:0 .5em;color:#036;background:transparent;font-size:1.3em;font-weight:bold;}
								fieldset div select{padding:1px;}
								div.fm-multi div{margin:5px 0;}
								div.fm-multi input{width:1em;}
								fieldset div.fm-req{font-weight:bold;}
								fieldset div.fm-req label:before{content:"* ";}
								#container{margin:0 auto;padding:1em;width:350px;text-align:left;}
								p#fm-intro{margin:0;}
								
				</style>
							
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
						<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/index.php">Amministratore</a></li>
						<li class="active"><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/form_insert_pizze.php">Inserisci Pizza</a></li>
						<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_pizze_amm.php">Lista Pizze DB</a></li>
						<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_utenti_amm.php">Lista Utenti DB</a></li>

						<li style="float:right;">
						<?php
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
						echo "<li class=\"active\"><a href=\"../shared/log_out.php?doLogout=true\">Log out {$_SESSION['utente']['nome']}</a></li>";
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
						<h1>Inserisci Nuova Pizza</h1>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- inserire codice php -->

						<?php
									echo "	
										<div id=\"contenitore\">
											<form action=\"\" method=\"post\" id=\"form_pizze\">
												<fieldset class=\"tratto\">
												<legend>Dati Pizza</legend>
												<div class=\"fm-req\">
													<label for=\"tipo\">Tipo</label>
													<input type=\"text\" name=\"tipo\" id=\"tipo\">
												</div>
												<div class=\"fm-req\">
													<label for=\"prezzo\">Prezzo</label>
													<input type=\"text\" name=\"prezzo\" id=\"prezzo\">
												</div>
												<div class=\"fm-req\">
													<label for=\"ingredienti\">Ingredienti</label>
													<input type=\"text\" name=\"ingredienti\" id=\"ingredienti\">
													<p style=\"font-weight:bold\">* separare gli ingredienti da una virgola</p>
												</div>
												</fieldset>
												<div id=\"fm-submit\" class=\"fm-req\">
												<input type=\"submit\" value=\"inserisci\">
												</div>
											</form>
										</div>
										<div id=\"info\"></div>	
									";	
						?>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->			
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
			require_once("../shared/form_accesso.php");
		}
	}
	catch(PDOException $e)
	{
		echo "error: ".$e->getMessage();
		require_once("../shared/errore_server.php");
	}
	catch(Exception $e)
	{
		echo "error: ".$e->getMessage();
		require_once("../shared/errore_server.php");
	}
?>