<?php
	try
	{
		session_start();
		//$_SESSION['amministratore']= true;
		//$_GET['tipo'] = "marcello";
		//$_GET['prezzo'] = "4,00";
		//$_GET['ingredienti'] = "pomodoro";
		//echo"<br> sessione:";print_r($_SESSION);echo"<br>";
		//echo"<br> get:";print_r($_GET);echo"<br>";

		$sql_sel1 = "
				select tipo
				from pizze
				where tipo = :tipo
		";
		$sql_sel2 = "
				select nome
				from ingredienti
				where nome = :nome
		";
		$sql_del1 = "
				delete from contiene
				where idpiz = :tipo
		";
		$sql_ins1 = "
				insert into contiene(idpiz,iding)
				values(:tipo,:nome)
		";
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			if(isset($_GET['tipo'],$_GET['ingredienti']) && !empty($_GET['tipo']) && !empty($_GET['ingredienti']))
			{
				$tipo = $_GET['tipo'];
				$lista_ingredienti = $_GET['ingredienti'];
				$findstr = ",";
				$array_ing = array();
				$tipo_exists = false;
				$insert_contiene = false;
				
				//separa la stringa lista_ingredienti e metti ogni ingrediente in un array
				if(!strlen($lista_ingredienti)==0)
				{
					while(strlen($lista_ingredienti) != 0)
					{
						if($pos = strpos($lista_ingredienti,$findstr)) //cerca la stringa ',' in ingredienti
						{
							if($pos != 0)	// se ',' non è nella prima posizione inserisci nell'array la stringa precedente
							{
								$ing = substr($lista_ingredienti,0,$pos);
								array_push($array_ing,$ing);
							}
							if(!$lista_ingredienti = substr($lista_ingredienti,$pos+1)) //finchè esiste una sottostringa sostituisci la lista ingredienti
							{
								$lista_ingredienti = "";
							}
						}
						else
						{
							array_push($array_ing,$lista_ingredienti);
							$lista_ingredienti = "";
						}
					}
				}
				
				//echo"<br>";print_r($array_ing);echo"<br>";
				if(isset($array_ing) && !empty($array_ing))
				{	
		
						require_once("../shared/credenziali_db.php");
						$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
						$dbh = new PDO($dsn);
						$sth = $dbh -> prepare($sql_sel1);
						$sth -> bindParam(':tipo', $tipo, PDO::PARAM_STR);
							
						if($sth -> execute())
						{
							if($result = $sth -> fetchColumn())
							{
								if($result == $tipo)
								{
									//esegui la seconda query $sql_sel2 seleziona gli ingredienti e controlla che siano tutti presenti
									$array_ing_no_db = array();
									$sth = $dbh -> prepare($sql_sel2);
									for($i=0;$i<count($array_ing);$i++)
									{
										$nome = $array_ing[$i];
										$sth -> bindParam(':nome', $nome);
										//print_r($sth->errorInfo());
										
										if($sth -> execute())
										{
											if(!$result = $sth -> fetchColumn())
											{	
												array_push($array_ing_no_db,$nome);
											}
										}
										else
										{
											$stato = "server_error";
											throw new Exception("errore execute sql_sel2");
										}
									}
									if(empty($array_ing_no_db))
									{
										////////////////////////////////////////////////////////
											try
											{
												if($dbh -> beginTransaction())
												{
													$sth = $dbh -> prepare($sql_del1);
													$sth -> bindParam(':tipo', $tipo);
													if($sth -> execute())
													{
															$sth = $dbh -> prepare($sql_ins1);
															for($i=0;$i<count($array_ing);$i++)
															{
																$nome = $array_ing[$i];
																$sth -> bindParam(':tipo', $tipo);
																$sth -> bindParam(':nome', $nome);
																//print_r($sth->errorInfo());
																if(!$sth -> execute())
																{
																	$stato = "server_error";
																	throw new Exception("errore exception sql_ins1");
																}
															}
															$dbh -> commit();
															$dbh = null;
															//echo "insert success";
															$stato = "successo";
													}
													else
													{
														$stato = "server_error";
														throw new Exception("errore exception sql_del1");
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
										///////////////////////////////////////////////////////
									}
									else
									{
										$str_assente = "";
										$stato = "assente";
										for($i = 0; $i < count($array_ing_no_db); $i++)
										{
											if($i == count($array_ing_no_db)-1)
											{
												$str_assente = $str_assente . $array_ing_no_db[$i];
											}
											else
											{
												$str_assente = $str_assente . $array_ing_no_db[$i] . " , ";
											}
										}

										throw new Exception("errore dopo execute sql_sel2 ".print_r($array_ing_no_db,true));
									}
									//print ("tipo : $tipo_exists contiene: $insert_contiene");	
								}
								else
								{
									$stato = "notipo";
									throw new Exception("valore tipo non presente in db");	
								}
							}
							else
							{
								$stato = "notipo";
								throw new Exception("valore tipo non presente in db");
							}
						}
						else
						{
							$stato = "server_error";
							throw new Exception("errore execute sql_sel1");
						}
				}
				else
				{
					//echo "array_ing è vuoto";	
					$stato = "novalido";
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
	array("stato"=>$stato),
	array("assente"=>$str_assente),
	array("presente"=>$str_presente),
	);
	echo json_encode($return);

?>