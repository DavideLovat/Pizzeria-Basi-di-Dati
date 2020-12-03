$(document).ready(function(){    
    $("#registra").validate({
    
        rules:{
            	login:{required:true},
	     	password:{required:true, minlength: 4, maxlength: 20},
		conferma:{required:true, equalTo:"#password"},
		nome:{required:true},
		cognome:{required:true},
		telefono:{required:true, remote:"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/registrazione/validate/check_telefono.php"},
		via:{required:true},
	     	ncivico:{required:true},
		cap:{required:true, maxlength: 5 ,remote:"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/registrazione/validate/check_cap.php"}, 
		citta:{required:true}
	    	
        },
        
        messages:{
            	login:{required:"inserire login"},
	     	password:{required:"inserire password", minlength:"minimo 4 caratteri", maxlength:"massimo 20 caratteri"},
		conferma:{required:"inserire conferma password", equalTo:"inserire lo stesso valore"},
		nome:{required:"inserire nome"},
		cognome:{required:"inserire cognome"},
		telefono:{required:"inserire numero di telefono", remote:"numero di telefono non corretto, inserire solo cifre"},
		via:{required:"inserire via"},
		ncivico:{required:"inserire n&deg;civico"},
		cap:{required:"inserire cap",minlength:"", maxlength:"inserire solo 5 caratteri", remote:"cap non valido"},
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