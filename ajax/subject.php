<?php

header('Content-Type: text/html; charset=utf-8');

$instructor = isset($_GET['i']) ? urldecode($_GET['i']) : null;
$description = isset($_GET['d']) ? $_GET['d'] : null;
$room = isset($_GET['r']) ? $_GET['r'] : null;
?>

<div class="cd-schedule-modal__event-info">
	<div><h2><b><?php echo $instructor; ?></b></h2><br>
	<?php echo $description; ?><br><br>
	Room Name: <b><?php echo $room; ?></b><br><br>

	
	</div>
