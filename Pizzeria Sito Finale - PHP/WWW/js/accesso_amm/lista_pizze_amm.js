$(document).ready(function(){
	$("button.rimuovi").click(Rimuovi);
	$("button.modifica").click(Modifica);
});

function Rimuovi()
{
	var tr_padre = Getparent(this,'TR');
	var td_tipo = $(tr_padre).find("td.tipo");
	var p_tipo = $(td_tipo).find("p.tipo");
	var tipo = $(p_tipo).text();
	
	var call = "rimuovi";
	var DatiJSON = 
	{
		"tipo":tipo,
	};
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/remove_pizze.php";
	AjaxCall(url_ajax,DatiJSON,tr_padre,call);
}

function Modifica()
{
	var tr_padre = Getparent(this,'TR');
	var td_tipo = $(tr_padre).find("td.tipo");
	var p_tipo = $(td_tipo).find("p.tipo");
	var tipo = $(p_tipo).text();
	call = "modifica";
	var DatiJSON = 
	{
		"tipo":tipo,
	};
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/modifica_lista_pizze.php";
	AjaxCall(url_ajax,DatiJSON,tr_padre,call);
}

function AjaxCall(url_ajax,DatiJSON,tr_padre,call)
{ 
		$.ajax({
				url:url_ajax,
				type:'GET',
				data: DatiJSON,
		}).done (function( ResultJSON ){
						alert("ajax success");
						CheckResult(ResultJSON,tr_padre,call);
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
function CheckResult(ResultJSON,tr_padre,call)
{
	alert("chiamata : "+call);
	var parsedJSON = $.parseJSON(ResultJSON);
	var stato = parsedJSON[0].stato;
	alert("stato : "+stato);
	if(stato == "successo")
	{
		if(call == "modifica")
		{
			var td_tipo = $(tr_padre).find("td.tipo");
			var p_tipo = $(td_tipo).find("p.tipo");
			var tipo = $(p_tipo).text();
			alert("pizza tipo : "+tipo);
			var url_ajax_success = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/form_mod_pizze.php?tipo="+tipo;
			window.location.assign(url_ajax_success);
		}
		else if(call == "rimuovi")
		{
			location.reload();
		}
	}
	else if(stato == "notipo")
	{
		alert("tipo assente");
		location.reload();
	}
	else if(stato == "noget")
	{
		alert("richiesta vuota");
	}
	else if(stato == "noamm")
	{
		location.reload();
	}	
	else if(stato == "server_error")
	{
		alert("errore del server");
	}
	else
	{
		alert("stato sconosciuto");
	}
}