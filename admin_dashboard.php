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

require_once 'class/Schedule.php';
$sc_crud = new Schedule();

require_once 'class/Calendar.php';
$ca_crud = new Calendar();

require_once 'class/Admin.php';
$ad_crud = new Admin();

$schedule_raw = json_decode($sc_crud->getScheduleToday(), true);
$calendar_raw = json_decode($ca_crud->getEventToday(), true);
$statistics_users_monthly_raw = json_decode($ad_crud->getRegisteredUsersMonthly(), true);
$statistics_users_total_raw = json_decode($ad_crud->getTotalRegisteredUsers(), true);
$statistics_students_total_raw = json_decode($ad_crud->getTotalStudents(), true);
$statistics_courses_total_raw = json_decode($ad_crud->getTotalCourses(), true);


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

                        // No Class (Due to event)
                        if($schedule_raw['code'] === 10005){
                            echo '<h6 class="text-danger">'.$schedule_raw['message'].'</h6>'.
                                 '<span class="text-muted small pt-2">Due to: <b>'.$schedule_raw['event_name'].'</b></span><br>';
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
                                    
                                    if($calendar_raw['data'][0]['allDay'] != true && $calendar_raw['data'][0]['noClass'] != true){
                                        if($calendar_raw['data'][0]['status'] === 0){
                                            $subText = '<span class="badge bg-danger">Event Ended</span>';
                                        } else {
                                            $subText = '<span class="badge bg-success">Ongoing</span>';
                                        }
                                    } else {
                                        $subText = '<span class="badge bg-danger">No Class</span>';
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
                <h5 class="card-title">Registered Users</h5>

                <!-- Line Chart -->
                <div id="lineChart"></div>

                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    new ApexCharts(document.querySelector("#lineChart"), {
                      series: [{
                        name: "Users",
                        data: [<?php

                        $counts = array();

                        foreach ($statistics_users_monthly_raw['data'] as $count) {
                            $counts[] = $count['UserCount'];
                        }
                        
                        $result = implode(',', $counts);
                        $result = rtrim($result, ',');
                        
                        echo $result;

                        ?>]
                      }],
                      chart: {
                        height: 350,
                        type: 'line',
                        zoom: {
                          enabled: false
                        }
                      },
                      dataLabels: {
                        enabled: false
                      },
                      stroke: {
                        curve: 'straight'
                      },
                      grid: {
                        row: {
                          colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                          opacity: 0.5
                        },
                      },
                      xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                      }
                    }).render();
                  });
                </script>
                <!-- End Line Chart -->
             </div>
          </div>
         </div>
         <!-- End Highlights Card -->


       </div>
     </div><!-- End Left side columns -->

     <!-- Right side columns -->
     <div class="col-lg-4">

       <div class="card info-card sales-card">
         <div class="card-body">
           <h5 class="card-title">Users <span>| Total</span></h5>
           <h6 class="text-danger"><?php echo number_format($statistics_users_total_raw['data']['UserCount']); ?></h6>
           <span class="text-muted small pt-2">Registered Users</span><br>                        
         </div>
       </div>
      
       <div class="card info-card sales-card">
         <div class="card-body">
           <h5 class="card-title">Students <span>| Total</span></h5>
           <h6 class="text-danger"><?php echo number_format($statistics_students_total_raw['data']['UserCount']); ?></h6>
           <span class="text-muted small pt-2">Enrolled Users</span><br>                        
         </div>
       </div>

       <div class="card info-card sales-card">
         <div class="card-body">
           <h5 class="card-title">Courses <span>| Total</span></h5>
           <h6 class="text-danger"><?php echo number_format($statistics_courses_total_raw['data']['UserCount']); ?></h6>
           <span class="text-muted small pt-2">Enrolled Courses</span><br>                        
         </div>
       </div>

     </div><!-- End Right side columns -->

   </div>
</section>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>