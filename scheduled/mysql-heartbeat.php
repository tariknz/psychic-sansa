<?php

//////////////////////////////////////////////////////////////
//															//
//	Scheduled task used for checking database connectivity. //
// 	Will check all db's in the config and display the 		//
//	status in the status bar under the navigation 			//
//															//
//	NOTE: Set this file up as a scheduled task 				//
//	to run for this functionality to work  					//
//															//
//////////////////////////////////////////////////////////////

include_once(dirname(dirname(__FILE__)).'/_config.php');

$status_xml = new SimpleXMLElement("<status></status>");
$status_xml->addAttribute('date', date("Y-m-d H:i:s"));

foreach ($DB_SERVERS as $key => $server) {
	checkMysql($server,$status_xml,$key);
}


function checkMysql($server,$status_xml,$key)
{
	$xml_server = $status_xml->addChild('server');
	$xml_server->addAttribute('hostname',$server['hostname']);
	$xml_server->addAttribute('key',$key);

	try {
		
		$dbh = new PDO("mysql:host={$server['hostname']}", $server['username'],$server['password'], array(PDO::ATTR_TIMEOUT => "5"));
    	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Throw PDOException.

    	echo $server['hostname'].': connected successfully<br />';
    	$status = "OK";

    	$xml_server->addAttribute('status',$status);

    	$dbh = null;

	} catch (PDOException $e) {
		$xml_server->addAttribute('status','ERROR');
		$xml_server->addAttribute('message',$e->getMessage());

		sendEmail($server['hostname'].': connection error',$e->getMessage());
		$status = "ERROR: ". $e->getMessage();
	}

	addToLog($server,$status,$status_xml);
}


function sendEmail($title,$body)
{
	global $MAIL_CONFIG;

	$sentat = "Sent at: ".date("Y-m-d H:i:s");

	$email = $MAIL_CONFIG['mail_to'];

	$email_from = $MAIL_CONFIG['mail_from'];
	$name_from = $MAIL_CONFIG['mail_name'];

	$send_using_gmail = true;

	require("../libs/class.phpmailer.php");

	$mail = new PHPMailer(true);

    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->SMTPAuth = true; // enable SMTP authentication
    $mail->Host = $MAIL_CONFIG['smtp_host']; // sets GMAIL as the SMTP server
    $mail->Port = $MAIL_CONFIG['port']; // set the SMTP port for the GMAIL server
    $mail->Username = $MAIL_CONFIG['username']; // GMAIL username
    $mail->Password = $MAIL_CONFIG['password']; // GMAIL password

	//Typical mail data
	$mail->AddAddress($email, $email);
	$mail->SetFrom($email_from, $name_from);
	$mail->Subject = $title;
	$mail->Body = $body;

	try{
	    $mail->Send();
	    echo "<br />Sent email with error.<br /><br />";
	} catch(Exception $e){
	    //Something went bad
	    echo "<br />Failed to send email. <br />".$e;
	    echo "<br /><br />".$mail->ErrorInfo."<br /><br />";
	}
}

function addToLog($server,$status,$status_xml){
	$myFile = dirname(__FILE__) . '/logs/'.$server['hostname'].'.log';
	$fh = fopen($myFile, 'a') or die("can't open file");
	$stringData = "[".date("Y-m-d H:i:s")."]".$server['hostname'].": ".$status."\n";
	fwrite($fh, $stringData);
	fclose($fh);

	$myFile = dirname(__FILE__) . '/logs/status.xml';
	$fh = fopen($myFile, 'w') or die("can't open file");

	$stringData = $status_xml->asXML();

	fwrite($fh, $stringData);
	fclose($fh);
}



?>