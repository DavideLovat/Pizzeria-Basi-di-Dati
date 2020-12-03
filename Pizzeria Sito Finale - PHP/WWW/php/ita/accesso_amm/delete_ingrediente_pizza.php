<?php
	try
	{
		session_start();
		require_once("../shared/credenziali_db.php");
		$_SESSION['amministratore'] = true;
		$_GET['tipo'] = "marcello";
		$_GET['old_ing'] = "aglio";	
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			if(isset($_GET['tipo'],$_GET['old_ing']) && !empty($_GET['tipo']) && !empty($_GET['old_ing']))
			{
				$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
				$dbh = new PDO($dsn);
				try
				{
					if($dbh -> beginTransaction())
					{
						$sql = "
							select tipo
							from pizze
							where tipo = :tipo
						";
						$sth = $dbh -> prepare($sql);
						$sth -> bindParam(':tipo',$_GET['tipo'],PDO::PARAM_STR);
						if($sth -> execute())
						{
							if($result = $sth -> fetchColumn())
							{
								$sql = "
										select *
										from contiene
										where idpiz = :idpiz and iding = :iding
								";
								$sth = $dbh -> prepare($sql);
								$sth -> bindParam(':idpiz',$_GET['tipo'],PDO::PARAM_STR);
								$sth -> bindParam(':iding',$_GET['old_ing'],PDO::PARAM_STR);
								if($sth -> execute())
								{
									if($result = $sth -> fetch())
									{
										$sql = "
												select COUNT(*)
												from contiene
												where idpiz = :tipo
												group by idpiz
										";
										$sth = $dbh -> prepare($sql);
										$sth -> bindParam(':tipo',$_GET['tipo'],PDO::PARAM_STR);
										if($sth -> execute())
										{
											if($count = $sth -> fetchColumn())
											{
												if($count > 1)
												{
													$sql = "
															delete from contiene
															where idpiz = :tipo and iding = :old_ing and							
															(select COUNT(*)
															from contiene
															where idpiz = :tipo
															group by idpiz)>1
													";
													$sth = $dbh -> prepare($sql);
													$sth -> bindParam(':tipo',$_GET['tipo'],PDO::PARAM_STR);
													$sth -> bindParam(':old_ing',$_GET['old_ing']);
													if($sth -> execute())
													{
														$dbh -> commit();
														$stato = "successo";
													}
													else
													{
														$stato = "server_error";
														throw new Exception("errore execute delete riga da tabella contiene");
													}
												}
												else
												{
													
													$stato = "nodelete";
													throw new Exception("numero ingredienti pizza rimasti pari a uno");
												}
											}
											else
											{
												$stato = "server_error";
												throw new Exception("errore fetchColumn sql conta elementi in tabella contiene");
											}
										}
										else
										{
											$stato = "server_error";
											throw new Exception("errore execute sql conta elementi in tabella contiene");
										}
									}
									else
									{
										$stato = "nocontiene";
										throw new Exception("ingrediente {$_GET['old_ing']} in pizza {$_GET['tipo']} non esiste in tabella contiene");
									}	
								}
								else
								{
									$stato = "server_error";
									throw new Exception("errore execute sql cerca idpiz e iding da contiene");	
								}
								
							}
							else
							{
								$stato = "notipo";
								throw new Exception("tipo assente");
							}
						}
						else
						{
							$stato = "server_error";
							throw new Exception("errore execute sql ricerca tipo in tabella pizze");
						}
					}
					else
					{
						$stato = "server_error";
						throw new Exception("errore begin Transaction");
					}
				}
				catch(PDOException $e)
				{
					if(isset($dbh))
					{
						$dbh -> rollBack();
						$dbh = null;
					}
					if(!isset($stato) || empty($stato))
					{
						$stato = "server_error";
					}
					echo "error: " . $e->getMessage();
				}
				catch(Exception $e)
				{
					if(isset($dbh))
					{
						$dbh -> rollBack();
						$dbh = null;
					}
					if(!isset($stato) || empty($stato))
					{
						$stato = "server_error";
					}
					echo "error: " . $e->getMessage();
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
		echo "error: " . $e->getMessage();
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
		echo "error: " . $e->getMessage();
	}
	
	$return = array(
	array("stato"=>$stato),
	);
	echo json_encode($return);
?>