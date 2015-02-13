<?php 
require_once "../../dbcon.php";
require_once dirname(__FILE__)."/../../lib/classess/config/variables.php";
require_once dirname(__FILE__)."/../../lib/classess/util_objects/util.php";
$utilObj = new util();

/*$Sql="Select * From Product";
$ProductResult=mysql_query($Sql);
$Countryarray=array( '0'=>'Please select a country', 'Afghanistan'=>'Afghanistan', 'Albania'=>'Albania', 'Algeria'=>'Algeria', 'American Samoa'=>'American Samoa', 'Andorra'=>'Andorra', 'Angola'=>'Angola', 'Anguilla'=>'Anguilla', 'Antarctica'=>'Antarctica', 'Antigua and Barbuda'=>'Antigua and Barbuda', 'Argentina'=>'Argentina', 'Armenia'=>'Armenia', 'Aruba'=>'Aruba', 'Australia'=>'Australia', 'Austria'=>'Austria', 'Åland Islands'=>'Åland Islands', 'Azerbaijan'=>'Azerbaijan', 'Bahamas'=>'Bahamas', 'Bahrain'=>'Bahrain', 'Bangladesh'=>'Bangladesh', 'Barbados'=>'Barbados', 'Belarus'=>'Belarus', 'Belgium'=>'Belgium', 'Belize'=>'Belize', 'Benin'=>'Benin', 'Bermuda'=>'Bermuda', 'Bhutan'=>'Bhutan', 'Bolivia'=>'Bolivia', 'Bosnia and Herzegovina'=>'Bosnia and Herzegovina', 'Botswana'=>'Botswana', 'Bouvet Island'=>'Bouvet Island', 'Brazil'=>'Brazil', 'British Indian Ocean Territory'=>'British Indian Ocean Territory', 'Brunei Darussalam'=>'Brunei Darussalam', 'Bulgaria'=>'Bulgaria', 'Burkina Faso'=>'Burkina Faso', 'Burundi'=>'Burundi', 'Cambodia'=>'Cambodia', 'Cameroon'=>'Cameroon', 'Canada'=>'Canada', 'Cape Verde'=>'Cape Verde', 'Cayman Islands'=>'Cayman Islands', 'Central African Republic'=>'Central African Republic', 'Chad'=>'Chad', 'Chile'=>'Chile', 'China'=>'China', 'Christmas Island'=>'Christmas Island', 'Cocos (Keeling) Islands'=>'Cocos (Keeling) Islands', 'Colombia'=>'Colombia', 'Comoros'=>'Comoros', 'Congo'=>'Congo', 'Democratic Republic Of Congo'=>'Democratic Republic Of Congo', 'Cook Islands'=>'Cook Islands', 'Costa Rica'=>'Costa Rica', 'Croatia'=>'Croatia', 'Cuba'=>'Cuba', 'Cyprus'=>'Cyprus', 'Czech Republic'=>'Czech Republic', 'Côte DIvoire'=>'Côte DIvoire', 'Denmark'=>'Denmark', 'Djibouti'=>'Djibouti', 'Dominica'=>'Dominica', 'Dominican Republic'=>'Dominican Republic', 'Ecuador'=>'Ecuador', 'Egypt'=>'Egypt', 'El Salvador'=>'El Salvador', 'Equatorial Guinea'=>'Equatorial Guinea', 'Eritrea'=>'Eritrea', 'Estonia'=>'Estonia', 'Ethiopia'=>'Ethiopia', 'Falkland Islands'=>'Falkland Islands', 'Faroe Islands'=>'Faroe Islands', 'Fiji'=>'Fiji', 'Finland'=>'Finland', 'France'=>'France', 'French Guiana'=>'French Guiana', 'French Polynesia'=>'French Polynesia', 'French Southern Territories'=>'French Southern Territories', 'Gabon'=>'Gabon', 'Gambia'=>'Gambia', 'Georgia'=>'Georgia', 'Germany'=>'Germany', 'Ghana'=>'Ghana', 'Gibraltar'=>'Gibraltar', 'Greece'=>'Greece', 'Greenland'=>'Greenland', 'Grenada'=>'Grenada', 'Guadeloupe'=>'Guadeloupe', 'Guam'=>'Guam', 'Guatemala'=>'Guatemala', 'Guernsey'=>'Guernsey', 'Guinea'=>'Guinea', 'Guinea-Bissau'=>'Guinea-Bissau', 'Guyana'=>'Guyana', 'Haiti'=>'Haiti', 'Heard and McDonald Islands'=>'Heard and McDonald Islands', 'Holy See (Vatican City State)'=>'Holy See (Vatican City State)', 'Honduras'=>'Honduras', 'Hong Kong'=>'Hong Kong', 'Hungary'=>'Hungary', 'Iceland'=>'Iceland', 'India'=>'India', 'Indonesia'=>'Indonesia', 'Iran'=>'Iran', 'Iraq'=>'Iraq', 'Ireland'=>'Ireland', 'Isle of Man'=>'Isle of Man', 'Israel'=>'Israel', 'Italy'=>'Italy', 'Jamaica'=>'Jamaica', 'Japan'=>'Japan', 'Jersey'=>'Jersey', 'Jordan'=>'Jordan', 'Kazakhstan'=>'Kazakhstan', 'Kenya'=>'Kenya', 'Kiribati'=>'Kiribati', 'North Korea'=>'North Korea', 'South Korea'=>'South Korea', 'Kuwait'=>'Kuwait', 'Kyrgyzstan'=>'Kyrgyzstan', 'Laos'=>'Laos', 'Latvia'=>'Latvia', 'Lebanon'=>'Lebanon', 'Lesotho'=>'Lesotho', 'Liberia'=>'Liberia', 'Libya'=>'Libya', 'Liechtenstein'=>'Liechtenstein', 'Lithuania'=>'Lithuania', 'Luxembourg'=>'Luxembourg', 'Macao'=>'Macao', 'Republic of Macedonia'=>'Republic of Macedonia', 'Madagascar'=>'Madagascar', 'Malawi'=>'Malawi', 'Malaysia'=>'Malaysia', 'Maldives'=>'Maldives', 'Mali'=>'Mali', 'Malta'=>'Malta', 'Marshall Islands'=>'Marshall Islands', 'Martinique'=>'Martinique', 'Mauritania'=>'Mauritania', 'Mauritius'=>'Mauritius', 'Mayotte'=>'Mayotte', 'Mexico'=>'Mexico', 'Federated States of Micronesia'=>'Federated States of Micronesia', 'Moldova'=>'Moldova', 'Monaco'=>'Monaco', 'Mongolia'=>'Mongolia', 'Montenegro'=>'Montenegro', 'Montserrat'=>'Montserrat', 'Morocco'=>'Morocco', 'Mozambique'=>'Mozambique', 'Myanmar'=>'Myanmar', 'Namibia'=>'Namibia', 'Nauru'=>'Nauru', 'Nepal'=>'Nepal', 'Netherlands'=>'Netherlands', 'Netherlands Antilles'=>'Netherlands Antilles', 'New Caledonia'=>'New Caledonia', 'New Zealand'=>'New Zealand', 'Nicaragua'=>'Nicaragua', 'Niger'=>'Niger', 'Nigeria'=>'Nigeria', 'Niue'=>'Niue', 'Norfolk Island'=>'Norfolk Island', 'Northern Mariana Islands'=>'Northern Mariana Islands', 'Norway'=>'Norway', 'Oman'=>'Oman', 'Pakistan'=>'Pakistan', 'Palau'=>'Palau', 'Palestine'=>'Palestine', 'Panama'=>'Panama', 'Papua New Guinea'=>'Papua New Guinea', 'Paraguay'=>'Paraguay', 'Peru'=>'Peru', 'Philippines'=>'Philippines', 'Pitcairn'=>'Pitcairn', 'Poland'=>'Poland', 'Portugal'=>'Portugal', 'Puerto Rico'=>'Puerto Rico', 'Qatar'=>'Qatar', 'Romania'=>'Romania', 'Russian Federation'=>'Russian Federation', 'Rwanda'=>'Rwanda', 'Réunion'=>'Réunion', 'St, Barthélemy'=>'St, Barthélemy', 'St, Helena, Ascension and Tristan Da Cunha'=>'St, Helena, Ascension and Tristan Da Cunha', 'St, Kitts And Nevis'=>'St, Kitts And Nevis', 'St, Lucia'=>'St, Lucia', 'St, Martin'=>'St, Martin', 'St, Pierre And Miquelon'=>'St, Pierre And Miquelon', 'St, Vincent And The Grenedines'=>'St, Vincent And The Grenedines', 'Samoa'=>'Samoa', 'San Marino'=>'San Marino', 'Sao Tome and Principe'=>'Sao Tome and Principe', 'Saudi Arabia'=>'Saudi Arabia', 'Senegal'=>'Senegal', 'Serbia'=>'Serbia', 'Seychelles'=>'Seychelles', 'Sierra Leone'=>'Sierra Leone', 'Singapore'=>'Singapore', 'Slovakia'=>'Slovakia', 'Slovenia'=>'Slovenia', 'Solomon Islands'=>'Solomon Islands', 'Somalia'=>'Somalia', 'South Africa'=>'South Africa', 'South Georgia and the South Sandwich Islands'=>'South Georgia and the South Sandwich Islands', 'Spain'=>'Spain', 'Sri Lanka'=>'Sri Lanka', 'Sudan'=>'Sudan', 'Suriname'=>'Suriname', 'Svalbard And Jan Mayen'=>'Svalbard And Jan Mayen', 'Swaziland'=>'Swaziland', 'Sweden'=>'Sweden', 'Switzerland'=>'Switzerland', 'Syrian Arab Republic'=>'Syrian Arab Republic', 'Taiwan'=>'Taiwan', 'Tajikistan'=>'Tajikistan', 'Tanzania'=>'Tanzania', 'Thailand'=>'Thailand', 'Timor-Leste'=>'Timor-Leste', 'Togo'=>'Togo', 'Tokelau'=>'Tokelau', 'Tonga'=>'Tonga', 'Trinidad and Tobago'=>'Trinidad and Tobago', 'Tunisia'=>'Tunisia', 'Turkey'=>'Turkey', 'Turkmenistan'=>'Turkmenistan', 'Turks and Caicos Islands'=>'Turks and Caicos Islands', 'Tuvalu'=>'Tuvalu', 'Uganda'=>'Uganda', 'Ukraine'=>'Ukraine', 'United Arab Emirates'=>'United Arab Emirates', 'United Kingdom'=>'United Kingdom', 'United States'=>'United States', 'US Minor Outlying Islands'=>'US Minor Outlying Islands', 'Uruguay'=>'Uruguay', 'Uzbekistan'=>'Uzbekistan', 'Vanuatu'=>'Vanuatu', 'Venezuela'=>'Venezuela', 'Viet Nam'=>'Viet Nam', 'Virgin Islands, British'=>'Virgin Islands, British', 'Virgin Islands, U,S,'=>'Virgin Islands, U,S,', 'Wallis and Futuna'=>'Wallis and Futuna', 'Western Sahara'=>'Western Sahara', 'Yemen'=>'Yemen', 'Zambia'=>'Zambia', 'Zimbabwe'=>'Zimbabwe');*/


