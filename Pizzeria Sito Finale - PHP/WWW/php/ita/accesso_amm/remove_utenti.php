<?php
	try
	{	
		session_start();
		/*
		$_SESSION['amministratore']= true;
		$_GET['login'] = "E";
		*/
		//echo"<br> sessione:";print_r($_SESSION);echo"<br>";
		//echo"<br> get:";print_r($_GET);echo"<br>";
		
		$sql_sel1 = "
				select login
				from utenti
				where login = :login
		";
		$sql_sel2 = "
				select distinct o.codind
				from ordini o, utenti u
				where o.codute = u.login and o.codute = :login  and o.codind <> u.codind
		";	//seleziona codici indirizzi ordinazioni utente tranne l'indirizzo di residenza
		$sql_sel3 = "
				select codind
				from utenti
				where login = :login
		";//seleziona codice indirizzo utente
		$sql_del1 = "
				delete from registrazione
				where idord in
				(select idord from ordini where codute = :login)
		";
		$sql_del2 = "
				delete from ordini
				where codute = :login
		";
		$sql_del3 = "
				delete from utenti
				where login = :login
		";
		$sql_del4 = "
				delete from indirizzi
				where idind = :codind
				and idind not in (select codind from ordini)
				and idind not in (select codind from utenti)
		";
		
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			if(isset($_GET['login']) && !empty($_GET['login']))
			{
				$login = $_GET['login'];

				$delete_ind_ordini = false;
				$delete_ind_utente = false;
				

						require_once("../shared/credenziali_db.php");
						$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
						$dbh = new PDO($dsn);
						$sth = $dbh -> prepare($sql_sel1);
						$sth -> bindParam(':login', $login, PDO::PARAM_STR);
						if($sth -> execute())
						{
							if($result = $sth -> fetchColumn())
							{
								if($result == $login)
								{
									
									//////////////////////////////////////////////
									
									//estrai codind degli indirizzi dagli ordini dell'utente
									$sth = $dbh -> prepare($sql_sel2);
									$sth -> bindParam(':login', $login, PDO::PARAM_STR);
									if($sth -> execute())
									{
										if($table_ind_ordini = $sth -> fetchAll())
										{
											$delete_ind_ordini = true;
											//echo "c'è un indirizzo in ordini";
										}
										else
										{
											$delete_ind_ordini = false;
											//echo "non è stato trovato alcun indirizzo in ordini";
										}
										
									}
									else
									{
										$stato = "server_error";
										throw new Exception("errore in execute sql_sel2");
									}
									
									//estrai codind indirizzo utente
									$sth = $dbh -> prepare($sql_sel3);
									$sth -> bindParam(':login', $login, PDO::PARAM_STR);
									if($sth -> execute())
									{
										if($table_ind_utente = $sth -> fetchAll())
										{
											$delete_ind_utente = true;
											//echo "indirizzo utente presente";
										}
										else
										{
											$delete_ind_utente = false;
											//echo"errore non è presente l'indirizzo utente";
										}
									}
									else
									{
										$stato = "server_error";
										throw new Exception("errore in execute sql_sel3");
									}

									if($delete_ind_ordini && $delete_ind_utente)
									{
										$table_marge = array_marge($table_ind_ordini,$table_ind_utente);
										//echo "<br>array marge :".print_r($array_marge,true)."<br>";
									}
									//elimina registrazione,ordini,indirizzi dell'utente e l'utente
									//echo "<br>table_ind_ordini: ".print_r($table_ind_ordini,true)."<br>table_ind_utente: ".print_r($table_ind_utente,true)."<br>table_marge: ".print_r($table_marge,true);
									try
									{
										if($dbh -> beginTransaction())
										{
											//elimina dati dalla registrazione
											$sth = $dbh -> prepare($sql_del1);
											$sth -> bindParam(':login', $login, PDO::PARAM_STR);
											if($sth -> execute())
											{	
												$sth = $dbh -> prepare($sql_del2);
												$sth -> bindParam(':login', $login, PDO::PARAM_STR);
												if($sth -> execute())
												{	
													$sth = $dbh -> prepare($sql_del3);
													$sth -> bindParam(':login', $login, PDO::PARAM_STR);
													if($sth -> execute())
													{	
														//echo "<br>delete_ind_ordini: $delete_ind_ordini,delete_ind_utente: $delete_ind_utente<br>";
														if($delete_ind_ordini || $delete_ind_utente)
														{
															$table_indirizzi = array();
															$sth = $dbh -> prepare($sql_del4);
															if($delete_ind_ordini && $delete_ind_utente)
															{
																//echo "1";
																$table_indirizzi = $table_marge;
															}
															else if($delete_ind_ordini && !$delete_ind_utente)
															{
																//echo "2";
																$table_indirizzi = $table_ind_ordini;
															}
															else if(!$delete_ind_ordini && $delete_ind_utente)
															{
																//echo "3";
																$table_indirizzi = $table_ind_utente;
															}
															if(!empty($table_indirizzi))
															{		
																foreach($table_indirizzi as $row => $array)
																{
																	foreach($array as $key => $codind)
																	{
																		$sth -> bindParam(':codind',$codind,PDO::PARAM_INT);
																		if(!$sth -> execute())
																		{
																			$commit = false;
																			$stato = "server_error";
																			throw new Exception("errore execute sql_del4 eliminazione indirizzi");
																		}
																	}
																}
															}
																$commit = true;
														}
														else
														{
															$commit = true;
														}
														if($commit)
														{
															$dbh -> commit();
															$dbh = null;
															//echo "success";
															$stato = "successo";
														}
													}
													else
													{
														$stato = "server_error";
														throw new Exception("errore in execute sql_del1");
													}
												}
												else
												{
													$stato = "server_error";
													throw new Exception("errore in execute sql_del1");
												}
											}
											else
											{
												$stato = "server_error";
												throw new Exception("errore in execute sql_del1");
											}
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
											$dbh -> rollback();
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
											$dbh -> rollback();
											$dbh = null;
										}
										if(!isset($stato) || empty($stato))
										{
											$stato = "server_error";
										}
										//print("error: ".$e->getMessage());
									}
									
									/////////////////////////////////////////////
								}
								else
								{
									$stato = "nologin";
									throw new Exception("non è presente il login $login in db");
								}
							}
							else
							{
								$stato = "nologin";
								throw new Exception("non è presente il login $login in db");
							}
						}
						else
						{
							$stato = "server_error";
							throw new Exception("errore in execute sql_sel1");
						}
			}
			else
			{
				//echo"get non settati";
				$stato = "noget";
			}
		}
		else
		{
			//echo "sessione amministratore non true";
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
	array("stato" => $stato), 
	);
	echo json_encode($return);
?>