	<?php 
	
	if(isset($_SESSION['messaggio']) && !empty($_SESSION['messaggio'])) 
	{
		$mess = $_SESSION['messaggio'];
		switch ($mess) {
			case "logusato":
				$str = "login già in uso";
				break;
			case "logvuoto":
				$str = "inserire un valore in login";
				break;
			case "dberror":
				$str = "Si è verificato un errore di connessione con il server, la invitiamo a riprovare";
				break;
			case "campovuoto":
				$str = "riempire tutti i campi";
				break;
			default:
				$str = "errore";
		}
		echo "<script language=\"JavaScript\">\n"; 
		echo "alert(\"$str\");\n"; 
		echo "</script>"; 
			
		unset($_SESSION['messaggio']);
	}
	
	?>
	
	<form action="action.php" method="post" id="registra">
            <fieldset>
		  <legend> Registrati </legend>
		  
		  <fieldset class="tratto">
                <legend>Personal information</legend>
		  <div class="fm-req">
		  <label for="login">Login:</label>
                <input id="login" name="login" type="text">
		  </div>   

		  <div class="fm-req">
		  <label for="password">Password:</label>
		  <input id="password" name="password" type="password">
		  </div>   

		  <div class="fm-req">
		  <label for="conferma">Conferma Password:</label>
		  <input id="conferma" name="conferma" type="password">
		  </div>   

		  <div class="fm-req">
		  <label for="nome">Nome:</label>
		  <input id="nome" name="nome" type="text">
		  </div>   

		  <div class="fm-req">
		  <label for="cognome">Cognome:</label>
		  <input id="cognome" name="cognome" type="text">
		  </div>
		  </fieldset>
		 
		  <fieldset class="tratto">
		  <legend>Contact information</legend>
		  <div class="fm-req">
		  <label for="telefono">Telefono/Cellulare:</label>
		  <input id="telefono" name="telefono" type="text">
		  </div>
		  </fieldset>
		  
		  <fieldset class="tratto">
		  <legend>Address </legend>  
		  <div class="fm-req">
		  <label for="via">via:</label>
                <input id="via" name="via" type="text">
		  </div>   

		  <div class="fm-req">
		  <label for="ncivico">n&deg;civico:</label>
		  <input id="ncivico" name="ncivico" type="text">
		  </div>   

		  <div class="fm-req">
		  <label for="cap">cap:</label>
		  <input id="cap" name="cap" type="text">
		  </div>

		  <div class="fm-req">
		  <label for="città">Citt&agrave;:</label>
		  <input id="citta" name="citta" type="text">
		  </div>   
		  </fieldset>	

		  </fieldset>
		  <div id="fm-submit" class="fm-req">
			<input name="Submit" value="Submit" type="submit"/>
		  </div>
            
        </form>
