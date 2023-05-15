<?php

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
 
	ob_start();
	$action = $_GET['op'];
	include '../class/Admin.php';
	$crud = new Admin();

	header('Content-Type: application/json');
	
	if($action == 'user_resend'){
		header('Content-Type: application/json'); 
		$logout = $crud->user_resend();
		if($logout)
			echo $logout;
	}

	if($action == 'user_delete'){
		header('Content-Type: application/json'); 
		$logout = $crud->user_delete();
		if($logout)
			echo $logout;
	}

	if($action == 'user_edit'){
		header('Content-Type: application/json'); 
		$logout = $crud->user_update();
		if($logout)
			echo $logout;
	}

	if($action == 'user_query'){
		header('Content-Type: application/json'); 
		$logout = $crud->user_query();
		if($logout)
			echo $logout;
	}

	if($action == 'user_create'){
		header('Content-Type: application/json'); 
		$logout = $crud->user_create();
		if($logout)
			echo $logout;
	}

	// STUDENT PAGE
	if($action == 'student_delete'){
		header('Content-Type: application/json'); 
		$logout = $crud->student_delete();
		if($logout)
			echo $logout;
	}

	// CALENDAR/ACTIVITY PAGE
	if($action == 'add_event'){
		header('Content-Type: application/json'); 
		$logout = $crud->event_create();
		if($logout)
			echo $logout;
	}


} else {
    http_response_code(404);
	//include($_SERVER['DOCUMENT_ROOT'].'/page/not_found.php'); // provide your own HTML for the error page
	die();
}

?>