//print_r($_POST);
extract($_POST);
if(isset($_POST['Order'])){
	session_start();
	
  if(!empty($_POST['ProductId'])){
 $ProductId=$_POST['ProductId'];
  // $key='TotalAmt_'.$ProductId;
  // $totalAmtPaid=$_POST[$key];
    //  $ProudctAmtPaid=$_POST[$key];
   //$Pkey='ProductName_'.$ProductId;
   //$ProductName=$_POST[$Pkey];
   $strwhere='ProductID='.$ProductId.'';
    $GetSubscriptionRecords=$utilObj->getSingleRow('ProductSubscription',$strwhere);
	$strwhereProd='ID='.$ProductId.'';
    $GetMerchantIdfromProduct=$utilObj->getSingleRow('Product',$strwhereProd);
	
	if(!empty($_POST['promo_code_value']))
	{
		$strwherepromo=' `ProductID`='.$ProductId.' AND `PromoCodeName`="'.$_POST['promo_code_value'].'"';
		$getpromocode_discount=$utilObj->getSingleRow('ProductPromoCode',$strwherepromo);
	}
	if(isset($_POST['co_op_shares_ecommerce']))
	{
		if($_POST['co_op_shares_ecommerce']=='1')
		{
			$strwhere=' `CoOPID`=9 AND `ProductID`='.$ProductId.'';
    		$getBonus_from_share_record=$utilObj->getSingleRow('Share',$strwhere);
			$share_id=$getBonus_from_share_record['ID'];
			$bonus_calculated=0+($getBonus_from_share_record['ShareBonus']*($GetMerchantIdfromProduct["ProductPrice"]/100));
			if(isset($_POST['quantity']))
			{
				if($_POST['quantity']>0)
				{
					$totalAmtPaid=($GetMerchantIdfromProduct["ProductPrice"])*(int)$_POST['quantity'];
					
				}
			}
			else
			{
				$totalAmtPaid=$GetMerchantIdfromProduct["ProductPrice"];
			}
		}
	}else
	{
		if($getpromocode_discount)
		{
			$totalAmtPaid=$getpromocode_discount['ProductPrice'];
		}
		else
		{
			$totalAmtPaid=$GetMerchantIdfromProduct["ProductPrice"];
		}
	}
	
   //print_r($GetSubscriptionRecords);
    if(is_array($GetSubscriptionRecords)){
        $strwhere='MerchantId='.$GetMerchantIdfromProduct['ManageMerchantAccID'].'';
		$merchantRecords=$utilObj->getSingleRow('ManageMerchantAcc',$strwhere);
		$normalorder=0;
		require_once "ProductPaymentProcess.php";
	}else{
	 // $strwhere='MerchantId=1'; //Default payment gateway set authorize.net.
	  $strwhere='MerchantId='.$GetMerchantIdfromProduct['ManageMerchantAccID'].'';
	  $merchantRecords=$utilObj->getSingleRow('ManageMerchantAcc',$strwhere);
	  $normalorder=1;
	  require_once "ProductPaymentProcess.php";
	}//end of isarray
	
  }//end of product id condtioan

}// end of order condition

   

