<?php
	try
	{
		session_start();
		header('content-Type: text/html;charset=UTF-8');
		require_once("../shared/credenziali_db.php");
	
		//begin da eliminare
		/*
		$_SESSION['amministratore'] = true;
		$_GET['login'] = "dlovat";
		*/
		//end

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
				<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/accesso_amm/form_mod_utenti.js"></script>	
				
				<style type="text/css">
					label { display: inline-block; width: 200px; text-align:right;  }
					.info_ajax {display:inline;}
					#contenitore{float:left;}
					#div_submit{text-align:right;}
					#form_ordini{float:right;text-align:right;}
				</style>
								
				<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/form.css">
				<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/global-3.css">
				
				<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/tabella.css"> --> 
				<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/lista-pizze/infoaggiungi.css"> -->
				<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/lista-pizze/lettere.css"> -->
				
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
						<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/form_insert_pizze.php">Inserisci Pizza</a></li>
						<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_pizze_amm.php">Lista Pizze DB</a></li>
						<li class="active"><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_utenti_amm.php">Lista Utenti DB</a></li>

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
						<h1>Modifica Pizza</h1>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- inserire codice php -->
	
<?php		
			if(isset($_GET['login']) && !empty($_GET['login']))
			{
				$login = $_GET['login'];
				$sql_sel1 = "
					select *
					from utenti,indirizzi
					where login = :login and codind = idind 
				";
				
				$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
				$dbh = new PDO($dsn);
				
				$sth = $dbh -> prepare($sql_sel1);
				$sth -> bindParam(':login',$login,PDO::PARAM_STR);
				if($sth -> execute())
				{
					if($result = $sth -> fetch(PDO::FETCH_ASSOC))
					{
						echo "
								<div id=\"contenitore\">
									<form action=\"\" method=\"get\" id=\"form_mod_utenti\">
										<div id=\"div_utente\">
											<div id=\"div_login\">
												<label for=\"login\">Login</label>
												<input type=\"text\" name=\"login\" id=\"login\" value=\"{$result['login']}\" readonly>
												<label for=\"new_login\">Nuovo Login</label>
												<input type=\"text\" name=\"new_login\" id=\"new_login\" class=\"new_input\" value=\"\">
												<div class=\"info_ajax\" id=\"info_login\"></div>
											</div>
											<div id=\"div_password\">
												<label for=\"password\">Password</label>
												<input type=\"text\" name=\"password\" id=\"password\" value=\"{$result['password']}\" readonly>
												<label for=\"new_password\">Nuova Password</label>
												<input type=\"text\" name=\"new_password\" id=\"new_password\" class=\"new_input\" value=\"\">
											</div>
											<div id=\"div_nome\">
												<label for=\"nome\">Nome</label>
												<input type=\"text\" name=\"nome\" id=\"nome\" value=\"{$result['nome']}\" readonly>
												<label for=\"new_nome\">Nuovo Nome</label>
												<input type=\"text\" name=\"new_nome\" id=\"new_nome\" class=\"new_input\" value=\"\">
											</div>
											<div id=\"div_cognome\">
												<label for=\"cognome\">Cognome</label>
												<input type=\"text\" name=\"cognome\" id=\"cognome\" value=\"{$result['cognome']}\" readonly>
												<label for=\"new_cognome\">Nuovo Cognome</label>
												<input type=\"text\" name=\"new_cognome\" id=\"new_cognome\" class=\"new_input\" value=\"\">
											</div>
											<div id=\"div_telefono\">
												<label for=\"telefono\">Telefono</label>
												<input type=\"text\" name=\"telefono\" id=\"telefono\" value=\"{$result['telefono']}\" readonly>
												<label for=\"new_telefono\">Nuovo Telefono</label>
												<input type=\"text\" name=\"new_telefono\" id=\"new_telefono\" class=\"new_input\" value=\"\">
											</div>
										</div>
										<br>
										<div id=\"div_indirizzo\">
											<div id=\"div_idind\">
												<input type=\"hidden\" name=\"idind\" id=\"idind\" value=\"{$result['idind']}\" readonly>
											</div>
											<div id=\"div_via\">
												<label for=\"via\">Via</label>
												<input type=\"text\" name=\"via\" id=\"via\" value=\"{$result['via']}\" readonly>
												<label for=\"new_via\">Nuova Via</label>
												<input type=\"text\" name=\"new_via\" id=\"new_via\" class=\"new_input\" value=\"\">
											</div>
											<div id=\"div_ncivico\">
												<label for=\"ncivico\">N&deg;Civico</label>
												<input type=\"text\" name=\"ncivico\" id=\"ncivico\" value=\"{$result['ncivico']}\" readonly>
												<label for=\"new_ncivico\">Nuvo N&deg;Civico</label>
												<input type=\"text\" name=\"new_ncivico\" id=\"new_ncivico\" class=\"new_input\" value=\"\">
											</div>
											<div id=\"div_cap\">
												<label for=\"cap\">CAP</label>
												<input type=\"text\" name=\"cap\" id=\"cap\" value=\"{$result['cap']}\" readonly>
												<label for=\"new_cap\">Nuovo CAP</label>
												<input type=\"text\" name=\"new_cap\" id=\"new_cap\" class=\"new_input\" value=\"\">
											</div>	
											<div id=\"div_citta\">
												<label for=\"citta\">Citt&agrave;</label>
												<input type=\"text\" name=\"citta\" id=\"citta\" value=\"{$result['citta']}\" readonly>
												<label for=\"new_citta\">Nuova Citt&agrave;</label>
												<input type=\"text\" name=\"new_citta\" id=\"new_citta\" class=\"new_input\" value=\"\">
											</div>	
											<div id=\"div_submit\">
												<input type=\"submit\" id=\"submit\" value=\"modifica\">
											</div>														
										</div>
										
									</form>
									<form action=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_ordini_amm.php\" method=\"get\" id=\"form_ordini\">	
										<div id=\"div_ordini\">
											<input type=\"hidden\" name =\"login\" value=\"{$result['login']}\" readonly><br> 
											<input type=\"submit\" value=\"Ordini\">
										</div>
									</form>
								</div>
								<div style=\"clear:both\"></div>
						";
					}
					else
					{
						echo "login assente nella tabella utenti. Utente non esiste";
					}
				}
				else
				{
					throw new Exception("errore execute sql_sel1");
				}
			}
			else
			{
				echo "il login dell'utente non &egrave; specificato";
			}
			
			echo "
				<div>
					<br>
					<a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_utenti_amm.php\">&laquo; back</a>
				</div>
			";
?>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->			
					</div>
				</div>
				
				<div id="footer">
					<p>Â© Copyright 2012 yourname.com. All Rights Reserved.  |  Design by <a href="http://www.schlafzimmerkomplett.net/" target="_blank">Schlafzimmer komplett</a></p>
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
		if(isset($dbh))
		{
			$dbh = null;
		}
		print("error: ".$e->getMessage());
		//require_once("../shared/errore_server.php"); //può creare un errore anche questo
	}
	catch(Exception $e)
	{
		if(isset($dbh))
		{
			$dbh = null;
		}
		print("error: ".$e->getMessage());
		//require_once("../shared/errore_server.php"); //può creare un errore anche questo
	}
?>