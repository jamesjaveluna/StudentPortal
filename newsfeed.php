<?php

$page_title = "Newsfeed";

ob_start();

session_start();

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
                           <img src="assets/img/profile/james.jpg" style="max-height: 46px;" alt="Profile" class="rounded-circle">
                           <div class="info">
                             <h5>James Javeluna</h5>
                             <p>BSIT 3A</p>
                           </div>
                           <div class="post-meta">
                             <span>2 hours ago</span>
                           </div>
                       </div>
                     <p>This is a sample newsfeed in Portal.</p>
                     <img src="../assets/img/post/test.jpg" class="card-img-bottom" alt="...">
                   </div>

                   <div class="card-footer">
                    
                       <button type="button" class="btn btn-danger rounded-pill">
                          <i class="bi bi-heart-fill text-danger"></i>
                          <span class="badge text-danger">Loved</span>
                          <span class="badge bg text-black-50">4</span>
                       </button>
                       <button type="button" class="btn btn-danger rounded-pill">
                          <i class="bi bi-chat-square-dots text-danger"></i>
                          <span class="badge text-black-50">Comments</span>
                          <span class="badge bg-light text-black-50">90+</span>
                       </button>
                   </div>
            </div>
         </div>
         <!-- End Newsfeed Card -->

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
                           <img src="assets/img/profile/james.jpg" style="max-height: 46px;" alt="Profile" class="rounded-circle">
                           <div class="info">
                             <h5>John Doe</h5>
                             <p>Position</p>
                           </div>
                           <div class="post-meta">
                             <span>2 hours ago</span>
                           </div>
                       </div>
                     This is a sample post design. Yuh yuh yuh.
                     <img src="../assets/img/card.jpg" class="card-img-bottom" alt="...">
                   </div>

                   <div class="card-footer">
                    
                       <button type="button" class="btn btn-danger rounded-pill">
                          <i class="bi bi-heart-fill text-danger"></i>
                          <span class="badge text-danger">Loved</span>
                          <span class="badge bg text-black-50">4</span>
                       </button>
                       <button type="button" class="btn btn-danger rounded-pill">
                          <i class="bi bi-chat-square-dots text-danger"></i>
                          <span class="badge text-black-50">Comments</span>
                          <span class="badge bg-light text-black-50">90+</span>
                       </button>
                   </div>
               </div>
            </div>

       </div>
     </div><!-- End Left side columns -->

     <!-- Right side columns -->
     <div class="col-lg-4">

       <!-- Recent Activity -->
       <div class="card">
         <div class="filter">
           <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
           <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
             <li class="dropdown-header text-start">
               <h6>Filter</h6>
             </li>

             <li><a class="dropdown-item" href="#">Today</a></li>
             <li><a class="dropdown-item" href="#">This Month</a></li>
             <li><a class="dropdown-item" href="#">This Year</a></li>
           </ul>
         </div>

         <div class="card-body">
           <h5 class="card-title">Recent Activity <span>| Today</span></h5>

           <div class="activity">

             <div class="activity-item d-flex">
               <div class="activite-label">32 min</div>
               <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
               <div class="activity-content">
                 <a href="#" class="fw-bold text-dark">Jiezel Ann Javeluna</a> reacted on your post.
               </div>
             </div><!-- End activity item-->

             <div class="activity-item d-flex">
               <div class="activite-label">56 min</div>
               <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
               <div class="activity-content">
                 <a href="#" class="fw-bold text-dark">Jiezel Ann Javeluna</a> commented on your post.
               </div>
             </div><!-- End activity item-->

             <div class="activity-item d-flex">
               <div class="activite-label">1 hr</div>
               <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
               <div class="activity-content">
                 <a href="#" class="fw-bold text-dark">James Javeluna</a> edited his post.
               </div>
             </div><!-- End activity item-->

             <div class="activity-item d-flex">
               <div class="activite-label">2 hrs</div>
               <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
               <div class="activity-content">
                 <a href="#" class="fw-bold text-dark">Jiezel Ann Javeluna</a> reported your post.
               </div>
             </div><!-- End activity item-->

             
           </div>

         </div>
       </div><!-- End Recent Activity -->

       <!-- Budget Report -->
       <div class="card">
         <div class="filter">
           <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
           <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
             <li class="dropdown-header text-start">
               <h6>Filter</h6>
             </li>

             <li><a class="dropdown-item" href="#">Today</a></li>
             <li><a class="dropdown-item" href="#">This Month</a></li>
             <li><a class="dropdown-item" href="#">This Year</a></li>
           </ul>
         </div>

         <div class="card-body pb-0">
           <h5 class="card-title">Budget Report <span>| This Month</span></h5>

           <div id="budgetChart" style="min-height: 400px;" class="echart"></div>

           <script>
             document.addEventListener("DOMContentLoaded", () => {
               var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                 legend: {
                   data: ['Allocated Budget', 'Actual Spending']
                 },
                 radar: {
                   // shape: 'circle',
                   indicator: [{
                       name: 'Sales',
                       max: 6500
                     },
                     {
                       name: 'Administration',
                       max: 16000
                     },
                     {
                       name: 'Information Technology',
                       max: 30000
                     },
                     {
                       name: 'Customer Support',
                       max: 38000
                     },
                     {
                       name: 'Development',
                       max: 52000
                     },
                     {
                       name: 'Marketing',
                       max: 25000
                     }
                   ]
                 },
                 series: [{
                   name: 'Budget vs spending',
                   type: 'radar',
                   data: [{
                       value: [4200, 3000, 20000, 35000, 50000, 18000],
                       name: 'Allocated Budget'
                     },
                     {
                       value: [5000, 14000, 28000, 26000, 42000, 21000],
                       name: 'Actual Spending'
                     }
                   ]
                 }]
               });
             });
           </script>

         </div>
       </div><!-- End Budget Report -->

       <!-- Website Traffic -->
       <div class="card">
         <div class="filter">
           <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
           <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
             <li class="dropdown-header text-start">
               <h6>Filter</h6>
             </li>

             <li><a class="dropdown-item" href="#">Today</a></li>
             <li><a class="dropdown-item" href="#">This Month</a></li>
             <li><a class="dropdown-item" href="#">This Year</a></li>
           </ul>
         </div>

         <div class="card-body pb-0">
           <h5 class="card-title">Website Traffic <span>| Today</span></h5>

           <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

           <script>
             document.addEventListener("DOMContentLoaded", () => {
               echarts.init(document.querySelector("#trafficChart")).setOption({
                 tooltip: {
                   trigger: 'item'
                 },
                 legend: {
                   top: '5%',
                   left: 'center'
                 },
                 series: [{
                   name: 'Access From',
                   type: 'pie',
                   radius: ['40%', '70%'],
                   avoidLabelOverlap: false,
                   label: {
                     show: false,
                     position: 'center'
                   },
                   emphasis: {
                     label: {
                       show: true,
                       fontSize: '18',
                       fontWeight: 'bold'
                     }
                   },
                   labelLine: {
                     show: false
                   },
                   data: [{
                       value: 1048,
                       name: 'Search Engine'
                     },
                     {
                       value: 735,
                       name: 'Direct'
                     },
                     {
                       value: 580,
                       name: 'Email'
                     },
                     {
                       value: 484,
                       name: 'Union Ads'
                     },
                     {
                       value: 300,
                       name: 'Video Ads'
                     }
                   ]
                 }]
               });
             });
           </script>

         </div>
       </div><!-- End Website Traffic -->

       <!-- News & Updates Traffic -->
       <div class="card">
         <div class="filter">
           <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
           <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
             <li class="dropdown-header text-start">
               <h6>Filter</h6>
             </li>

             <li><a class="dropdown-item" href="#">Today</a></li>
             <li><a class="dropdown-item" href="#">This Month</a></li>
             <li><a class="dropdown-item" href="#">This Year</a></li>
           </ul>
         </div>

         <div class="card-body pb-0">
           <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

           <div class="news">
             <div class="post-item clearfix">
               <img src="assets/img/news-1.jpg" alt="">
               <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
               <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
             </div>

             <div class="post-item clearfix">
               <img src="assets/img/news-2.jpg" alt="">
               <h4><a href="#">Quidem autem et impedit</a></h4>
               <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
             </div>

             <div class="post-item clearfix">
               <img src="assets/img/news-3.jpg" alt="">
               <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
               <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
             </div>

             <div class="post-item clearfix">
               <img src="assets/img/news-4.jpg" alt="">
               <h4><a href="#">Laborum corporis quo dara net para</a></h4>
               <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...</p>
             </div>

             <div class="post-item clearfix">
               <img src="assets/img/news-5.jpg" alt="">
               <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
               <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos eius...</p>
             </div>

           </div><!-- End sidebar recent posts-->

         </div>
       </div><!-- End News & Updates -->

     </div><!-- End Right side columns -->

   </div>
</section>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>