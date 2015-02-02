<?php 
include "../../lib/include.php";
require(dirname(__FILE__)."/../../lib/classes/business_objects/xmlapi.php");
$objWebSiteServer = new WebSiteServer();
$WebServerData = $objWebSiteServer->GetAllWebSiteServer( "id = 2 and isreseller = 0 AND HasDeleted = 0" ,array("*"));
//echo "<pre>";
//print_r($WebServerData);
//echo "</pre>";
$WebResellerServerData = $objWebSiteServer->GetAllWebSiteServer( "isreseller = 1 AND HasDeleted = 0" ,array("*"));
//echo "<pre>";
//print_r($WebResellerServerData);
//echo "</pre>";

$ip             = $WebServerData[0]['internal_ip']; 
    
$reseller 	= $WebResellerServerData[0]['username'];
$reseller_pass 	= $WebResellerServerData[0]['password'];
$root       = $WebServerData[0]['username'];
$root_pass  = $WebServerData[0]['password'];
$xmlapi = new xmlapi($ip);
$objWebsites = new WebSites();
$xmlapi->password_auth($root,$root_pass);
//$xmlapi->password_auth($reseller,$reseller_pass);
$xml = $xmlapi->listaccts(".info");
//echo "<pre>";
//print_r($xml);
//echo "</pre>";
//$founded_domains = array("ezb2589");

foreach($xml->acct as $acct){
    $domain = substr($acct->domain, -5);
    if($domain != ".info"){
        $user = substr($acct->user,0, 7);
        //echo "UserName LIKE '$acct->user%' - $acct->domain <br>";
        $WebsiteExist = $objWebsites->GetAllWebsites(" UserName = '$user' ",array(WEBSITES.".ID"));
        $objWebsites->UpdateWebsite("UserName = '$acct->user' AND HostedOn = 'ezbhost3'", 
                array("DomainName"=>$acct->domain,
                      "Status"=>5
                    )
                );
    }
}

// we need to scan a list of domains from db that can be used
$CandidateDomains = $objWebsites->GetAllWebsites(" DomainName NOT LIKE '%.info%' AND HostedOn = 'ezbhost3' AND ".WEBSITES.".Status = 5 AND ".WEBSITES.".HasDeleted = 0  AND Clients.HasDeleted = 0 AND isRecycleAble = 1 ORDER BY UserName ASC;",array(WEBSITES.".UserName"));
//echo "<pre>";
//print_r($CandidateDomains);
//echo "</pre>";
/*
$founded_domains = array("ezb2589"=>"ezb2589",
                         "ezb2588"=>"ezb2588",
                         "ezb2588a"=>"ezb2588a",
                         "ezb2588b"=>"ezb2588b",
                         "ezb2590"=>"ezb2590",
                         "ezb2591"=>"ezb2591");
*/
 foreach($CandidateDomains as $domain){
    $founded_domains[]=$domain["UserName"];
}
//echo "<pre>";
//print_r($founded_domains);
//echo "</pre>";
$c = 'a';
$chars = array($c);
while ($c < 'z') $chars[] = ++$c;


