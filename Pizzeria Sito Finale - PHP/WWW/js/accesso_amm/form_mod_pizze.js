$(document).ready(function(){
	
	$("input.new_ing").hide();
	$("input.annulla_mod").hide();
	$("input.conferma_mod").hide();
	$("div.info_ajax").text("");
	alert("start");
	$("#form_mod_pizze").submit(function(event){
		event.preventDefault();
		alert("submit prevent");
	});
	$(".aggiorna").click(Aggiorna);
	$(".sostituisci").click(Sostituisci);
	$(".aggiungi").click(Aggiungi);
	$(".rimuovi").click(Rimuovi);
	$(".modifica").click(Modifica);
	$(".annulla_mod").click(Annulla);
	$(".conferma_mod").click(Conferma);
	
});



function Aggiorna()
{
	Clear(this);
 	var tipo = $("#tipo").val();
	var prezzo = $("#new_prezzo").val();
	var info_ajax = $("#info_prezzo");
	var div_padre = $("#div_prezzo");
	var DatiJSON = 
	{
		"tipo":tipo,
		"prezzo":prezzo
	};
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/update_prezzo_pizze.php";
	var call = "aggiorna";
	AjaxCall(url_ajax,DatiJSON,info_ajax,div_padre,call);
	alert("buono");
}
function Sostituisci()
{
	Clear(this);
	var tipo = $("#tipo").val();
	var ingredienti = $("#ingredienti").val();
	var info_ajax = $("#info_ingredienti");
	var div_padre = $("#div_ingredienti");

	var DatiJSON = 
	{
		"tipo":tipo,
		"ingredienti":ingredienti
	};
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/sostituisci_ingredienti_pizza.php";
	var call = "sostituisci";
	AjaxCall(url_ajax,DatiJSON,info_ajax,div_padre,call);
	alert("buono");
}
function Aggiungi()
{
	Clear(this);
	var tipo = $("#tipo").val();
	var ingredienti = $("#ingredienti").val();
	var info_ajax = $("#info_ingredienti");
	var div_padre = $("#div_ingredienti");

	var DatiJSON = 
	{
		"tipo":tipo,
		"ingredienti":ingredienti
	};
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/add_ingredienti_pizza.php";
	var call = "aggiungi";
	AjaxCall(url_ajax,DatiJSON,info_ajax,div_padre,call);
	alert("buono");	
}
function Rimuovi()
{
	Clear(this);
	var div_padre = Getparent(this,'DIV');
	var input_ing = $(div_padre).find("input.lista_ing");
	var old_ing = $("input.old_ing").val();	
	var tipo = $("#tipo").val();
	var info_ajax= $(div_padre).find("div.info_lista_ing");
	

	alert(old_ing);
	var DatiJSON = 
	{
		"tipo":tipo,
		"old_ing":old_ing
	};
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/delete_ingrediente_pizza.php";
	var call = "rimuovi";
	AjaxCall(url_ajax,DatiJSON,info_ajax,div_padre,call);
	alert("buono");
}
function Modifica()
{	
	Clear(this);	
	var div_padre = Getparent(this,'DIV');
	var input_old_ing = $(div_padre).find("input.old_ing");
	var input_new_ing = $(div_padre).find("input.new_ing");
	var input_rimuovi = $(div_padre).find("input.rimuovi");
	var input_modifica = $(div_padre).find("input.modifica"); 
	var input_annulla = $(div_padre).find("input.annulla_mod");
	var input_conferma = $(div_padre).find("input.conferma_mod");
	var info_ajax= $(div_padre).find("div.info_lista_ing");
	
	$(input_new_ing).show();
	$(input_rimuovi).hide();
	$(input_modifica).hide();
	$(input_annulla).show();
	$(input_conferma).show();
	

	alert("buono");
}
function Annulla()
{	
	Clear(this);
	var div_padre = Getparent(this,'DIV');

	var input_new_ing = $(div_padre).find("input.new_ing");
	var input_rimuovi = $(div_padre).find("input.rimuovi");
	var input_modifica = $(div_padre).find("input.modifica"); 
	var input_annulla = $(div_padre).find("input.annulla_mod");
	var input_conferma = $(div_padre).find("input.conferma_mod");
	var info_ajax= $(div_padre).find("div.info_lista_ing");

	$(input_new_ing).val("");
	$(input_new_ing).hide();
	$(input_rimuovi).show();
	$(input_modifica).show();
	$(input_annulla).hide();
	$(input_conferma).hide();
	
	alert("buono");
}
function Conferma()
{
	//$("#ingredienti").val("");
	//$("#prezzo").val("");

	var div_padre = Getparent(this,'DIV');
	var input_old_ing = $(div_padre).find("input.old_ing");
	var input_new_ing = $(div_padre).find("input.new_ing");
	var info_ajax= $(div_padre).find("div.info_lista_ing");	
	var old_ing = $(input_old_ing).val();
	var new_ing = $(input_new_ing).val();
	var tipo = $("#tipo").val();
	alert(old_ing+" , "+new_ing);
	var DatiJSON = 
	{
		"tipo":tipo,
		"old_ing":old_ing,
		"new_ing":new_ing
	};
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/update_ingrediente_pizza.php";
	var call = "conferma";
	AjaxCall(url_ajax,DatiJSON,info_ajax,div_padre,call);
	// da inserire nella funzione ajax e ricordarsi di modificare gli input visisbili e nascoste

	alert("buono");
}


