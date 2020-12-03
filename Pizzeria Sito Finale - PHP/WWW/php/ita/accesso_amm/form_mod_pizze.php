<?php
	try
	{
		session_start();

		header('content-Type: text/html;charset=UTF-8');

		require_once("../shared/credenziali_db.php");

		//$_SESSION['amministratore'] = true;
		//$_GET['tipo'] = "marcello";

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
				<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/accesso_amm/form_mod_pizze.js"></script>	

				<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/form.css">
				<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/global-3.css">
				<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/tabella.css"> --> 
				<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/lista-pizze/infoaggiungi.css"> -->
				<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/lista-pizze/lettere.css"> -->
				
				 <style type="text/css">
								label { display: inline-block; width: 100px;  }
								.info_ajax {display:inline;}
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
						<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/form_insert_pizze.php">Inserisci Pizza</a></li>
						<li  class="active"><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_pizze_amm.php">Lista Pizze DB</a></li>
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
						<h1>Modifica Pizza</h1>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- inserire codice php -->

<?php
			if(isset($_GET['tipo']) && !empty($_GET['tipo']))
			{
				$sql = "
					select * 
					from pizze,contiene
					where tipo=idpiz and tipo = :tipo
				";
				$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
				$dbh = new PDO($dsn);
				
				$sth = $dbh -> prepare($sql);
				$sth -> bindParam(':tipo',$_GET['tipo'],PDO::PARAM_STR);
				if($sth -> execute())
				{
					if($result= $sth -> fetchAll(PDO::FETCH_ASSOC))
					{
				
						echo "	
						
							<div><h3>Pizza Tipo: <span style=\"color:black\">{$result[0]['tipo']}<span></h3> </div>
							<div id=\"contenitore\">
								<form action=\"\" method=\"get\" id=\"form_mod_pizze\">
									<div id=\"div_tipo\">
										<input type=\"hidden\" name=\"tipo\" id=\"tipo\" value=\"{$result[0]['tipo']}\" readonly>
									</div>
									<div id=\"div_prezzo\">
										<label for=\"old_prezzo\">Prezzo Attuale:</label>
										<input type=\"text\" name=\"prezzo\" id=\"old_prezzo\" value=\"{$result[0]['prezzo']}\"  onlyread>
										<!-- <label for=\"old_prezzo\">Nuovo Prezzo:</label> -->
										<input type=\"text\" name=\"prezzo\" id=\"new_prezzo\" value=\"\">
										<input type=\"submit\" value=\"Aggiorna\" class=\"aggiorna bottone\">
										<div id=\"info_prezzo\" class=\"info_ajax\"></div>
									</div>
									<div id=\"div_ingredienti\">
										<label for=\"ingredienti\">Ingredienti:</label>
										<input type=\"text\" name=\"ingredienti\" id=\"ingredienti\">
										<input type=\"submit\" value=\"Sostituisci\" class=\"sostituisci bottone\">
										<input type=\"submit\" value=\"Aggiungi\" class=\"aggiungi bottone\">
										<div id=\"info_ingredienti\" class=\"info_ajax\"></div>
										<p style=\"font-weight:bold\">* separare gli ingredienti da una virgola</p>
									</div>
									<br>
									<div id=\"div_lista_ingredienti\">
									";
									$count=1;
									foreach($result as $row => $array)
										foreach($array as $key => $val)
										{
											if(!strcmp($key,"iding"))
											{
												echo"
													<div class=\"$val\">
														<label for=\"$val\">Ingrediente $count:</label>
														<input type=\"text\" name=\"old_ing\" value=\"$val\" class=\"old_ing\" readonly> 
														<input type=\"text\" name=\"new_ing\" value=\"\" class=\"new_ing second\"> 
														<input type=\"submit\" value=\"Rimuovi\" class=\"rimuovi bottone first\">
														<input type=\"submit\" value=\"Modifica\" class=\"modifica bottone first\">
														<input type=\"submit\" value=\"Annulla\" class=\"annulla_mod bottone second\">
														<input type=\"submit\" value=\"Conferma\" class=\"conferma_mod bottone second\">
														<div class=\"info_ajax info_lista_ing\"></div>
													</div>
												";	
												$count++;				
											}
										}	
						echo"
									</div>
								</form>				
							</div>							
						";
					}
					else
					{
						throw new Exception("tipo non esiste in db");
					}
				}
				else
				{
					throw new Exception("errore execute");
				}
			}
			else
			{
				echo "il tipo della pizza non &egrave; specificato";
			}
			
			echo "
				<div>
					<br>
					<a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_pizze_amm.php\">&laquo; back</a>
				</div>
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
		if(isset($dbh))
		{
			$dbh = null;
		}
		print("error: ".$e->getMessage());
	}
	catch(Exception $e)
	{
		if(isset($dbh))
		{
			$dbh = null;
		}
		print("error: ".$e->getMessage());
	}
?>