
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="/PHPCommandIPoC/static/bootstrap.min.css">
<link rel="shortcut icon" href="/PHPCommandIPoC/static/favi.ico"/>


<style type="text/css">
	
body{
    background: url("/cve201610033/static/string.jpg")no-repeat center center fixed; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover
    
}
.vertical-offset-100{
    padding-top:100px;
}
</style>

<title>PHPCommandI</title>


<div class="container">
    <div class="row vertical-offset-100">
    	<div class="col-md-6 col-md-offset-3">
			<form class="form-horizontal" role="form" method="post" action="poc.php">
				<div class="form-group">
					<label for="name" class="col-sm-2 control-label">EmailTo</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="emailTo" name="emailTo" placeholder="example@domain.com" value="">
				</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-sm-2 control-label">EmailFrom</label>
					<div class="col-sm-10">
					<input type="text" class="form-control" id="emailFrom" name="emailFrom" placeholder="example@domain.com" value="">
					</div>
				</div>
				<div class="form-group">
					<label for="message" class="col-sm-2 control-label">Message</label>
					<div class="col-sm-10">
					<textarea class="form-control" rows="4" name="body"></textarea>
					</div>
				</div>
				<div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<input id="submit" name="submit" type="submit" value="PHP Mail" class="btn btn-primary">
					<input id="submit" name="submit" type="submit" value="PHPMailer" class="btn btn-primary">
				</div>
				</div>

		</form>
		</div>
	</div>
</div>




<?php

require __DIR__ . '/vendor/autoload.php';

if (isset($_POST['emailFrom']) and isset($_POST['emailTo']) and isset($_POST['body']) and !strcmp($_POST['submit'],'PHP Mail')){

	$emailTo = $_POST['emailTo'];

	$emailFrom = $_POST['emailFrom'];

	$body = $_POST['body'];

//PHP mail() Function let as a "functionality" to add extra parameters to the target executable. Default configurations will call "sendmail"

$subject = "My subject";
$headers = "";
$extraparams = "-f".$emailFrom;

echo '1';

mail($emailTo,$subject,$body,$headers,$extraparams);



}elseif (isset($_POST['emailFrom']) and isset($_POST['emailTo']) and isset($_POST['body']) and !strcmp($_POST['submit'],'PHPMailer')) {
	
	$emailTo = $_POST['emailTo'];

	$emailFrom = $_POST['emailFrom'];

	$body = $_POST['body'];



//PHPMailer uses the PHP mail() function when no more configurations are provided. It is funny though, that only inject to mail() the "extraparams" when the "Sender" attribute is set. This Sender attribute is crafted in a similar way than in the previous case, but is only being used when function "addAddress()" is used. If we set the $mail attributes manually, Sender will be empty and the vulnerability will not be exploitable.
$mail = new PHPMailer;

error_log($emailFrom,0);
$mail->setfrom($emailFrom,'Bob');
//$mail->From = $emailFrom; ==> If we use this, Sender will be empty.

$mail->addAddress($emailTo, 'Alice');

$mail->Body    = $body;

//error_log($mail->Sender,0);

if(!$mail->send()) {
	
	    echo 'Message could not be sent.';

	        echo 'Mailer Error: ' . $mail->ErrorInfo;

	        } else {

	            echo 'Message has been sent';

	        }


}


?>
