<?php

$page_title = "Dashboard";
$return_url = $_SERVER['REQUEST_URI'];

require_once('./class/config/config.php');

if(GEN_MAINTENANCE === true){
  include('./pages/maintenance.php');
  exit();
}

ob_start();

session_start();

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}

// Switch to admin panel
if($_SESSION['user']['panel'] === 'admin'){
  header("Location: ./blank.php");
  exit();
}

require_once 'class/Schedule.php';
$sc_crud = new Schedule();

require_once 'class/Calendar.php';
$ca_crud = new Calendar();

$schedule_raw = json_decode($sc_crud->getScheduleToday(), true);
$calendar_raw = json_decode($ca_crud->getEventToday(), true);

//var_dump($calendar_raw);

?>

<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="./../">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
    <!-- Calendar Card -->

   <div class="row">

     <!-- Left side columns -->
     <div class="col-lg-8">
       <div class="row">
        <div class="col-12">
            <div class="row">
                <!-- Schedule Card -->
                <div class="col-lg-6">
                    <div class="card info-card sales-card">
                      <div class="card-body">
                        <h5 class="card-title">Class <span>| Today</span></h5>
                        <?php
                        
                        // Class
                        if($schedule_raw['code'] === 10000){
                            echo '<h6>'.$schedule_raw['data']['code'].'</h6>' .
                                 '<span class="text-muted small pt-2">'.$schedule_raw['data']['time'].'</span><br>' .
                                 '<span class="text-danger small pt-1 fw-bold">'.$schedule_raw['data']['room_name'].'</span>';
                        } 

                        // No Class
                        if($schedule_raw['code'] === 10001){
                            echo '<h6 class="text-danger">'.$schedule_raw['message'].'</h6>'.
                                 '<span class="text-muted small pt-2">No Schedule found.</span><br>';
                        } 

                        // Vacant
                        if($schedule_raw['code'] == 10002){
                            echo '<h6 class="text-success">'.$schedule_raw['message'].'</h6>'.
                                 '<span class="text-muted small pt-2">Next Subject: <b>'.$schedule_raw['data']['code'].'</b></span><br>' .
                                 '<span class="text-secondary small pt-1 fw-bold">'.$schedule_raw['data']['time'].' - '.$schedule_raw['data']['room_name'].'</span>';
                        } 

                        // Complete
                        if($schedule_raw['code'] === 10003){
                            echo '<h6 class="text-success">'.$schedule_raw['message'].'</h6>'.
                                 '<span class="text-muted small pt-2">All classes is done</span><br>';
                        } 

                        // Unavailable
                        if($schedule_raw['code'] === 10004){
                            echo '<h6 class="text-warning">'.$schedule_raw['message'].'</h6>'.
                                 '<span class="text-muted small pt-2">No schedule found.</span><br>';
                        } 
                        
                        ?>
                        
                      </div>
                    </div>
                </div>
                <!-- End Schedule Card -->

                <!-- Event Card -->
                <div class="col-lg-6">
                    <div class="card info-card revenue-card">


                      <div class="card-body">
                        <h5 class="card-title">Event <span>| Today</span></h5>
                            <?php
                            
                                // Has event
                                if($calendar_raw['code'] === 10000){
                                    $start = date("g:i A", strtotime($calendar_raw['data'][0]['start']));
                                    $end = date("g:i A", strtotime($calendar_raw['data'][0]['end']));

                                    $start_date = date("M d, Y", strtotime($calendar_raw['data'][0]['start']));
                                    $end_date = date("M d, Y", strtotime($calendar_raw['data'][0]['end']));

                                    $start_date = date("M d, Y", strtotime($calendar_raw['data'][0]['start']));
                                    $end_date = date("M d, Y", strtotime($calendar_raw['data'][0]['end']));

                                    $start_date_two = date("M d", strtotime($calendar_raw['data'][0]['start']));
                                    $end_date_two = date("d, Y", strtotime($calendar_raw['data'][0]['end']));
                                    
                                    $formattedTime = $start . ' - ' . $end; //Used in events with time
                                    $formattedDateOne = $start_date; //Displays 1 date only.
                                    $formattedDateTwo = $start_date_two. '-' .$end_date_two; //Used in dates that are not similar
                                    
                                    
                                    if($calendar_raw['data'][0]['status'] === 0){
                                        $subText = '<span class="badge bg-danger">Event Ended</span>';
                                    } else {
                                        $subText = '<span class="badge bg-success">Ongoing</span>';
                                    }

                                    if($calendar_raw['data'][0]['location'] !== null || !empty($calendar_raw['data'][0]['location'])){
                                        $subText .= '<span class="text-secondary small pt-1 fw-bold"> ('.$calendar_raw['data'][0]['location'].')</span>';
                                    }

                                    if($calendar_raw['data'][0]['allDay'] !== "true"){
                                        echo '<h6>'.$calendar_raw['data'][0]['title'].'</h6>' .
                                         '<span class="text-muted small pt-2">'.$formattedTime.'</span><br>';
                                        echo $subText;
                                    } else {
                                        //Check if the date is similar
                                        if($start_date === $end_date){
                                            echo '<h6>'.$calendar_raw['data'][0]['title'].'</h6>' .
                                            '<span class="text-muted small pt-2">'.$formattedDateOne.'</span><br>';
                                            if($calendar_raw['data'][0]['isHoliday'] === 'true'){
                                                echo '<span class="badge bg-danger">Holiday</span>';
                                            }

                                        } else {
                                            echo '<h6>'.$calendar_raw['data'][0]['title'].'</h6>' .
                                            '<span class="text-muted small pt-2">'.$formattedDateTwo.'</span><br>';
                                            if($calendar_raw['data'][0]['isExam'] === "false") {
                                                echo $subText;
                                            }
                                        }
                                    }
                                } 

                                // No Event
                                if($calendar_raw['code'] === 10001){
                                    echo '<h6 class="text-dark">No Event</h6>'.
                                         '<span class="text-muted small pt-2">No event scheduled.</span><br>';
                                } 

                                // Multiple Events
                                if($calendar_raw['code'] === 10002){
                                    echo '<h6 class="text-success">Multiple Events</h6>'.
                                         '<span class="text-muted small pt-2">'.$calendar_raw['message'].'</span><br>'.
                                         '<button type="button" class="btn btn-danger btn-sm">View Calendar</button>';
                                } 

                                // Has event registered but your course is not included
                                if($calendar_raw['code'] === 10003){
                                    echo '<h6 class="text-dark">No Event</h6>'.
                                         '<span class="text-muted small pt-2">No event scheduled.</span><br>';
                                } 

                                // Unable to connect
                                if($calendar_raw['code'] === 10004){
                                    echo '<h6 class="text-danger">'.$calendar_raw['message'].'</h6>'.
                                         '<span class="text-muted small pt-2">Unable to connect to Calendar System.</span><br>';
                                } 
                            
                            ?>
                      </div>

                    </div>
                </div>
                <!-- End Event Card -->
            </div>
         </div>

         <!-- Highlights Card-->
         <div class="col-12">
            <div class="card">
            <div class="card-body">
              <h5 class="card-title">Highlights <span>| This Month</span></h5>

              <!-- Slides with indicators -->
              <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="../assets/img/highlights/1.jpg" class="d-block w-100" alt="...">
                  </div>
                  <div class="carousel-item">
                    <img src="../assets/img/highlights/2.jpg" class="d-block w-100" alt="...">
                  </div>
                  <div class="carousel-item">
                    <img src="../assets/img/highlights/3.jpg" class="d-block w-100" alt="...">
                  </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>

              </div><!-- End Slides with indicators -->

            </div>
          </div>
         </div>
         <!-- End Highlights Card -->


       </div>
     </div><!-- End Left side columns -->

     <!-- Right side columns -->
     <div class="col-lg-4">

       <!-- Cecilian Research -->
       <div class="card">
         
         <div class="card-body">
           <h5 class="card-title">Cecilian Research</h5>
           <div class="task-container">
             <button type="button" class="btn btn-danger text-white view-btn">View</button>
             <div class="task-info">
               <h3 class="title">Impact of COVID-19 on Mental Health</h3>
               <p class="description">Study the impact of the COVID-19 pandemic on mental health...</p>
               <p class="description"> Researchers: <b>Nurse Office</b></p>
             </div>
           </div>
           <div class="task-container">
             <button type="button" class="btn btn-danger text-white view-btn">View</button>
             <div class="task-info">
               <h3 class="title">Sustainable Tourism in Cebu</h3>
               <p class="description"> Investigate the impact of social media on the mental health...</p>
               <p class="description"> Researchers: <b>HTM 1A (Group 1)</b></p>
             </div>
           </div>
           <div class="task-container">
             <button type="button" class="btn btn-danger text-white view-btn">View</button>
             <div class="task-info">
               <h3 class="title">Cyberbullying Among Adolescents</h3>
               <p class="description">Study the prevalence and impact of cyberbullying among...</p>
               <p class="description"> Researchers: <b>EDUC (Group 5)</b></p>
             </div>
           </div>


         </div>
       </div>
       <!-- End Cecilian Research -->

     </div><!-- End Right side columns -->

   </div>
</section>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>