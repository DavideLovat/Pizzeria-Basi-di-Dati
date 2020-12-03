$(document).ready(function(){
	
	$('#form_pizze').submit(function(event) {
		event.preventDefault();
		alert('Handler for .submit() called.');
		var tipo = $("#tipo").val();
		var prezzo = $("#prezzo").val();
		var ingredienti = $("#ingredienti").val();
	  	var DatiJSON = 
		{
			"tipo":tipo,
			"prezzo":prezzo,
			"ingredienti":ingredienti
		};
		var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/insert_pizze.php";
		AjaxCall(url_ajax,DatiJSON);	
	});
});	

function AjaxCall(url_ajax,DatiJSON)
{	
	$.ajax({
				url:url_ajax,
				type:'POST',
				data: DatiJSON,
		}).done (function( ResultJSON ){				
					alert("successo");
					$("#info").html(ResultJSON);				
					$("#tipo").val("");
					$("#prezzo").val("");
					$("#ingredienti").val("");	
		})
		.fail(function(){
				alert("fallito");
		});	

}
