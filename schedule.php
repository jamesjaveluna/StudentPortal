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
                  <h5 class="card-title"><span>Status: <b>Irregular Student</b></span></h5>
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
                         <li><span>13:00</span></li>
                         <li><span>13:30</span></li>
                         <li><span>14:00</span></li>
                         <li><span>14:30</span></li>
                         <li><span>15:00</span></li>
                         <li><span>15:30</span></li>
                         <li><span>16:00</span></li>
                         <li><span>16:30</span></li>
                         <li><span>17:00</span></li>
                         <li><span>17:30</span></li>
                         <li><span>18:00</span></li>
                         <li><span>18:30</span></li>
                         <li><span>19:00</span></li>
                         <li><span>19:30</span></li>
                         <li><span>20:00</span></li>
                         <li><span>20:30</span></li>
                       </ul>
                     </div> <!-- .cd-schedule__timeline -->
                   
                     <div class="cd-schedule__events">
                       <ul>
                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Monday</span></div>
                   
                           <ul>
                             <li class="cd-schedule__event">
                               <a data-start="10:00" data-end="12:30" data-content="event-abs-circuit" data-event="event-1" href="#0">
                                 <em class="cd-schedule__name">SIA312</em>
                               </a>
                             </li>
                   
                             <li class="cd-schedule__event">
                               <a data-start="15:30" data-end="17:00" data-content="event-rowing-workout" data-event="event-2" href="#0">
                                 <em class="cd-schedule__name">MS309</em>
                               </a>
                             </li>
                   
                           </ul>
                         </li>
                   
                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Tuesday</span></div>
                   
                           <ul>
                             <li class="cd-schedule__event">
                               <a data-start="13:00" data-end="14:30"  data-content="event-rowing-workout" data-event="event-3" href="#0">
                                 <em class="cd-schedule__name">SIA312</em>
                               </a>
                             </li>
                   
                   
                             <li class="cd-schedule__event">
                               <a data-start="15:30" data-end="17:00" data-content="event-abs-circuit" data-event="event-4" href="#0">
                                 <em class="cd-schedule__name">IAS311</em>
                               </a>
                             </li>
                   
                           </ul>
                         </li>
                   
                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Wednesday</span></div>
                   
                           <ul>
                             <li class="cd-schedule__event">
                               <a data-start="10:00" data-end="12:30" data-content="event-restorative-yoga" data-event="event-1" href="#0">
                                 <em class="cd-schedule__name">GE9</em>
                               </a>
                             </li>
                   
                             <li class="cd-schedule__event">
                               <a data-start="15:30" data-end="17:00" data-content="event-rowing-workout" data-event="event-2" href="#0">
                                 <em class="cd-schedule__name">MS309</em>
                               </a>
                             </li>
                           </ul>
                         </li>
                   
                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Thursday</span></div>
                   
                           <ul>
                             <li class="cd-schedule__event">
                               <a data-start="13:00" data-end="14:30"  data-content="event-rowing-workout" data-event="event-3" href="#0">
                                 <em class="cd-schedule__name">SIA312</em>
                               </a>
                             </li>
                   
                             <li class="cd-schedule__event">
                               <a data-start="15:30" data-end="17:00"  data-content="event-rowing-workout" data-event="event-4" href="#0">
                                 <em class="cd-schedule__name">IAS311</em>
                               </a>
                             </li>

                             <li class="cd-schedule__event">
                               <a data-start="17:00" data-end="20:00"  data-content="event-rowing-workout" data-event="event-5" href="#0">
                                 <em class="cd-schedule__name">PT206</em>
                               </a>
                             </li>
                   
                   
                           </ul>
                         </li>
                   
                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Friday</span></div>
                   
                           <ul>
                             <li class="cd-schedule__event">
                               <a data-start="10:00" data-end="12:30"  data-content="event-rowing-workout" data-event="event-6" href="#0">
                                 <em class="cd-schedule__name">WS310</em>
                               </a>
                             </li>
                   
                             <li class="cd-schedule__event">
                               <a data-start="12:30" data-end="15:30" data-content="event-abs-circuit" data-event="event-6" href="#0">
                                 <em class="cd-schedule__name">WS310</em>
                               </a>
                             </li>
                   
                             <li class="cd-schedule__event">
                               <a data-start="15:30" data-end="17:00"  data-content="event-yoga-1" data-event="event-4" href="#0">
                                 <em class="cd-schedule__name">IAS310</em>
                               </a>
                             </li>

                             <li class="cd-schedule__event">
                               <a data-start="17:00" data-end="19:00"  data-content="event-rowing-workout" data-event="event-7" href="#0">
                                 <em class="cd-schedule__name">NATSCI</em>
                               </a>
                             </li>
                           </ul>
                         </li>

                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Saturday</span></div>
                   
                           <ul>
                             <li class="cd-schedule__event">
                               <a data-start="07:30" data-end="12:30"  data-content="event-rowing-workout" data-event="event-8" href="#0">
                                 <em class="cd-schedule__name">SAD</em>
                               </a>
                             </li>
                   
                             
                           </ul>
                         </li>

                         <li class="cd-schedule__group">
                           <div class="cd-schedule__top-info"><span>Sunday</span></div>
                   
                           <ul>
                             
                             
                           </ul>
                         </li>
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