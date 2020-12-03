
function Rimuovi(idord)
{	
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/ordini/cancella_ordinazione.php";
	var ParamJSON =
	{
		"idord":idord
	}
	AjaxCall(url_ajax,ParamJSON);
}

function AjaxCall(url_ajax,ParamJSON)
{	
	$.ajax({
				url:url_ajax,
				type:'POST',
				data: ParamJSON,
				error: function(){

				},
		}).done (function( DataJSON ){
					
						var parsedJSON = $.parseJSON(DataJSON);
						var stato = parsedJSON.stato;
						if(stato == "true")
						{
							$("#"+ParamJSON['idord']).remove();
							alert("ordine rimosso");
							if($("#ordini").is(":empty"))
							{
								$("#ordini").html("<p>non ci sono ordini<p>")
							}
						}
						else
						{
							alert("ordine non rimosso");
						}					
		});	

}

