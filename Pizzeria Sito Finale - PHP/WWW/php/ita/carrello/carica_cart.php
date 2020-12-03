<?php
		session_start();
		header('content-Type: text/html;charset=UTF-8');
?>
<?php
		/*
		$_SESSION['nocart'][0]['marcello']['tipo'] = 'marcello';
		$_SESSION['nocart'][0]['marcello']['prezzo'] = '1,00';
		$_SESSION['nocart'][0]['marcello']['prezzo_lista'] = '1,00';
		$_SESSION['nocart'][0]['marcello']['quantita'] = '5';
		
		$_SESSION['nocart'][1]['mar']['tipo'] = 'mar';
		$_SESSION['nocart'][1]['mar']['prezzo'] = '2';
		$_SESSION['nocart'][1]['mar']['prezzo_lista'] = '2';
		$_SESSION['nocart'][1]['mar']['quantita'] = '5';
		
		$_SESSION['cart']['marce']['tipo'] = 'marce';
		$_SESSION['cart']['marce']['prezzo'] = '2';
		$_SESSION['cart']['marce']['prezzo_lista'] = '2';
		$_SESSION['cart']['marce']['quantita'] = '5';
		
		$_SESSION['cart']['marcello']['tipo'] = 'marcello';
		$_SESSION['cart']['marcello']['prezzo'] = '1,00';
		$_SESSION['cart']['marcello']['prezzo_lista'] = '1,00';
		$_SESSION['cart']['marcello']['quantita'] = '5';
		
		$_SESSION['cart']['marinara']['tipo'] = 'marinara';
		$_SESSION['cart']['marinara']['prezzo'] = '1,00';
		$_SESSION['cart']['marinara']['prezzo_lista'] = '1,00';
		$_SESSION['cart']['marinara']['quantita'] = '5';
		
		$_SESSION['cart']['marea']['tipo'] = 'marea';
		$_SESSION['cart']['marea']['prezzo'] = '1,00';
		$_SESSION['cart']['marea']['prezzo_lista'] = '1,00';
		$_SESSION['cart']['marea']['quantita'] = '5';
		*/
		//unset($_SESSION['nocart']);
		//unset($_SESSION['cart']);
		
		echo "sessione:";print_r($_SESSION);echo "<br>";
		echo "nocart: ";print_r($_SESSION['nocart']);echo "<br>";
		echo "cart: ";print_r($_SESSION['cart']);echo "<br>";

?>
<?php
		echo "<div class=\"CSSTableGenerator\">";
		echo "	<div id=\"info\"></div>
			<div id=\"info_prezzo\"></div>
			<table id=\"nocart\">
				<tr>
					<td colspan=\"3\">Elementi Carrello Sospesi</td>
				</tr>
				<tr>
					<td class=\"tipo\">Pizza</td>
					<td class=\"prezzo\">Prezzo</td>
					<td class=\"quantita\">Quantit&agrave;</td>
				</tr>
			</table>
		";

		echo "
			<table id=\"cart\">
				<tr>
					<td colspan=\"3\">Elementi Carrello</td>
				<tr>
				<tr>
					<td class=\"tipo\">Pizza</td>
					<td class=\"prezzo\">Prezzo</td>
					<td class=\"quantita\">Quantit&agrave;</td>
				</tr>
			</table>
		";
		echo "</div>";
		echo "<button id=\"ordina\" type=\"button\">Ordina</button>";
?>

<?php
		if((isset($_SESSION['cart']) && !empty($_SESSION['cart'])) || (isset($_SESSION['nocart']) && !empty($_SESSION['nocart'])))
		{	
			$sql = "select * from pizze where tipo = :tipo"; 
			require_once("../shared/credenziali_db.php");
			$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
			$dbh = new PDO($dsn);
			$sth = $dbh->prepare($sql);
			
		}
?>

