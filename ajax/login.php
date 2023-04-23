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
	
	if($action == 'logout'){
		header('Content-Type: application/json'); 
		$logout = $crud->logout();
		if($logout)
			echo $logout;
	}

} else {
    http_response_code(404);
	//include($_SERVER['DOCUMENT_ROOT'].'/page/not_found.php'); // provide your own HTML for the error page
	die();
}

?>