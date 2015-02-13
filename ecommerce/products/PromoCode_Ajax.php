<?php  include "../../lib/include.php";
$utilObj = new util();
 $strWhere='ProductID='.$_REQUEST['Prodid'].'';
 $ProductPromoRec=$utilObj->getMultipleRow('ProductPromoCode', $strWhere);
 ?>
 <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<link type="text/css" href="<?php echo SITE_ADDRESS;?>/themes/site/css/jquery-ui/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<!--<link rel="stylesheet" type="text/css" href="../../css/styles.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../../co_op/css/styles.css">-->

<script type="text/javascript" src="../../../co_op/tab-view/js/tab-view.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/styles.css" media="screen" />
<link rel="stylesheet" href="../../../co_op/tab-view/css/tab-view.css" type="text/css" media="screen">

<script type="text/javascript" src="../../../javascript/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="../../../co_op/css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
 <script type="text/javascript">


$(document).ready(function() {


<?  foreach($ProductPromoRec as $PromoVal)
	 { ?>
//$('#PromoCodeEdit_<?= $PromoVal['ID']?>').fancybox({
//			'width'                       : 400,
//
//			'height'                      : 300,
//
//			'autoScale'                   : false,
//
//			'transitionIn'                : 'none',
//
//			'transitionOut'                : 'none',
//
//			'href'                        : this.href,
//
//			'type'                        : 'iframe',
//			'hideOnOverlayClick'          :false,
//			//onClosed: function() {  window.location.reload();  },
//		    'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); 
//              $.fancybox.resize();
//		    }
//			
//			});
			
			$('#PromoCodeEdit_<?= $PromoVal['ID']?>').fancybox({
				'href':$('#PromoCodeEdit_<?= $PromoVal['ID']?>').attr('href'),
				onClosed: function() {  showresult($('#Pid').val());  },
				'hideOnOverlayClick':false
				});
<? } ?>
			
			
			$('#PromoCodeADD').fancybox({
				'href':$('#PromoCodeADD').attr('href'),
				onClosed: function() {  showresult($('#Pid').val());  },
				'hideOnOverlayClick':false
				});
});
</script>
 <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"  >
    <tr><td colspan="4" align="right"><a  href='ProductAndSubscriptionPromoCodePopup.php?Task=add&Productid=<?=$_REQUEST['Prodid']?>' id='PromoCodeADD'>Add Promotional Code</a></td></tr>
     <tr id="headerbar">
     <td>Promotional Code</td>
     <td>Product Price</td>
     <td>Subscription Price</td>
     <td>Action</td>
     <tr>
     <? 
	 $color=1;
if(count($ProductPromoRec)>0){
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';

	 
	 foreach($ProductPromoRec as $PromoVal)
	 {
	 ?>
     <tr id="<?php echo $colors; ?>">
     <td> <?= $PromoVal['PromoCodeName']?></td>
     <td> <?= $PromoVal['ProductPrice']?></td>
     <td> <?= $PromoVal['SubscriptionPrice']?></td>
     <td><a href='ProductAndSubscriptionPromoCodePopup.php?Task=update&promoid=<?= $PromoVal['ID']?>' id='PromoCodeEdit_<?=$PromoVal['ID']?>' /> <img border="0" title="Edit Category" src="../../images/icon_page_edit.png"></td>
     </tr>  
     <? }?>
     
      <?php $color++; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="5">No Product Promo Code Found</td>
    </tr>
    <?php }  ?>  
    </table>