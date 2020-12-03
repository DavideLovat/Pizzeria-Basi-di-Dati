<?php
	session_start();
	header('content-Type: text/html;charset=UTF-8');
	
	$sql = "
		select * 
		from indirizzi 
		where idind = (select codind from utenti where login = :login)
	";
try
{	
	require_once("../shared/credenziali_db.php");
	$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
	$dbh = new PDO($dsn);
	$sth = $dbh -> prepare($sql);
	$sth -> bindParam(':login',$_SESSION['utente']['login'],PDO::PARAM_STR);
	if($sth -> execute())
	{
		$result = $sth -> fetch(PDO::FETCH_ASSOC);
		echo"
			  <fieldset class=\"tratto\">
			  <legend>Address </legend>  
			  <div class=\"fm-req\">
				<label for=\"via\">via:</label>
				<input id=\"via\" name=\"via\" type=\"text\" value=\"{$result['via']}\">
			  </div>   

			  <div class=\"fm-req\">
				  <label for=\"ncivico\">n&deg;civico:</label>
				  <input id=\"ncivico\" name=\"ncivico\" type=\"text\" value=\"{$result['ncivico']}\">
			  </div>   

			  <div class=\"fm-req\">
				  <label for=\"cap\">cap:</label>
				  <input id=\"cap\" name=\"cap\" type=\"text\" value=\"{$result['cap']}\">
			  </div>

			  <div class=\"fm-req\">
				  <label for=\"città\">Citt&agrave;:</label>
				  <input id=\"citta\" name=\"citta\" type=\"text\" value=\"{$result['citta']}\">
			  </div>   
			 </fieldset>	  
		";

	}
	else
	{
		throw new Exception("errore exception");
	}
}
catch(PDOException $e)
{
	
	echo"
		<fieldset class='tratto'>
		  <legend>Address </legend>  
		  <div class='fm-req'>
		  	<label for='via'>via:</label>
		 	 <input id='via' name='via' type='text'>
		  </div>   

		  <div class='fm-req'>
			  <label for='ncivico'>n&deg;civico:</label>
			  <input id='ncivico' name='ncivico' type='text'>
		  </div>   

		  <div class='fm-req'>
			  <label for='cap'>cap:</label>
			  <input id='cap' name='cap' type='text'>
		  </div>

		  <div class='fm-req'>
			  <label for='città'>Citt&agrave;:</label>
			  <input id='citta' name='citta' type='text'>
		  </div>   
		  </fieldset>	  
	";
	echo"
		<script>alert(\"impossibile caricare info utente\");</script>
	";
}
catch(Exception $e)
{
	
}
?>
