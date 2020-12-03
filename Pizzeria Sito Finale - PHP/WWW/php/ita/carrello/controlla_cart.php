<?php
	if(isset($_SESSION['cart']) && !empty($_SESSION['cart']))
	{	//$bool=true;
		$sql = "select * from pizze where tipo = :tipo"; 
		require_once("../shared/credenziali_db.php");
		$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
		$dbh = new PDO($dsn);
		$sth = $dbh->prepare($sql);
		
		
		foreach($_SESSION['cart'] as $key_pizza=>$array)
		{
			$sth->bindParam(':tipo', $array['tipo'], PDO::PARAM_STR);
			//$sth->bindValue(':tipo', 'marra', PDO::PARAM_STR);

			$sth->execute();
			if(!$result = $sth->fetch())
			{
				echo "<div>{$array['tipo']} non disponibile</div>";
				/*if($bool)
				{
				echo"
						<table id=\"cestino\">
						</table>
					";
					$bool=false;
				}
				echo"	
						<script>$.(\"#cestino\").append(\"<tr><td>{$array['tipo']} non disponibile</td></tr>\")</script>
					";*/
				unset($_SESSION['cart'][$key_pizza]);
				if(empty($_SESSION['cart']))
				{unset($_SESSION['cart']);}	
			}
		}
		
	}
?>