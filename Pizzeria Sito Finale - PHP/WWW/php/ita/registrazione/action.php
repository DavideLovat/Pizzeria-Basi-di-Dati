<?php
session_start();
if(isset($_POST['login']) && !empty($_POST['login']))
{
	try
	{
	/* codi di controllo per verificare l'esistenza del Login nel DB */
		require_once("../shared/credenziali_db.php");
		$l = false; 	/* variabile di controllo per il login ->(false:login in uso , true:login non in uso) */
		$c = false; 	/* variabile di controllo della connessione ->(false: query non è stata eseguita, true: query eseguita */
		
		$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
		$dbh = new PDO($dsn);

		$sql = "SELECT COUNT(login) AS num_log FROM utenti WHERE login = ? GROUP BY login";
		$sth = $dbh->prepare($sql);
		
		$login = $_POST['login'];
		$sth->bindParam(1,$login,PDO::PARAM_STR);
		
		if($sth->execute())
		{
			$result = $sth->fetchColumn();
			$c = true;
			if($result>0)
			{$l = false;}
			else
			{$l = true;}
			
			
		}
		else
		{
			throw new Exception('errore esterno 1');
		}
		
	/* Fine codice di controllo per verificare l'esitenza del Login nel DB */
	/* controllo valori delle variabili $l e $c con 3 possibili casi: */
	/* 1)se ($l->true,$c->true) inserire dati del form nel DB e inviare l'utente alla pagina di conferma inserimento dati */	
	/* 2)se ($l->false,$c->true) inviare l'utente alla pagina di sottoscrizione dei dati e settare la variabile di sessione messaggio($_SESSION['messaggio']) informare della presenza del login */
	/* 3)se ($l->?,$c->false) inviare l'utente alla pagina di sottoscrizione dei dati e settare la variabile di sessione messaggio($_SESSION['messaggio']) per informare di problemi nella connessione */

	/* caso (1) */
			if($l && $c)
			{
				$post = array($_POST['password'],$_POST['nome'],$_POST['cognome'],$_POST['telefono'],$_POST['via'],$_POST['ncivico'],$_POST['cap'],$_POST['citta']); 
				$b = true;
				foreach($post as $val)
				{	
					if(!isset($val) || empty($val))
					{
						$b=false;
						$dbh = null;
						$_SESSION['messaggio']="campovuoto";
						header("Location: http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/registrazione");
					}
				}
				
				if($b)
				{require_once("insert_utente.php");}
			
			}
	/*caso (2) */
			else if(!$l && $c)
			{
				
				$dbh=null;
				$_SESSION['messaggio']="logusato";
				header("Location: http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/registrazione");
				
			}
	/*caso (3) */
			else
			{
				throw new Exception('errore esterno 2');
			}
	}
	catch(PDOException $e)
	{
		$dbh=null;
		$_SESSION['messaggio']="dberror";
		header("Location: http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/registrazione");
		//print("error: ".$e->getMessage());
	}
	catch(Exception $e)
	{
		$dbh=null;
		$_SESSION['messaggio']="dberror";
		header("Location: http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/registrazione");
		//print("error: ".$e->getMessage());
	}
}
else
{
	$_SESSION['messaggio']="logvuoto";

	header("Location: http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/registrazione");

}
?>