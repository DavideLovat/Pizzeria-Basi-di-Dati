$(document).ready(function(){
	$("button.rimuovi").click(Rimuovi);
	$("button.modifica").click(Modifica);
	alert("start");
});

function Rimuovi()
{
	alert("function Rimuovi")
	var tr_padre = Getparent(this,'TR');
	var td_login = $(tr_padre).find("td.login");
	var p_login = $(td_login).find("p.login");
	var login = $(p_login).text();
	
	var call = "rimuovi";
	var DatiJSON = 
	{
		"login":login,
	};
	alert(login);
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/remove_utenti.php";
	AjaxCall(url_ajax,DatiJSON,tr_padre,call);
}

function Modifica()
{
	alert("function Modifica");
	var tr_padre = Getparent(this,'TR');
	var td_login = $(tr_padre).find("td.login");
	var p_login = $(td_login).find("p.login");
	var login = $(p_login).text();
	call = "modifica";
	var DatiJSON = 
	{
		"login":login,
	};
	alert(login);
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/modifica_lista_utenti.php";
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
	alert("chiamata: "+call);
	var parsedJSON = $.parseJSON(ResultJSON);
	var stato = parsedJSON[0].stato;
	alert("stato: "+stato);
	if(stato == "successo")
	{
		if(call == "modifica")
		{
			var td_login = $(tr_padre).find("td.login");
			var p_login = $(td_login).find("p.login");
			var login = $(p_login).text();
			alert("pizza login : "+login);
			var url_ajax_success = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/form_mod_utenti.php?login="+login;
			window.location.assign(url_ajax_success);
		}
		else if(call == "rimuovi")
		{
			alert("utente rimosso con successo");
			location.reload();
		}
	}
	else if(stato == "nologin")
	{
		var td_login = $(tr_padre).find("td.login");
		var p_login = $(td_login).find("p.login");
		var login = $(p_login).text();

		alert("login "+login+" assente");
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
