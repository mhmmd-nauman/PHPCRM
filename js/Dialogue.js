// JavaScript Document
function modalbox(linkt,setTitle,type,addclosebutton){
		if(type == 'large'){
			SetWidth = 820;
			SetHeight = 600;
		}else if(type == 'medium'){
			SetWidth = 620;
			SetHeight = 360;
		}else if(type == 'small'){
			SetWidth = 330;
			SetHeight = 220;
		} else if(type=="xlarge"){
			SetWidth = 920;
			SetHeight = 560;
		}else if(type == ''){
			SetWidth = 620;
			SetHeight = 360;
		}else if(type != ''){					/* if the parameter is passed as 400X400(first 400 is width and the second 400 is height)*/
			 var n = type.split("X");
			 SetWidth = n[0];
			 SetHeight = n[1];
		}
		
		if(addclosebutton == true){
			$('<iframe id="some-dialog" class="window-Frame" src='+linkt+' />').dialog({
				  autoOpen: true,
				  width: SetWidth,
				  height: SetHeight,
				  modal: true,
				  resizable: true,
				  title:setTitle,
				  closeOnEscape: false,
				  buttons: {
					'Close': function() {
						jQuery('#some-dialog').dialog('close');
						location.reload(true);
					},
				  }
			  }).width(SetWidth-20).height(SetHeight-20);
		  }else{
		  	$('<iframe id="some-dialog" class="window-Frame" src='+linkt+' />').dialog({
				  autoOpen: true,
				  width: SetWidth,
				  height: SetHeight,
				  modal: true,
				  resizable: true,
				  title:setTitle,
				  closeOnEscape: true,
			  }).width(SetWidth-20).height(SetHeight-20);
		  }
	}