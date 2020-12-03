<?php
	session_start();
	header('content-Type: text/html;charset=UTF-8');

	require_once("../shared/credenziali_db.php");
?>
<?php
	if(isset($_SESSION['utente']['login'],$_SESSION['utente']['nome']) && !empty($_SESSION['utente']['login']) && !empty($_SESSION['utente']['nome']))
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
			<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/registrazione/validate/formvalidate.js"></script>
			<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/ordini/tabella_ordini.js"></script>	

			<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/global-2.css">
			<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/ordini/tabella_ordini.css">

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
						echo "<li class=\"active\"><a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/ordini/\">Ordini</a></li>";
					}
					?>

					<?php
					if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
					{	
						echo "<li><a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/index.php\">Amministratore</a></li>";
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
					<h1><span> Ordini </span></h1>
					<?php	
						echo "<div id=\"ordini\"></div>";

						try
						{		$login = $_SESSION['utente']['login'];
								$sql = "select r.idpiz,r.idord,r.prezzo,r.quantita,o.giorno,o.ora,i.via,i.ncivico,i.cap,i.citta
										from registrazione r,ordini o,indirizzi i
										where o.codute = :login and r.idord = o.idord and o.codind = i.idind
										order by o.giorno,o.ora asc
								";
								
								$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
								$dbh = new PDO($dsn);
								$sth = $dbh -> prepare($sql);
								$sth -> bindParam(':login',$login,PDO::PARAM_STR);
								if($sth -> execute())
								{	
									if($result = $sth -> fetchAll(PDO::FETCH_ASSOC))
									{	//echo "result : "; print_r ($result); echo"<br>";
										$unico = array();	//dichiaro array unico
										
										foreach($result as $indice=>$array)
										{
											if(!isset($unico) || empty($unico))
											{	
												$unico[$array['idord']] = $array;
											}
											else
											{	
													if(array_key_exists($array['idord'], $unico))
													{
														continue; 	
													}
													else
													{
														$unico[$array['idord']] = $array;
													}
												
											}
										}//echo"<br>";print_r($unico);echo"<br>";
										
										
										foreach($unico as $key=>$array1)
										{		
												$idord = $array1['idord'];
												$giorno = $array1['giorno'];
												$ora = $array1['ora'];
												$via = $array1['via'];
												$ncivico = $array1['ncivico'];
												$cap = $array1['cap'];
												$citta = $array1['citta'];
												echo "
														<script>
															var newDiv = $(\"<div></div>\").attr({class:\"div_ordine\",id:\"$idord\"}).css({\"border-style\":\"solid\",\"border-width\":\"1px\"});
															var p_ord = $(\"<p></p>\").attr({class:\"p_idord\"}).text(\"ordine n\u00B0 $idord\");		
															var div_ord = $(\"<div></div>\").attr({class:\"div_idord\"}).append(p_ord);
															var div_int = $(\"<div></div>\").attr({class:\"div_int\"});
															var div_table = $(\"<div></div>\").attr({class:\"CSSTableGenerator \"});
															var newTable = $(\"<table></table>\").attr({class:\"table_ordine\",id:\"table$idord\"}).html(\"<tr><td>pizza</td><td>prezzo</td><td>quantit&agrave;</td></tr>\");
															
															var div_data = $(\"<div></div>\").html(\"<p>giorno: $giorno ora: $ora</p>\");
															var div_indirizzo = $(\"<div></div>\").html(\"<p>indirizzo:<br>via: $via<br>ncivico: $ncivico<br>cap: $cap<br>citt&agrave;: $citta</p>\");
															var div_consegna =  $(\"<div></div>\").attr({class:\"div_consegna\"}).append(div_data,div_indirizzo);
															
															
															var button_rimuovi = $(\"<button>Rimuovi</button>\").attr({type:\"button\",class:\"rimuovi\",onclick:\"Rimuovi('".$idord."')\"});
															$(div_table).append(newTable);
															$(div_int).append(div_table,div_consegna,button_rimuovi);
															$(newDiv).append(div_ord,div_int);
															//$(\"div#ordini div:last\").after(newDiv);
															$(\"div#ordini\").append(newDiv);
														</script>
												";
											foreach($result as $row=>$array2)
											{
												if($idord == $array2['idord'])
												{	//echo"qui";
													$tipo = $array2['idpiz'];
													$prezzo = $array2['prezzo'];
													$quantita = $array2['quantita'];
													
													echo"
														<script>
															var Table = $(\"div#$idord\").find(\"table.table_ordine\");
															//alert($(Table).prop('tagName'));
															var p_tipo = $(\"<p></p>\").text(\"$tipo\");
															var tipo = $(\"<td></td>\").append(p_tipo);

															var p_prezzo = $(\"<p></p>\").text(\"$prezzo\");
															var prezzo=$(\"<td></td>\").append(p_prezzo);
															
															var p_quantita = $(\"<p></p>\").text(\"$quantita\");
															var quantita = $(\"<td></td>\").append(p_quantita);

															var newRow = $(\"<tr />\");//alert(newRow);
															$(newRow).append(tipo,prezzo,quantita);
															id =\"#\"+$(Table).attr(\"id\");	
															$(id+\" tr:last\").after(newRow);//alert(id);
															
															//$(Table).append(newRow);
														</script>
													";
												}
												else
												{
													continue;
												}
											}
										}
									}
									else
									{
										echo"
											<script>
												$(\"#ordini\").html(\"<p>non ci sono ordini<p>\");
											</script>
										";
									}
								}
								else
								{
									throw new Exception("errore execute");
								}
						}
						catch(PDOException $e)
						{
								echo $e->getMessage();					
						}
						catch(Exception $e)
						{
								echo $e->getMessage();				
						}
					?>	
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
?>