$(document).ready(function(){
	
	alert("start");
	$("form.form_rimuovi").submit(function(event){
		event.preventDefault();
		alert("submit prevent");
	});

	$("input.rimuovi").click(Rimuoviordine);
	alert("qui");
	
});

function Rimuoviordine()
{
	alert("qui");
	
	var form_padre = Getparent(this,'FORM');
	var input_idord = $(form_padre).find("input.input_idord");
	var input_codute = $(form_padre).find("input.input_codute");
	
	var idord = $(input_idord).val();
	var codute = $(input_codute).val();
	alert(idord+","+codute);
	var DatiJSON = 
	{
		"idord":idord,
		"codute":codute,
	};
	url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/remove_ordini.php";
	call = "rimuovi";
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
