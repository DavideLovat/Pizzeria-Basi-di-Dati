<?php
	try
	{
		session_start();

		header('content-Type: text/html;charset=UTF-8');

		require_once("../shared/credenziali_db.php");
		/*
		$_SESSION['amministratore'] = true;
		$_GET['codute'] = "dlovat";
		$_GET['idord'] = "93";
		*/
		//print_r($_GET);
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
				<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/accesso_amm/form_mod_ordini.js"></script>	
				
				<style type="text/css">
								
								div.div_form {text-align:left;}
								label { display: inline-block;width:100px;text-align:right;}
								div.contenitore_input{width:520px;}
								div.button_right {text-align:right;}
								input {width:150px;}
								div.button_right input{text-align:right;width:auto;}
								
				</style>	
				<!--<style type="text/css">
					label { display: inline-block; width: 200px; text-align:right;  }
					.info_ajax {display:inline;}
					#contenitore{float:left;}
					#div_submit{text-align:right;}
					#form_ordini{float:right;text-align:right;}
				</style>-->
								
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
						<h1>Modifica Ordine</h1>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- inserire codice php -->		
<?php		
			if(isset($_GET['codute'],$_GET['idord']) && !empty($_GET['idord']) && !empty($_GET['codute']))
			{
				$codute = $_GET['codute'];
				$idord = $_GET['idord'];
				$sql = "
					select o.idord,o.giorno,o.ora,o.codute,o.codind,r.idpiz,r.quantita,r.prezzo,i.via,i.ncivico,i.cap,i.citta
					from ordini o,registrazione r,indirizzi i
					where o.codute = :codute and o.idord = :idord and o.idord = r.idord and o.codind = i.idind
				";
				$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
				$dbh = new PDO($dsn);
				
				$sth = $dbh -> prepare($sql);
				$sth -> bindParam(':codute',$codute,PDO::PARAM_STR);
				$sth -> bindParam(':idord',$idord,PDO::PARAM_STR);
				if($sth -> execute())
				{	
					if($result = $sth -> fetchAll())
					{
						$result[0]['idord'];
						echo "
							<div>
								<div>
									<h3>ordine: <span style=\"color:black;\">{$result[0]['idord']}</span> &nbsp;&nbsp; utente: <span style=\"color:black;\">{$result[0]['codute']}</span></h3>
								</div>
								<div class=\"div_form\">
									<form action=\"\" method=\"get\">
										<fieldset class=\"tratto\">
											<legend>Dati Ordine</legend>
											<div class=\"contenitore_input\">
												<div>
													<input type=\"hidden\" name=\"idord\" id=\"ord_idord\" value=\"{$result[0]['idord']}\" readonly>
													<input type=\"hidden\" name=\"idord\" id=\"ord_codute\" value=\"{$result[0]['codute']}\" readonly>
												</div>

												<div>
													<label for=\"giorno\">Giorno</label>
													<input type=\"text\" name=\"giorno\" id=\"giorno\" value=\"{$result[0]['giorno']}\" readonly>
													<label for=\"new_giorno\">Nuovo Giorno</label>
													<input type=\"date\" name=\"new_giorno\" id=\"new_giorno\" value=\"\">
												</div>
												
												<div>
													<label for=\"ora\">Ora</label>
													<input type=\"text\" name=\"ora\" id=\"ora\" value=\"{$result[0]['ora']}\" readonly>
													<label for=\"new_ora\">Nuova Ora</label>
													<input type=\"time\" name=\"new_ora\" id=\"new_ora\" value=\"\">
												</div>
												
												<div>
													<label for=\"via\">Via</label>
													<input type=\"text\" name=\"via\" id=\"via\" value=\"{$result[0]['via']}\" readonly>
													<label for=\"new_via\">Nuova Via</label>
													<input type=\"text\" name=\"new_via\" id=\"new_via\" value=\"\">
												</div>
												
												<div>
													<label for=\"ncivico\">N&deg;Civico</label>
													<input type=\"text\" name=\"ncivico\" id=\"ncivico\" value=\"{$result[0]['ncivico']}\" readonly>
													<label for=\"\">Nuovo N&deg;Civico</label>
													<input type=\"text\" name=\"new_ncivico\" id=\"new_ncivico\" value=\"\">
												</div>
												
												<div>
													<label for=\"cap\">Cap</label>
													<input type=\"text\" name=\"cap\" id=\"cap\" value=\"{$result[0]['cap']}\" readonly>
													<label for=\"\">Nuovo Cap</label>
													<input type=\"text\" name=\"new_cap\" id=\"new_cap\" value=\"\">
												</div>
												
												<div>
													<label for=\"citta\">Citt&agrave;</label>
													<input type=\"text\" name=\"citta\" id=\"citta\" value=\"{$result[0]['citta']}\" readonly>
													<label for=\"\">Nuova Citt&agrave;</label>
													<input type=\"text\" name=\"new_citta\" id=\"new_citta\" value=\"\">
												</div>
												
												<div  class=\"button_right\">
													<input type=\"submit\" id=\"modifica_ord\" name=\"modifica\">
												</div>
											</div>
										</fieldset>
									</form>
								</div>
						";
						foreach($result as $array)
						{
							echo "
								<div class=\"div_form\">
									<form action=\"\" method=\"get\">
										<fieldset class=\"tratto\">
										<legend>Elemento Ordine</legend>
											<div>
												<b>{$array['idpiz']}</b>
											</div>
											<div class=\"contenitore_input\">
												<div>
													<input type=\"hidden\" name=\"idpiz\" class=\"reg_idpiz\" value=\"{$array['idpiz']}\" readonly>
													<input type=\"hidden\" name=\"idord\" class=\"reg_idord\" value=\"$result[0]['idord']\" readonly>
													<input type=\"hidden\" name=\"codute\" class=\"reg_codute\" value=\"{$array[0]['codute']}\" readonly>
												</div>
												
												<div>
													<label for=\"quantita\">Quantit&agrave;</label>
													<input type=\"text\" name=\"quantita\" id=\"quantita\" value=\"{$array['quantita']}\">
													<label for=\"new_quantita\">Quantit&agrave;</label>
													<input type=\"text\" name=\"new_quantita\" id=\"new_quantita\" value=\"\">
												</div>
												
												<div>
													<label for=\"prezzo\">Prezzo</label>
													<input type=\"text\" name=\"prezzo\" id=\"prezzo\" value=\"{$array['prezzo']}\">
													<label for=\"new_prezzo\">Nuovo Prezzo</label>
													<input type=\"text\" name=\"new_prezzo\" id=\"new_prezzo\" value=\"\">
												</div>
											
												<div  class=\"button_right\">
													<input type=\"submit\" class=\"rimuovi_reg\" value=\"Rimuovi\">
													<input type=\"submit\" class=\"modifica_reg\" value=\"Modifica\">	
												</div>
											</div>
										</fieldset>
									</form>
								</div>
							";
						}
						echo "</div>";
					}
					else
					{
						echo "ordine non esiste in tabella ordini";
					
					}
				}
				else
				{
					throw new Exception("errore execute sql");
				}
			}
			else
			{
				if(!isset($_GET['codute']))
				{
					echo "il get codute dell'ordine non &egrave; specificato";
				}
				else if(!isset($_GET['idord']))
				{
					echo "il get idord dell'ordine non &egrave; specificato";
				}
			}
			echo "
				<div>
					<br>
					<a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_ordini_amm.php?login={$_GET['codute']}\">&laquo; back</a>
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
		echo "error: " . $e->getMessage();
	}
	catch(Exception $e)
	{
		if(isset($dbh))
		{
			$dbh = null;
		}
		echo "error: " . $e->getMessage();
	}
?>