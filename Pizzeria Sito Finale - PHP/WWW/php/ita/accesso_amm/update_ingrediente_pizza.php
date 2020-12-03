<?php
	try
	{	
		session_start();
		//$_SESSION['amministratore']= true;
		//$_GET['tipo'] = "marcello";
		//$_GET['old_ing'] = "aglio";
		//$_GET['new_ing'] = "basilico";
		//echo"<br> sessione:".print_r($_SESSION,true)."<br>";
		//echo"<br> get:".print_r($_GET,true)."<br>";
		
		$sql_tipo = "
				select *
				from pizze
				where tipo = :tipo
		";
		
		$sql_sel1 = "
				select *
				from contiene 
				where idpiz = :tipo and iding = :old_nome or iding = :new_nome
		";
		$sql_sel2 = "
				select nome
				from ingredienti
				where nome = :new_nome
		";
		$sql_up1 = "
				update contiene
				set iding = :new_nome
				where idpiz = :tipo and iding = :old_nome
		";
		
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			if(isset($_GET['tipo'],$_GET['new_ing'],$_GET['old_ing']) && !empty($_GET['tipo']) && !empty($_GET['new_ing']) && !empty($_GET['old_ing']))
			{
				$tipo = $_GET['tipo'];
				$old_nome = $_GET['old_ing'];
				$new_nome = $_GET['new_ing'];
				$check_select = false;


						require_once("../shared/credenziali_db.php");
						$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
						$dbh = new PDO($dsn);
						$sth = $dbh -> prepare($sql_tipo);
						$sth -> bindParam(':tipo',$tipo,PDO::PARAM_STR);
						if($sth -> execute())
						{
							if($sth -> fetchColumn())
							{
								$sth = $dbh -> prepare($sql_sel1);
								$sth -> bindParam(':tipo', $tipo, PDO::PARAM_STR);
								$sth -> bindParam(':old_nome', $old_nome, PDO::PARAM_STR);
								$sth -> bindParam(':new_nome', $new_nome, PDO::PARAM_STR);
								if($sth -> execute())
								{
									if($result = $sth -> fetchAll())
									{
										foreach($result as $indice => $array)
										{
											if($array['idpiz'] == $tipo)
											{
												if($array['iding'] == $new_nome)
												{
													$check_select = false;
													$stato = "contiene_presente_new";
													throw new Exception("valore iding $new_nome già presente in idpiz");
												}
												if($array['iding'] == $old_nome)
												{
													$check_select = true;
												}
											}
										}
										if($check_select)
										{
												//esegui la seconda query sql_sel2
												$sth = $dbh -> prepare($sql_sel2);
												$sth -> bindParam(':new_nome', $new_nome, PDO::PARAM_STR);
												if($sth -> execute())
												{
													if($result = $sth -> fetchColumn())
													{
														if($result == $new_nome)
														{
															$exists_ingredienti = true;
															/////////////////////////////////////////////////
															//update ingrediente in contiene

															try
															{
																		if($dbh -> beginTransaction())
																		{
																			$sth = $dbh -> prepare($sql_up1);
																			$sth -> bindParam(':tipo', $tipo, PDO::PARAM_STR);
																			$sth -> bindParam(':old_nome', $old_nome, PDO::PARAM_STR);
																			$sth -> bindParam(':new_nome', $new_nome, PDO::PARAM_STR);
																			if($sth -> execute())
																			{
																				$dbh -> commit();
																				$dbh = null;
																				//echo "success";
																				$stato = "successo";
																			}
																			else
																			{
																				$stato = "server_error";
																				throw new Exception ("errore execute sql_up1");
																			}
																		}
																		else
																		{
																			$stato = "server_error";
																			throw new Exception ("errore inizio transazione sql_up1");
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
			
														///////////////////////////////////////////////
														}
														else
														{
															$stato = "assente";
															throw new Exception("valore nome $new_nome non presente in tabella ingredienti in db");
														}
													}
													else
													{
														$stato = "assente";
														throw new Exception("valore nome $new_nome non presente in tabella ingredienti in db");
													}
												}
												else
												{
													$stato = "server_error";
													throw new Exception("errore execute sql_sel2");
												}
										}
										else
										{
											$stato = "contiene_assente_old";
											throw new Exception("valore idpiz $tipo con iding $old_nome non presente in contiene in db");
										}
									}
									else
									{
										$stato = "contiene_assente_old";
										throw new Exception("valore idpiz $tipo con iding $old_nome non presente in contiene in db");
									}
								}
								else
								{
									$stato = "error_server";
									throw new Exception("errore execute sql_sel1");
								}

							}
							else
							{
								$stato = "notipo";
							}
						}
						else
						{
							$stato = "server_error";
							throw new Exception("errore execute sql_tipo");
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
	array("assente" => $new_nome),
	array("presente" => ""),
	array("contiene_assente_old" => $old_nome),
	array("contiene_presente_new" => $new_nome),
	);
	echo json_encode($return);

?>
