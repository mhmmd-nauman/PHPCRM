
<div id="nav">
   
  <ul class="sf-menu">
      
  <li> <a href="<?php echo SITE_ADDRESS;?>Home.php">Home</a> </li>
  <?php if($_SESSION['member_group']!='6'){?>
   <li> <a href="<?php echo SITE_ADDRESS;?>Members.php">Clients</a> </li>
    <li class="current"> <a href="<?php echo SITE_ADDRESS;?>HubFlxMembers.php">Websites</a> </li>
   

    
   
    <li> <a href="#">Admin</a>
      <ul>
        <li><a href="#">System</a>
        
          <ul>
            <li><a href="#">Groups</a>
              <ul>
                <li><a href="<?php echo SITE_ADDRESS;?>Groups.php">Member Groups</a> </li>
                
                </ul>
                
            </li>
            
			  <li><a href="SystemSetting.php" class="hubopus_popup" id="addhubopususer">CRM Setting</a>
         
            </li>
			
          </ul>
		  
        </li>
        
		
        <li><a href="#">Reports</a>
		
          <ul>
            <li><a href="Reports.php">Sale Report</a>
              
            </li>
            
          </ul>
        </li>
        
		
            
          </ul>
        </li>

    
      
    <?php } ?>
   
    <li> <a href="<?php echo SITE_ADDRESS;?>Logout.php">Logout</a> </li>
  </ul>
</div>

