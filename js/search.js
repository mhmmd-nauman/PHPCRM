/* Search hide-show start here */
			jQuery(document).ready(function(){ 		
				jQuery(".aoo").show();
				
				jQuery('#search_close').click(function(event){
				jQuery("#cate_main").slideUp(400);
				jQuery("#show_options").show();
				event.stopPropagation();
			});
			jQuery('#input_box_2').click(function(event){
				jQuery("#cate_main").hide();
				jQuery("#show_options").show();
				event.stopPropagation();
			});
			
		        jQuery("#show_options").click(function(event){
				jQuery("#show_options").hide();
		        jQuery("#cate_main").slideDown(400);
				event.stopPropagation();				
		       });
			   
			  });
/* Search hide-show end here */


