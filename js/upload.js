/***   Script from:   http://coursesweb.net/ajax/   ***/
  
// Function that adds a new box for upload in the form
function add_upload(form_id) {
  // Gets the element before which the new box will be added

  var element = document.getElementById('sub');

  // create the new "file" <input>, and its attributes
  var new_el = document.createElement("input");
  new_el.setAttribute("type", "file");
  new_el.setAttribute("name", "file_up[]");
  document.getElementById(form_id).insertBefore(new_el, element);
}

// Function that sends form data, by passing them to an iframe
function uploading(theform){
  // Adds the code for the iframe
  document.getElementById('ifrm').innerHTML = '<iframe id="uploadframe" name="uploadframe" src="upload-file.php" frameborder="0"></iframe>';

  document.theform.submit();		// Executa trimiterea datelor
  
  // Restore the form
  document.getElementById('uploadform').innerHTML = '<label><b>Select Call</b></label><select id="callSelect" name="callSelect"><option value="calls-fc">Founders Call</option><option value="calls-jj">Charge Call</option><option value="calls-lm">Leadership Mastermind</option></select><br /><br /><input type="file" id="test" class="file_up" name="file_up[]" /><input type="submit" value="Upload" id="sub" class="MOSGLsmButton" />';

  return false;
}
function call_me(){
	document.getElementById('load').innerHTML = '<img src="images/loader.gif" alt="Uploading...."/>';
	return true;
	}