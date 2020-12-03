<?php
	try
	{
		session_start();
		header('content-Type: text/html;charset=UTF-8');
		require_once("../shared/credenziali_db.php");

		$sql_sel_utente = "
				select login 
				from utenti 
				where login = :login
		";
		$sql_sel_utente_all = "
				select *
				from utenti
				where login = :login
		";
		$sql_sel_indirizzo = "
				select idind
				from indirizzi
	 			where via = :via and ncivico = :ncivico and cap = :cap and citta = :citta
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

		$sql_up_codind_utente = "
				update utenti
				set codind = :codind
				where login = :login
		";

		$sql_insert_indirizzo = "
				insert into indirizzi(idind,via,ncivico,cap,citta)
				values(DEFAULT,:via,:ncivico,:cap,:citta)
				returning idind
		";
		$sql_insert_utente = "
				insert into utenti(login,password,nome,cognome,telefono,codind)
				values(:login,:password,:nome,:cognome,:telefono,:codind);
		";
		$sql_sel_ordini = "
				select *
				from ordini
				where codute = :login
		";
		$sql_update_codute_ordini = "
				update ordini
				set codute = :newlogin
				where codute = :login
		";
		$sql_delete_utente = "
				delete from utenti
				where login = :login
		";
		
		$_SESSION['amministratore'] = true;
		$_GET['login'] = "b";
		$_GET['new_login'] = "";
		$_GET['new_password'] = "mino";
		$_GET['new_nome'] = "Paperino";
		$_GET['new_cognome'] = "Donato";
		$_GET['new_telefono'] = "0438";
		$_GET['new_via'] = "Ciccina";
		$_GET['new_ncivico'] = "Ciccina";
		$_GET['new_cap'] = "Ciccina";
		$_GET['new_citta'] = "Ciccina";
		
	
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			if
			(
			   isset($_GET['login']) && !empty($_GET['login'])
			   && isset($_GET['new_login'],$_GET['new_password'],$_GET['new_nome'],$_GET['new_cognome'],$_GET['new_telefono'],$_GET['new_via'],$_GET['new_ncivico'],$_GET['new_cap'],$_GET['new_citta'])
			   && ( !empty($_GET['new_login']) || !empty($_GET['new_password']) || !empty($_GET['new_nome']) || !empty($_GET['new_cognome']) || !empty($_GET['new_telefono']) || (!empty($_GET['new_via']) && !empty($_GET['new_ncivico']) && !empty($_GET['new_cap']) && !empty($_GET['new_citta'])))
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

				$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
				$dbh = new PDO($dsn);
				
				$sth = $dbh -> prepare($sql_sel_utente);
				$sth -> bindParam(':login', $_GET['login'],PDO::PARAM_STR);
				if($sth -> execute())
				{
					if($result = $sth -> fetchColumn())
					{	
						try
						{
							if($dbh -> beginTransaction())
							{
							
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

								//sostituisco i valori dei campi password,nome,cognome,telefono se get settati
								if(!empty($_GET['new_password']) || !empty($_GET['new_nome']) || !empty($_GET['new_cognome']) || !empty($_GET['new_telefono']))
								{
									$utente = array();
									if(!empty($_GET['new_password']))
									{
										$utente['password'] = $_GET['new_password']; 
									}
									
									if(!empty($_GET['new_nome']))
									{
										$utente['nome'] = $_GET['new_nome'];
									}
													
									if(!empty($_GET['new_cognome']))
									{
										$utente['cognome'] = $_GET['new_cognome'];
									}
													
									if(!empty($_GET['new_telefono']))
									{
										$utente['telefono'] = $_GET['new_telefono'];
									}
									
									foreach($utente as $key => $val)
									{
										switch($key)
										{
											case "password":
												$sql_up_password = "
														update utenti
														set password = :password
														where login = :login
												";
												$sth = $dbh -> prepare($sql_up_password);
												$sth -> bindParam(':password',$val,PDO::PARAM_STR);
												$sth -> bindParam(':login',$_GET['login'],PDO::PARAM_STR);
												if(!$sth -> execute())
												{
													$stato = "server_error";
													throw new Exception("errore execute sql_up_password");
												}
											break;
											case "nome":
												$sql_up_nome = "
														update utenti
														set nome = :nome
														where login = :login
												";
												$sth = $dbh -> prepare($sql_up_nome);
												$sth -> bindParam(':nome',$val,PDO::PARAM_STR);
												$sth -> bindParam(':login',$_GET['login'],PDO::PARAM_STR);
												if(!$sth -> execute())
												{
													$stato = "server_error";
													throw new Exception("errore execute sql_up_nome");
												}
											break;
											case "cognome":
												$sql_up_cognome = "
														update utenti
														set cognome = :cognome
														where login = :login
												";
												$sth = $dbh -> prepare($sql_up_cognome);
												$sth -> bindParam(':cognome',$val,PDO::PARAM_STR);
												$sth -> bindParam(':login',$_GET['login'],PDO::PARAM_STR);
												if(!$sth -> execute())
												{
													$stato = "server_error";
													throw new Exception("errore execute sql_up_cognome");
												}
											break;
											case "telefono":
												$sql_up_telefono = "
														update utenti
														set telefono = :telefono
														where login = :login
												";
												$sth = $dbh -> prepare($sql_up_telefono);
												$sth -> bindParam(':telefono',$val,PDO::PARAM_STR);
												$sth -> bindParam(':login',$_GET['login'],PDO::PARAM_STR);
												if(!$sth -> execute())
												{
													$stato = "server_error";
													throw new Exception("errore execute sql_up_telefono");
												}
											break;
										}
									}
								}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
								
								//controllo che il nuovo indirizzo se settato non esista in tabella indirizzi
								if(!empty($_GET['new_via']) && !empty($_GET['new_ncivico']) && !empty($_GET['new_cap']) && !empty($_GET['new_citta']))
								{	
										$indirizzo = array("via" => $_GET['new_via'], "ncivico" => $_GET['new_ncivico'], "cap" => $_GET['new_cap'], "citta" => $_GET['new_citta']);					
					
										$sth = $dbh -> prepare("select codind from utenti where login = :login");
										$sth -> bindParam(':login',$_GET['login'],PDO::PARAM_STR);
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
													{	//echo "<br>1";
														//indirizzo esiste gia update codind utenti

														$sth = $dbh -> prepare($sql_up_codind_utente);
														$sth -> bindParam(':codind',$result_idind,PDO::PARAM_INT);
														$sth -> bindParam(':login',$_GET['login'],PDO::PARAM_STR); 
													

														//echo "<br>2";
														if($sth -> execute())
														{	//echo "<br>3";
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
															throw new Exception("errore execute update codind utente");
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
																//update codind con result_idind in tabella utenti
																$sth = $dbh -> prepare($sql_up_codind_utente);
																$sth -> bindParam(':codind',$result_idind,PDO::PARAM_INT);
																$sth -> bindParam(':login',$_GET['login'],PDO::PARAM_STR); 
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
																	throw new Exception("errore execute update codind utente");
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
								
								//controllo che il nuovo login se settato non esista in tabella utenti
								if(isset($_GET['new_login']) && !empty($_GET['new_login']))
								{
									$sth = $dbh -> prepare($sql_sel_utente);
									$sth -> bindParam(':login', $_GET['new_login'],PDO::PARAM_STR);
									if($sth -> execute())
									{
										if($result = $sth -> fetchColumn())
										{
											$stato = "login_usato";
											throw new Exception ("login già in uso");
										}
										else
										{
											$sth = $dbh -> prepare($sql_sel_utente_all);
											$sth -> bindParam('login',$_GET['login'],PDO::PARAM_STR);
											if($sth -> execute())
											{
												if($utente = $sth -> fetch())
												{
													$sth = $dbh -> prepare($sql_insert_utente);
								
													$sth -> bindParam(':login',$_GET['new_login'],PDO::PARAM_STR);
															
													$sth -> bindParam(':password',$utente['password'],PDO::PARAM_STR);
														
													$sth -> bindParam(':nome',$utente['nome'],PDO::PARAM_STR);
															
													$sth -> bindParam(':cognome',$utente['cognome'],PDO::PARAM_STR);
															
													$sth -> bindParam(':telefono',$utente['telefono'],PDO::PARAM_STR);
														
													$sth -> bindParam(':codind',$utente['codind'],PDO::PARAM_STR);		
												
													if($sth -> execute())
													{
														$sth = $dbh -> prepare($sql_update_codute_ordini);
														$sth -> bindParam(':newlogin',$_GET['new_login'],PDO::PARAM_STR);
														$sth -> bindParam(':login',$_GET['login'],PDO::PARAM_STR);
														if($sth -> execute())
														{
															$sth = $dbh -> prepare($sql_delete_utente);															
															$sth -> bindParam(':login',$_GET['login'],PDO::PARAM_STR);
															if(!$sth -> execute())
															{	
																$stato = "server_error";
																throw new Exception("errore execute sql_delete_utente");
															}
														}
														else
														{
															$stato = "server_error";
															throw new Exception("errore execute sql_update_codute_ordini");
														}
													}
													else
													{
														$stato = "server_error";
														throw new Exception("errore execute sql_insert_utente");
													}
												}
											}
											else
											{
												$stato = "server_error";
												throw new Exception("errore execute sql_sel_utente_all");
											}
										}
									}
									else
									{
										$stato = "server_error";
										throw new Exception ("errore execute sql_sel_utente");
									}
								}
								
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
								
							}
							else
							{
								$stato = "server_error";
								throw new Exception("errore begin transaction");
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
					}
					else
					{
						$stato = "nologin";
						throw new Exception("login non presente in tabella utenti db");
					}
				}
				else
				{
					$stato = "server_error";
					throw new Exception("errore exception sql_sel_utente");
				}
			}
			else
			{
				$stato = "noget";
			}
		}	
		else
		{
			$stato = "noamm";
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
	array("stato"=>$stato),
	);
	echo json_encode($return);
	

?>