<?php 
require_once "../../lib/include.php";
$utilObj = new util();
$accessflag!='no';
if(!empty($_REQUEST['catid'])){
 $strWhere='ID='.$_REQUEST['catid'].'';
 $categoryRec=$utilObj->getSingleRow('ProductCategory', $strWhere);
}

?>
<script type="text/javascript" src="../../../javascript/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../../co_op/tab-view/js/tab-view.js"></script>
<link rel="stylesheet" href="../../../co_op/tab-view/css/tab-view.css" type="text/css" media="screen">
<div id="dhtmlgoodies_tabView1"  style="width:99%;">
<form name="form1" method="post" action="ProductCategoryList.php">
<input name="Task" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
<input name="id" type="hidden" value="<?php echo $_REQUEST['catid'];?>" size="40">
<div class="dhtmlgoodies_aTab">
<table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
    <tr>
      <td colspan="2" id="name"><b><?php if($_REQUEST['Task']=='add') echo 'Add Category';else echo $categoryRec['CategoryName'];?></b></td>
    </tr>
     <tr>
      <td colspan="2" height="10" id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    
    <tr>
      <td align="right">Product Name:</td>
      <td><input name="Category_Name" type="text" value="<?php echo $categoryRec['CategoryName']; ?>" size="40" style="height:20px;"></td>
    </tr>
      <tr>
      <td colspan="2" height="5" id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    <tr>
      <td align="right" valign="top">Description:</td>
      <td><textarea name="Cat_Description"   style="width: 263px; height: 103px;"><?php echo $categoryRec['Description']; ?></textarea></td>
    </tr>
    
     <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
       <tr>
      <td align="right" valign="top">Product Price:</td>
      <td><textarea name="Cat_Description"   style="width: 263px; height: 103px;"><?php echo $categoryRec['Description']; ?></textarea></td>
    </tr>
    
     <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    
      <tr>
      <td align="right" valign="top">Category:</td>
      <td><div style="height:130px;width:250px;overflow:auto;border:1px solid #373737;" id="ScrollCB">
      <input type="checkbox" name="MemberGroup[]" value="1" id="MemberGroup1">
      <label for="MemberGroup1">Super Administrators</label>
       <br>
       <input type="checkbox" name="MemberGroup[]" value="5" id="MemberGroup5">
       <label for="MemberGroup5">Administrators</label>
        <br>
       <input type="checkbox" name="MemberGroup[]" value="19" id="MemberGroup19">
         <label for="MemberGroup19">Billing Financial</label>
         <br>
         </div></td>
         </tr>
    
     <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    
    <tr>
      <td colspan="2" align="center">
        <input type="submit" name="Submit" class="MOSGLsmButton" style="margin-top:6px;" value="Save">
     </td>
    </tr>
    </table>
   </div>
   
   <div class="dhtmlgoodies_aTab">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
    <tr>
      <td colspan="2" id="name"><b><?php if($_REQUEST['Task']=='add') echo 'Add Category';else echo $categoryRec['CategoryName'];?></b></td>
    </tr>
     <tr>
      <td colspan="2" height="10" id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    
    <tr>
      <td align="right">Product Name:</td>
      <td><input name="Category_Name" type="text" value="<?php echo $categoryRec['CategoryName']; ?>" size="40" style="height:20px;"></td>
    </tr>
      <tr>
      <td colspan="2" height="5" id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    <tr>
      <td align="right" valign="top">Description:</td>
      <td><textarea name="Cat_Description"   style="width: 263px; height: 103px;"><?php echo $categoryRec['Description']; ?></textarea></td>
    </tr>
    
     <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <input type="submit" name="Submit" class="MOSGLsmButton" style="margin-top:6px;" value="Save">
     </td>
    </tr>
    </table>
   </div>
   
</form>
</div>
  <script type="text/javascript">
   initTabs('dhtmlgoodies_tabView1',Array('Product Information','Subscription'),0,"","");
</script>

