<?php
	session_start();
	header('content-Type: text/html;charset=UTF-8');
	
	require_once("../shared/credenziali_db.php");
	echo "accesso".$_SESSION['accesso']."<br>";
	print_r ($_SESSION);
	if(isset($_SESSION['utente']['login'],$_SESSION['utente']['nome']) && !empty($_SESSION['utente']['login']) && !empty($_SESSION['utente']['nome']))
	{
		/*$_SESSION['order']['marc']['tipo'] = 'marc';
		$_SESSION['order']['marc']['prezzo'] = '3 euro';
		$_SESSION['order']['marc']['prezzo_lista'] = '2 euro';
		$_SESSION['order']['marc']['quantita'] = '5';

		$_SESSION['order']['mino']['tipo'] = 'mino';
		$_SESSION['order']['mino']['prezzo'] = '3 euro';
		$_SESSION['order']['mino']['prezzo_lista'] = '2 euro';
		$_SESSION['order']['mino']['quantita'] = '5';*/
		
		echo"	
			<div id=\"info\"></div>
			<div class=\"CSSTableGenerator\">	
			<table id=\"noorder\" border=\"1\">
				<tr><td colspan=\"4\">Elementi Ordine modificati</td></tr>
				<tr>
					<td>Pizza</td>
					<td>Prezzo</td>
					<td>Quantita</td>
					<td>Totale</td>	
				</tr>
			</table>
			</div>
			<br>
			<div class=\"CSSTableGenerator\">
			<table id=\"order\" border=\"1\">
				<tr><td colspan=\"4\">Elementi Ordine</td></tr>
				<tr>
					<td>Pizza</td>
					<td>Prezzo</td>
					<td>Quantita</td>
					<td>Totale</td>	
				</tr>

			</table>
			</div>
			<div id=\"scelta\">
			</div>
		";
		echo"sessione ordini:  "; print_r($_SESSION['order']); echo "<br>";
		echo"sessione cart :  "; print_r($_SESSION['cart']); echo "<br>";
?>
<?php
	
		if(isset($_SESSION['order'],$_SESSION['consegna']) && !empty($_SESSION['order']) && !empty($_SESSION['consegna']) && !empty($_SESSION['consegna']['giorno']) && !empty($_SESSION['consegna']['ora']))
		{	
			$empty_noorder = true;
			$empty_order = true;
			$sql = "select * from pizze where tipo = :tipo"; 
				$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
				$dbh = new PDO($dsn);
				$sth = $dbh->prepare($sql);
			
			foreach($_SESSION['order'] as $key_pizza=>$array)
			{
				$sth->bindParam(':tipo', $array['tipo'], PDO::PARAM_STR);
				$sth->execute();
				if(!$result = $sth->fetch())
				{	
					if($empty_noorder)
					{
						$empty_noorder = false;
					}
					
					$tipo = $_SESSION['order'][$key_pizza]['tipo'];
					$prezzo = $_SESSION['order'][$key_pizza]['prezzo'];
					$quantita = $_SESSION['order'][$key_pizza]['quantita'];
					$prezzo_tot = $_SESSION['order'][$key_pizza]['quantita'] * $_SESSION['order'][$key_pizza]['prezzo'];
					unset($_SESSION['order'][$key_pizza]);
					
					echo "
						<script>
							var newRow = $(\"<tr />\");
							
							var p_tipo = $(\"<p></p>\").text(\"$tipo\");
							var tipo = $(\"<td></td>\").append(p_tipo);

							var p_prezzo = $(\"<p></p>\").text(\"$prezzo\");
							var prezzo=$(\"<td></td>\").append(p_prezzo);
							
							var p_quantita = $(\"<p></p>\").text(\"$quantita\");
							var quantita = $(\"<td></td>\").append(p_quantita);

							var p_prezzo_tot = $(\"<p></p>\").text(\"$prezzo_tot\");
							var prezzo_tot = $(\"<td></td>\").append(p_prezzo_tot);	
						
							$(newRow).append(tipo,prezzo,quantita,prezzo_tot);
							$(\"#noorder tr:last\").after(newRow);
						</script>
					";
					echo"
						<script>
							$(\"#info\").append(\"<p>elemento $tipo rimosso</p>\");
						</script>
					";

				}
				else
				{	if($empty_order)
					{
						$empty_order = false;
					}
					if($result['prezzo']!=$array['prezzo'])
					{	
						$_SESSION['order'][$key_pizza]['prezzo_lista'] = $_SESSION['order'][$key_pizza]['prezzo'];
						$_SESSION['order'][$key_pizza]['prezzo'] = $result['prezzo'];
						$change = true;
					}
					else
					{
						$change = false;
					}
					if(isset($change) && $change)
					{	
						$div_prezzo=" prezzo cambiato da {$_SESSION['order'][$key_pizza]['prezzo_lista']} a {$_SESSION['order'][$key_pizza]['prezzo']}";	
					}
					else
					{
						$div_prezzo="";
					}

					$tipo = $_SESSION['order'][$key_pizza]['tipo'];
					$prezzo = $_SESSION['order'][$key_pizza]['prezzo'];
					$quantita = $_SESSION['order'][$key_pizza]['quantita'];
					$prezzo_tot = $_SESSION['order'][$key_pizza]['quantita'] * $_SESSION['order'][$key_pizza]['prezzo']; 
					
					echo "
						<script>
							var newRow = $(\"<tr />\");
							
							var p_tipo = $(\"<p></p>\").text(\"$tipo\");
							var tipo = $(\"<td></td>\").append(p_tipo);

							var p_prezzo = $(\"<p></p>\").text(\"$prezzo\");
							var div_prezzo = $(\"<div></div>\").text(\"$div_prezzo\");
							var prezzo=$(\"<td></td>\").append(p_prezzo,div_prezzo);
							
							var p_quantita = $(\"<p></p>\").text(\"$quantita\");
							var quantita = $(\"<td></td>\").append(p_quantita);

							var p_prezzo_tot = $(\"<p></p>\").text(\"$prezzo_tot\");
							var prezzo_tot = $(\"<td></td>\").append(p_prezzo_tot);	
						
							$(newRow).append(tipo,prezzo,quantita,prezzo_tot);
							$(\"#order tr:last\").after(newRow);
						</script>
					";
					
				}
			}
			if(isset($empty_order) && empty($empty_order) && isset($empty_noorder) && !empty($empty_noorder))
			{
				
				require_once("inserisci_ordine_db.php");
				echo "
					<script>
						$(\"#div_ordine\").html(\"<br>Dati immessi nel DB<br>\");
						//window.location.href = \"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/home/\";
					</script>
				";
				
			}
			else
			{
				if(isset($empty_noorder) && empty($empty_noorder))
				{	echo"1";
					echo"
						<script>	
							if($(\"#noorder\").is(\":hidden\"))
							{
									//$(\"#noorder\").show();
								
							}
						</script>
					";	

				}
				else
				{
					echo"2";
					echo"
						<script>
							if($(\"#noorder\").is(\":visible\"))
							{	
								$(\"#noorder\").hide();	
							}
						</script>
					";	
				}
				if(isset($empty_order) && empty($empty_order))
				{	echo"3";
					echo"
						<script>
							var b_conferma = $(\"<button></button>\").attr({type:\"button\",class:\"conferma\"}).text(\"Conferma Ordine\");
							$(\"#scelta\").append(b_conferma);
						</script>
					";		
				}
				else
				{	echo"4";
					echo "
						<script>
						var newRow = $(\"<tr />\");
						var no_elementi = $(\"<td></td>\").attr(\"colspan\",\"4\").text(\"nessun elemento\"); 
						$(newRow).append(no_elementi);
						$(\"#order tr:last\").after(newRow);
						</script>
					";	
				}
			}
		}
		else
		{
			echo "
				<script>
					
					window.location.href = \"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/carrello/opzioni_consegna.php\";
					
				</script>
			";

		}
		

?>
<?php
		echo"
			<script src=\"ordine.js\"></script>
		";
?>
<?php
	}
	else
	{
		require("../shared/form_accesso.php");
	}
?>

