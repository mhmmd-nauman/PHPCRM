<?php
require_once "../../dbcon.php";
require_once "../../lib/classess/config/variables.php";
require_once "../../lib/classess/util_objects/util.php";
$utilObj = new util();

$getpayment_response=$utilObj->getSingleRow('PaymentResponse', 'ID=1');
echo '<pre>';
print_r($getpayment_response);
echo "</pre>";
$query_string='';
foreach($getpayment_response as $key=>$value)
{
	if(is_int($key))
	{
	}
	else
	{
		$query_string.='x_'.$key.'='.$value.'&';
	}
}
print_r($query_string);