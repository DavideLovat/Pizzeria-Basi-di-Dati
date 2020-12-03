<?php 
session_start();
/*$_POST['idord'] = "62";
$_SESSION['utente']['login']="davide";
$_SESSION['utente']['nome']="davide";
print_r($_SESSION['utente']);
*/
if(isset($_SESSION['utente']) && !empty($_SESSION['utente']) && !empty($_SESSION['utente']['login']) && !empty($_SESSION['utente']['nome']))
{	
	if(isset($_POST['idord']) && !empty($_POST['idord']))
	{	
		$idord = $_POST['idord'];
		$login = $_SESSION['utente']['login'];
		//controllare sessione utente
		//controllare se sono settate le variabili post
		//controllare che sia l'utente registrato
		try
		{	
			require_once("../shared/credenziali_db.php");
			$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
			$dbh = new PDO($dsn);
			if($dbh->beginTransaction())
			{	
				$sql ="select * from ordini where idord=:idord and codute=:login";
				$sth = $dbh -> prepare($sql);
				$sth -> bindParam(':idord',$idord,PDO::PARAM_INT);
				$sth -> bindParam(':login',$login,PDO::PARAM_STR);
				if($sth -> execute())
				{
					if($sth->fetch())
					{
						
						
						$sql = "delete from registrazione where idord=:idord";
						$sth = $dbh -> prepare($sql);
						$sth -> bindParam(':idpiz',$idpiz,PDO::PARAM_STR);
						$sth -> bindParam(':idord',$idord,PDO::PARAM_INT);
						if($sth -> execute())
						{
							$sql = "select * from registrazione where idord=:idord";
							$sth = $dbh -> prepare($sql);
							$sth -> bindParam(':idord',$idord,PDO::PARAM_INT);
							if($sth -> execute())
							{	if(!$sth->fetch())
								{
									$sql = "delete from ordini where idord=:idord and codute=:login";
									$sth = $dbh -> prepare($sql);
									$sth -> bindParam(':login',$login,PDO::PARAM_STR);
									$sth -> bindParam(':idord',$idord,PDO::PARAM_INT);
									if($sth ->execute())
									{
										$sql = "select idord from ordini where idord=:idord and codute=:login";
										$sth = $dbh -> prepare($sql);
										$sth -> bindParam(':login',$login,PDO::PARAM_STR);
										$sth -> bindParam(':idord',$idord,PDO::PARAM_INT);
										if($sth -> execute())
										{	
											if(!$sth->fetch())
											{
												$dbh -> commit(); //echo "successo";
												$stato = "true";
											}
											else
											{
												throw new Exception ("errore fetch controllo eliminazione ordine");
											}
										}
										else
										{
											throw new Exception ("errore execute controllo eliminazione ordine");
										}
									}	
									else
									{
										throw new Exception ("errore execute eliminazione ordine");
									}	
								}
								else
								{
									throw new Exception ("errore fetch controllo eliminazione registrazione");
								}		
							}
							else
							{
								throw new Exception ("errore execute controllo eliminazione registrazione");

							}
						}
						else
						{
							throw new Exception ("errore execute eliminazione registrazione");
						}
					}
					else
					{
						throw new Exception ("errore fetch select ordine");
					}
				}
				else
				{
					throw new Exception ("errore execute");
				}
			}
			else
			{
				throw new Exception ("errore inizio transazione");
			}
		}
		catch(PDOException $e)
		{
				if(isset($dbh))
				{
					$dbh -> rollBack();
					$dbh = null;
				}
				//echo $e->getMessage();				
				//echo '<br>ordine non cancellato';
				$stato = "false";	
				
		}
		catch(Exception $e)
		{
				if(isset($dbh))
				{
					$dbh -> rollBack();
					$dbh = null;
				}
				//echo $e->getMessage();				
				//echo '<br>ordine non cancellato';
				$stato = "false";
		}
	}
	else
	{
		//echo "no post idordine";
		$stato = "false";
	}
}
else
{
	//echo "no sessione utente";
	$stato = "false";
}
$return = array("stato"=>$stato);
echo json_encode($return);
?>