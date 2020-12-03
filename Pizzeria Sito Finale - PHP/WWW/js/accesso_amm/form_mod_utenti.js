$(document).ready(function(){
	
	$("div.info_ajax").text("");
	alert("start");
	$("#form_mod_utenti").submit(function(event){
		event.preventDefault();
		alert("submit prevent");
	});
	$("#submit").click(Modifica);

});

function Modifica()
{
	Clear(this);
	
	//informazioni utente
 	var login = $("#login").val();
	var info_login = $("#info_login");

	var new_login = $("#new_login").val();	
	var new_password = $("#new_password").val();
	var new_nome = $("#new_nome").val();
	var new_cognome = $("#new_cognome").val();
	var new_telefono = $("#new_telefono").val();

	//informazioni indirizzi
	
	var new_via = $("#new_via").val();
	var new_ncivico = $("#new_ncivico").val();
	var new_cap = $("#new_cap").val();
	var new_citta = $("#new_citta").val();

	alert(" login: "+login);
	
	alert(" new_login: "+new_login+" new_password: "+new_password+" new_nome: "+new_nome+" new_cognome: "+new_cognome+" new_telefono: "+new_telefono);
	alert(" new_via: "+new_via+" new_ncivico: "+new_ncivico+" new_cap: "+new_cap+" new_citta: "+new_citta);

	var DatiJSON = 
	{
		"login":login,
		"new_login":new_login,
		"new_password":new_password,
		"new_nome":new_nome,
		"new_cognome":new_cognome,
		"new_telefono":new_telefono,
		"new_via":new_via,
		"new_ncivico":new_ncivico,
		"new_cap":new_cap,
		"new_citta":new_citta
	};
	var url_ajax = "http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/update_utenti.php";
	var call = "modifica";
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
	else if(stato == "noget_ind")
	{
		alert("compilare tutti i campi indirizzo");
	}

	else if(stato == "server_error")
	{
		alert("errore nel server")
	}
	else if(stato == "nologin")
	{
		alert("login "+$("#login").val()+" assente");
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
