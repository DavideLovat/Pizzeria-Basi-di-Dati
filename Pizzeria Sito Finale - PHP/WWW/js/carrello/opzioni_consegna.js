
$(document).ready(function(){
	

	$("#button_nuovo_indirizzo").click(function(){
		$("#div_indirizzo_consegna").load("form_nuovo_indirizzo.php");
		
		
	});

	$("#button_indirizzo_utente").click(function(){
		$("#div_indirizzo_consegna").load("form_indirizzo_utente.php");
		
	});
	
});
 	