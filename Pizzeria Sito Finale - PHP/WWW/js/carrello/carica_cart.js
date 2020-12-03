
$("p").on("click",function(){
  alert("ciao");});
$("input.quantita").on("change",Inputchange);
$("a.modifica").on("click",Changecart);	
$("a.rimuovi_cart").on("click",Removecart);
$("a.rimuovi_nocart").on("click",Removenocart);
$("button.aggiungi").on("click",Addtocart);
$("button#ordina").on("click",Consegna);


function Inputchange()
	{		
		var val =$(this).val();
		//alert(val);
		var td = Getparent(this,'TD');

		var a1=$(td).find("a.modifica"); //var a1=$(td).find("a:eq(0)"); //alert($(a1).prop('tagName'));
		
		if(val==0)
		{
			
			a1.text("rimuovi");
				
		}
		else
		{
			a1.text("salva");
		}
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





function Changecart(event)
{
		event.preventDefault();	//previene l'evento di default del tag
		//rowJSON
		var tr = Getparent(this,'TR');
		var p_tipo = $(tr).find("td.tipo").find("p.tipo");
		var p_prezzo = $(tr).find("td.prezzo").find("p.prezzo");
		var i_quantita = $(tr).find("td.quantita").find("input.quantita");
		//sessionJSON
		var tipo = p_tipo.text();
		var prezzo = p_prezzo.text();
		var quantita = i_quantita.val();
		//alert(tipo+prezzo+quantita);
		var sessionJSON = 
		{
			"tipo":tipo,
			"prezzo":prezzo,
			"quantita":quantita
		};
		//alert($.param(sessionJSON));
		var rowJSON =
		{
			"p_tipo":p_tipo,
			"p_prezzo":p_prezzo,
			"i_quantita":i_quantita
		};
		
		url_ajax="cart_update.php";
		AjaxCall(url_ajax,rowJSON,sessionJSON);
		
}

function Removecart(event)
{
		event.preventDefault();
		var tr = Getparent(this,'TR');
		var p_tipo = $(tr).find("td.tipo").find("p.tipo");
		var p_prezzo = $(tr).find("td.prezzo").find("p.prezzo");
		var i_quantita = $(tr).find("td.quantita").find("input.quantita");
		//sessionJSON
		var tipo = p_tipo.text();
		var prezzo = p_prezzo.text();
		var quantita = i_quantita.val();
		//alert(tipo+prezzo+quantita);
		var sessionJSON = 
		{
			"tipo":tipo,
			"prezzo":prezzo,
			"quantita":quantita
		};
		//alert($.param(sessionJSON));
		var rowJSON =
		{
			"p_tipo":p_tipo,
			"p_prezzo":p_prezzo,
			"i_quantita":i_quantita
		};
		
		url_ajax="cart_remove.php";
		AjaxCall(url_ajax,rowJSON,sessionJSON);
}

function Removenocart(event)
{
	event.preventDefault();
	var tr = Getparent(this,'TR');
	var p_tipo = $(tr).find("td.tipo").find("p.tipo");
	var indice = tr.attr("id");
	alert(indice);
	var tipo = p_tipo.text();
	var sessionJSON=
	{	
		"indice":indice,
		"tipo":tipo,
	};
	var rowJSON =
		{
			"p_tipo":p_tipo,
		};

	url_ajax="nocart_remove.php";
	AjaxCall(url_ajax,rowJSON,sessionJSON);
	
}

function Addtocart()
{
	var tr = Getparent(this,'TR');
	var p_tipo = $(tr).find("td.tipo").find("p.tipo");
	
	var indice = tr.attr("id");
	var tipo = p_tipo.text();
	alert(indice+","+tipo);
	var sessionJSON = 
	{
		"indice":indice,
		"tipo":tipo
	} 
	var rowJSON =
		{
			"p_tipo":p_tipo
		};
	url_ajax="nocart_aggiungi_cart.php";
	AjaxCall(url_ajax,rowJSON,sessionJSON);
}
function Consegna()
{
	window.location.assign("opzioni_consegna.php");
}
/*function Sendorder()
{
	url_ajax="create_session_order.php";
	$.ajax({
				url:url_ajax,
				type:'GET',
				error: function(){
					alert("errore nell'ordine");
				},
		}).done (function(){
						
				window.location.assign("http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/ordini/controlla_ordine.php");			
				
		});	

				
}
*/
/*
function CheckAjaxResponse(DataJSON,rowJSON,sessionJSON)
{
	
}
*/

function AjaxCall(url_ajax,rowJSON,sessionJSON)
{	
	$.ajax({
				url:url_ajax,
				type:'GET',
				data: sessionJSON,
				error: function(){

				},
		}).done (function( DataJSON ){
						//$("#info").append(DataJSON+" ");	
						//CheckAjaxResponse(DataJSON,rowJSON,pizzaJSON);
						$("#lista_cart").load("http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/carrello/carica_cart.php");
						
				
		});	

}


