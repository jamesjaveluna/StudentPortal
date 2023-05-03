<?php

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
 
	ob_start();
	$action = $_GET['op'];
	include '../class/Import.php';
	$crud = new Import();

	header('Content-Type: application/json');
	
	if($action == 'student'){
		$login = $crud->student();
		if($login)
			echo $login;
	}

} else {
    http_response_code(404);
	//include($_SERVER['DOCUMENT_ROOT'].'/page/not_found.php'); // provide your own HTML for the error page
	die();
}

?>