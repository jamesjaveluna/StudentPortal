<?php

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
 
	ob_start();
	$action = $_GET['op'];
	include '../class/Support.php';
	$crud = new Support();

	header('Content-Type: application/json');
	
	if($action == 'send_message'){
		$response = $crud->sendMessage();
		if($response)
			echo $response;
	}

	if($action == 'create_ticket'){
		$response = $crud->createTicket();
		if($response)
			echo $response;
	}

} else {
    http_response_code(404);
	//include($_SERVER['DOCUMENT_ROOT'].'/page/not_found.php'); // provide your own HTML for the error page
	die();
}

?>