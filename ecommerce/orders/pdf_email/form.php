<?php
if (!empty($_POST)) {

	// Used for later to determine result
	$success = $error = false;

	// Object syntax looks better and is easier to use than arrays to me
	$post = new stdClass;
	
	// Usually there would be much more validation and filtering, but this
	// will work for now.
	foreach ($_POST as $key => $val)
		$post->$key = trim(strip_tags($_POST[$key]));
		
	// Check for blank fields
	if (empty($post->name) OR empty($post->email) OR empty($post->about))
		$error = true;
		
	else {
		
		// Get this directory, to include other files from
		$dir = dirname(__FILE__);
		
		// Get the contents of the pdf into a variable for later
		ob_start();
		require_once($dir.'/pdf.php');
		$pdf_html = ob_get_contents();
		ob_end_clean();
		
		// Load the dompdf files
		require_once($dir.'/dompdf/dompdf_config.inc.php');
		
		$dompdf = new DOMPDF(); // Create new instance of dompdf
		$dompdf->load_html($pdf_html); // Load the html
		$dompdf->render(); // Parse the html, convert to PDF
		$pdf_content = $dompdf->output(); // Put contents of pdf into variable for later
		
		// Get the contents of the HTML email into a variable for later
		ob_start();
		require_once($dir.'/html.php');
		$html_message = ob_get_contents();
		ob_end_clean();
		
		// Load the SwiftMailer files
		require_once($dir.'/swift/swift_required.php');

		$mailer = new Swift_Mailer(new Swift_MailTransport()); // Create new instance of SwiftMailer

		$message = Swift_Message::newInstance()
				       ->setSubject('How To Create and Send An HTML Email w/ a PDF Attachment') // Message subject
					   ->setTo(array($post->email => $post->name)) // Array of people to send to
					   ->setFrom(array('no-reply@net.tutsplus.com' => 'Nettuts+')) // From:
					   ->setBody($html_message, 'text/html') // Attach that HTML message from earlier
					   ->attach(Swift_Attachment::newInstance($pdf_content, 'nettuts.pdf', 'application/pdf')); // Attach the generated PDF from earlier
		
		// Send the email, and show user message
		if ($mailer->send($message))
			$success = true;
		else
			$error = true;
		
	}

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Creating &amp; Sending An HTML Email w/ a PDF Attachment</title>
	<style type="text/css">
		html, body, h1, h2, h3, h4, h5, h6, p, span, ul, li, div, form, input, select, textarea, button {margin:0; padding:0;}
		ul {list-style:none;}
		a, a:hover {text-decoration:none; outline:0;}
		a img {border:0;}
		
		body {font:12px/16px Verdana, Arial, sans-serif; background:#001F1E;}
		#container {width:400px; margin:10px auto; padding:10px; overflow:hidden; border:1px solid #000; border-radius:10px; -moz-border-radius:10px; -webkit-border-radius:10px; background:#F9F9F9;}
		#container h1 {margin-bottom:20px; font-size:40px; line-height:40px; font-family:'HelveticaNeue-Light', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight:normal;}
		.message {margin-bottom:10px; padding:5px;}
		.success {color:#4F8A10; border:1px solid #4F8A10; background:#DFF2BF;}
		.error {color:#D8000C; border:1px solid #D8000C; background:#FFBABA;}
		label {display:block; margin-bottom:3px; cursor:pointer;}
		.input, textarea, select, button {display:block; width:390px; margin-bottom:10px; padding:3px; font:22px/22px 'HelveticaNeue-Light', 'Helvetica Neue', Helvetica, Arial, sans-serif; border:1px solid #CCC; border-top-width:2px;}
		textarea {font-size:13px; line-height:16px;}
		select {width:396px;}
		button {float:right; width:auto; margin-bottom:0; padding:3px 30px; cursor:pointer; font-size:16px; border:1px solid #999; border-bottom-width:2px; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; background:#EEE;}
		button:active {border-bottom-width:1px; padding:4px 30px 3px; background:#E9E9E9;}
	</style>
</head>
<body>

	<div id="container">
	
		<h1>The Form</h1>
		
		<?php if ($success) { ?>
			<div class="message success">
				<h4>Congratulations! It worked! Now check your email.</h4>
			</div>
		<?php } elseif ($error) { ?>
			<div class="message error">
				<h4>Sorry, an error occurred. Try again!</h4>
			</div>
		<?php } ?>
		
		<form method="post" action="">
			<label for="name">Your Name:</label>
			<input type="text" name="name" id="name" class="input" />
			
			<label for="email">Your Email:</label>
			<input type="text" name="email" id="email" class="input" />
			
			<label for="language">Favorite Language:</label>
			<select name="language" id="language">
				<option value="PHP">PHP</option>
				<option value="HTML">HTML</option>
				<option value="Javascript">Javascript</option>
				<option value="CSS">CSS</option>
				<option value="Other">Other</option>
			</select>
			
			<label for="about">Tell me about yourself:</label>
			<textarea name="about" id="about" rows="4" cols="40"></textarea>
			
			<p><button type="submit">Submit!</button></p>
		</form>
	
	</div>

</body>
</html>