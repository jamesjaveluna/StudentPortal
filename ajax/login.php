<?php

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
 
	ob_start();
	$action = $_GET['op'];
	include '../class/User.php';
	$crud = new User();

	header('Content-Type: application/json');
	
	if($action == 'process'){
		$login = $crud->login();
		if($login)
			echo $login;
	}

	if($action == 'verify'){
		$response = $crud->verify();
		if($response)
			echo $response;
	}

	if($action == 'register'){
		$register = $crud->register();
		if($register)
			echo $register;
	}
	
	if($action == 'logout'){
		header('Content-Type: application/json'); 
		$logout = $crud->logout();
		if($logout)
			echo $logout;
	}

	if($action == 'admin_resend'){
		header('Content-Type: application/json'); 
		$logout = $crud->admin_resendVerification();
		if($logout)
			echo $logout;
	}

	if($action == 'admin_delete'){
		header('Content-Type: application/json'); 
		$logout = $crud->admin_delete();
		if($logout)
			echo $logout;
	}

	if($action == 'admin_edit'){
		header('Content-Type: application/json'); 
		$logout = $crud->admin_update();
		if($logout)
			echo $logout;
	}

	if($action == 'admin_nameid_query'){
		header('Content-Type: application/json'); 
		$logout = $crud->admin_nameid_query();
		if($logout)
			echo $logout;
	}

	if($action == 'admin_create'){
		header('Content-Type: application/json'); 
		$logout = $crud->admin_create();
		if($logout)
			echo $logout;
	}

} else {
    http_response_code(404);
	//include($_SERVER['DOCUMENT_ROOT'].'/page/not_found.php'); // provide your own HTML for the error page
	die();
}

?>