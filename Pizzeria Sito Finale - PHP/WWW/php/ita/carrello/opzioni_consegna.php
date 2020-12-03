	<?php
	session_start();
	header('content-Type: text/html;charset=UTF-8');

	require_once("../shared/credenziali_db.php");
?>
<?php 
	if(isset($_SESSION['cart']) && !empty($_SESSION['cart']))
	{
		if(isset($_SESSION['utente']['login'],$_SESSION['utente']['nome']) && !empty($_SESSION['utente']['login']) && !empty($_SESSION['utente']['nome']))
		{
		
			$sql = "
				select * 
				from indirizzi 
				where idind = (select codind from utenti where login = :login)
			";

			$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
			$dbh = new PDO($dsn);
			$sth = $dbh -> prepare($sql);
			$sth -> bindParam(':login',$_SESSION['utente']['login'],PDO::PARAM_STR); 
			if($sth -> execute())
			{
				$result = $sth -> fetch(PDO::FETCH_ASSOC);print_r($_SESSION['utente']);print_r($result);
				
				$today = getdate();
				$dmin = mktime($today['hours'],$today['minutes'],$today['seconds'], $today['mon'],   $today['mday'],   $today['year']);
				$min_giorno = date("Y-m-d",$dmin);
				$dmax = mktime(0, 0, 0, $today['mon'],   $today['mday'],	  $today['year']+1);
				$max_giorno = date("Y-m-d",$dmax);
				//$min_ora = date("H:i",$tmin); // manca $tmin che non è specificato
				
				$ora = date("H:i",$dmin);

				echo"	
					<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
					\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
					<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
					<head>
					<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />
					<meta name=\"author\" content=\"Your Company\" />
					<meta name=\"keywords\" content=\"Your Company\" />
					<meta name=\"description\" content=\"Your Company\" />
					<meta name=\"robots\" content=\"all\" />
					<!-- inzio script aggiunti -->	

					<script src=\"http://code.jquery.com/jquery-1.9.1.js\"></script>
					<script type=\"text/javascript\" src=\"http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js\"></script>
					<script type=\"text/javascript\" src=\"http://wwwstud.dsi.unive.it/dlovat/WWW/js/carrello/opzioni_consegna.js\"></script>
					<script type=\"text/javascript\" src=\"http://wwwstud.dsi.unive.it/dlovat/WWW/js/carrello/validate/opzioni_consegna_validate.js\"></script>

					<link rel=\"stylesheet\" type=\"text/css\" href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/global-2.css\">
					<link rel=\"stylesheet\" type=\"text/css\" href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/tabella.css\">
					<link rel=\"stylesheet\" type=\"text/css\" href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/css/carrello/carrello.css\">
					<link rel=\"stylesheet\" type=\"text/css\" href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/form.css\">

					<!-- fine script aggiunti -->
					<title>Chalk Rock</title>
					</head>
					<body>
					<div id=\"wrapper\">
						<div id=\"header\">
							<h1><span class=\"black\">Pizzeria</span> <span class=\"orange\">Online</span></h1>
							<h2>Your Slogan</h2>
						</div>
						
						<div id=\"main\">
						 
						   <div id=\"content\" style=\"width:95%\">
								<h1><span> Opzioni Consegna </span></h1>
								<form action=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/inserisci_ordine/controlla_ordine.php\" method=\"post\" id=\"consegna\">
									<fieldset>
										<legend>Modalit&agrave; Consegna</legend>
											
										<fieldset class='tratto'>
											<legend>Data\Ora </legend>
											<div class='fm-req'>
												<label for=\"giorno\">Giorno Consegna:</label>
												<input type=\"date\" id=\"giorno\" name=\"giorno\" min=\"$min_giorno\" max=\"$max_giorno\" value=\"$min_giorno\">
											</div>

											<div class='fm-req'>
												<label for=\"ora\">Ora Consegna:</label>
												<input type=\"time\" id=\"ora\" name=\"ora\" value=\"$ora\">
											</div>
										</fieldset>

										<div id=\"div_indirizzo_consegna\">
											 <fieldset class=\"tratto\">
												  <legend>Address </legend>  
												  <div class=\"fm-req\">
													<label for=\"via\">via:</label>
													<input id=\"via\" name=\"via\" type=\"text\" value=\"{$result['via']}\">
												  </div> 

												  <div class=\"fm-req\">
													<label for=\"ncivico\">n&deg;civico:</label>
													<input id=\"ncivico\" name=\"ncivico\" type=\"text\" value=\"{$result['ncivico']}\">
												  </div>   

												  <div class=\"fm-req\">
													<label for=\"cap\">cap:</label>
													<input id=\"cap\" name=\"cap\" type=\"text\" value=\"{$result['cap']}\">
												  </div>

												  <div class=\"fm-req\">
													<label for=\"città\">Citt&agrave;:</label>
													<input id=\"citta\" name=\"citta\" type=\"text\" value=\"{$result['citta']}\">
												  </div> 												  
											 </fieldset>	  					
										</div>	
										<input id=\"submit_consegna\" type=\"submit\" value=\"Conferma\">
										
									</fieldset>
								</form>									
									
									<div id=\"div_indirizzo_utente\">
										<div>
											<div><span>via:</span> {$result['via']}</div>
											<div><span>n&deg;civico:</span> {$result['ncivico']}</div>
											<div><span>cap:</span> {$result['cap']}</div>
											<div><span>Citt&agrave;:</span> {$result['citta']}</div>
										</div>
										<button style=\"float:left;\" type=\"button\" id=\"button_indirizzo_utente\">Inserisci Indirizzo Utente</button>
										<button type=\"button\" id=\"button_nuovo_indirizzo\">Nuovo Indirizzo</button><br>
									</div>
									
						   </div>
						 

						</div>
						<div id=\"footer\">
							<p>Â© Copyright 2012 yourname.com. All Rights Reserved.  |  Design by <a href=\"http://www.schlafzimmerkomplett.net/\" target=\"_blank\">Schlafzimmer komplett</a></p>
						</div>
						<div class=\"cleaner\"></div>
					</div>
				";
				echo"
					</body>
					</html>
				";	
			}
			else
			{
				require_once("../shared/errore_server.php");
			}
		}
		else
		{
			require_once("../shared/form_accesso.php");
		}
	}
	else
	{
		header("Location: http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/carrello/");
	}
?>
