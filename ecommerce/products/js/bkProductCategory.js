$(document).ready(function() {
$('#operationmsg').fadeOut(8000);

$('#CategoryADD').fancybox({'href':$('#CategoryADD').attr('href')});


/*------add product-------------*/

$('#ProductAdd').fancybox({'href':$('#ProductAdd').attr('href'),
			'width'                       : 500,

			'height'                      : 400,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'                : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe',
			
		    'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); }
			});

/*-------------------end---------------*/

  var callback = function(value) { 
       if(value==true)
       $("#divId").dialog("open");
	 }
 
$('a.deletecat').click(function() { 
   $("#modal_confirm_yes_no").dialog("open");
   $('#DeleteRecId').val($(this).attr('id'));
    return false; // prevent default
});

$("#divId").dialog({
           autoOpen: false,
           modal: true,
           height: 105,
           width: 342
       });
	   
 $("#modal_confirm_yes_no").dialog({
            bgiframe: true,
            autoOpen: false,
            minHeight: 100,
            width: 370,
            modal: true,
            closeOnEscape: false,
            draggable: false,
            resizable: false,
            buttons: {
                    'Yes': function(){
                        $(this).dialog('close');
                         callback(true);
                    },
                    'No': function(){
                        $(this).dialog('close');
                         callback(false);
                    }
                }
        });
 });
 
/*  function callback(value){
    alert(value);
      $('#DeleteRecId').val($(this).attr('id'));
	   $("#divId").dialog("open");
	 }*/


function showDialog(id){
  $('#fancyboxid').val(id);
   $("#divId").dialog("open");
    return false;
}

function PasswordsubmitForm() {
   var formval=$('#passwordsubmit').serialize();
   var fancyid=$("#fancyboxid").val();
   var DeleteID=$('#DeleteRecId').val();
   $.ajax({type:'POST', url: 'ProductAndCategoryDelete.php', data:formval, 
	success: function(response) {
    if(response=='yes'){
	 if(DeleteID!==''){
	   var DelId=DeleteID.split('_');
	   var relarr=$('#'+DeleteID+'').attr('rel');
	    $.post($('#'+DeleteID+'').attr('href'), {id:DelId[1],Task:relarr}, 
		function(data){
		  window.location='ProductCategoryList.php';
		});
	   }
	  else
      $('#'+fancyid+'').fancybox({'href':$('#'+fancyid+'').attr('href')}).trigger('click');
	  
	  $("#divId").dialog('close');
    }else if(response=='no'){
	   $('#wrongpassword').show();
	   $("#wrongpassword").html('Invalid Password');
	   $('#wrongpassword').fadeOut(8000);
	   }
	else{
	    $('#wrongpassword').show();
	    $("#wrongpassword").html('Enter Password');
		$('#wrongpassword').fadeOut(8000);
		}
   }});

    return false;
}

