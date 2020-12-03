<?php
	try
	{
		session_start();
		/*
			$_SESSION['amministratore']= true;
			$_GET['tipo'] = "margherita";
			$_GET['prezzo'] = "3,00";
			$_GET['ingredienti'] = "pomodoro";
		*/
		echo"<br> sessione:";print_r($_SESSION);echo"<br>";
		echo"<br> get:";print_r($_GET);echo"<br>";
		$sql_sel1 = "
				select tipo 
				from pizze 
				where tipo = :tipo
		";
		$sql_sel2 = "
				select nome
				from ingredienti
				where nome = :nome
		";
		$sql_ins1 = "
				insert into pizze(tipo,prezzo) values(:tipo,:prezzo)
		";
		$sql_ins2 = "
				insert into contiene(idpiz,iding) values(:tipo,:nome)
		";
		
		if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
		{
			if(isset($_GET['tipo'],$_GET['prezzo'],$_GET['ingredienti']) && !empty($_GET['tipo']) && !empty($_GET['prezzo']) && !empty($_GET['ingredienti']))
			{	
				$tipo = $_GET['tipo'];
				$prezzo = $_GET['prezzo'];
				$lista_ingredienti = $_GET['ingredienti'];
				$findstr = ",";
				$array_ing = array();
				$insert_tipo = false;
				$insert_nome = false;
				//separa la stringa lista_ingredienti e metti ogni ingrediente in un array
				if(!strlen($lista_ingredienti)==0)
				{
					while(strlen($lista_ingredienti) != 0)
					{
						if($pos = strpos($lista_ingredienti,$findstr)) //cerca la stringa ',' in ingredienti
						{
							if($pos != 0)	// se ',' non è nella prima posizione inserisci nell'array la stringa precedente
							{
								$ing = substr($lista_ingredienti,0,$pos);
								array_push($array_ing,$ing);
							}
							if(!$lista_ingredienti = substr($lista_ingredienti,$pos+1)) //finche esiste una sottostringa sostituisci la lista ingredienti
							{
								$lista_ingredienti = "";
							}
						}
						else
						{
							array_push($array_ing,$lista_ingredienti);
							$lista_ingredienti = "";
						}
					}
				}
				echo"<br>";print_r($array_ing);echo"<br>";
				if(isset($array_ing) && !empty($array_ing))
				{	
						require_once("../shared/credenziali_db.php");
						$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
						$dbh = new PDO($dsn);
						$sth = $dbh -> prepare($sql_sel1);
						$sth -> bindParam(':tipo', $tipo, PDO::PARAM_STR);
							
						if($sth -> execute())
						{
							if($result = $sth -> fetchColumn())
							{
								if($result == $tipo)
								{
									throw new Exception("valore tipo già presenta in db");
								}
								else
								{
									$insert_tipo = true;	
								}
							}
							else
							{
								$insert_tipo = true;
							}
						}
						else
						{
							throw new Exception("errore execute sql_sel1");
						}
						//esegui la seconda query $sql_sel2 seleziona gli ingredienti e controlla che siano tutti presenti
						$array_ing_no_db = array();
						$sth = $dbh -> prepare($sql_sel2);
						for($i=0;$i<count($array_ing);$i++)
						{
							$nome = $array_ing[$i];
							$sth -> bindParam(':nome', $nome);
							//print_r($sth->errorInfo());
							
							if($sth -> execute())
							{
								if(!$result = $sth -> fetchColumn())
								{	
									array_push($array_ing_no_db,$nome);
								}
							}
							else
							{
								throw new Exception("errore execute sql_sel2");
							}
						}
						if(empty($array_ing_no_db))
						{
							$insert_nome = true;
						}
						else
						{
							throw new Exception("errore dopo execute sql_sel2 ".print_r($array_ing_no_db,true));
						}print ("tipo : $insert_tipo nome: $insert_nome");
						if($insert_tipo && $insert_nome)
						{	
							//inserisce tipo,prezzo in pizze, idpiz e iding in contiene 
							try
							{		$success = true;
									if($dbh -> beginTransaction())
									{
										$sth = $dbh -> prepare($sql_ins1);
										$sth -> bindParam(':tipo',$tipo,PDO::PARAM_STR);
										$sth -> bindParam(':prezzo',$prezzo,PDO::PARAM_STR);
										if($sth -> execute())
										{
											$sth = $dbh -> prepare($sql_ins2);
											foreach($array_ing as $nome)
											{
												$sth -> bindParam(':tipo',$tipo,PDO::PARAM_STR);
												$sth -> bindParam(':nome',$nome,PDO::PARAM_STR);
												if(!$sth -> execute())
												{
													$success = false;
													throw new Exception("errore execute sql_ins2");
												}
											}
											if($success)
											{
												$dbh->commit();
												$dbh = null;
												echo "<br>success<br>";
											}
											else
											{
												throw new Exception("no success");	
											}
										}
										else
										{
											throw new Exception("errore execute sql_ins1");
										}
									}
									else
									{
										throw new Exception("errore begin transaction");
									}
							}
							catch(PDOException $e)
							{
								if(isset($dbh))
								{	
									$dbh -> rollback();
									$dbh = null;
								}
								print("error: ".$e->getMessage());
							}
							catch(Exception $e)
							{
								if(isset($dbh))
								{
									$dbh -> rollback();
									$dbh = null;
								}
								print("error: ".$e->getMessage());
							}
						}
						else
						{
							throw new Exception("dati inseriti non corretti");
						}
				}
				else
				{
					echo "array_ing è vuoto";	
				}
			}
			else
			{
				echo"get non settati";
			}
		}
		else
		{
			echo "sessione amministratore non true";
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