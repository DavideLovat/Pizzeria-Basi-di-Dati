<?php
	try
	{
		session_start();
		/*
		$_SESSION['amministratore']= true;
		$_GET['idord']=48; 
		$_GET['codute']="davide";
		*/
		//echo"<br> sessione:";print_r($_SESSION);echo"<br>";
		//echo"<br> get:";print_r($_GET);echo"<br>";

		$sql_sel1 = "
				select idord
				from ordini
				where idord = :idord and codute = :codute
		";
		$sql_del1 = "
			delete from registrazione
			where idord = :idord
		";
		$sql_del2 = "
			delete from ordini
			where idord = :idord and codute = :codute
		";
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			if(isset($_GET['idord'],$_GET['codute']) && !empty($_GET['idord']) && !empty($_GET['codute']))
			{
				$idord = $_GET['idord'];
				$codute = $_GET['codute'];
				//$delete_registrazione = false;
				//$delete_ordini = false;
				

					require_once("../shared/credenziali_db.php");
						$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
						$dbh = new PDO($dsn);
						$sth = $dbh -> prepare($sql_sel1);
						$sth -> bindParam(':idord', $idord, PDO::PARAM_INT);
						$sth -> bindParam(':codute', $codute, PDO::PARAM_STR);
						if($sth -> execute())
						{
							if($result = $sth -> fetchColumn())
							{
								if($result == $idord)
								{
									//////////////////////////////////////////

									try
									{
											if($dbh -> beginTransaction())
											{
												$sth = $dbh -> prepare($sql_del1);
												$sth -> bindParam(':idord', $idord,PDO::PARAM_INT);
												if($sth -> execute())
												{
													$sth = $dbh -> prepare($sql_del2);
													$sth -> bindParam(':idord', $idord,PDO::PARAM_INT);
													$sth -> bindParam(':codute', $codute,PDO::PARAM_STR);
													if($sth -> execute())
													{
														$dbh -> commit();
														$dbh = null;													
														$stato = "successo";
														//echo "success";
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
												throw new Exception("errore begin transaction sql_up1");
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

									/////////////////////////////////////////
								}
								else
								{
									$stato = "noordine";
									throw new Exception("valore idord non presente in db");
								}
							}
							else
							{
								$stato = "noordine";
								throw new Exception("valore idord non presente in db");
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
	array("stato" => $stato),
	);
	echo json_encode($return);

?>