foreach($founded_domains as $recycledomains){
    foreach($chars as $letter){
       
        if(!in_array($recycledomains.$letter,(array)$founded_domains)){
            // needs to remove the item
            // echo strlen($recycledomains)." $recycledomains else  <br>"; 
            if(strlen($recycledomains) == 7){
                
                //echo " if UserName='$recycledomains$letter' AND ".WEBSITES.".Status in( 1,3) AND ".WEBSITES.".HasDeleted = 0   <br>";
                $NextCandidateDomains = $objWebsites->GetAllWebsites(" UserName='$recycledomains$letter'",array(WEBSITES.".*"));
               // print_r($NextCandidateDomains); 
                if($NextCandidateDomains[0]['ID'] > 0){
                   // echo " $recycledomains else  <br>"; 
                 }else{
                    $founded_domains_available[] =  $recycledomains.$letter;
                 }
                
             }else{
                 $recycledomains_lastchar = substr($recycledomains, -1);
                 ++$recycledomains_lastchar;
                 //echo " $recycledomains_lastchar <br>";
                  $recycledomains_firstchar = substr($recycledomains,0, 7);
                 //echo "<br>";
                 $recycledomains = $recycledomains_firstchar.$recycledomains_lastchar;
                 $NextCandidateDomains = $objWebsites->GetAllWebsites(" UserName='$recycledomains'",array(WEBSITES.".UserName",WEBSITES.".ID"));
                 if($NextCandidateDomains[0]['ID'] > 0){
                    //echo " $recycledomains else  <br>"; 
                 }else{
                    $founded_domains_available[] =  $recycledomains;
                 }
                
             }
             break;
        }
    }
}
//echo "<pre>";
//print_r($founded_domains_available);
//echo "</pre>";
$WebsiteServer = $objWebSiteServer->GetAllWebSiteServer(" HasDeleted = 0 ",array("*"));
//exit;
?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.9.1.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>  
<form action="WebSites.php?Task=CreateTempDomains" method="post" enctype="multipart/form-data" name="form1" target="_top">
<div style="padding-top:10px;"></div>
 <div id="tabs">
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
    
      <?php if( count($founded_domains_available) == 0 ){?>
      <tr>
       <td colspan="3" id="tabsubheading">Domain Information</td>
     </tr>
     <tr>
       <td colspan="3" >&nbsp;</td>
     </tr>
      <tr>
       <td colspan="3" >No domain available!</td>
     </tr>
    <?php } else {?>
    <tr>
       <td colspan="3" id="tabsubheading">Domain Information</td>
     </tr>
     <tr>
       <td colspan="3" >&nbsp;</td>
     </tr>
     
     <tr>
      <td   valign="top">Available Domains:</td>
      <td>
      <div style="height:300px;width:600px;overflow:auto; border:1px solid #DBDBDB; border-radius:5px; padding-left:10px; padding-top:5px;" id="ScrollCB">
      <?php 
	  /*-----------array recursive function---------*/
	  $displayed_domains="";
	 
	  $i=0; 
	
	   foreach($founded_domains_available as $domain):
	   if($_REQUEST['Task']=='update'){
		   if(in_array_r($categoryval['ID'],$productassociatCat)==true) 
			$chacked='checked';
			else
		   $chacked='';
	   }
          $domain_key=substr($domain,0, 7).".info";
          //echo "<br>";
	  if(!in_array($domain_key, (array)$displayed_domains)){
	  ?>
            <div style=" float:left; width:100%;">
                <input type="checkbox" name="SelectedDomains[]" value="<?php echo $domain."_".substr($domain,0, 7);?>.info" id="MemberGroup1"  <?php echo $chacked;?> >
           &nbsp;<?php echo substr($domain,0, 7).".info / ".$domain;?>

            </div>
           <?php 
            
            $displayed_domains["$domain_key"] = $domain_key;
         }
         $i++;
      endforeach;
      //print_r($displayed_domains);
      ?>      
      </td>
	<td>&nbsp;</td>
    </tr>
  
    <?php }?> 
	 
     <tr>
       <td >&nbsp;</td>
       <td ><label></label></td>
     </tr>
     
     <tr>
      <td colspan="2"><input type="hidden" name="postback" value="1" />        <div align="center"></div></td>
      </tr>
   </table>
  </div>
  <div style="height:25px;">&nbsp;</div>
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
       <td colspan="2">
	   <div align="center">
	   <input type="submit" name="Submit" value="Create Domains"  class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()"  />
	   
        </div>
																																		<input type="hidden" name="oldimage"  value=""/>
           <input type="hidden" name="postback" value="1" />
		
	
  <input type="hidden" name="checkupdate" value="1">
  <input type="hidden" name="image" value="" />
  <input type="hidden" name="siteasigndate" value="<?php echo $HubopususerRows[0]['SiteAsignDate'];?>" />
 <?php //echo $HubopususerRows[0]['SiteAsignDate'];?>
 </td>
 </tr>
 </table>
	  
    
</form>
