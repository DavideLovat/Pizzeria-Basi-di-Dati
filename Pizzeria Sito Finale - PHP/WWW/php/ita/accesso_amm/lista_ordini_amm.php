<?php
	try
	{
		session_start();

		header('content-Type: text/html;charset=UTF-8');

		require_once("../shared/credenziali_db.php");
		/*
		$_SESSION['amministratore'] = true;
		$_GET['login'] = "dlovat";
		*/

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
				<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/accesso_amm/lista_ordini_amm.js"></script>
				
				<style type="text/css">								
								div.div_principale {margin-bottom:10px;}
								div.title_ordine 
								{	
									margin:0px;
									border:1px solid #BEBEBE;
						
									background-image: linear-gradient(bottom, rgb(196,100,4) 35%, rgb(255,128,0) 84%);
									background-image: -o-linear-gradient(bottom, rgb(196,100,4) 35%, rgb(255,128,0) 84%);
									background-image: -moz-linear-gradient(bottom, rgb(196,100,4) 35%, rgb(255,128,0) 84%);
									background-image: -webkit-linear-gradient(bottom, rgb(196,100,4) 35%, rgb(255,128,0) 84%);
									background-image: -ms-linear-gradient(bottom, rgb(196,100,4) 35%, rgb(255,128,0) 84%);

									background-image: -webkit-gradient(
										linear,
										left bottom,
										left top,
										color-stop(0.35, rgb(196,100,4)),
										color-stop(0.84, rgb(255,128,0))
									);


								}
								//div.title_ordine_left {float:left;}
								//div.title_ordine_right {float:right;}
								div.title_ordine_left {display: inline-block;padding:5px;}
								div.title_ordine_left span {}
								div.title_ordine_right {display: inline-block;float:right;padding:5px;}
								
								div.contenuto_ordine {border-style:none solid solid; border-width:1px; border-color:#BEBEBE; padding:10px; margin:0px;}
								form {display: inline-block;}
				</style>	
				
				<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/global-3.css">
				<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/tabella.css">
				
				<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/form.css"> -->
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
						<h1>Lista Ordini Utente: <?php if(isset($_GET['login']) && !empty($_GET['login'])){echo "{$_GET['login']}";} ?></h1>
						
						<div>
							<br>
							<a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/form_mod_utenti.php?login=<?php echo"{$_GET['login']}"; ?>">&laquo; back</a>
							
						</div>
						<br>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- inserire codice php -->

<?php
			if(isset($_GET['login']) && !empty($_GET['login']))
			{
				$codute = $_GET['login'];
				$sql = "
					select o.idord,o.giorno,o.ora,o.codute,o.codind,i.via,i.ncivico,i.cap,i.citta,r.idpiz,r.quantita,r.prezzo 
					from ordini o,indirizzi i,registrazione r
					where o.codute = :codute and o.codind = i.idind and o.idord = r.idord
					order by o.giorno DESC,o.ora DESC
				";
				$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
				$dbh = new PDO($dsn);
				
				$sth = $dbh -> prepare($sql);
				$sth -> bindParam(':codute',$codute,PDO::PARAM_STR);
				if($sth -> execute())
				{
					if($result= $sth -> fetchAll(PDO::FETCH_ASSOC))
					{	//echo "<br>".print_r($result,true)."<br>";
						$array_lista;
						
						foreach($result as $array)
						{
							if(empty($array_lista))
							{	
								$array_lista[$array['idord']]['idord'] = $array['idord'];
								$array_lista[$array['idord']]['giorno'] = $array['giorno'];
								$array_lista[$array['idord']]['ora'] = $array['ora'];
								$array_lista[$array['idord']]['codute'] = $array['codute'];
								$array_lista[$array['idord']]['codind'] = $array['codind'];
								$array_lista[$array['idord']]['via'] = $array['via'];
								$array_lista[$array['idord']]['ncivico'] = $array['ncivico'];
								$array_lista[$array['idord']]['cap'] = $array['cap'];
								$array_lista[$array['idord']]['citta'] = $array['citta'];
								
								$array_elementi = array("idpiz" => $array['idpiz'], "quantita" => $array['quantita'], "prezzo" => $array['prezzo']);
								$array_lista[$array['idord']]['elementi'] = array($array_elementi);
								//print_r($array_lista);
							}
							else 
							{
								if(array_key_exists($array['idord'],$array_lista))
								{
									$array_elementi = array("idpiz" => $array['idpiz'], "quantita" => $array['quantita'], "prezzo" => $array['prezzo']);
									array_push($array_lista[$array['idord']]['elementi'],$array_elementi);
								}
								else
								{
									$array_lista[$array['idord']]['idord'] = $array['idord'];
									$array_lista[$array['idord']]['giorno'] = $array['giorno'];
									$array_lista[$array['idord']]['ora'] = $array['ora'];
									$array_lista[$array['idord']]['codute'] = $array['codute'];
									$array_lista[$array['idord']]['codind'] = $array['codind'];
									$array_lista[$array['idord']]['via'] = $array['via'];
									$array_lista[$array['idord']]['ncivico'] = $array['ncivico'];
									$array_lista[$array['idord']]['cap'] = $array['cap'];
									$array_lista[$array['idord']]['citta'] = $array['citta'];
									
									$array_elementi = array("idpiz" => $array['idpiz'], "quantita" => $array['quantita'], "prezzo" => $array['prezzo']);
									$array_lista[$array['idord']]['elementi'] = array($array_elementi);
								}
							}
						}
									
						//print_r($array_lista);
						//crea righe tabella
						foreach($array_lista as $key => $array)
						{	
							if(!isset($str_script1))
							{
								$str_script1 = "";
							}		
							//echo "<br>$str_ing";
							$str_script1 = $str_script1 . "
												<script>
													alert(\"ciao\");
													//contenitori
													
													var newDiv = $(\"<div></div>\").attr({id:\"div_{$array['idord']}\",class:\"div_principale\"});	//contenitore principale
													var divTitle = $(\"<div></div>\").attr({id:\"div_title_{$array['idord']}\",class:\"title_ordine\"});	
													var divContenuto = $(\"<div></div>\").attr({id:\"div_contenuto_{$array['idord']}\",class:\"contenuto_ordine\"});
													var divTable = $(\"<div></div>\").attr({class:\"CSSTableGenerator\"}).css({clear:\"both\"});
													var newTable = $(\"<table></table>\").attr({id:\"table_{$array['idord']}\"}).html(\"<tr> <td>Pizza</td> <td>Prezzo</td> <td>Quantit&agrave;</td> </tr>\");
													var newFormRimuovi = $(\"<form></form>\").attr({class:\"form_rimuovi\",id:\"form_rimuovi_{$array['idord']}\",action:\"\",method:\"get\"}); 	
													var newFormModifica = $(\"<form></form>\").attr({class:\"form_modifica\",id:\"form_modifica_{$array['idord']}\",action:\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/form_mod_ordini.php\",method:\"get\"});
													
													//contenuto DIVTITLE
													var divcodute = $(\"<div></div>\").attr({class:\"title_ordine_left\"}).html(\"<b><span>login:</span></b> {$array['codute']}\");
													var dividord = $(\"<div></div>\").attr({class:\"title_ordine_left\"}).html(\"<b><span>ordine n\u00B0:</span></b> {$array['idord']}\");
													var divgiorno = $(\"<div></div>\").attr({class:\"title_ordine_right\"}).text(\"{$array['giorno']}\");
													var divora = $(\"<div></div>\").attr({class:\"title_ordine_right\"}).text(\"{$array['ora']}\");
													
													//contenuto FORM
													var input_rimuovi_codute = $(\"<input />\").attr({type:\"hidden\",name:\"codute\",class:\"input_codute\",value:\"{$array['codute']}\"});
													var input_rimuovi_idord = $(\"<input />\").attr({type:\"hidden\",name:\"idord\",class:\"input_idord\",value:\"{$array['idord']}\"});
													var input_modifica_codute = $(\"<input />\").attr({type:\"hidden\",name:\"codute\",class:\"input_codute\",value:\"{$array['codute']}\"});
													var input_modifica_idord = $(\"<input />\").attr({type:\"hidden\",name:\"idord\",class:\"input_idord\",value:\"{$array['idord']}\"});
													var button_rimuovi = $(\"<input />\").attr({type:\"submit\",class:\"rimuovi\",value:\"rimuovi\"}).text(\"Rimuovi\");
													var button_modifica = $(\"<input />\").attr({type:\"submit\",class:\"modifica\",value:\"modifica\"}).text(\"Modifica\");
													
													$(divTable).append(newTable);
													$(newFormRimuovi).append(input_rimuovi_codute,input_rimuovi_idord,button_rimuovi);
													$(newFormModifica).append(input_modifica_codute,input_modifica_idord,button_modifica);
													$(divTitle).append(divcodute,dividord,divora,divgiorno);
													$(divContenuto).append(divTable,newFormRimuovi,newFormModifica);
													$(newDiv).append(divTitle,divContenuto);
													$(\"#div_lista_ordini\").append(newDiv);
													
													
												</script>
											";
							foreach($array['elementi'] as $array_elementi)
							{
								if(!isset($str_script2))
								{
									$str_script2 = ""; 
								}
								$str_script2 = $str_script2 . "
																<script>
																	var newRow = $(\"<tr />\");
																	var td_idpiz = $(\"<td></td>\");
																	var td_quantita = $(\"<td></td>\");
																	var td_prezzo = $(\"<td></td>\");
																	
																	var p_idpiz = $(\"<p></p>\").text(\"{$array_elementi['idpiz']}\");
																	var p_quantita = $(\"<p></p>\").text(\"{$array_elementi['quantita']}\");
																	var p_prezzo = $(\"<p></p>\").text(\"{$array_elementi['prezzo']}\");
																	
																	$(td_idpiz).append(p_idpiz);
																	$(td_quantita).append(p_quantita);
																	$(td_prezzo).append(p_prezzo);
																	
																	$(newRow).append(td_idpiz,td_quantita,td_prezzo);
																	$(\"#table_{$array['idord']}\").append(newRow);
																</script>
															";
							}
						}
						$str_script_html = $str_script1 . $str_script2;
							
						echo "	
								<div id=\"div_lista_ordini\">
								</div>
								$str_script_html
						";
					}
					else
					{
						echo "utente non ha oridini in db";
					}
				}
				else
				{
					//echo "errore execute";
					throw new Exception("errore execute sql");
				}
			}
			else
			{
				
				echo "
					get login non specificato
				";
			}
			echo "
				<div>
					<br>
					<a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/form_mod_utenti.php?login={$_GET['login']}\">&laquo; back</a>
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