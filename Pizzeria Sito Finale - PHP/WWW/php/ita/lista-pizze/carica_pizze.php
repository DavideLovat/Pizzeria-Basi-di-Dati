<?php
	try
	{	require_once("../shared/credenziali_db.php");
		
		$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
		$dbh = new PDO($dsn);
		
		$sql1 = "
			select * 
			from pizze 
			where tipo like :word and tipo not in
			(select tipo from pizze_q_null where tipo like :word)
			order by $ordina
			";
		$sql2 = "
			select c.idpiz,c.iding,vm.min 
			from contiene c,ing_q_min vm 
			where vm.idpiz = c.idpiz and c.idpiz like :word and c.idpiz not in
			(select v.tipo from pizze_q_null v where v.tipo like :word) 
			order by c.idpiz
			";
			
		if($stampa)
		{		
				$sth = $dbh -> prepare($sql1);
					if($lettera)
					{	$word_upper = "a";//strtoupper($word);
						if($sth -> execute(array(":word"=>"$word%")))
						{
							$result1 = $sth->fetchAll();
						}
						else
						{
							throw new Exception("errore execute sql1");
						}
						
						$sth = $dbh -> prepare($sql2);
						if($sth -> execute(array(":word"=>"$word%")))
						{
							$result2 = $sth->fetchAll();
						}
						else
						{
							throw new Exception("errore execute sql2");
						}
					}
					else
					{
						if($sth -> execute(array(":word"=>"%$word%")))
						{
							$result1 = $sth->fetchAll();
						}
						else
						{
							throw new Exception("errore execute sql1");
						}
						
						$sth = $dbh -> prepare($sql2);
						if($sth -> execute(array(":word"=>"%$word%")))
						{
							$result2 = $sth->fetchAll();
						}
						else
						{
							throw new Exception("errore execute sql2");
						}
					}
					//print_r($result1);
					echo'<br>';
					//print_r($result2);
					echo"<div id=\"info\"></div>";
					echo "<div class=\"CSSTableGenerator\">";		
					echo"<table id=\"padre\">
					<tr>
					<td>nome</td>
					<td>prezzo</td>
					<td>ingredienti</td>
					<td>numero elementi</td>
					<td>aggiungi</td>
					</tr>
					";
					if(!empty($result1) && !empty($result2))
					{ 
								foreach($result1 as $arr1)
								{	$find = false;
									$figlio ="{$arr1 ['tipo']}";
																		
									foreach($result2 as $arr2)
									{
										if($arr1['tipo'] == $arr2['idpiz'])
										{
											if(!$find)
											{	echo"	
													<tr id=\"$figlio\">
													<td class=\"tipo\"><p class=\"tipo\">{$arr1['tipo']}</p></td>
													<td class=\"prezzo\"><p class=\"prezzo\">{$arr1['prezzo']}</p></td>
													<td class=\"ingredienti\">
													";
												$find = true;
											}
											echo "<p>{$arr2['iding']}</p>";
										}
									}
									echo"
										</td>
										<td class=\"quantita\">
											<input type=\"number\" class=\"quantita\" value=\"1\" min=\"1\" max=\"100\">
											<div class=\"div_quantita\">1</div>
											<div class=\"div_quantita_carrello\">
									";
												if(isset($_SESSION['cart'][$figlio]['quantita'])&& !empty($_SESSION['cart'][$figlio]['quantita']))
												{echo "quantita carrello {$_SESSION['cart'][$figlio]['quantita']}";	}
												else
												{echo "quantita carrello 0";}
									echo"		</div>
										</td>
										<td class=\"aggiungi\">
											<button class=\"aggiungi\" type=\"button\">aggiungi</button> 
											<div class=\"div_aggiungi\">3</div> 
										</td>
										</tr>
									";
									$count++;
								}
					}
					else
					{
								echo "<tr><td colspan=\"4\"><p>nessun elemento</p></td></tr>";
					}
								echo "</table>";
								echo "</div>";
								echo "<div id=\"infoaggiungi\">4</div>";
								echo "<div class=\"div_lettera\">";
			
									$alfabeto = range('a','z');
									foreach($alfabeto as $val)
									{
											if($val == $word && $_SESSION['chiamata'] == "char")
											{
												echo"
													|<a class=\"lettere lettere_segnate\" href=\"index.php?char=$val&ordina=$ordina\">$val</a>
												";	
											}
											else
											{
												echo"
													|<a class=\"lettere\" href=\"index.php?char=$val&ordina=$ordina\">$val</a>
												";	
											}
									}
								echo "</div>";

		}
		else
		{
			echo "nessun elemento trovato nella ricerca";
		}
		//$str = print_r($_SESSION,true);
		//echo "$str";
	}
	catch(PDOException $e)
			{
				echo 'errore:'.$e->getMessage();
			}
	catch(Exception $e)
	{
		echo 'errore:'.$e->getMessage();
	}

	?>
