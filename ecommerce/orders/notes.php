<body style="margin: 0;padding: 0;">
<?php
// set "Verbal Authorization here
include "../../lib/outer_include.php";
$OrderID = $_REQUEST['order'];
$status = mysqli_query($link,"SELECT * from `OrderDetail` WHERE OrderID = '" . $OrderID . "'");

?>
<form name="extra" action="saveNote.php">
<textarea name="extraNotes"><?php  while($row = mysqli_fetch_array($status)){
echo $row['Description'] . " ";
}?></textarea>
<br />
<input type="hidden" name="order" value="<?php echo $_REQUEST['order'];?>" />
<input type="submit" value="Save Note" />
</form>
</body>