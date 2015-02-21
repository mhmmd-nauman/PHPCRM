<?php 
	include dirname(__FILE__)."/../lib/include.php";
	$UserGroup = array();
	foreach((array)$_SESSION['user_in_groups'] as $Group){
		$UserGroup[] = $Group;
	}
?>
<script type="text/javascript">
$(function() {
	$("#crmsetting").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,550,800);
	});
});
</script>

<div id="nav">
   
  <ul class="sf-menu">
   <?php if($_SESSION['isSaleGroup'] == 1 && $_SESSION['isAdmin'] != 1){?>
  <li> <a href="<?php echo SITE_ADDRESS;?>Home.php">Home</a> </li>
  <li> <a href="<?php echo SITE_ADDRESS."clients/Clients.php"?>">Contacts</a> </li>
  <li> <a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrdersAndSubscriptionList.php"?>">Orders</a></li> 
  <li> <a href="<?php echo SITE_ADDRESS;?>Logout.php">Logout</a> </li>
  <?php } elseif($_SESSION['isGYBFulfillment'] == 1 && $_SESSION['isAdmin'] != 1) {?>
  <li> <a href="<?php echo SITE_ADDRESS;?>Home.php">Home</a> </li>
  <li> <a href="<?php echo SITE_ADDRESS."clients/Clients.php"?>">Contacts</a> </li>   
  <li> <a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrdersAndSubscriptionList.php"?>">Orders</a></li> 
   <li><a href="<?php echo SITE_ADDRESS."products/websites/WebSites.php"?>">Websites</a></li>
  <li> <a href="<?php echo SITE_ADDRESS;?>Logout.php">Logout</a> </li>
   <?php }else{?>
  <li> <a href="<?php echo SITE_ADDRESS;?>Home.php">Home</a> </li>
  <li ><a href="<?php echo SITE_ADDRESS."clients/Clients.php"?>">Contacts</a>
      
  </li>
  <?php
  # Not In Array 17 -> Means, if the user belongs to GYB Fulfillment then the user must not see Websites Link
  # In Array 2 -> Super Admin, In Array 3 -> Admin
  # In array 18 -> Website Fulfillment user must see the websites link in the Navigation
  
  if(in_array(2, (array)$_SESSION['user_in_groups']) || in_array(18, (array)$_SESSION['user_in_groups'])){
  ?>
    <li><a href="<?php echo SITE_ADDRESS."products/websites/WebSites.php"?>">Websites</a>
        <ul>
            <li><a href="<?php echo SITE_ADDRESS."products/websites/WebSites.php"?>">Websites</a>
            <li><a href="<?php echo SITE_ADDRESS."products/websites/SubDomains.php"?>">Sub Domains</a></li>
        </ul>
    </li>
    
    <?php if(!in_array(2, (array)$_SESSION['user_in_groups'])){?>
    <li><a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrdersAndSubscriptionList.php"?>">Orders</a></li>
    
  <?php
	}
  }
  # Only the Super Admin or the Admin has the access to the Reports link in the Navigation
  if(in_array(2,(array)$UserGroup) or in_array(3,(array)$UserGroup)){
  ?>
    <li><a href="#">Reports</a>
        <ul>
        	<li><a href="#">Orders</a>
                <ul>
                    <!-- <li><a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrdersList.php"?>">Orders (Old)</a> </li>-->
                    <li><a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrdersAndSubscriptionList.php"?>">Sales Orders</a></li>
                    <li><a href="<?php echo SITE_ADDRESS."ecommerce/orders/SendGridEmailStatus.php"?>">Sendgrid Emails</a></li>
                    <li><a href="<?php echo SITE_ADDRESS."ecommerce/orders/chargebacks.php"?>">Chargebacks</a></li>
                    <!-- <li><a href="<?php echo SITE_ADDRESS."ecommerce/orders/Reports/monthlySalesReport.php"?>">Monthly Sales Report</a> </li>-->
                </ul>
        	</li>
            <li><a href="#">Employees</a>
                <ul>
                	<li><a href="<?php echo SITE_ADDRESS."users/TimeCardUsers.php"?>">Time Card</a> </li>
                </ul>
            </li>
        </ul>
    </li>
        
  <?php
  }
  # Only Super Admin i.e., In Array 2 has the right to see all the links in the admin link in the navigation
  # The Admin does not have the access to see all the links in the navigation.
  if(in_array(2,(array)$UserGroup)){
  ?>
    <li><a href="#">Admin</a>
      <ul>
        <li><a href="#">System</a>
          <ul>
            <li><a href="#">Groups</a>
              <ul>
                <li><a href="<?php echo SITE_ADDRESS."groups/Groups.php"?>">User Groups</a></li>
                <li><a href="<?php echo SITE_ADDRESS."groups/Tags.php"?>">Tags</a> </li>
                <li><a href="<?php echo SITE_ADDRESS."groups/Company.php"?>">Companies</a> </li>
                </ul>
                
            </li>
            <li><a href="<?php echo SITE_ADDRESS."users/Users.php"?>">Users</a> </li>
			
			<li><a href="<?php echo SITE_ADDRESS."crm-setting/SystemSetting.php"?>" id="crmsetting" title="CRM Settings">CRM Settings</a></li>
			<li><a href="<?php echo SITE_ADDRESS."users/Zones.php"?>"  title="Zones">Time Zones</a></li>   
          <li><a href="<?php echo SITE_ADDRESS."products/websites/WebSiteServer.php"?>">Server Accounts</a></li> 
          </ul>
        </li>
        <li><a href="#">Ecommerce</a>
          <ul>
            <li><a href="#">Products</a>
              <ul>
                <li><a href="<?php echo SITE_ADDRESS."ecommerce/products/ProductAndSubscriptionList.php"?>">Products / Subscriptions</a> </li>
                <li><a href="<?php echo SITE_ADDRESS."packages/Packages.php"?>">Packages</a> </li>
                <li><a href="<?php echo SITE_ADDRESS."product-tasks/Tasks.php"?>">Product Tasks</a> </li>
               <!-- <li><a href="<?php echo SITE_ADDRESS."checklist/Checklist.php"?>">CheckList</a> </li>-->
                <li><a href="<?php echo SITE_ADDRESS."ecommerce/products/ProductCategoryList.php"?>">Categories</a> </li>
                <li><a href="<?php echo SITE_ADDRESS."ecommerce/products/PromotionalCodeList.php"?>">Promotional Codes</a> </li>
              </ul>
            </li>
            <li><a href="#">Commissions</a>
              <ul>
                <li><a href="<?php echo SITE_ADDRESS."ecommerce/commissions/SalePersonsComission.php"?>">Agent Commissions</a></li>
              </ul>
            </li>
            <li><a href="#">Gateways</a>
              <ul>
                <li><a href="<?php echo SITE_ADDRESS."ecommerce/settings/ManageMerchantAcc.php"?>">Merchant Accounts</a> </li>
                  </ul>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    <?php
	# If the the User belongs to Admin group then only show him the Users link in the navigation.
	# The Users page will only show the user which belongs to the same comapny of the logged in admin.
    }elseif(in_array(3,(array)$UserGroup)){
	?>
    <li><a href="#">Admin</a>
      <ul>
            <li><a href="<?php echo SITE_ADDRESS."users/Users.php"?>">Users</a> </li>
      </ul>
        
    </li>
	
	<?php
	}
	?>
    <li> <a href="<?php echo SITE_ADDRESS;?>Logout.php">Logout</a> </li>
	  <?php }?>
  </ul>
</div>