$(document).ready(function() {
$('#operationmsg').fadeOut(8000);

$('#CategoryADD').fancybox({
				'href':$('#CategoryADD').attr('href'),
				'hideOnOverlayClick':false
				});


/*------add product-------------*/
$('#ProductAdd').fancybox({
			'width'                       : 500,

			'height'                      : 525,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'                : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe',
			 'hideOnOverlayClick'                       :false,
			
		    'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); 
              $.fancybox.resize();
		    }
			
			});

/*-------------------end---------------*/


/*---------------edit product fancybox----------------*/

$('.productedit').fancybox({
					'href'                        : this.href,
					
					'width'                       : 500,
		
					'height'                      : 525,
		
					'autoScale'                   : false,
		
					'transitionIn'                : 'none',
		
					'transitionOut'                : 'none',
		
					'type'                        : 'iframe',
					
					'hideOnOverlayClick'           :false,
					
					'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'});
					//$('#fancybox-inner').height(425);
                    //$('#fancybox-wrap').height(425); 
					$.fancybox.resize();

					}
					});


<!---------------end-------------->

/*----------------------category fancybox---------------*/
 $('.Categoryedit').fancybox({'href': this.href,'hideOnOverlayClick':false});

/*---------------categoy fancybox end-------------*/

 var callback = function(value) { 
       if(value==true){
	   var DeleteID=$('#DeleteRecId').val();
       var pages=$('#page').val();
	   /*----------------product delete code for product and category-------*/
	   if(DeleteID!==''){
		  var DelId=DeleteID.split('_');
		   var relarr=$('#'+DeleteID+'').attr('rel');
			$.post($('#'+DeleteID+'').attr('href'), {id:DelId[1],Task:relarr,PageName:pages}, 
			function(data){
			 if(pages=='Product')
			  window.location='ProductAndSubscriptionList.php';
			  else
			  window.location='ProductCategoryList.php';
			});
		   
		   
	   }
       //$("#divId").dialog("open");// This is for password dialogbox
	  }
 }
 
$('a.deletecat').click(function() { 
   $("#modal_confirm_yes_no").dialog("open");
   $('#DeleteRecId').val($(this).attr('id'));
    return false; // prevent default
});

/*----------------End-------*/



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
	 
	 
/*----------password popup form open----*/
/*function showDialog(id){
  $('#fancyboxid').val(id);
   $("#divId").dialog("open");
    return false;
}*/

/*function PasswordsubmitForm() {
   var formval=$('#passwordsubmit').serialize();
   var ptype=$("#prodtype").val();
   var fancyid=$("#fancyboxid").val();
   var DeleteID=$('#DeleteRecId').val();
   var pages=$('#page').val()
   $.ajax({type:'POST', url: 'ProductAndCategoryDelete.php', data:formval, 
	success: function(response) {
   if(response=='yes'){
	 if(DeleteID!==''){
	  var DelId=DeleteID.split('_');
	   var relarr=$('#'+DeleteID+'').attr('rel');
	    $.post($('#'+DeleteID+'').attr('href'), {id:DelId[1],Task:relarr,PageName:pages}, 
		function(data){
		 if(pages=='Product')
		  window.location='ProductAndSubscriptionList.php';
		  else
		  window.location='ProductCategoryList.php';
		});
		
		
	   }else{
			    if(ptype=='iframe'){
			    
				$('#'+fancyid+'').fancybox({'href':$('#'+fancyid+'').attr('href'),
					'width'                       : 500,
		
					'height'                      : 400,
		
					'autoScale'                   : false,
		
					'transitionIn'                : 'none',
		
					'transitionOut'                : 'none',
		
					'type'                        : 'iframe',
					
					 'hideOnOverlayClick'                       :false,
					
					'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); }
					}).trigger('click');
				   
			   }
			  else
			  $('#'+fancyid+'').fancybox({'href':$('#'+fancyid+'').attr('href'),'hideOnOverlayClick':false}).trigger('click');
			  $("#divId").dialog('close');
	   }
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

  $("#adminpassword").val('');
    return false;
}*/