<?php
		if(isset($_SESSION['nocart']) && !empty($_SESSION['nocart']))
		{
			echo"
				<script>
					if($(\"#nocart\").is(\":hidden\"))
					{
						$(\"#nocart\").show();
					}
				</script>
			";
	//controllo se in nocart ci sono elementi dinuovo presenti in DB
			foreach($_SESSION['nocart'] as $indice=>$array_pizza) 
			{
				foreach($array_pizza as $key_pizza=>$array)
				{
					$sth->bindParam(':tipo', $array['tipo'], PDO::PARAM_STR);
					$sth->execute();
					if($result = $sth->fetch())
					{	
						if($array['prezzo']!=$result['prezzo'])
						{
								$_SESSION['nocart'][$indice][$key_pizza]['prezzo_lista'] = $_SESSION['nocart'][$indice][$key_pizza]['prezzo'];
								$_SESSION['nocart'][$indice][$key_pizza]['prezzo'] = $result['prezzo'];
								
						}
						if($_SESSION['nocart'][$indice][$key_pizza]['prezzo'] != $_SESSION['nocart'][$indice][$key_pizza]['prezzo_lista'])
						{	
							$div_prezzo=" prezzo cambiato da {$_SESSION['nocart'][$indice][$key_pizza]['prezzo_lista']} a {$_SESSION['nocart'][$indice][$key_pizza]['prezzo']}";	
						}
						else
						{
							$div_prezzo="";
						}
						
						$tipo = $_SESSION['nocart'][$indice][$key_pizza]['tipo'];
						$prezzo = $_SESSION['nocart'][$indice][$key_pizza]['prezzo'];
						$quantita = $_SESSION['nocart'][$indice][$key_pizza]['quantita'];
						echo"
							<script>
							alert(\"ciao\");
							var button = $(\"<button></button>\").attr({type:\"button\",class:\"aggiungi\"}).text(\"Aggiungi\");
							var newRow = $(\"<tr />\").attr({id:\"$indice\"});
						
							var a_tipo = $(\"<a></a>\").attr({class:\"rimuovi_nocart\",href:\"\"}).text(\"Rimuovi\");
							var p_tipo = $(\"<p></p>\").attr({class:\"tipo\"}).text(\"$tipo\");	
							var tipo=$(\"<td></td>\").attr(\"class\",\"tipo\").append(p_tipo,a_tipo);

							var p_prezzo = $(\"<p></p>\").attr({class:\"prezzo\"}).text(\"$prezzo\");
							var div_prezzo = $(\"<div></div>\").attr({class:\"prezzo\"}).text(\"$div_prezzo\");
							var prezzo=$(\"<td></td>\").attr(\"class\",\"prezzo\").append(p_prezzo,div_prezzo);

							var p_quantita = $(\"<p></p>\").attr({class:\"quantita\"}).text(\"$quantita\");
							var quantita=$(\"<td></td>\").attr(\"class\",\"quantita\").append(p_quantita);

							var aggiungi=$(\"<td></td>\").attr(\"class\",\"aggiungi\").append(button,\"<div>aggiungere elemento all'ordine?</div>\");

							$(newRow).append(tipo,prezzo,quantita,aggiungi);
							$(\"#nocart tr:last\").after(newRow);
							
							</script>

						";		
						
					}
					else
					{
						if($_SESSION['nocart'][$indice][$key_pizza]['prezzo'] != $_SESSION['nocart'][$indice][$key_pizza]['prezzo_lista'])
						{	
							$div_prezzo=" prezzo cambiato da {$_SESSION['nocart'][$indice][$key_pizza]['prezzo_lista']} a {$_SESSION['nocart'][$indice][$key_pizza]['prezzo']}";		
						}	
						else
						{
							$div_prezzo="";
						}
						$tipo = $_SESSION['nocart'][$indice][$key_pizza]['tipo'];
						$prezzo = $_SESSION['nocart'][$indice][$key_pizza]['prezzo'];
						$quantita = $_SESSION['nocart'][$indice][$key_pizza]['quantita'];
					
						echo"
							<script>
							alert(\"ciao\");
							var newRow = $(\"<tr />\").attr({id:\"$indice\"});
							
							var a_tipo = $(\"<a></a>\").attr({class:\"rimuovi_nocart\",href:\"\"}).text(\"Rimuovi\");
							var p_tipo = $(\"<p></p>\").attr({class:\"tipo\"}).text(\"$tipo\");	
							var tipo=$(\"<td></td>\").attr(\"class\",\"tipo\").append(p_tipo,a_tipo);
							
							var p_prezzo = $(\"<p></p>\").attr({class:\"prezzo\"}).text(\"$prezzo\");
							var div_prezzo = $(\"<div></div>\").attr({class:\"prezzo\"}).text(\"$div_prezzo\");
							var prezzo=$(\"<td></td>\").attr(\"class\",\"prezzo\").append(p_prezzo,div_prezzo);
							
							var p_quantita = $(\"<p></p>\").attr({class:\"quantita\"}).text(\"$quantita\");
							var quantita=$(\"<td></td>\").attr(\"class\",\"quantita\").append(p_quantita);
							
							var aggiungi=$(\"<td></td>\").attr(\"class\",\"aggiungi\").html(\"<div>elemento non disponibile</div>\");

							$(newRow).append(tipo,prezzo,quantita,aggiungi);
							$(\"#nocart tr:last\").after(newRow);
							
							</script>

						";
					}
				}
			}	
		}
		else
		{
			echo "
				<script>
					if($(\"#nocart\").is(\":visible\"))
					{
						$(\"#nocart\").hide();
					}
				</script>
			";
		}
?>

<?php
	//controlla cart e se ci sono elementi in cart non più presenti nel DB che verranno messi in nocart
		if(isset($_SESSION['cart']) && !empty($_SESSION['cart']))
		{
			foreach($_SESSION['cart'] as $key_pizza=>$array)
			{	
				$sth->bindParam(':tipo', $array['tipo'], PDO::PARAM_STR);
				$sth->execute();
				if($result = $sth->fetch())
				{
					if($result['prezzo']!=$array['prezzo'])
					{	
						$_SESSION['cart'][$key_pizza]['prezzo_lista'] = $_SESSION['cart'][$key_pizza]['prezzo'];
						$_SESSION['cart'][$key_pizza]['prezzo'] = $result['prezzo'];
					}
					if($_SESSION['cart'][$key_pizza]['prezzo'] != $_SESSION['cart'][$key_pizza]['prezzo_lista'])
					{	
						$div_prezzo=" prezzo cambiato da {$_SESSION['cart'][$key_pizza]['prezzo_lista']} a {$_SESSION['cart'][$key_pizza]['prezzo']}";	
					}
					else
					{
						$div_prezzo="";
					}
					$tipo = $_SESSION['cart'][$key_pizza]['tipo'];
					$prezzo = $_SESSION['cart'][$key_pizza]['prezzo'];
					$quantita = $_SESSION['cart'][$key_pizza]['quantita'];

					echo"
						<script>
						alert(\"miao\");
						var newRow = $(\"<tr />\");
						var p_tipo = $(\"<p></p>\").attr({class:\"tipo\"}).text(\"$tipo\");
						var a_tipo = $(\"<a></a>\").attr({class:\"rimuovi_cart\",href:\"\"}).text(\"Rimuovi\");
						var tipo=$(\"<td></td>\").attr(\"class\",\"tipo\").append(p_tipo,a_tipo);
						
						var p_prezzo = $(\"<p></p>\").attr({class:\"prezzo\"}).text(\"$prezzo\");
						var div_prezzo = $(\"<div></div>\").attr({class:\"prezzo\"}).text(\"$div_prezzo\");
						var prezzo=$(\"<td></td>\").attr(\"class\",\"prezzo\").append(p_prezzo,div_prezzo);
						
						var input_quantita = $(\"<input />\").attr({class:\"quantita\",type:\"number\",min:\"0\",max:\"100\",value:\"$quantita\"});
						var a_quantita = $(\"<a></a>\").attr({class:\"modifica\",href:\"\"});
						var quantita=$(\"<td></td>\").attr(\"class\",\"quantita\").append(input_quantita,a_quantita);

						$(newRow).append(tipo,prezzo,quantita);
						$(\"#cart tr:last\").after(newRow);
						
						</script>

					";
					echo"
						<script>
							if($(\"button#ordina\").is(\":hidden\"))
							{
								$(\"button#ordina\").show();
							}
						</script>
					";		
				}
				else
				{	
					if(isset($_SESSION['nocart']) && !empty($_SESSION['nocart']))
					{
						
						$array_indice = array_keys($_SESSION['nocart']);
						if(($length = count($array_indice))>0)
						{
							$indice = $array_indice[0];
							for($i=0; $i<$length-1; $i++)
							{	
								if($indice < $array_indice[$i+1])
								{
									
									$indice = $array_indice[$i+1];
									
								}
							}
							$indice++;
						}
						else
						{
							$indice = 0;
						}
					}
					else
					{
						$indice = 0;
					}
					$_SESSION['nocart'][$indice][$key_pizza]= $_SESSION['cart'][$key_pizza];
					unset($_SESSION['cart'][$key_pizza]);

					if($_SESSION['nocart'][$indice][$key_pizza]['prezzo'] != $_SESSION['nocart'][$indice][$key_pizza]['prezzo_lista'])
					{	
						$div_prezzo=" prezzo cambiato da {$_SESSION['nocart'][$indice][$key_pizza]['prezzo_lista']} a {$_SESSION['nocart'][$indice][$key_pizza]['prezzo']}";		
					}	
					else
					{
						$div_prezzo="";
					}
					$tipo = $_SESSION['nocart'][$indice][$key_pizza]['tipo'];
					$prezzo = $_SESSION['nocart'][$indice][$key_pizza]['prezzo'];
					$quantita = $_SESSION['nocart'][$indice][$key_pizza]['quantita'];
				
					echo"
							<script>
							alert(\"miao\");
							var newRow = $(\"<tr />\").attr({id:\"$indice\"});
							
							var a_tipo = $(\"<a></a>\").attr({class:\"rimuovi_nocart\",href:\"\"}).text(\"Rimuovi\");
							var p_tipo = $(\"<p></p>\").attr({class:\"tipo\"}).text(\"$tipo\");	
							var tipo=$(\"<td></td>\").attr(\"class\",\"tipo\").append(p_tipo,a_tipo);
							
							var p_prezzo = $(\"<p></p>\").attr({class:\"prezzo\"}).text(\"$prezzo\");
							var div_prezzo = $(\"<div></div>\").attr({class:\"prezzo\"}).text(\"$div_prezzo\");
							var prezzo=$(\"<td></td>\").attr(\"class\",\"prezzo\").append(p_prezzo,div_prezzo);
							
							var p_quantita = $(\"<p></p>\").attr({class:\"quantita\"}).text(\"$quantita\");
							var quantita=$(\"<td></td>\").attr(\"class\",\"quantita\").append(p_quantita);
							
							var aggiungi=$(\"<td></td>\").attr(\"class\",\"aggiungi\").html(\"<div>elemento non disponibile</div>\");

							$(newRow).append(tipo,prezzo,quantita,aggiungi);
							$(\"#nocart tr:last\").after(newRow);
							
							</script>

					";

					echo"
						<script>
							if($(\"#nocart\").is(\":hidden\"))
							{
								$(\"#nocart\").show();
							}
						</script>
					";
					echo"
						<script>
							$(\"div#info\").append(\"elemento $tipo aggiunto agli Ordini Sospesi<br>\");
						</script>
					";
				}
			}	
		}
		else
		{
			
			echo"
				<script>
				alert(\"miao\");
				var no_elementi = $(\"<td></td>\").attr(\"colspan\",\"3\").text(\"nessun elemento\"); 
				var newRow = $(\"<tr />\");
				$(newRow).append(no_elementi);
				$(\"#cart tr:last\").after(newRow);
				</script>
			";
			echo"
				<script>
					if($(\"button#ordina\").is(\":visible\"))
					{
						$(\"button#ordina\").hide();
					}
				</script>
			";		

		}
		echo "<script src=\"http://wwwstud.dsi.unive.it/dlovat/WWW/js/carrello/carica_cart.js\"></script>";

?>