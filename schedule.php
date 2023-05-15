<?php

$page_title = "Schedule";
$return_url = $_SERVER['REQUEST_URI'];

ob_start();

session_start();

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}

require_once 'class/Schedule.php';
$crud = new Schedule();

$schedule_raw = json_decode($crud->getSchedule(), true);

if($schedule_raw['code'] === 10000){
    $schedule_data = $schedule_raw['data'];
} else {
    include 'no-record.php';
    exit();
}

?>


<div class="pagetitle">
  <h1>Schedule</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="./../">Home</a></li>
      <li class="breadcrumb-item active">Class Schedule</li>
    </ol>
  </nav>
</div>


<section class="section dashboard">
    <!-- Calendar Card -->
        
           <div class="card info-card customers-card">
             <div class="card-body">
                  <h5 class="card-title"><span>Last Updated: <b><?php echo $schedule_raw['last_update_display']; ?></b></span></h5>
                  <div class="cd-schedule cd-schedule--loading margin-bottom-lg js-cd-schedule" style="width: calc(100% - 1.25em) !important;">
                     <div class="cd-schedule__timeline">
                       <ul>
                         <li><span>07:00</span></li>
                         <li><span>07:30</span></li>
                         <li><span>08:00</span></li>
                         <li><span>08:30</span></li>
                         <li><span>09:00</span></li>
                         <li><span>09:30</span></li>
                         <li><span>10:00</span></li>
                         <li><span>10:30</span></li>
                         <li><span>11:00</span></li>
                         <li><span>11:30</span></li>
                         <li><span>12:00</span></li>
                         <li><span>12:30</span></li>
                         <li><span>01:00</span></li>
                         <li><span>01:30</span></li>
                         <li><span>02:00</span></li>
                         <li><span>02:30</span></li>
                         <li><span>03:00</span></li>
                         <li><span>03:30</span></li>
                         <li><span>04:00</span></li>
                         <li><span>04:30</span></li>
                         <li><span>05:00</span></li>
                         <li><span>05:30</span></li>
                         <li><span>06:00</span></li>
                         <li><span>06:30</span></li>
                         <li><span>07:00</span></li>
                         <li><span>07:30</span></li>
                         <li><span>08:00</span></li>
                         <li><span>08:30</span></li>
                       </ul>
                     </div> <!-- .cd-schedule__timeline -->
                   
                     <div class="cd-schedule__events">
                       <ul>
                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Monday</span></div>
                   
                           <ul>
                           <?php

                           if(isset($schedule_data['MONDAY'])){
                                foreach($schedule_data['MONDAY'] as $schedule){
                                      echo ' <li class="cd-schedule__event">
                                          <a data-start="'.$schedule['military_time']['start_time'].'" data-end="'.$schedule['military_time']['end_time'].'" data-content="subject.php?i='.$schedule['instructor_name'].'&d='.$schedule['description'].'&r='.$schedule['room_name'].'" data-event="'.$schedule['data-event'].'" href="#0">
                                            <em class="cd-schedule__name">'.$schedule['code'].'</em>
                                          </a>
                                        </li>';
                                }
                           }

                           ?>
                           </ul>
                         </li>
                   
                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Tuesday</span></div>
                   
                           <ul>
                             <?php
                                
                                if(isset($schedule_data['TUESDAY'])){
                                    foreach($schedule_data['TUESDAY'] as $schedule){
                                          echo ' <li class="cd-schedule__event">
                                          <a data-start="'.$schedule['military_time']['start_time'].'" data-end="'.$schedule['military_time']['end_time'].'" data-content="subject.php?i='.$schedule['instructor_name'].'&d='.$schedule['description'].'&r='.$schedule['room_name'].'" data-event="'.$schedule['data-event'].'" href="#0">
                                            <em class="cd-schedule__name">'.$schedule['code'].'</em>
                                          </a>
                                        </li>';
                                    }
                                }

                             ?>
                   
                           </ul>
                         </li>
                   
                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Wednesday</span></div>
                   
                           <ul>
                           <?php
                                if(isset($schedule_data['WEDNESDAY'])){
                                    foreach($schedule_data['WEDNESDAY'] as $schedule){
                                          echo ' <li class="cd-schedule__event">
                                          <a data-start="'.$schedule['military_time']['start_time'].'" data-end="'.$schedule['military_time']['end_time'].'" data-content="subject.php?i='.$schedule['instructor_name'].'&d='.$schedule['description'].'&r='.$schedule['room_name'].'" data-event="'.$schedule['data-event'].'" href="#0">
                                            <em class="cd-schedule__name">'.$schedule['code'].'</em>
                                          </a>
                                        </li>';
                                    }
                                }
                           ?>
                           </ul>
                         </li>
                   
                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Thursday</span></div>
                   
                           <ul>
                           <?php
                                if(isset($schedule_data['THURSDAY'])){
                                    foreach($schedule_data['THURSDAY'] as $schedule){
                                          echo ' <li class="cd-schedule__event">
                                          <a data-start="'.$schedule['military_time']['start_time'].'" data-end="'.$schedule['military_time']['end_time'].'" data-content="subject.php?i='.$schedule['instructor_name'].'&d='.$schedule['description'].'&r='.$schedule['room_name'].'" data-event="'.$schedule['data-event'].'" href="#0">
                                            <em class="cd-schedule__name">'.$schedule['code'].'</em>
                                          </a>
                                        </li>';
                                    }
                                }
                           ?>
                           </ul>
                         </li>
                   
                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Friday</span></div>
                   
                           <ul>
                           <?php
                                if(isset($schedule_data['FRIDAY'])){
                                    foreach($schedule_data['FRIDAY'] as $schedule){
                                          echo ' <li class="cd-schedule__event">
                                          <a data-start="'.$schedule['military_time']['start_time'].'" data-end="'.$schedule['military_time']['end_time'].'" data-content="subject.php?i='.$schedule['instructor_name'].'&d='.$schedule['description'].'&r='.$schedule['room_name'].'" data-event="'.$schedule['data-event'].'" href="#0">
                                            <em class="cd-schedule__name">'.$schedule['code'].'</em>
                                          </a>
                                        </li>';
                                    }
                                }
                           ?>
                           </ul>
                         </li>

                        
                           <?php
                              if(isset($schedule_data['SATURDAY'])){
                                echo ' <li class="cd-schedule__group">
                                    <div class="cd-schedule__top-info"><span>Saturday</span></div>
                   
                                    <ul>';
                                    foreach($schedule_data['SATURDAY'] as $schedule){
                                          echo ' <li class="cd-schedule__event">
                                          <a data-start="'.$schedule['military_time']['start_time'].'" data-end="'.$schedule['military_time']['end_time'].'" data-content="subject.php?i='.$schedule['instructor_name'].'&d='.$schedule['description'].'&r='.$schedule['room_name'].'" data-event="'.$schedule['data-event'].'" href="#0">
                                            <em class="cd-schedule__name">'.$schedule['code'].'</em>
                                          </a>
                                        </li>';
                                    }
                                echo '
                                   </ul>
                                 </li>';
                              }
                           ?>

                         
                             <?php
                             
                                if(isset($schedule_data['SUNDAY'])){
                                echo '<li class="cd-schedule__group">
                                      <div class="cd-schedule__top-info"><span>Sunday</span></div>
                                      
                                      <ul>';
                                        foreach($schedule_data['SUNDAY'] as $schedule){
                                         echo ' <li class="cd-schedule__event">
                                          <a data-start="'.$schedule['military_time']['start_time'].'" data-end="'.$schedule['military_time']['end_time'].'" data-content="subject.php?i='.$schedule['instructor_name'].'&d='.$schedule['description'].'&r='.$schedule['room_name'].'" data-event="'.$schedule['data-event'].'" href="#0">
                                            <em class="cd-schedule__name">'.$schedule['code'].'</em>
                                          </a>
                                        </li>';
                                        }
                                echo '</ul>
                                    </li>';
                                }
                                
                           ?>
                       </ul>
                     </div>
                   
                     <div class="cd-schedule-modal">
                       <header class="cd-schedule-modal__header">
                         <div class="cd-schedule-modal__content">
                           <span class="cd-schedule-modal__date"></span>
                           <h3 class="cd-schedule-modal__name"></h3>
                         </div>
                   
                         <div class="cd-schedule-modal__header-bg"></div>
                       </header>
                   
                       <div class="cd-schedule-modal__body">
                         <div class="cd-schedule-modal__event-info"></div>
                         <div class="cd-schedule-modal__body-bg"></div>
                       </div>
                   
                       <a href="#0" class="cd-schedule-modal__close text-replace">Close</a>
                     </div>
                   
                     <div class="cd-schedule__cover-layer"></div>
                   </div> <!-- .cd-schedule -->

             </div>
           </div>

        </div>
       <!-- End Calendar Card -->
</section>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>