$(document).ready(function(){
	
	alert("start");
	$("form").submit(function(event){
		event.preventDefault();
		alert("submit prevent");
	});
	$("#modifica_ord").click(Modificaordini);
	$(".rimuovi_reg").click(Rimuoviregistrazione);
	$(".modifica_reg").click(Modificaregistrazione);
});

function Modificaordini()
{
	//informazioni ordine
 	var idord = $("#ord_idord").val();
	var codute = $("#ord_codute").val();
	
	//informazioni indirizzo ordine
	var new_giorno = $("#new_giorno").val();
	var new_ora = $("#new_ora").val(); 
	var new_via = $("#new_via").val(); 
	var new_ncivico = $("#new_ncivico").val();  
	var new_cap = $("#new_cap").val(); 
	var new_citta = $("#new_citta").val(); 

	var DatiJSON = 
	{
		"idord":idord,
		"codute":codute,
		"new_giorno":new_giorno,
		"new_ora":new_ora,
		"new_via":new_via,
		"new_ncivico":new_ncivico,
		"new_cap":new_cap,
		"new_citta":new_citta
	};

	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/update_ordini.php";
	var call = "modifica_ord";
	AjaxCall(url_ajax,DatiJSON,call);
	alert("buono");
	
}

function Rimuoviregistrazione()
{
	var form_padre = Getparent(this,'FORM');
	var idpiz = $(form_padre).find("input.reg_idpiz");
	var idord = $(form_padre).find("input.reg_idord");
	var codute = $(form_padre).find("input.reg_codute");
	var quantita = $(form_padre).find("input.quantita");
	var prezzo = $(form_padre).find("input.prezzo");
	var new_quantita = $(form_padre).find("input.new_quantita");
	var new_prezzo = $(form_padre).find("input.new_prezzo");
	
	var DatiJSON =
	{
		"idpiz":idpiz,
		"idord":idord,
		"codute":codute,
		"quantita":quantita,
		"prezzo":prezzo,
		"new_quantita":new_quantita,
		"new_prezzo":new_prezzo
	};
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/";
	var call = "rimuovi_reg";
	AjaxCall(url_ajax,DatiJSON,call);
	alert("buono");	
}

function Modificaregistrazione()
{
	var form_padre = Getparent(this,'FORM');
	var idpiz = $(form_padre).find("input.reg_idpiz");
	var idord = $(form_padre).find("input.reg_idord");
	var codute = $(form_padre).find("input.reg_codute");
	var quantita = $(form_padre).find("input.quantita");
	var prezzo = $(form_padre).find("input.prezzo");
	var new_quantita = $(form_padre).find("input.new_quantita");
	var new_prezzo = $(form_padre).find("input.new_prezzo");
	
	var DatiJSON =
	{
		"idpiz":idpiz,
		"idord":idord,
		"codute":codute,
		"quantita":quantita,
		"prezzo":prezzo,
		"new_quantita":new_quantita,
		"new_prezzo":new_prezzo
	};
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/";
	var call = "modifica_reg";
	AjaxCall(url_ajax,DatiJSON,call);
	alert("buono");	
}
function AjaxCall(url_ajax,DatiJSON,call)
{ 
		$.ajax({
				url:url_ajax,
				type:'GET',
				data: DatiJSON,
		}).done (function( ResultJSON ){
						alert("ajax success");
						CheckResult(ResultJSON,call);
						
						
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
}
function CheckResult(ResultJSON,call)
{
	alert("chiamata: "+call);
	var parsedJSON = $.parseJSON(ResultJSON);
	var stato = parsedJSON[0].stato;
	alert("stato: "+stato);
	if(stato == "successo")
	{
		alert("successo");
		location.reload();
	}
	else if(stato == "noget")
	{
		alert("inserire un valore");
	}
	else if(stato == "noget_ind")
	{
		alert("compilare tutti i campi indirizzo");
	}
	else if(stato == "server_error")
	{
		alert("errore nel server")
	}
	else if(stato == "noordine")
	{
		alert("ordine "+ $("#ord_idord").val() +" assente");
		location.reload();
	}
	else if(stato == "noamm")
	{
		location.reload();
	}
	else
	{
		alert("stato sconosciuto");
	}	
}
