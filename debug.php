<?php
require_once './assets/utility.php';

$utility = new Utility();

$time = '7:30 PM -9:00 AM';

$utility->addMeridiem2($time);

?>