<?php 
	try
	{
		$stampa;
		$word;
		$alfabeto = range('a','z');
		$sql_search = "
			SELECT tipo FROM pizze
			ORDER BY tipo ASC
			LIMIT 1
		";
		
		if(isset($_GET['char']) && !empty($_GET['char']))
		{	
			foreach($alfabeto as $val)
			{
				if(!strcmp($_GET['char'],$val))
				{	
					$lettera = true;
					$word = $_GET['char'];
					$_SESSION['word']= $word;
					$_SESSION['chiamata'] = "char";
					$stampa = true;
					break;
				}
			}

		}
		else if(isset($_GET['string']) && !empty($_GET['string']))
		{
			$word = $_GET['string'];
			$_SESSION['word']= $word;
			$_SESSION['chiamata'] = "string";
			$stampa = true;
			$lettera = false;
		}
		else
		{	
			$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
			$dbh = new PDO($dsn);

			$sth = $dbh->query($sql_search);
	
			if($result_first = $sth->fetchColumn())
			{
				$ascii_char = ord($result_first);
				$word = chr($ascii_char);
				$_SESSION['word']= $word;
				$lettera = true;
				$stampa = true;
				$_SESSION['chiamata'] = "char";
			}
			else
			{
				$stampa = false;
				unset($_SESSION['word']);
				$_SESSION['chiamata'] = "nulla";
			}
			
		}
		
		if(isset($_GET['ordina']))
		{
			switch($_GET['ordina'])
			{
			case "tipo":
				$ordina = "tipo";
				break;
			case "prezzo":
				$ordina = "prezzo";
				break;
			default: $ordina = "tipo";			

			}
		}
		else
		{$ordina = "tipo";	}
		$dbh = null;
	}
	catch(PDOException $e)
	{
		$dbh = null;
		
	}

?>