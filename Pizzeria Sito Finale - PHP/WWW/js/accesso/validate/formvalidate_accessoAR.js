$(document).ready(function(){    
    
	$("#messaggio").hide();
	
	$("#accessoAR").submit(function(event){
		//event.preventDefault();
	}); 

	$("#accessoAR").validate({
    	
        rules:{
            	login:{required:true},
	     	password:{required:true}
        },
        
        messages:{
            login:{required:"inserire login"},
	     password:{required:"inserire password"}
        },
        
        submitHandler: function(form) {
            alert('I dati sono stati inseriti correttamente');
			
		var login = $("#login").val();
		var password = $("#password").val();
		var DataJSON = {"login":login, "password":password};
		//alert(login+","+password+",lj: "+DataJSON.login+",pj: "+DataJSON.password);


		$.ajax({
			type:"post",
			url:"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso/verifica_accesso.php",
			data:DataJSON,
		})
		.done(function(ResultJSON){
			var parsedJSON = $.parseJSON(ResultJSON);
			var stato = parsedJSON.stato;//alert("stato: "+stato);
			switch(stato)
			{
				case "failure":
					$("#messaggio").show();
					return false;
				break;
				case "success":
					form.submit();
				break;
				default:
					$("#messaggio").show();
					return false;
			}

			
		})
		.fail(function(){
			alert("chiamata ajax fallita");
			//form.submit(function(){return false;});

		});
		
		
        },
        
        invalidHandler: function() { 
            alert('I dati inseriti non sono corretti, ricontrollali....');
	     $("#info").text("insert log");
        },		
        
    });
});