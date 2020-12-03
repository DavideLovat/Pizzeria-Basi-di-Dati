<?php
session_start();
header('content-Type: text/html;charset=UTF-8');
require_once("../shared/credenziali_db.php");
?>
<?php
$controllo = false;
$stato="failure";
$arrayPizza_JSON = array("tipo" => "", "prezzo" => "", "prezzo_lista" => "", "quantita" => "");

if(isset($_GET['tipo'],$_GET['prezzo'],$_GET['quantita']) && !empty($_GET['tipo']))
{
$get_tipo = $_GET['tipo'];
$get_prezzo = $_GET['prezzo'];
$get_quantita = $_GET['quantita'];
 
$sql = "
	select * 
	from pizze 
	where tipo=:tipo and tipo not in
	(select tipo from pizze_q_null where tipo like'$char%')
	";
	if($get_quantita>=1 && $get_quantita<=100)
	{
		try{
			$dsn = "pgsql:host=$pdo_host port=$pdo_port dbname=$pdo_db user=$pdo_user password=$pdo_pass";
			$dbh = new PDO($dsn);

			if($sth = $dbh->prepare($sql))
			{	if($sth->bindParam(':tipo', $get_tipo, PDO::PARAM_STR))
				{	if($sth->execute())
					{	if($db_pizza = $sth->fetch(PDO::FETCH_ASSOC))
						{	
							if(empty($db_pizza))
							{
								$controllo = true;
								$stato = "empty";
							}
							else
							{	$db_tipo = $db_pizza['tipo'];
								$db_prezzo = $db_pizza['prezzo'];
								if(isset($_SESSION['cart'][$db_tipo]['quantita']))
								{
									$session_quantita = $_SESSION['cart'][$db_tipo]['quantita'];
								}
								else
								{
									$session_quantita = 0;

								}
								if($get_prezzo == $db_prezzo)
								{	if(!isset($_SESSION['cart'][$db_tipo]['tipo']))
									{	
										$_SESSION['cart'][$db_tipo]['tipo'] = $db_tipo;	
									}
									$_SESSION['cart'][$db_tipo]['prezzo'] = $db_prezzo;
									$_SESSION['cart'][$db_tipo]['prezzo_lista'] = $get_prezzo;
									
									$sum = $session_quantita + $get_quantita;
									if($sum <= 100)
									{
										
										$_SESSION['cart'][$db_tipo]['quantita'] = $sum;
									}
									else
									{
										$_SESSION['cart'][$db_tipo]['quantita'] = 100;
									}
									$session_quantita = $_SESSION['cart'][$db_tipo]['quantita'];
									$controllo = true;
									$stato = "success";
									$arrayPizza_JSON = array("tipo" => $db_tipo, "prezzo" => $db_prezzo, "prezzo_lista" => $get_prezzo, "quantita" => $session_quantita);
									
								}
								else
								{
									$stato = "change";
									$arrayPizza_JSON = array("tipo" => "", "prezzo" => $db_prezzo, "prezzo_lista" => $get_prezzo, "quantita" => $session_quantita );
								}
							}
						}
						else
						{
							$controllo = true;
							$stato = "empty";
						}
					}
				}
			}	
						
		}
		catch(PDOException $e)
		{$controllo=false; $stato="server_error";}
		catch(Exception $e)
		{$controllo=false; $stato="server_error";}
	}
}
$return = array(
		array("stato"=>$stato),
		$arrayPizza_JSON,	
	);
echo json_encode($return);
?>