<?php
	try
	{	
		session_start();
		$_SESSION['amministratore']= true;
		$_GET['tipo'] = "diavola";
		//echo"<br> sessione:";print_r($_SESSION);echo"<br>";
		//echo"<br> get:";print_r($_GET);echo"<br>";
		$sql_sel1 = "
				select tipo 
				from pizze 
				where tipo = :tipo
		";
		$sql_del1 = "
				delete from contiene
				where idpiz = :tipo
		";
		$sql_del2 = "
				delete from pizze
				where tipo = :tipo
		";
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			if(isset($_GET['tipo']) && !empty($_GET['tipo']))
			{	
				$tipo = $_GET['tipo'];
				
				

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
									//echo"risultato sql_sel1: ".print_r($result,true)."<br>";
									
									///////////////////////////////////
									
									try
									{
										if($dbh -> beginTransaction())
										{
											$sth = $dbh -> prepare($sql_del1);
											$sth -> bindParam(':tipo', $tipo, PDO::PARAM_STR);
											if($sth -> execute())
											{
												$sth = $dbh -> prepare($sql_del2);
												$sth -> bindParam(':tipo', $tipo, PDO::PARAM_STR);
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
													throw new Exception("errore execute sql_del2");
												}
											}
											else
											{
												$stato = "server_error";
												throw new Exception("errore execute sql_del1");
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
									
									//////////////////////////////////
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
						//esegue la query sql1_del1 e sql_del2 dentro un blocco di inizio transazione se il tipo esiste
						
				
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