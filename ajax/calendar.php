<?php
	ob_start();
	$action = $_GET['op'];
	include '../class/Calendar.php';
	$crud = new Calendar();

	header('Content-Type: application/json');

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
 
	if($action == 'get_events'){
		header('Content-Type: application/json'); 
		$logout = $crud->getEvents();
		if($logout)
			echo $logout;
	}

} elseif (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'){

	


} else {
    http_response_code(404);
	//include($_SERVER['DOCUMENT_ROOT'].'/page/not_found.php'); // provide your own HTML for the error page
	die();
}

?>