function AjaxCall(url_ajax,DatiJSON,info_ajax,div_padre,call)
{ 
		$.ajax({
				url:url_ajax,
				type:'GET',
				data: DatiJSON,
		}).done (function( ResultJSON ){
						alert("ajax success");
						CheckResult(ResultJSON,info_ajax,div_padre,call);
						
						
		})
		.fail(function(){
						alert("ajax fail");
		});
}
function Getparent(objfiglio,tagpadre)
{
		var objpadre = objfiglio;
		while(!$(objpadre).is(tagpadre))
		{
			var objpadre = $(objpadre).parent(); //alert($(td).prop('tagName'));
		}	
		return objpadre;
}
function Clear(elemento)
{
	$(".info_ajax").text("");
	$(".second").hide();
	$(".first").show();
	$(".new_ing").val("");
	/*if ($(elemento).is(".aggiorna"))
	{
		$("#ingredienti").val("");
	}
	else if($(elemento).is(".aggiungi,.sostituisci"))
	{
		$("#prezzo").val("");
	}
	else
	{
		$("#ingredienti").val("");
		$("#prezzo").val("");
	}*/	
}
function CheckResult(ResultJSON,info_ajax,div_padre,call)
{
	alert(call);
	var parsedJSON = $.parseJSON(ResultJSON);
	var stato = parsedJSON[0].stato;
	alert(stato);
	if(stato == "successo")
	{
		alert("successo");
		location.reload();
	}
	else if(stato == "noget")
	{
		alert("inserire un valore");
	}
	else if(stato == "server_error")
	{
		alert("errore nel server")
	}
	else if(stato == "notipo")
	{
		alert("tipo "+$("#tipo").val()+" assente");
		location.reload();
	}
	else if(stato == "noamm")
	{
		location.reload();
	}
	else if(stato == "assente")
	{
		alert("ingredienti "+parsedJSON[1].assente+" assenti in tabella ingredienti");

	}
	else if(stato == "presente")
	{
		alert("ingredienti "+parsedJSON[2].presente+" presenti in pizza "+$("#tipo").val());
	}
	else if(stato == "contiene_assente_old")
	{
		alert("ingrediente "+parsedJSON[3].contiene_assente_old+" da modificare assente in pizza "+$("#tipo").val());
		location.reload();
	}
	else if(stato == "contiene_presente_new")
	{
		alert("nuovo ingrediente "+parsedJSON[4].contiene_presente_new+" presente in pizza "+$("#tipo").val());
	}
	else if(stato == "nodelete")
	{
		alert("ingredienti pizza pari a uno. Impossibile rimuovere ingrediente senza eliminare pizza");
	}
	else if(stato == "nocontiene")
	{
		var input_ing = $(div_padre).find("input.lista_ing");
		var old_ing = $("input.old_ing").val();	
		var tipo = $("#tipo").val();

		alert("elemento ingrediente "+ old_ing +" in pizza "+ tipo +" non esiste");
	}
	else
	{
		alert("stato sconosciuto");
	}	
}