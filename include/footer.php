<?php if($_SESSION['Member']['ID'] == '95'){
	echo "<pre>";
	//print_r($_SESSION);
	echo "</pre>";
?>
<?
?>
<!--
237659,228919
-->
<?PHP }?>
</div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){ 
        var tz = jstz.determine();
        var zone = tz.name();
        $("#zone").val(zone);
        $("#zone_out").val(zone);
        
    });
 setInterval(function(){KeepSessionAlive();},30000);   
 function KeepSessionAlive(){
     var tz = jstz.determine();
     var zone = tz.name();
     $("#zone").val(zone);
     //alert(zone);
     $.ajax({
                type:"post",
                url:"<?php echo SITE_ADDRESS;?>ajax/KeepAlive.php?zone="+zone,

                data:"",

                success:function(data){
                   // alert(data);
                    $("#current_time").text(data);
            }
		   
		   
       });
 }
 function SaveComments(Task){
        var ProjectID   = $('#ProjectID').val();
        var CommentsBox = $('#CommentsBox').val();
        $.ajax({
                   type:"post",
                   url:"<?php echo SITE_ADDRESS;?>ajax/KeepAlive.php",

                   data:"Task="+Task+"&ProjectID="+ProjectID+"&CommentsBox="+CommentsBox,

                   success:function(data){
                       alert("Save!");
                       $("#current_time").text(data);
               }


          });
 }
</script>