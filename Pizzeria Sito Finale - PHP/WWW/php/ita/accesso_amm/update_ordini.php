<?php
	try
	{	
		session_start();
		
		$_SESSION['amministratore']= true;
		$_GET['idord'] = "102";
		$_GET['codute'] = "b";
		$_GET['new_giorno'] = "2001-12-12";
		$_GET['new_ora'] = "15:30";
		$_GET['new_via'] = "mio";
		$_GET['new_ncivico'] = "mio";
		$_GET['new_cap'] = "mio";
		$_GET['new_citta'] = "mio";
		
		//echo"<br> sessione:";print_r($_SESSION);echo"<br>";
		//echo"<br> get:";print_r($_GET);echo"<br>";
		
		$sql_sel_ordine = "
				select *
				from ordini
				where idord = :idord and codute = :codute
		";
		
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			if
			(
			   isset($_GET['idord'],$_GET['codute']) && !empty($_GET['idord']) && !empty($_GET['codute'])
			   && isset($_GET['new_giorno'],$_GET['new_ora'],$_GET['new_via'],$_GET['new_ncivico'],$_GET['new_cap'],$_GET['new_citta'])
			   && ( !empty($_GET['new_giorno']) || !empty($_GET['new_ora']) || (!empty($_GET['new_via']) && !empty($_GET['new_ncivico']) && !empty($_GET['new_cap']) && !empty($_GET['new_citta'])))
			)
			{	
				if(!empty($_GET['new_via']) || !empty($_GET['new_ncivico']) || !empty($_GET['new_cap']) || !empty($_GET['new_citta']))
				{
					$array_get_indirizzo = array($_GET['new_via'],$_GET['new_ncivico'],$_GET['new_cap'],$_GET['new_citta']);
					//echo "<br>valore array indirizzo" . print_r($array_get_indirizzo,true) . "<br>";
					foreach($array_get_indirizzo as $val)
					{
						if(empty($val))
						{
							$stato = "noget_ind";
							throw new Exception("non tutti i campi dell'indirizzo sono stati segnati");
						}
					}

				}
						require_once("../shared/credenziali_db.php");
						$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
						$dbh = new PDO($dsn);
						
						//controlla se esiste l'ordine, indicare le modifiche su ordine
						$sth = $dbh -> prepare($sql_sel_ordine);						
						$sth -> bindParam(':idord',$_GET['idord'], PDO::PARAM_INT);
						$sth -> bindParam(':codute',$_GET['codute'], PDO::PARAM_STR);
						if($sth -> execute())
						{
							if($result_ordine = $sth -> fetch())
							{
								//inizio modifica ordine 
								try
								{
									if($dbh -> beginTransaction())
									{
									
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

										//sostituisco i valori dei campi giorno,ora in ordini se get settati
										if(!empty($_GET['new_giorno']) || !empty($_GET['new_ora']))
										{	
											$up_ordine = array();
											if(!empty($_GET['new_giorno']))
											{
												$up_ordine['giorno'] = $_GET['new_giorno'];
											}
											
											if(!empty($_GET['new_ora']))
											{
												$up_ordine['ora'] = $_GET['new_ora'];
											}
											//print_r ($up_ordine);
											foreach($up_ordine as $key => $val)
											{
												switch($key)
												{
													case "giorno":
																			
														$sql_up_giorno = "
																update ordini
																set giorno = :giorno
																where idord = :idord and codute = :codute
														";
														$sth = $dbh -> prepare($sql_up_giorno);
														$sth -> bindParam(':giorno',$val);
														$sth -> bindParam(':idord',$_GET['idord'],PDO::PARAM_INT);
														$sth -> bindParam(':codute',$_GET['codute'],PDO::PARAM_STR);
														if(!$sth -> execute())
														{															
															$stato = "server_error";
															throw new Exception("errore execute sql_up_giorno");
														}
													break;
													case "ora":
														$sql_up_ora = "
																update ordini
																set ora = :ora
																where idord = :idord and codute = :codute
														";
														$sth = $dbh -> prepare($sql_up_ora);
														$sth -> bindParam(':ora',$val);
														$sth -> bindParam(':idord',$_GET['idord'],PDO::PARAM_INT);
														$sth -> bindParam(':codute',$_GET['codute'],PDO::PARAM_STR);
														if(!$sth -> execute())
														{
															$stato = "server_error";
															throw new Exception("errore execute sql_up_ora");
														}
													break;
												}
											}
										
										}
										
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

										//se get settati controllo che il nuovo indirizzo non esista in tabella indirizzi altrimenti lo inserisco
										if(!empty($_GET['new_via']) && !empty($_GET['new_ncivico']) && !empty($_GET['new_cap']) && !empty($_GET['new_citta']))
										{	
											$indirizzo = array("via" => $_GET['new_via'], "ncivico" => $_GET['new_ncivico'], "cap" => $_GET['new_cap'], "citta" => $_GET['new_citta']);					
											
											//Inizio Query SQL Update Indirizzo Ordine
											$sql_sel_indirizzo = "
													select idind
													from indirizzi
													where via = :via and ncivico = :ncivico and cap = :cap and citta = :citta
											";

											$sql_up_codind_ordine = "
													update ordini
													set codind = :codind
													where idord = :idord and codute = :codute
											";

											$sql_delete_indirizzo = "
													delete from indirizzi i
													where i.idind = :codind
													and i.idind not in
													(
														select codind
														from ordini
													)
													and i.idind not in
													(
														select codind
														from utenti
													)
											";
											
											$sql_insert_indirizzo = "
													insert into indirizzi(idind,via,ncivico,cap,citta)
													values(DEFAULT,:via,:ncivico,:cap,:citta)
													returning idind
											";

											//Fine Query SQL Update Indirizzo Ordine
											$sth = $dbh -> prepare("select codind from ordini where idord = :idord and codute = :codute");
											$sth -> bindParam(':idord',$_GET['idord'],PDO::PARAM_INT); 
											$sth -> bindParam(':codute',$_GET['codute'],PDO::PARAM_STR);
											if($sth -> execute())
											{
												if($codind = $sth -> fetchColumn())
												{
													//verificare che nuovo indirizzo non esiste ancora
													//print_r($indirizzo);
													$sth = $dbh -> prepare($sql_sel_indirizzo);
													
													$sth -> bindParam(':via',$indirizzo['via']);
													$sth -> bindParam(':ncivico',$indirizzo['ncivico']);
													$sth -> bindParam(':cap',$indirizzo['cap']);
													$sth -> bindParam(':citta',$indirizzo['citta']);
													
													if($sth -> execute())
													{	
														if($result_idind = $sth -> fetchColumn())
														{	//echo "<br>indirizzo esiste gia update codind ordini";
															//indirizzo esiste gia update codind ordini
															$sth = $dbh -> prepare($sql_up_codind_ordine);
															$sth -> bindParam(':codind',$result_idind,PDO::PARAM_INT);
															$sth -> bindParam(':idord',$_GET['idord'],PDO::PARAM_INT); 
															$sth -> bindParam(':codute',$_GET['codute'],PDO::PARAM_STR); 

															if($sth -> execute())
															{	//echo "<br>codind ordine modificato";
																//elimino indirizzo vecchio se non è usato in tabella ordini o utenti
																$sth = $dbh -> prepare($sql_delete_indirizzo);
																$sth -> bindParam(':codind',$codind,PDO::PARAM_INT);
																if(!$sth -> execute())
																{	
																	$stato = "server_error";
																	throw new Exception("errore sql_delete_indirizzo");
																}
															}
															else
															{	
																$stato = "server_error";
																throw new Exception("errore execute update codind ordine");
															}
														}
														else
														{	
															//indirizzo non esiste
															$sth = $dbh -> prepare($sql_insert_indirizzo);
																	
															$sth -> bindParam(':via',$indirizzo['via'],PDO::PARAM_STR);
																	
															$sth -> bindParam(':ncivico',$indirizzo['ncivico'],PDO::PARAM_STR);
							
															$sth -> bindParam(':cap',$indirizzo['cap'],PDO::PARAM_STR);
																			
															$sth -> bindParam(':citta',$indirizzo['citta'],PDO::PARAM_STR);
									
															if($sth -> execute())
															{	
																
																if($result_idind = $sth -> fetchColumn())
																{	//echo "<br>id nuovo indirizzo: " . $result_idind . "<br>";
																	//update codind con result_idind in tabella ordini
																	$sth = $dbh -> prepare($sql_up_codind_ordine);
																	$sth -> bindParam(':codind',$result_idind,PDO::PARAM_INT);
																	$sth -> bindParam(':idord',$_GET['idord'],PDO::PARAM_INT); 
																	$sth -> bindParam(':codute',$_GET['codute'],PDO::PARAM_STR); 
																	if($sth -> execute())
																	{	
																		//elimino indirizzo vecchio se non è usato in tabella ordini o utenti
																		$sth = $dbh -> prepare($sql_delete_indirizzo);
																		$sth -> bindParam(':codind',$codind,PDO::PARAM_INT);
																		if(!$sth -> execute())
																		{	
																			$stato = "server_error";
																			throw new Exception("errore sql_delete_indirizzo");
																		}
																	}
																	else
																	{
																		$stato = "server_error";
																		throw new Exception("errore execute update codind ordine");
																	}
																}
																else
																{
																	$stato = "server_error";
																	throw new Exception("errore fetchColumn");
																}
															}
															else
															{
																$stato = "server_error";
																throw new Exception("errore execute inserisci indirizzo");
															}
														}
													}
													else
													{
														$stato = "server_error";
														throw new Exception("errore sql_sel_indirizzo");
													}
												}
												else
												{
													$stato = "server_error";
													throw new Exception("errore sql_delete_indirizzo");

												}
											}
											else
											{
												$stato = "server_error";
												throw new Exception("errore sql_delete_indirizzo");
											}	
										}
										
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
										
										//commmit della transazione
										if($dbh -> commit())
										{
											$stato = "successo";
										}
										else
										{
											$stato = "server_errore";
											throw new Exception("errore  commit");
										}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
										
									}
									else
									{
										$stato = "server_error";
										throw new Exception("errore inizio transazione");
									}
								}
								catch(PDOException $e)
								{
									if(isset($dbh))
									{
										$dbh ->rollBack();
										$dbh = null;
									}
									if(!isset($stato) || empty($stato))
									{
										$stato = "server_error";
									}
									//print("error: ".$e->getMessage());
								}
								catch(Exception $e)
								{
									if(isset($dbh))
									{
										$dbh ->rollBack();
										$dbh = null;
									}
									if(!isset($stato) || empty($stato))
									{
										$stato = "server_error";
									}
									//print("error: ".$e->getMessage());
								}
								//fine modifica ordine
							}
							else
							{
								$stato = "noordine";
								throw new Exception("non esisete l'ordine");
							}
						}
						else
						{
							$stato = "server_error";
							throw new Exception("errore execute sql_sel_ordine");
						}
			}
			else
			{
				$stato = "noget";
				//echo"get non settati";
			}
		}
		else
		{
			$stato = "noamm";
			//echo "sessione amministratore non true";
		}
							
	}
	catch(PDOException $e)
	{
		if(isset($dbh))
		{
			$dbh = null;
		}
		if(!isset($stato) || empty($stato))
		{
			$stato = "server_error";
		}
		//print("error: ".$e->getMessage());
	}
	catch(Exception $e)
	{
		if(isset($dbh))
		{
			$dbh = null;
		}
		if(!isset($stato) || empty($stato))
		{
			$stato = "server_error";
		}
		//print("error: ".$e->getMessage());
	}	
	$return = array(
	array("stato" => $stato),
	);
	echo json_encode($return);
?>