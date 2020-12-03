<?php
if(!isset($_SESSION['utente']['login'],$_SESSION['utente']['nome']))
{
	if(isset($_POST['login'],$_POST['password']) && !empty($_POST['login']) && !empty($_POST['password']))
	{
		$_SESSION['dati'] = true;
	}
	else
	{
		$_SESSION['dati'] = false;
	}

	if($_SESSION['dati'] == true)
	{
		try
		{
		$login = $_POST['login'];
		$password = $_POST['password'];

		$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
		$dbh = new PDO($dsn);
		
		$sql = "SELECT * FROM utenti WHERE login=:login AND password=:password";
		$sth = $dbh->prepare($sql);

		$sth->bindParam(':login', $login, PDO::PARAM_STR);
		$sth->bindParam(':password', $password, PDO::PARAM_STR);

		$sth->execute();
		
		if($result = $sth->fetch(PDO::FETCH_ASSOC))
		{	
			if($result['login'] == $login && $result['password'] == $password )
			{
				$nome = $result['nome'];
				$_SESSION['utente'] = array('login'=>$login,'nome'=>$nome);
				$_SESSION['accesso'] = true;
			}
			else
			{
				$_SESSION['accesso'] = false;
			}
			
		}
		else
		{
			$_SESSION['accesso'] = false;
		}
		}
		catch(PDOException $e)
		{
			$_SESSION['accesso'] = false;
			echo $e->getMessage();
		}
	}
	else if($_SESSION['dati'] == false)
	{
		$_SESSION['accesso'] = false;
	}
}
//print('$_SESSION[accesso] '.$_SESSION['accesso']."<br>");
//print('$_SESSION[dati]'.$_SESSION[dati]);
?>