?>
<!--<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>themes/site/css/layout.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>themes/site/css/form.css" media="screen" />
<div style="padding-right:45px;" class="loginBox">
<div class="topLogin"></div>
<form method="Post" action="" id="orderForm">
<div class="midLogin">
<div class="loginForm">
<div class="loginRow"><label>First Name <span class="star">*</span> :</label>
<div class="inputBpx"><input type="text" class="in_put" id="Contact0FirstName" name="Contact0FirstName">
<div class="err_class" id="error_Contact0FirstName"></div>
</div>
</div>
<div class="loginRow"><label>Last Name <span class="star">*</span> :</label>
<div class="inputBpx"><input type="text" class="in_put" id="Contact0LastName" name="Contact0LastName">
<div class="err_class" id="error_Contact0LastName"></div>
</div>
</div>
<div class="loginRow"><label>Email <span class="star">*</span> :</label>
<div class="inputBpx"><input type="text" class="in_put" id="Contact0Email" name="Contact0Email">
<div class="err_class" id="error_Contact0Email"></div>
</div>
</div>
<div class="loginRow"><label>Cell Phone <span class="star"></span> :</label>
<div class="inputBpx"><input type="text" class="in_put" name="Contact0Phone2">
<div class="err_class" id="error_Contact0Phone1"></div>
</div>
</div>
<div class="loginRow"><label>Street Address 1 <span class="star">*</span> :</label>
<div class="inputBpx"><input type="text" class="in_put" id="Contact0StreetAddress1" name="Contact0StreetAddress1">
<div class="err_class" id="error_Contact0StreetAddress1"></div>
</div>
</div>
<div class="loginRow"><label>Street Address 2   :</label>
<div class="inputBpx"><input type="text" class="in_put" id="Contact0StreetAddress2" name="Contact0StreetAddress2">
<div class="err_class" id="error_Contact0StreetAddress2"></div>
</div>
</div>
<div class="loginRow"><label>City <span class="star">*</span> :</label>
<div class="inputBpx"><input type="text" class="in_put" id="Contact0City" name="Contact0City">
<div class="err_class" id="error_Contact0City"></div>
</div>
</div>
<div class="loginRow"><label>State <span class="star">*</span> :</label>
<div class="inputBpx"><input type="text" class="in_put" id="Contact0State" name="Contact0State">
<div class="err_class" id="error_Contact0State"></div>
</div>
</div>
<div class="loginRow"><label>Postal Code <span class="star">*</span> :</label>
<div class="inputBpx"><input type="text" class="in_put" id="Contact0PostalCode" name="Contact0PostalCode">
<div class="err_class" id="error_Contact0PostalCode"></div>
</div>
</div>
<div class="loginRow"><label>Country <span class="star">*</span> :</label>
<div class="inputBpx">
<select class="in_putselc" id="Contact0Country" name="Contact0Country">  
<?php 
foreach($Countryarray as $key=>$values):
$selected='';
if($key=='United States')
$selected='selected';

