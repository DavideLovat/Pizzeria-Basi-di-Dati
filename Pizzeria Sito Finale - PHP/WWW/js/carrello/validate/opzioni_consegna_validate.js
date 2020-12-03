$(document).ready(function(){    
    $("#consegna").validate({
    
        rules:{
            	giorno:{required:true, remote:"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/carrello/validate/check_giorno.php"},
	     	ora:{required:true, remote:"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/carrello/validate/check_ora.php"},
		via:{required:true},
	     	ncivico:{required:true},
		cap:{required:true, maxlength: 5 ,remote:"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/carrello/validate/check_cap.php"}, 
		citta:{required:true}
        },
        
        messages:{
            	giorno:{required:"inserire giorno",remote:"giorno antecedente a quello attuale"},
	     	ora:{required:"inserire ora", remote:"ora o data antecedente a quella attuale" },
		via:{required:"inserire via"},
		ncivico:{required:"inserire n&deg;civico"},
		cap:{required:"inserire cap",maxlength:"inserire solo 5 caratteri", remote:"cap non valido"},
		citta:{required:"inserire citt&agrave;"}

        },
        
        submitHandler: function(form) { 
            alert('I dati sono stati inseriti correttamente');
            form.submit();
        },
        
        invalidHandler: function() { 
            alert('I dati inseriti non sono corretti, ricontrollali....');
        },		
        
    });
	
});