<?php
try
{	
/* Inizia la transazione, disabilita autocommit */
	
	if($dbh->beginTransaction())
	{
		$indirizzo = array(':via'=>$_POST['via'],':ncivico'=>$_POST['ncivico'],':cap'=>$_POST['cap'],':citta'=>$_POST['citta']);

		$utente = array(':login'=>$_POST['login'],':password'=>$_POST['password'],':nome'=>$_POST['nome'],':cognome'=>$_POST['cognome'],':telefono'=>$_POST['telefono']);
		
		/*
		print_r($indirizzo);
		print_r($utente);
		*/	
		$sql =	'SELECT idind FROM indirizzi WHERE via=:via AND ncivico=:ncivico AND cap=:cap AND citta=:citta';
		
		$sth = $dbh->prepare($sql);
		foreach($indirizzo as $sign=>$val)
		{
			$sth->bindValue($sign, $val, PDO::PARAM_STR);
		}

		if($sth->execute())
		{
			$result = $sth->fetchColumn();
			
			
			if($result == "")
			{
				$sql =	'INSERT INTO indirizzi(idind,via,ncivico,cap,citta) VALUES(DEFAULT,:via,:ncivico,:cap,:citta) RETURNING(idind)';
				$sth = $dbh->prepare($sql);
				
				foreach($indirizzo as $sign=>$val)
				{
					$sth->bindValue($sign, $val, PDO::PARAM_STR);
				}


				if($sth->execute())
				{
					$result = $sth->fetchColumn();
					
				}
				else
				{
					throw new Exception('errore file interno 1'); 
				}
			}
			$sql = 'INSERT INTO utenti(login,password,nome,cognome,telefono,codind) VALUES(:login,:password,:nome,:cognome,:telefono,:codind)';
			$sth = $dbh->prepare($sql);
			foreach($utente as $sign=>$val)
			{
				$sth->bindValue($sign, $val, PDO::PARAM_STR);
			}
			$sth->bindParam(':codind', $result, PDO::PARAM_INT);
			if($sth->execute())
			{	
				if($dbh->commit())
				{
					$dbh = null;
					header("Location:  http://wwwstud.dsi.unive.it/dlovat/WWW/html/ita/shared/registrazione_successo.html");	
				}
				else
				{
					
					throw new Exception('errore file interno 2');
						
				}
		
			}
			else
			{
				throw new Exception('errore file interno 3');	
			}
		}
		else
		{
			throw new Exception("errore file interno 4");
		}
	}
	else
	{
		throw new Exception('errore file interno 5'); 

	}

		
}
catch(PDOException $e)
{
	$dbh->rollBack();
	$dbh=null;
	$_SESSION['messaggio']="dberror";
	header("Location: http://wwwstud.dsi.unive.it/dlovat/WWW/html/ita/shared/errore_server.html");	
	//print("error: ".$e->getMessage());
}
catch(Exception $e)
{
	$dbh->rollBack();
	$dbh=null;
	$_SESSION['messaggio']="dberror";
	header("Location: http://wwwstud.dsi.unive.it/dlovat/WWW/html/ita/shared/errore_server.html");
	//print("error: ".$e->getMessage());
}
/* Connessione del DataBase ritorna in modalità autocommit */		
?>
	
