<?php
	try
	{
		session_start();
		header('content-Type: text/html;charset=UTF-8');
		require_once("../shared/credenziali_db.php");
?>
<?php
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			if(isset($_GET['login']) && !empty($_GET['login']))
			{
				$login = $_GET['login'];
				$sql = "
					select login
					from utenti
					where login = :login
				";
				
					$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
					$dbh = new PDO($dsn);
					$sth = $dbh -> prepare($sql);
					$sth -> bindParam(':login', $login, PDO::PARAM_STR);
					if($sth -> execute())
					{
						if($result = $sth -> fetchColumn())
						{
							if($result == $login)
							{
								$stato = "successo";
							}
							else
							{
								$stato = "nologin";
								throw new Exception("login assente in tabella pizze db");
							}
						}
						else
						{
							$stato = "nologin";
							throw new Exception("login assente in tabella pizze db");
						}
					}
					else
					{
						$stato = "server_error"; 
						throw new Exception("errore execute ");
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