<?php
include $_SERVER['DOCUMENT_ROOT']."/lib/include.php";
$objClient = new Clients();
# Part to read a csv file and then create the pdfs with the details in that pdf file.
# All the records which are there in the pdf file must be converted into the pdf file.
function readCSV($csvFile){
	$file_handle = fopen($csvFile, 'r');
	while (!feof($file_handle) ) {
		$line_of_text[] = fgetcsv($file_handle, 1024);
	}
	fclose($file_handle);
	return $line_of_text;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function generateRandomNumber($length = 5) {
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
$dir = $_SERVER['DOCUMENT_ROOT'];
if(isset($_POST['uploadsubmit']) and $_POST['Task'] = "UploadCSV"){
	echo "PDFs are being created. Please Wait....";
	if ($_FILES["csvuploaded"]["error"] > 0) {
    	$error = "Please UPLOAD a proper csv file.";
		header("Location:Uploadcsv.php?Message=$error&message_class=message_error");
	}else{
		$csv_name = $_FILES['csvuploaded']['name'];
		$rename = explode(".",$_FILES['csvuploaded']['name']);
		$newcsv = $rename[0];
		$ext = $rename[1];
		$rand1 = generateRandomString();
		$newcsvname = $newcsv."_".$rand1.".".$ext;
		if(move_uploaded_file($_FILES['csvuploaded']['tmp_name'],$dir."/chargebacks/".$newcsvname)){
			$newcsvto_upload = $newcsvname;
		}else{
			$newcsvto_upload = "";
			header("Location:Uploadcsv.php?Message=Some Error Occured. Please try Again.");
		}
	}
	$csvFile = $newcsvto_upload;
	$csv = readCSV($csvFile);
}
$i = 0;
# If only single pdf is to be created then use this array.
if(isset($_POST['createsingle']) and $_POST['Task'] = "CreteSinglescv"){
	echo "PDF is being created. Please Wait....";
	# Create an array with a single value only.
	$csv = array($_POST['nametocreatepdf']);
	$i = 1;
}

# Get the contents of the pdf into a variable for later
require_once($dir.'/chargebacks/dompdf/dompdf_config.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="http://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff" rel="stylesheet" type="text/css" >
<link rel="stylesheet" href="../css/styles.css"  />
<title>Chargebacks</title>
<style type="text/css">
*{margin:0;padding:0}
</style>
</head>
<body>
<?php
foreach((array)$csv as $u){
	if($i != 0 and !empty($u[0])){
	  $name = $date = $created = "";
	  if(isset($_POST['createsingle']) and $_POST['Task'] = "CreteSinglescv"){
	  	$name = trim($_POST['nametocreatepdf']);
		$date = date("<b>M d</b>, Y",strtotime(trim($_POST['datetocreatepdf'])));
		$explodedate =  explode("/",$_POST['datetocreatepdf']);
		$Year_g = $explodedate[2];
		$Month_g = $explodedate[0];
		$Date_g = $explodedate[1];
		$append_date = $Year_g.$Month_g.$Date_g;
		$datestamp = $append_date.generateRandomNumber()."_".$name;
	  }else{
	  	 $name = ucfirst($u[0]);
		 $date = date("<b>M d</b>, Y",strtotime($u[1]));
		 $explodedate =  explode("/",$u[1]);
		 $Year = $explodedate[2];
		 $onlyyear = explode(" ",$Year);
		 $o_year = $onlyyear[0];
		 $Month = $explodedate[0];
		 $Date = $explodedate[1];
		 $append_date = $o_year.$Month.$Date;
		 $datestamp = $append_date.generateRandomNumber()."_".$name;
	  }
      $html = '';
	  $html .= '<link href="http://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff" rel="stylesheet" type="text/css" >';
	  $html .= '<div style="background-color: #ffffff; border: 0px;">
        <div style="padding:10px;">
          <h3 style="color: #003399;">PERSONAL ACKNOWLEDGEMENT LETTER FOR: '.$name.'</h3>
          <hr style="background-color: #1B3861; border: 1px solid #1B3861; height: 10px; width: 100%;" />
          <hr style="background-color: #A4A4A4; border: 1px solid #A4A4A4; height: 10px; width: 80%;" />
          <div class="left_clear"></div>
          <table class="Text_letter" style="margin-top:0px; color: #808080; font-family: sans-serif; font-size: 17px; line-height: 20px;" width="100%">
            <tbody>
              <tr>
                <td style="padding-right:10px;" valign="top">Success in life always requires personal commitment. Whether you are enrolling in a university or in business, success requires engagement and effort on your behalf.  Success with Millionaire Operating System is no different.  Our goal is to give you tools that help you to succeed, but success still requires commitment from you.  Back to the university versus business examples, if you were attending a university, you would have to pay tuition, but paying tuition alone doesn&prime;t guarantee success.  You still have to study, attend class and pass exams.  Likewise, in business, if you purchase a franchise business, paying for the business does not guarantee success.  You still</td>
                <td colspan="2" valign="top">have to work the business.  At Millionaire Operating System, we work hard to make success even easier than traditional franchises or even attending college.  However, work is still required on your part.  This letter is intended to confirm that you understand the commitment required from you in order to succeed.  We will work hard to provide leads, traffic and sales, but success still requires your personal effort and commitment.  Provided below is a quick reminder of what you have learned, what you&prime;re commitment and role will be in your success and a reminder of what we are doing for you at Millionaire Operating System to help ensure your success.</td>
              </tr>
            </tbody>
          </table>
          <div class="left_clear"></div>
          <table style="line-height:25px; width:100%;" width="100%;">
            <tbody>
              <tr>
                <td align="center"><img src="images/member_blank.png" /></td>
                <td align="center"><img src="images/oslogo.png" /></td>
              </tr>
            </tbody>
          </table>
          <div class="left_clear"></div>
          <table class="Text_letter" style="margin-top:0px; color: #808080; font-family: sans-serif; font-size: 17px; line-height: 20px;" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
              <tr height="55">
                <th style="border-right:3px solid #fff; color:#fff; text-align:center;" bgcolor="#1b3861" width="49%">YOUR ACKNOWLEDGEMENT</th>
                <th style=" text-align:center; color:#fff;" bgcolor="#1b3861" width="49%">HOW WE HELP</th>
              </tr>
              <tr height="55">
                <td style="border-right:3px solid #fff; padding-right:10px;" bgcolor="#ffffff" width="49%">Foundational to your success is how you think.  You commit to continually expanding your mind by reading at least one book per month from our recommended reading list.</td>
                <td bgcolor="#ffffff" width="49%">MOS provides a recommended reading list and video library on topics such as business, mindset, leadership and more. (www.themillionaireos.com/mind-set-for-success)</td>
              </tr>
              <tr height="55">
                <td style="border-right:3px solid #fff; padding-right:10px;" bgcolor="#d7eefb" width="49%">You commit to continuing your education, allowing you to better understand the various components required to drive results for your business.</td>
                <td bgcolor="#d7eefb" width="49%">MOS provides web-based training curriculum as well as live training events, webinars and conference calls. (www.themillionaireos.com)</td>
              </tr>
              <tr height="55">
                <td style="border-right:3px solid #fff; padding-right:10px;" bgcolor="#ffffff" width="49%">As your business, you are responsible for driving all marketing and advertising efforts required to generate interest, traffic and leads necessary to provide quality sales prospects.</td>
                <td bgcolor="#ffffff" width="49%">MOS assists members with marketing and advertising by allowing them to leverage existing traffic and lead generation funnels, providing access to sales leads through our co-op.<br /></td>
              </tr>
              <tr height="55">
                <td style="border-right:3px solid #fff; padding-right:10px;" bgcolor="#d7eefb" width="49%">To increase your business exposure you commit to regular, if not daily, efforts to post new ads and explore and implement new marketing and advertising channels for your business.</td>
                <td bgcolor="#d7eefb" width="49%">MOS provides tools and training related to marketing and advertising.  Including tools and templates to help make daily advertising efforts easier than ever.</td>
              </tr>
              <tr height="55">
                <td style="border-right:3px solid #fff; padding-right:10px;" bgcolor="#ffffff" width="49%">To be successful, you acknowledge that you must learn what your product is and how to sell it to customers.  For long-term success, you commit to completely owning the sales process.</td>
                <td bgcolor="#ffffff" width="49%">MOS provides sales training, mentors and calls to assist in closing new leads/prospects, thus providing time for our members to prepare to take over sales for their business.</td>
              </tr>
              <tr height="25" bgcolor="#ffffff">
              	<td colspan="2">&nbsp;</td>
              </tr>
              <tr height="55">
                <td style="padding-bottom:10px;" colspan="2" bgcolor="#d7eefb" width="100%"><span style="color:#767676; font-weight:bold; font-size: 20px;"> STATEMENT OF PERSONAL </span><span  style="color:#767676; font-weight:bold; font-size: 20px;">ACKNOWLEDGEMENT: </span>
                  <div class="left_clear"></div>
                  By signing below I hereby acknowledge that success requires effort on my behalf and I am signing this Personal Commitment Letter to confirm that I am willing to do my part to continue learning and working to ensure my business grows and flourishes.  I also acknowledge that I understand and know that failure on my behalf to do my part may jeopardize my ability to recognize success and create income.</td>
              </tr>
              <tr height="25" bgcolor="#ffffff">
              	<td colspan="2">&nbsp;</td>
              </tr>
              <tr bgcolor="#ffffff" height="50">
              	<td ><span style="float:left;">Member Name:</span><span style="text-align:center; border-bottom: 2px solid #CCCCCC; margin-left: 60px; color:#000;">'.$name.'</span></td>
                <td ><span>Date:&nbsp;&nbsp;&nbsp;</span><span style="text-align:center; margin-left: 60px; color:#000;">'.$date.'</span></td>
              </tr>
			  <tr>
			  	<td colspan="2">&nbsp;</td>
			  </tr>
              <tr bgcolor="#ffffff" height="50">
              	<td><span style="float:left;">Member Signature:</span><span style="font-family: \'Herr Von Muellerhoff\', cursive; text-align:center; margin-left: 28px; color:#346599; font-size:35px;">'.$name.'</span></td>
                <td >&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>';
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper("legal", "portrait");
		$dompdf->render(); 
		$pdf_file = $dompdf->output();
		$pdfname = "";
		$pdfname = $datestamp.".pdf";
		
		if(file_put_contents($dir.'/chargebacks/agreements/'.$datestamp.".pdf", $pdf_file)){
			$created = 1;
			$objClient->InsertChargebacksdetailspdf(array(
				"Created"	   			=> date("Y-m-d h:i:s",strtotime($append_date)),
				"LastEdited"			=> date("Y-m-d h:i:s",time()),
				"pdfname"				=> $pdfname,
				"uploadedby"			=> $_SESSION['Member']['ID'],
			));
		}else{
			$created = 0;
		}
	}
	$i++;
}

if($created == 1){
	if(isset($_POST['uploadsubmit']) and $_POST['Task'] = "UploadCSV"){
		unlink($dir.'/chargebacks/'.$csvFile);
	}
	echo "<div class='message_success' style='width:96%;'>PDF File(s) Created Successfully. Please wait you will be redirected in a moment.</div>";
	?>
    <script type="text/javascript">
		window.location.href = "Uploadcsv.php?Message=PDF File(s) Created Successfully&message_class=message_success";
	</script>
    <?php
}else{
	header("Location:Uploadcsv.php?Message=Some Error Occured. Please Try Again&message_class=message_error");
}
?>
</body>
</html>
