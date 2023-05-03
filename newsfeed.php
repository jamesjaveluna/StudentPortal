<?php

$page_title = "Newsfeed";
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
  <h1>Newsfeed</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="./../">Home</a></li>
      <li class="breadcrumb-item active">Newsfeed</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
   <div class="row">

     <!-- Left side columns -->
     <div class="col-lg-8">
       <div class="row">

         <!-- Newsfeed Card -->
         <div class="col-xxl-12 col-xl-12">
            <div class="card newsfeed">
                   <div class="filter">
                     <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                     <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                       <li class="dropdown-header text-start">
                         <h6>Action:</h6>
                       </li>

                       <li><a class="dropdown-item" href="#">Report</a></li>
                     </ul>
                   </div>

                   <div class="card-body">
                       <div class="author">
                           <img src="assets/img/post/logo/SCO.png" style="max-height: 46px;" alt="Profile" class="rounded-circle">
                           <div class="info">
                             <h5>Supreme Student Council</h5>
                             <p>College Department</p>
                           </div>
                           <div class="post-meta">
                             <span>2 hours ago</span>
                           </div>
                       </div>
                     <p><b>HAPPY LABOR DAY!</b><br><br>
                        Today, we come together to celebrate and honor hardworking individuals across the globe. On this special occasion, we recognize the dedication, perseverance, and contributions of workers from all walks of life.<br><br>
                        Labor Day serves as a reminder of the immense value and impact of labor in shaping our communities and driving progress. It's a day to appreciate the efforts of everyone who works tirelessly to make a difference, regardless of their profession or industry.<br><br>
                        Whether you're a teacher, healthcare professional, office worker, entrepreneur, construction worker, artist, or part of any other profession, your hard work and dedication are vital to our society's growth and development.<br><br>
                        Today, we express our gratitude to every individual who contributes their skills, passion, and time to their respective fields. Your unwavering commitment plays a crucial role in building a better future for all.<br><br>
                        On this Labor Day, let us pause and reflect on the achievements and challenges faced by workers worldwide. It's a day to honor the collective spirit of labor and acknowledge the immense impact each worker makes.<br><br>
                        To all the hardworking individuals out there, we applaud your dedication, perseverance, and the positive influence you bring to the world. Happy Labor Day!</p>
                     <img src="../assets/img/post/344267143_1178168036086823_257712452172620523_n.jpg" class="card-img-bottom" alt="...">
                   </div>

                   <div class="card-footer">
                    <div class="d-grid gap-2 mt-3">
                      <button class="btn btn-primary" type="button"><i class="bi bi-facebook me-1"></i> View Post</button>
                    </div>
                   </div>
            </div>
         </div>

         <div class="col-xxl-12 col-xl-12">
            <div class="card newsfeed">
                   <div class="filter">
                     <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                     <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                       <li class="dropdown-header text-start">
                         <h6>Action:</h6>
                       </li>

                       <li><a class="dropdown-item" href="#">Report</a></li>
                     </ul>
                   </div>

                   <div class="card-body">
                       <div class="author">
                           <img src="assets/img/post/logo/SCC.gif" style="max-height: 46px;" alt="Profile" class="rounded-circle">
                           <div class="info">
                             <h5>St. Cecilia's College - Cebu, Inc.</h5>
                             <p>School</p>
                           </div>
                           <div class="post-meta">
                             <span>2 hours ago</span>
                           </div>
                       </div>
                     <p>"The story of our country's progress is written by the hands of our workers"<br><br>
                        Today, we salute you and recognize your contributions to our society. Your dedication and commitment are indeed the backbone of our nation, and we are grateful for everything you do!<br><br>
                        MABUHAY ANG MGA MANGGAGAWANG PILIPINO!</p>
                     <img src="../assets/img/post/344391327_781245487010895_3617784451846556249_n.jpg" class="card-img-bottom" alt="...">
                   </div>

                   <div class="card-footer">
                    <div class="d-grid gap-2 mt-3">
                      <button class="btn btn-primary" type="button"><i class="bi bi-facebook me-1"></i> View Post</button>
                    </div>
                   </div>
            </div>
         </div>

         <div class="col-xxl-12 col-xl-12">
            <div class="card newsfeed">
                   <div class="filter">
                     <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                     <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                       <li class="dropdown-header text-start">
                         <h6>Action:</h6>
                       </li>

                       <li><a class="dropdown-item" href="#">Report</a></li>
                     </ul>
                   </div>

                   <div class="card-body">
                       <div class="author">
                           <img src="assets/img/post/logo/itech.jpg" style="max-height: 46px;" alt="Profile" class="rounded-circle">
                           <div class="info">
                             <h5>SCC-ITech Society</h5>
                             <p>BSIT Department</p>
                           </div>
                           <div class="post-meta">
                             <span>2 hours ago</span>
                           </div>
                       </div>
                     <p>Someone got lost yesterday on our IT DAYS 2023 !<br><br>
                        Updates for photos and champions of the successful event yesterday will soon be posted. Stay tuned.<br><br>
                        Simplifying Complexity with Technology</p>
                     <img src="../assets/img/post/344352861_795691718790712_5309463931876884503_n.jpg" class="card-img-bottom" alt="...">
                   </div>

                   <div class="card-footer">
                    <div class="d-grid gap-2 mt-3">
                      <button class="btn btn-primary" type="button"><i class="bi bi-facebook me-1"></i> View Post</button>
                    </div>
                   </div>
            </div>
         </div>
         <!-- End Newsfeed Card -->

         
         

       </div>
     </div><!-- End Left side columns -->

     <!-- Right side columns -->
     <div class="col-lg-4">

       <!-- Recent Activity -->
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
       </div><!-- End Recent Activity -->

       

     </div><!-- End Right side columns -->

   </div>
</section>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>