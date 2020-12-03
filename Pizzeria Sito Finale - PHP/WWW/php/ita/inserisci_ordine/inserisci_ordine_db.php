<?php

// Manca il controllo della presenza della pizza nel db infatti se pizza tipo manca l'inserimento in tabella registrazione fallisce
	/*
	session_start();
	require_once("../shared/credenziali_db.php");
	$_SESSION['utente']['login'] = "davide";
	
	$_SESSION['order']['marinara']['tipo'] = "marinara";
	$_SESSION['order']['marinara']['prezzo'] = "5";
	$_SESSION['order']['marinara']['quantita'] = "2";
	$_SESSION['order']['diavola']['tipo'] = "marcello";
	$_SESSION['order']['diavola']['prezzo'] = "3";
	$_SESSION['order']['diavola']['quantita'] = "2";
	$_SESSION['consegna']['giorno'] = "12-12-2001";
	$_SESSION['consegna']['ora'] = "00:00";
	$_SESSION['consegna']['via'] = "sanfrate";
	$_SESSION['consegna']['ncivico'] = "34f";
	$_SESSION['consegna']['cap'] = "13456";
	$_SESSION['consegna']['citta'] = "Poleto";
	*/
	print_r($_SESSION);
	if(isset($_SESSION['utente'],$_SESSION['order'],$_SESSION['consegna']) && !empty($_SESSION['utente']) && !empty($_SESSION['order'])
	&& !empty($_SESSION['consegna']['giorno']) && !empty($_SESSION['consegna']['ora']) && !empty($_SESSION['consegna']['via']) && !empty($_SESSION['consegna']['ncivico']) && !empty($_SESSION['consegna']['cap']) && !empty($_SESSION['consegna']['citta']))
	{
	
		try
		{	
			$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
			$dbh = new PDO($dsn);
			
				if($dbh->beginTransaction())
				{
					
					$sql = "select login from utenti where login = :login";
					$sth = $dbh -> prepare($sql);
					$sth -> bindParam(':login', $_SESSION['utente']['login'], PDO::PARAM_STR);
					if($sth -> execute())
					{
						if($utente_db = $sth -> fetchColumn())
						{
							
								if($utente_db == $_SESSION['utente']['login'])
								{
									$data = $_SESSION['consegna']['giorno']; 
									$ora = $_SESSION['consegna']['ora'];
									$via = $_SESSION['consegna']['via'];
									$ncivico =  $_SESSION['consegna']['ncivico'];
									$cap =  $_SESSION['consegna']['cap'];	
									$citta =  $_SESSION['consegna']['citta'];	
									
									$login = $utente_db;
									
									$sql = "select * from indirizzi where via=:via and ncivico=:ncivico and cap=:cap and citta=:citta";
									$sth = $dbh->prepare($sql);
									$sth -> bindParam(':via',$via,PDO::PARAM_STR);
									$sth -> bindParam(':ncivico',$ncivico,PDO::PARAM_STR);
									$sth -> bindParam(':cap',$cap,PDO::PARAM_STR);
									$sth -> bindParam(':citta',$citta,PDO::PARAM_STR);
									if($sth->execute())
									{
										if($result=$sth->fetch(PDO::FETCH_ASSOC))
										{
											$indirizzo = $result['idind']; 
										}
										else
										{
											$sql="insert into indirizzi(idind,via,ncivico,cap,citta) values(default,:via,:ncivico,:cap,:citta) returning idind";
											$sth = $dbh->prepare($sql);
											$sth -> bindParam(':via',$via,PDO::PARAM_STR);
											$sth -> bindParam(':ncivico',$ncivico,PDO::PARAM_STR);
											$sth -> bindParam(':cap',$cap,PDO::PARAM_STR);
											$sth -> bindParam(':citta',$citta,PDO::PARAM_STR);
											if($sth->execute())
											{
												if($result=$sth->fetchColumn())
												{
													$indirizzo = $result; echo "<br>".$indirizzo ;
												}
												else
												{
													throw new Exception("errore fetchColumn insert indirizzo");
												}
											}
											else
											{
												throw new Exception("errore execute insert indirizzo");
											}
										}
									}
									else
									{
										throw new Exception("execute sql indirizzi");
									}
									echo "<br>".$indirizzo ;
									$sql = "insert into ordini(idord,giorno,ora,codute,codind) values(DEFAULT,:data,:ora,:login,:indirizzo) returning(idord)";
									$sth = $dbh -> prepare($sql);
									$sth -> bindParam(':data',$data);
									$sth -> bindParam(':ora',$ora);
									$sth -> bindParam(':login',$login);
									$sth -> bindParam(':indirizzo',$indirizzo);
									echo "<br>$data , $ora , $login , $indirizzo<br>";
									if($sth -> execute())
									{
										$ordini_db = $sth -> fetch();
										if(!empty($ordini_db) && array_key_exists('idord', $ordini_db))
										{
											$idord = $ordini_db['idord'];
											foreach($_SESSION['order'] as $key_pizza => $array)
											{	
												$tipo = $array['tipo'];
												$quantita = $array['quantita'];
												$prezzo = $array['prezzo'];	//controllare che il prezzo sia lo stesso nel db
												$sql= "insert into registrazione(idpiz,idord,quantita,prezzo)
													values (:tipo,:idord,:quantita,:prezzo)
														";
												$sth = $dbh -> prepare($sql);
												$sth -> bindParam(':tipo',$tipo,PDO::PARAM_STR);
												$sth -> bindParam(':idord',$idord,PDO::PARAM_INT);
												$sth -> bindParam(':quantita',$quantita,PDO::PARAM_INT);
												$sth -> bindParam(':prezzo',$prezzo);
												if($sth->execute())
												{	
													$sql= "select idpiz,idord from registrazione where idpiz = :tipo and idord = :idord";
													$sth = $dbh -> prepare($sql);
													$sth -> bindParam(':tipo',$tipo,PDO::PARAM_STR);
													$sth -> bindParam(':idord',$idord,PDO::PARAM_INT);
													if($sth->execute())
													{
														if(!$result = $sth ->fetch())
														{
															throw new Exception("controllo registrazione rollback");
														}
														else
														{
															echo "<br>ci sono<br>";
														}
													}
													else
													{
														throw new Exception("controllo registrazione execute errore");
													}
												}
												else
												{
													throw new Exception("registrazione execute errore");		
												}
											}
											if($dbh -> commit())
											{
												unset($_SESSION['cart'],$_SESSION['order'],$_SESSION['noorder'],$_SESSION['consegna']);
												echo 'ordine inviato';
											}
											else
											{
												throw new Exception("errore commit");
											}
										}
										else
										{
											throw new Exception("ordini_db vuoto");
										}
									}
									else
									{
										throw new Exception("errore execute2");
									}
								}
								else
								{
									throw new Exception("utente login diverso da utente sessione");
								}
						}
						else
						{
							throw new Exception("utente assente in db");
						}
					}
					else
					{
						throw new Exception("errore execute1");
					}
				}
				else
				{
					throw new Exception("errore begintransaction");
				}
			
		}
		catch(PDOException $e)
		{
			if(isset($dbh))
			{
				$dbh -> rollBack();
				$dbh = null;
			}
			echo $e->getMessage();				
			echo 'ordine non inviato';	
		}
		catch(Exception $e)
		{
			if(isset($dbh))
			{
				$dbh -> rollBack();
				$dbh = null;
			}
			echo $e->getMessage();				
			echo 'ordine non inviato';
		}
	}
	else
	{
		echo"sessione non settata";
	}
?>