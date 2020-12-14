<?php
//environment setup
define('ENVIRONMENT', 'sandbox');  // 'sandbox' or 'beta-sandbox' or 'live'

//receivers payment common subject
define('EMAIL_SUBJECT', 'Vendor Payment'); // you can set any subject

//receiver Type
define('RECEIVER_TYPE', 'EmailAddress'); // 'EmailAddress' or 'PhoneNumber' or 'UserID'

//currency setup
define('CURRENCY', 'USD'); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

//API version
define('VERSION', '90.0');

//sender API Credentials
define('API_USERNAME', 'masspay001_api2.gmail.com'); //sender api username
define('API_PASSWORD', 'NMTEYHL74XKFJMX6'); //sender api password
define('API_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31A4H-vtPftMVaVYRD0oZMfwyc3YUi'); //sender api signature

function wvs_paypal_post_data($methodName_, $nvpStr_,$API_UserName, $API_Password, $API_Signature, $environment, $version)
{
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if ("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	//$version = urlencode($version);
	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	
	//$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
  $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
  /*echo $nvpreq;
  echo '<br />';
  echo $nvpStr_;
  die('++++++++++++++++++++++++++++++++++++++++++++++');*/
	
	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq."&".$nvpStr_);
	
	// Get response from the server.
	$httpResponse = curl_exec($ch);
	if (!$httpResponse) {
		echo $methodName_ . ' failed: ' . curl_error($ch) . '(' . curl_errno($ch) .')';
	}
	
	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);
	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}
	if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		die();
	}
	return $httpParsedResponseAr;
}