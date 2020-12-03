<?php
	try
	{

	session_start();
	header('content-Type: text/html;charset=UTF-8');

	require_once("../shared/credenziali_db.php");

	if(isset($_POST['login'],$_POST['password']) && !empty($_POST['login']) && !empty($_POST['password']))
	{	
		$login = $_POST['login'];
		$password = $_POST['password'];
		$sql = "select * from utenti where login=:login and password=:password ";
		$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
		$dbh = new PDO($dsn);
		$sth = $dbh -> prepare($sql);
		$sth -> bindParam(':login',$login,PDO::PARAM_STR);
		$sth -> bindParam(':password',$password,PDO::PARAM_STR);
		if($sth -> execute())
		{
			if($result = $sth->fetch())
			{
				$_SESSION['utente'] = array("login"=>$result['login'],"nome"=>$result['nome']);
				//gestione amministratore
				
				$s_login = $_SESSION['utente']['login'];
				$login = "amministratore";
				$s_nome = $_SESSION['utente']['nome'];
				$nome = "amministratore";
				if(!strcmp($s_login,$login) && !strcmp($s_nome,$nome))
				{
					$_SESSION['amministratore'] = true;
				}
				else
				{
					$_SESSION['amministratore'] = false;
				}

				//fine gestione amministratore
				$stato = "success";
			}
			else
			{
				$stato = "failure";			
			}
		}
		else
		{
			throw new Exception("errore execute");
		}
		
	}
	else
	{
		$stato = "failure";
	}
	}
	catch(PDOException $e)
	{
		$stato = "failure";
	}
	catch(Exception $e)
	{
		$stato = "failure";
	}
	
	$return = array("stato"=>$stato);

	echo json_encode($return);

?>
