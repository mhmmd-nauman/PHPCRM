<?php
if(isset($_REQUEST['task'])){
	if($_REQUEST['task']=="upload"){
		
	
	}
}

?>
<body style="margin: 0;padding: 0;">
<form action="upload_file.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" />
<input type="hidden" name="order" value="<?php echo $_REQUEST['order'];?>" />
<input type="hidden" name="agent" value="<?php echo $_REQUEST['agent'];?>" />
<input type="submit" name="submit" value="Upload PDF">
</form>
</body>