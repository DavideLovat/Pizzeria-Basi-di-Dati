$(document).ready(function()
{	
	alert("start");
	$(".div_aggiungi").hide();
	$(".div_quantita").hide();

	$("input.quantita").change(function(){
		var quantita_val = $(this).val();
		var padre_tr = this;
		//alert("input quantita");
		while(!$(padre_tr).is("TR"))
		{
				padre_tr = $(padre_tr).parent();
		}
		//alert("fase 1: crea oggetti");
		var td_quantita = $(padre_tr).find("td.quantita");
		var td_aggiungi = $(padre_tr).find("td.aggiungi");
		
		var div_quantita = $(td_quantita).find("div.div_quantita");
		var div_aggiungi = $(td_aggiungi).find("div.div_aggiungi");	
		//alert("fase 2: if");
		if($(div_quantita).is(":visible"))
		{	//alert("if 1");
			if(quantita_val >= 1 && quantita_val <= 100)
			{	//alert("if 1 interno");
				$(div_quantita).hide(200);
			}

		}
		if($(div_aggiungi).is(":visible"))
		{	//alert("if 2");
			if(quantita_val >= 1 && quantita_val <= 100)
			{	//alert("if 1 interno");
				$(div_aggiungi).hide(200);
			}

		}
		
	});
	
	$("button.aggiungi").click(function()
	{
		
		var padre_tr = this;
		while(!$(padre_tr).is("TR"))
		{
				padre_tr = $(padre_tr).parent();
		}
		//trova elementi riga
		var td_tipo = $(padre_tr).find("td.tipo");
		var td_prezzo = $(padre_tr).find("td.prezzo");
		var td_quantita = $(padre_tr).find("td.quantita");
		var td_aggiungi = $(padre_tr).find("td.aggiungi");
		//$("#info").append(td_tipo+td_prezzo+td_quantita+" ");	

		var p_tipo = $(td_tipo).find("p.tipo");
		var p_prezzo = $(td_prezzo).find("p.prezzo");
		var input_quantita = $(td_quantita).find("input.quantita");
		
		var div_quantita = $(td_quantita).find("div.div_quantita");
		var div_quantita_carrello = $(td_quantita).find("div.div_quantita_carrello");
		var div_aggiungi = $(td_aggiungi).find("div.div_aggiungi");	
		
		$(div_quantita_carrello).innerHTML = "";
		//$("#info").append(p_tipo+p_prezzo+input_quantita+" ");
				
		var tipo_text = $(p_tipo).text();
		var prezzo_text = $(p_prezzo).text();	
		var quantita_val = $(input_quantita).val();
		
		//$("#info").append(tipo_text+prezzo_text+quantita_val+" ");
		alert("tipo: "+tipo_text+" prezzo: "+prezzo_text+" quantita: "+quantita_val+" ");
		
	
		var rowJSON = 
		{
			"padre_tr": padre_tr,
			"div_quantita_carrello": div_quantita_carrello,
			"p_prezzo": p_prezzo,
		};
		
		var pizzaJSON = 
		{
			"tipo": tipo_text,
			"prezzo": prezzo_text,
			"quantita": quantita_val,
		};
		
		if(quantita_val >= 1 && quantita_val <= 100)
		{
			alert("chiamata ajax");
			AjaxAddCart(rowJSON,pizzaJSON);	
		}
		else
		{
			if($(div_quantita).is(":hidden"))
			{
				$(div_quantita).text("valore compreso tra 1 e 100").show(200);
			}
			if($(div_aggiungi).is(":hidden"))
			{
				$(div_aggiungi).text("inserisci un valore compreso tra 1 e 100").show(200);
			}
		}
		
	});

});




function CheckAjaxResponse(DataJSON,rowJSON,pizzaJSON)
{//$("#info").append(" DataJSON: "+DataJSON+" rowJSON: "+rowJSON+" pizzaJSON: "+pizzaJSON+" ");
						//var parsedJSON = $.parseJSON(DataJSON);
						//$.each(parsedJSON, function(index, val) {
   						//$("#info").append(parsedJSON[index].val);
						//});
						//$("#info").append(parsedJSON[0].region);
						//$("#info").append(DataJSON);
						var parsedJSON = $.parseJSON(DataJSON);
						var stato = parsedJSON[0].stato;
						$("#info").append(stato+" ");
						switch(stato)
						{
							case "failure":
								alert("richiesta incorretta.");
								break;
							case "server_error":
								alert("errore nel server");
								break;
							case "empty":
								$(rowJSON.padre_tr).html("<td colspan='4'><p>elemento "+pizzaJSON.tipo+" obsoleto rimosso.</p></td>");
								$(rowJSON.padre_tr).delay(1000).hide("200",function(){
									$(rowJSON.padre_tr).remove();
									var n = $("#padre tr").length;
									if(n == 1){$("#padre").append("<tr><td colspan='4'><p>nessun elemento</p></td></tr>");}
								});
								break;
							case "change":
								var old_price = parsedJSON[1].prezzo_lista;
								var new_price = parsedJSON[1].prezzo;
								pizzaJSON.prezzo = new_price;
								$(rowJSON.p_prezzo).text(new_price);
								var r = confirm("prezzo sostituito da "+old_price+" a "+new_price);
								if (r==true)
								{
									AjaxAddCart(rowJSON,pizzaJSON);
								}
								else
								{
									var str="elemento "+pizzaJSON.tipo+" non aggiunto.\nQuantit\u00E0 "+parsedJSON[1].quantita;					
									//$("#infoaggiungi").text(str);
									$("#infoaggiungi").slideDown(400).text(str).delay(1000).slideUp(400); //delay precedente era 700

								}
								break;
							case "success":
								//$(rowJSON.td_quantita+" div").get(1).innerHTML = "quantita carrello "+parsedJSON[1].quantita;
								$(rowJSON.div_quantita_carrello).text("quantita carrello "+parsedJSON[1].quantita);
								var str="elemento "+parsedJSON[1].tipo+" aggiunto\nQuantit\u00E0 "+parsedJSON[1].quantita;
								//$("#infoaggiungi").text(str);
								$("#infoaggiungi").slideDown(400).text(str).delay(1000).slideUp(400);
								break;
							default:
						}
}

function AjaxAddCart(rowJSON,pizzaJSON)
{ 
		$.ajax({
				url:'http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/lista-pizze/add_cart.php',
				type:'GET',
				data: pizzaJSON,
				error: function(){

				},
		})
		.done (function( DataJSON ){
						alert("ajax chiamata successo");
						//$("#info").append(DataJSON+" ");	
						CheckAjaxResponse(DataJSON,rowJSON,pizzaJSON);
										
		})
		.fail(function(){
				alert(" ajax fallito");
		});
}