echo "<option value='".$key."'  ".$selected.">".$values."</option>";
endforeach;
?>
</select>
<div class="err_class" id="error_Contact0Country"></div>
</div>
</div>
<div class="inffieldseperator"></div>
<input type="hidden" id="CopyAddresses" value="billing@themillionaireos.com" name="CopyAddresses">
<div class="loginRow"><label>Card Type <span class="star">*</span> :</label>
<div class="inputBpx"><select class="in_putselc" id="CreditCard0CardType" name="CreditCard0CardType"> <option value="0">Please select a card type </option> <option value="American Express">American Express</option> <option value="Discover">Discover</option> <option value="MasterCard">MasterCard</option> <option value="Visa">Visa</option> </select>
<div class="err_class" id="error_CreditCard0CardType"></div>
<img src="assets/Themes/TTIInfuserOrder2/images/cc-trans.png" class="text"></div>
</div>
<div class="loginRow"><label>Card Number <span class="star">*</span> :</label>
<div class="inputBpx"><input type="text" class="in_put" id="CreditCard0CardNumber" name="CreditCard0CardNumber" maxlength="16">
<div class="err_class" id="error_CreditCard0CardNumber"></div>
</div>
</div>
<div class="loginRow"><label>Expiration Month <span class="star">*</span> :</label>
<div class="inputBpx"><select class="in_putselc2" id="CreditCard0ExpirationMonth" name="CreditCard0ExpirationMonth"> <option value="01">01</option> <option value="02">02</option> <option value="03">03</option> <option value="04">04</option> <option value="05">05</option> <option value="06">06</option> <option value="07">07</option> <option value="08">08</option> <option value="09">09</option> <option value="10">10</option> <option value="11">11</option> <option value="12">12</option> </select>
<div class="err_class" id="error_CreditCard0ExpirationMonth"></div>
</div>
</div>
<div class="loginRow"><label>Expiration Year <span class="star">*</span> :</label>
<div class="inputBpx"><select class="in_putselc2" id="CreditCard0ExpirationYear" name="CreditCard0ExpirationYear"> <option value="2011">2011</option> <option value="2012">2012</option> <option value="2013">2013</option> <option value="2014">2014</option> <option value="2015">2015</option> <option value="2016">2016</option> <option value="2017">2017</option> <option value="2018">2018</option> <option value="2019">2019</option> <option value="2020">2020</option> <option value="2021">2021</option> <option value="2022">2022</option> <option value="2023">2023</option> <option value="2024">2024</option> <option value="2025">2025</option> </select>
<div class="err_class" id="error_CreditCard0ExpirationYear"></div>
</div>
</div>
<div class="loginRow"><label>CVC <span class="star">*</span> :</label>
<div class="inputBpx"><input type="text" class="default-input sale-select-req" style="float:left;" size="5" id="CreditCard0VerificationCode" name="CreditCard0VerificationCode"> <span title="Visa/MC/Discover - 3 digit code on back of card&lt;br /&gt;AmEx - 4 digit code on front of card" style="background-image:url(../images/icon_question.png); background-size:14px; margin-left:-4px;" class="toolTip"></span>
<div class="err_class" id="error_CreditCard0ExpirationYear"></div>
</div>
</div>
<div class="inffieldseperator"></div>
<div class="formTitle">The Millionaire OS Product Orders And Subscription</div>
<?php  
while($ProductRec=mysql_fetch_assoc($ProductResult)){?>
<div class="planWrp">
<div class="plan" style="width:550px;">
<div style="float:left; width:480px;">
<span style="font-weight:700; color:#AE0406;margin:5px 0px;">
<input type="radio" id="ProductId" value="<?php echo $ProductRec['ID'];?>" name="ProductId" 
<?php if($ProductId==$ProductRec['ID']) echo "checked";?>>
<input type="hidden" id="ProductName" value="<?php echo $ProductRec['ProductName'] ;?>" name="ProductName_<?php echo $ProductRec['ID'];?>">

<?php echo $ProductRec['ProductName'];?></span>
</div>

<div style="float:left;">
<span style="font-weight:700;margin:5px 0px;">Amount<br> $<?php echo $ProductRec['ProductPrice'];?></span>
</div>
</div>
</div>
<?php 
 $TotalOrderPrice=$ProductRec['ProductPrice'];
 $charges="(One Time Order)";
$Sql="Select * From  ProductSubscription where ProductID=".$ProductRec['ID']."";
$SubscriptionResult=mysql_query($Sql);
if(mysql_num_rows($SubscriptionResult)>0){
$subscriptionRec=mysql_fetch_assoc($SubscriptionResult);
?>


<div class="planWrp">
<div class="plan" style="width:550px;">
<div style="float:left; width:480px;">
<span style="font-weight:700; color:#AE0406;margin:5px 0px;">

<?php echo $ProductRec['ProductName']; ?>&nbsp;Subscription
<?php if($subscriptionRec['Duration']!=0){
?>
(
<?php 
if($subscriptionRec['Duration']==30)
$prefix='Days';
else
$prefix='Months';
echo $subscriptionRec['Duration'].'&nbsp;'.$prefix;?>) 
<?php } ?>
</span>
</div>
<div style="float:left;">
<span style="font-weight:700;margin:5px 0px;">Amount<br> $<?php echo $subscriptionRec['SubscriptionPrice'];?></span>
</div>
</div>
</div>
<?php
if($subscriptionRec['DaysBeforeStart']==0){
 $TotalOrderPrice=(float)$subscriptionRec['SubscriptionPrice']+(float)$ProductRec['ProductPrice'];
 $charges="(Subscription Charges Apply Now)";
 }else
  $charges="(Subscription Charges Apply After 30 Days)";
  }
 ?>
<div style="width: 580px; height: 1px; float: left; border-bottom: 1px dotted #7a7a7a; margin: 5px 0px 5px 20px;"></div>
<div class="planWrp">
<div class="plan"><span style="font-weight:700; margin:5px 0px;">Amount You Pay Now <span style="font-size:10px;"><?php echo $charges; ?></span><br> </span></div>
<div class="plan" style="width:auto"><span style="font-weight:700;  margin:5px 0px;">
<input type="hidden" id="TotalAmt" value="<?php echo $TotalOrderPrice ;?>" name="TotalAmt_<?php echo $ProductRec['ID'];?>">
$<?php echo $TotalOrderPrice;?></span></div>
</div>

<div style="width: 580px; height: 1px; float: left; border-bottom: 1px dotted #7a7a7a; margin: 5px 0px 5px 20px;"></div>
<?php } ?>
<div class="inputBpx" style="padding-left:250px;">
<input type="submit" class="orderBtn Grn_order_btn_disable" id="Order" value="Order " name="Order" ></div>
</form>
</div>

</div>

<div class="botLogin"></div>
<div style="clear:both"></div>
<div style="text-align: center;"></div>-->







