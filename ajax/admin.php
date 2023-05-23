<?php

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
 
	ob_start();
	$action = $_GET['op'];
	include '../class/Admin.php';
	$crud = new Admin();

	header('Content-Type: application/json');
	
	if($action == 'user_resend'){
		header('Content-Type: application/json'); 
		$response = $crud->user_resend();
		if($response)
			echo $response;
	}

	if($action == 'user_delete'){
		header('Content-Type: application/json'); 
		$response = $crud->user_delete();
		if($response)
			echo $response;
	}

	if($action == 'user_edit'){
		header('Content-Type: application/json'); 
		$response = $crud->user_update();
		if($response)
			echo $response;
	}

	if($action == 'user_query'){
		header('Content-Type: application/json'); 
		$response = $crud->user_query();
		if($response)
			echo $response;
	}

	if($action == 'user_create'){
		header('Content-Type: application/json'); 
		$response = $crud->user_create();
		if($response)
			echo $response;
	}

	// STUDENT PAGE
	if($action == 'student_delete'){
		header('Content-Type: application/json'); 
		$response = $crud->student_delete();
		if($response)
			echo $response;
	}

	// CALENDAR/ACTIVITY PAGE
	if($action == 'add_event'){
		header('Content-Type: application/json'); 
		$response = $crud->event_create();
		if($response)
			echo $response;
	}

	// SUPPORT/TICKET PAGE
	if($action == 'send_reply'){
		header('Content-Type: application/json'); 
		$response = $crud->sendReply();
		if($response)
			echo $response;
	}

	if($action == 'support_update_status'){
		header('Content-Type: application/json'); 
		$response = $crud->updateTicketStatus();
		if($response)
			echo $response;
	}

	if($action == 'support_delete_note'){
		header('Content-Type: application/json'); 
		$response = $crud->supportDeleteNote();
		if($response)
			echo $response;
	}

	if($action == 'support_add_note'){
		header('Content-Type: application/json'); 
		$response = $crud->supportAddNote();
		if($response)
			echo $response;
	}


} else {
    http_response_code(404);
	//include($_SERVER['DOCUMENT_ROOT'].'/page/not_found.php'); // provide your own HTML for the error page
	die();
}

?>