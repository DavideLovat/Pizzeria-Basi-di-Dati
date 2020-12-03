$(document).ready(function(){    
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
            form.submit();
        },
        
        invalidHandler: function() { 
            alert('I dati inseriti non sono corretti, ricontrollali....');
        },		
        
    });
});