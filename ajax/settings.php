<?php

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
 
	ob_start();
	$action = $_GET['op'];
	include '../class/User.php';
	$crud = new User();

	header('Content-Type: application/json');
	
	if($action == 'changepass'){
		$response = $crud->settingsChangePass();
		if($response)
			echo $response;
	}

	if($action == 'verify'){
		$response = $crud->verify();
		if($response)
			echo $response;
	}

	if($action == 'resend-email'){
		$response = $crud->resend_email();
		if($response)
			echo $response;
	}

} else {
    http_response_code(404);
	//include($_SERVER['DOCUMENT_ROOT'].'/page/not_found.php'); // provide your own HTML for the error page
	die();
}

?>