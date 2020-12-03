<?php
	try
	{
		session_start();
		header('content-Type: text/html;charset=UTF-8');
		require_once("../shared/credenziali_db.php");
		
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			$sql_sel1 = "
				SELECT login FROM utenti
				ORDER BY login ASC
				LIMIT 1
			";
			
			$sql_execute = "
				select login,nome,cognome
				from utenti
				where login like :word
				order by login asc
			";
			
			$array_lista;

			$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
			$dbh = new PDO($dsn);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Your Company" />
	<meta name="keywords" content="Your Company" />
	<meta name="description" content="Your Company" />
	<meta name="robots" content="all" />
<!-- inzio script aggiunti -->	
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
	<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/accesso_amm/lista_utenti_amm.js"></script>
	
	<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/global-3.css">
	<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/tabella.css">
	<!-- <link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/lista-pizze/infoaggiungi.css"> -->
	<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/lista-pizze/lettere.css">

<!-- fine script aggiunti -->	
	<title>Chalk Rock</title>
</head>

<body>
<div id="wrapper">
	<div id="header">
    	<h1><span class="black">Pizzeria</span> <span class="orange">Online</span></h1>
        <h2>Your Slogan</h2>
    </div>
    <div id="navi">
    	<ul>
	        	<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/home/">Home</a></li>
			<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/index.php">Amministratore</a></li>
			<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/form_insert_pizze.php">Inserisci Pizza</a></li>
			<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_pizze_amm.php">Lista Pizze DB</a></li>
			<li class="active"><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/lista_utenti_amm.php">Lista Utenti DB</a></li>

			<li style="float:right;">
			<?php
				echo "
					<form action=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/lista-pizze/\" method=\"get\">
						Cerca Pizza: <input type=\"search\" name=\"string\">
						<input type=\"hidden\" name=\"ordina\" value=\"$ordina\" />
						<input type=\"submit\">
					</form>
				";	
			?>
			</li>
        </ul>
    </div>
    <div id="subnavi">
    	<ul>
        	<!-- <li class="active"><a href="#">Item</a></li> -->
		<?php
		if(isset($_SESSION['utente']['login'],$_SESSION['utente']['nome']) && !empty($_SESSION['utente']['nome']) && !empty($_SESSION['utente']['login']))
		{	
			echo "<li class=\"active\"><a href=\"../shared/log_out.php?doLogout=true\">Log out {$_SESSION['utente']['nome']}</a></li>";
		}
		else
		{
			echo "
				<li><a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso/\">Accedi</a></li>
			";
		}
		?>
		<li style="float:right;"><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/registrazione/">Registrati</a></li>

        </ul>
    </div>
		
    <div id="main">
	 
		<div id="content" style="width:95%">
			<h1>Lista Utenti Amministratore <?php  echo "- Carattere " . strtoupper($_GET['word']); ?></h1>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- inserire codice php -->
			<?php 
				echo "
					<form action=\"{$_SERVER['PHP_SELF']}\" method=\"get\"> 
						Cerca Utente: <input type=\"search\" name = \"word\" id=\"cerca\" value=\"\">
						<input type=\"submit\" value=\"Cerca\">
					</form>
				";	
			?>
<?php
			if(isset($_GET['word']) && !empty($_GET['word']))
			{
				$word = $_GET['word'];
				if(isset($_GET['iniziale']) && $_GET['iniziale'] == true)
				{
					$iniziale = true;
				}
				else
				{
					$iniziale = false;
				}
				$start = true;
			}
			else
			{
				$sth = $dbh -> query($sql_sel1);
				
				if($result_first = $sth->fetchColumn())
				{
					$ascii_char = ord($result_first);
					$word = chr($ascii_char);
					$iniziale = true;
					$start = true;
				}
				else
				{
					$start = false;
				}

			}
			if($start)
			{
					echo "
						<div class=\"CSSTableGenerator\">
							<table id=\"lista_utenti\">
								<tr>
									<td>Login</td>
									<td>Nome</td>
									<td>Cognome</td>
									<td>Rimuovi</td>
									<td>Modifica</td>
								</tr>		
							</table>
						</div>
					";
					$array_alfabeto = range('a','z');
					foreach($array_alfabeto as $val)
					{
						if($val == $word && $iniziale)
						{
							echo "
								| <a class=\"lettere lettere_segnate\" href= '{$_SERVER['PHP_SELF']}?word=$val&iniziale=true'>$val</a>
							";
						}
						else
						{
							echo "
								| <a class=\"lettere\" href= '{$_SERVER['PHP_SELF']}?word=$val&iniziale=true'>$val</a>
							";
						}
					}

					$sth = $dbh -> prepare($sql_execute);
					if(isset($iniziale) && !empty($iniziale))
					{
						$array_execute = array(":word"=>"$word%");
					}
					else 
					{
						$array_execute = array(":word"=>"%$word%");
					}
					if($sth -> execute($array_execute))
					{
						if($array_lista = $sth -> fetchAll(PDO::FETCH_ASSOC))
						{	
							//echo "<br>" . print_r($array_lista,true) . "<br>";
							//crea righe tabella
							foreach($array_lista as $row => $array)
							{	//echo "<br>".print_r($array,true)."<br>";
								//echo "<br>$str_ing";
								echo"
										<script>
										//alert(\"ciao\");
										
										var newRow = $(\"<tr />\").attr({id:\"{$array['login']}\"});
										
										var p_login = $(\"<p></p>\").attr({class:\"login\"}).text(\"{$array['login']}\");	
										var td_login = $(\"<td></td>\").attr(\"class\",\"login\").append(p_login);

										var p_nome = $(\"<p></p>\").attr({class:\"nome\"}).text(\"{$array['nome']}\");
										var td_nome = $(\"<td></td>\").attr(\"class\",\"nome\").append(p_nome);
										
										var p_cognome = $(\"<p></p>\").attr({class:\"cognome\"}).text(\"{$array['cognome']}\");
										var td_cognome = $(\"<td></td>\").attr(\"class\",\"cognome\").append(p_cognome);

										var button_rimuovi = $(\"<button></button>\").attr({type:\"button\",class:\"rimuovi\"}).text(\"Rimuovi\");
										var td_rimuovi=$(\"<td></td>\").attr(\"class\",\"rimuovi\").append(button_rimuovi);
										
										var button_modifica = $(\"<button></button>\").attr({type:\"button\",class:\"modifica\"}).text(\"Modifica\");
										var td_modifica=$(\"<td></td>\").attr(\"class\",\"modifica\").append(button_modifica);

										$(newRow).append(td_login,td_nome,td_cognome,td_rimuovi,td_modifica);
										$(\"#lista_utenti tr:last\").after(newRow);
										
										</script>
								";		
							}
						}
						else
						{
							echo "
								<script>
									var newRow = $(\"<tr />\");
								
									var p_empty = $(\"<p></p>\").attr({class:\"empty\"}).text(\"nessun elemento trovato\");	
									var td_empty = $(\"<td></td>\").attr({class:\"empty\" , colspan:\"3\"}).append(p_empty);
								
									$(newRow).append(td_empty);
									$(\"#lista_utenti tr:last\").after(newRow);
								</script>
							";	
						}
					}
					else
					{
						throw new Exception("errore execute sql_sel2");
					}
			}
			else
			{
				echo "<p>nessun elemento presente</p>";
			}
?>	
	
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->			
		</div>
    </div>
	
    <div id="footer">
        <p>© Copyright 2012 yourname.com. All Rights Reserved.  |  Design by <a href="http://www.schlafzimmerkomplett.net/" target="_blank">Schlafzimmer komplett</a></p>
    </div>
    <div class="cleaner"></div>
</div>
</body>
</html>

<?php
		}
		else
		{
			require_once("../shared/form_accesso.php");
		}
	}
	catch(PDOException $e)
	{
		if(isset($dbh))
		{
			$dbh = null;
		}
		print("error: ".$e->getMessage());
	}
	catch(Exception $e)
	{
		if(isset($dbh))
		{
			$dbh = null;
		}
		print("error: ".$e->getMessage());
	}
?>
