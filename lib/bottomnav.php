<?php
  //print_r($_SERVER['QUERY_STRING']);
  $paramString = "";
  parse_str($_SERVER['QUERY_STRING'], $paramArray);
  
  foreach($paramArray as $key => $value){
   
   if($key != "page"){
    if($key !="record_perpage"){
		if($key !="action"){
		  if($key !="Task"){
			$paramString .="$key=$value&";
			}
		}
	}
   }
  }
  $paramString = substr($paramString, 0, strlen($paramString) - 1);
  ?>
  
  
  <table width="100%" align='left' border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td >&nbsp;</td>
		  <td>&nbsp;</td>
    		</tr>
		<tr>
		<td nowrap="nowrap"><?php if ($total_records > 10) { ?>
		  <?php echo $ret->offset+1?>-<?php echo (($ret->offset)+$ret->limit);?> Records<br>
		  of <?php echo $total_records;?> total		
		  <?php } ?></td>
		<td align="center" >
		<?php
	
	 if($ret->numPages <=1){
	 
	 
	 
	 }
	  else  {
      
	   ?>
		<table border="0" align="right" cellpadding="0" cellspacing="0"  >
		  <tr>
		  
			   <td nowrap="nowrap">
		     
		   <?php if($ret->page-1 > 0){ ?>
			<a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?page=1&<?php echo $paramString;?>'><span>First</span></a>
		   <?php }?>
		   <?php if($ret->page-1 > 0){ ?>
			<a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?page=<?php echo $ret->page-1;?>&<?php echo $paramString;?>'><span>Prev</span></a>		    
		    <?php } else { ?>
		   <a ><span style="cursor:default; color:#999999;">Prev</span></a>
		  <?php } ?>
			 
			
		 <?php if(($ret->page-1) > 0 ){ ?>
		
		  <a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?page=<?php echo $ret->page - 1;?>&<?php echo $paramString;?>'>
		  <span><?php echo $ret->page-1;?></span>		  </a>
		  <?php } ?>
		  
		  <!--on page-->
		 <a ><span><b><?php echo $ret->page; ?></b></span></a> 
		 
		 
		 <?php if(($ret->page+1) <= $ret->numPages){ ?>
		  <a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?page=<?php echo $ret->page +1;?>&<?php echo $paramString; ?>'>
		  <span><?php echo $ret->page+1; ?></span>		  </a>
		  <?php } ?> 
		  <?php if(($ret->page+2) <=  $ret->numPages){ ?>
		  <a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?page=<?php echo $ret->page +2;?>&<?php echo $paramString;?>'>
		   <span><?=$ret->page+2?></span>		  </a>
		  <?php } ?>
		  <?php if(($ret->page+3) <=  $ret->numPages){ ?>
		  <a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?page=<?php echo $ret->page +3;?>&<?php echo $paramString; ?>'>
		   <span><?php echo $ret->page+3; ?></span>		  </a>
		  <?php } ?>		  
		 <?php if($ret->page+1 <= $ret->numPages){ ?>
			<a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $ret->page+1;?>&<?php echo $paramString;?>'><span>Next</span></a>		   
		   <?php } else { ?>
		   <a  ><span style="cursor:default; color:#999999;">Next</span></a>		   
		   <?php } ?>		   
		   
		<?php if($ret->page+1 <= $ret->numPages){ ?>
			<a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?page=<?php echo $ret->numPages;?>&<?php echo $paramString;?>'><span>Last</span></a>
		 <?php }?>
		   
		   </td>
		  </tr>
		  </table>
		<?php
		}
		?>		</td>
		
		
		<td align="right" nowrap="nowrap">
		<div align="right">
		<?php if ($total_records > 10) { ?>
		
		Entries Per Page<br>
		
		<a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?&record_perpage=10&<?php echo $paramString;?>'>10		</a>
		<a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF']?>?record_perpage=25&<?php echo $paramString;?>'>
		25		</a>
        <a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?record_perpage=50&<?php echo $paramString;?>'>
		50		</a>
		<a  class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?record_perpage=100&<?php echo $paramString;?>'>
		100		</a>
        <a  class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?record_perpage=250&<?php echo $paramString; ?>'>
		250		</a>
        <a  class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?record_perpage=500&<?php echo $paramString; ?>'>
		500		</a>
        <a  class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?record_perpage=1000&<?php echo $paramString; ?>'>
		1000		</a>
        <?php
		if(basename($_SERVER['PHP_SELF'])=='Leads.php' || basename($_SERVER['PHP_SELF'])=='LeadsLite.php')
			{ ?>
			<a  class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?record_perpage=2000&<?php echo $paramString;?>'>2000</a>	
            <a  class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?record_perpage=5000&<?php echo $paramString;?>'>5000</a>					
	<?php }
		?>
		<a class="fancybox" rel="gallery1" href='<?php echo $_SERVER['PHP_SELF'];?>?record_perpage=all&<?php echo $paramString;?>' onclick="return showall_confirm()" >
		All		</a>		
		<?php } ?>	</div>	</td>
		</tr>
</table>
<script type="text/javascript">
function showall_confirm(){
	if(!confirm('Warning: Clicking OK can take a long time to load and may lockup your computer.')){
	return false;
	}
}
</script>