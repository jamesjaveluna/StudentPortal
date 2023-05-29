<?php

$page_title = "Calendar of Activity";
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
  <h1>Need Help?</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Cecilian Portal</li>
      <li class="breadcrumb-item active">Help</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

 <section class="section faq">
   <div class="col-lg-12">
    <div class="card">
         <div class="card-body">
           <h5 class="card-title">Getting Started</h5>

           <div class="accordion accordion-flush" id="faq-group-2">

             <div class="accordion-item">
               <h2 class="accordion-header">
                 <button class="accordion-button collapsed" data-bs-target="#faqsTwo-1" type="button" data-bs-toggle="collapse">
                   How do I create an account?
                 </button>
               </h2>
               <div id="faqsTwo-1" class="accordion-collapse collapse" data-bs-parent="#faq-group-2">
                 <div class="accordion-body">
                   Ut quasi odit odio totam accusamus vero eius. Nostrum asperiores voluptatem eos nulla ab dolores est asperiores iure. Quo est quis praesentium aut maiores. Corrupti sed aut expedita fugit vero dolorem. Nemo rerum sapiente. A quaerat dignissimos.
                 </div>
               </div>
             </div>

             <div class="accordion-item">
               <h2 class="accordion-header">
                 <button class="accordion-button collapsed" data-bs-target="#faqsTwo-5" type="button" data-bs-toggle="collapse">
                   I'm receiving an error message stating "Student ID and/or Birthdate cannot be found" during registration. What should I do?
                 </button>
               </h2>
               <div id="faqsTwo-5" class="accordion-collapse collapse" data-bs-parent="#faq-group-2">
                 <div class="accordion-body">
                   Aut necessitatibus maxime quis dolor et. Nihil laboriosam molestiae qui molestias placeat corrupti non quo accusamus. Nemo qui quis harum enim sed. Aliquam molestias pariatur delectus voluptas quidem qui rerum id quisquam. Perspiciatis voluptatem voluptatem eos. Vel aut minus labore at rerum eos.
                 </div>
               </div>
             </div>

             <div class="accordion-item">
               <h2 class="accordion-header">
                 <button class="accordion-button collapsed" data-bs-target="#faqsTwo-2" type="button" data-bs-toggle="collapse">
                   What should I do if I can't register an account?
                 </button>
               </h2>
               <div id="faqsTwo-2" class="accordion-collapse collapse" data-bs-parent="#faq-group-2">
                 <div class="accordion-body">
                   In minus quia impedit est quas deserunt deserunt et. Nulla non quo dolores minima fugiat aut saepe aut inventore. Qui nesciunt odio officia beatae iusto sed voluptatem possimus quas. Officia vitae sit voluptatem nostrum a.
                 </div>
               </div>
             </div>

             <div class="accordion-item">
               <h2 class="accordion-header">
                 <button class="accordion-button collapsed" data-bs-target="#faqsTwo-3" type="button" data-bs-toggle="collapse">
                   What should I do if I didn't receive my verification code?
                 </button>
               </h2>
               <div id="faqsTwo-3" class="accordion-collapse collapse" data-bs-parent="#faq-group-2">
                 <div class="accordion-body">
                   Voluptates magni amet enim perspiciatis atque excepturi itaque est. Sit beatae animi incidunt eum repellat sequi ea saepe inventore. Id et vel et et. Nesciunt itaque corrupti quia ducimus. Consequatur maiores voluptatum fuga quod ut non fuga.
                 </div>
               </div>
             </div>

             <div class="accordion-item">
               <h2 class="accordion-header">
                 <button class="accordion-button collapsed" data-bs-target="#faqsTwo-4" type="button" data-bs-toggle="collapse">
                   I forgot my password, how can I reset it?
                 </button>
               </h2>
               <div id="faqsTwo-4" class="accordion-collapse collapse" data-bs-parent="#faq-group-2">
                 <div class="accordion-body">
                   Numquam ut reiciendis aliquid. Quia veritatis quasi ipsam sed quo ut eligendi et non. Doloremque sed voluptatem at in voluptas aliquid dolorum.
                 </div>
               </div>
             </div>

           </div>

         </div>
       </div>
   </div>
   <div class="row">
     

     <div class="col-lg-6">
     <!-- Account Issues -->
     <div class="card">
            <div class="card-body">
              <h5 class="card-title">Account Settings</h5>
              <div class="accordion accordion-flush" id="topic-group-1">

             <div class="accordion-item">
               <h2 class="accordion-header">
                 <button class="accordion-button collapsed" data-bs-target="#topicOne-1" type="button" data-bs-toggle="collapse">
                   How to change profile visibility?
                 </button>
               </h2>
               <div id="topicOne-1" class="accordion-collapse collapse" data-bs-parent="#topic-group-1">
                 <div class="accordion-body">To change your profile visibility, please follow these steps:<br><br>
                 <ol class="list-group list-group-numbered list-group-flush">
                   <li class="list-group-item">Log in to your account and navigate to the Account Settings or Profile Settings section.</li>
                   <li class="list-group-item">Look for the option related to profile visibility or privacy settings.</li>
                   <li class="list-group-item">Adjust the settings according to your preferences to either make your profile visible to everyone or limit its visibility to specific individuals or groups.</li>
                   <li class="list-group-item">Save the changes.</li>
                 </ol><br>
                   Please note that while you can control the visibility of your profile for regular users, School Admins may still have access to view your profile information due to their administrative privileges. If you have concerns about specific individuals having access to your profile, we recommend reaching out to the platform administrators or support team for further assistance or clarification.
                 </div>
               </div>
             </div>

             <div class="accordion-item">
               <h2 class="accordion-header">
                 <button class="accordion-button collapsed" data-bs-target="#topicOne-2" type="button" data-bs-toggle="collapse">
                   How to control email notifications?
                 </button>
               </h2>
               <div id="topicOne-2" class="accordion-collapse collapse" data-bs-parent="#topic-group-1">
                 <div class="accordion-body">
                   In minus quia impedit est quas deserunt deserunt et. Nulla non quo dolores minima fugiat aut saepe aut inventore. Qui nesciunt odio officia beatae iusto sed voluptatem possimus quas. Officia vitae sit voluptatem nostrum a.
                 </div>
               </div>
             </div>

           </div>
            </div>
     </div>
      <!-- End Account Issues -->

      

     </div>

     <div class="col-lg-6">

       <!-- Account Issues -->
     <div class="card">
            <div class="card-body">
              <h5 class="card-title">Account Settings</h5>
              <div class="accordion accordion-flush" id="topic-group-2">

             <div class="accordion-item">
               <h2 class="accordion-header">
                 <button class="accordion-button collapsed" data-bs-target="#topicTwo-1" type="button" data-bs-toggle="collapse">
                   The schedule displayed on the platform does not match the schedule I received from the School Registrar. What should I do?
                 </button>
               </h2>
               <div id="topicTwo-1" class="accordion-collapse collapse" data-bs-parent="#topic-group-2">
                 <div class="accordion-body">If you notice any discrepancies between the schedule displayed on the platform and the schedule provided by the School Registrar, please ensure that you have the most up-to-date information from the Registrar. If the issue persists, we recommend contacting the Registrar's office to clarify any discrepancies or updates.</div>
               </div>
             </div>

             <div class="accordion-item">
               <h2 class="accordion-header">
                 <button class="accordion-button collapsed" data-bs-target="#topicTwo-2" type="button" data-bs-toggle="collapse">
                    Some schedule entries have a NULL room name. What does this mean?
                 </button>
               </h2>
               <div id="topicTwo-2" class="accordion-collapse collapse" data-bs-parent="#topic-group-2">
                 <div class="accordion-body">
                    A null room name indicates that the School Registrar has not assigned a specific room for the corresponding class. In such cases, the location of the class will be communicated separately by the instructor or the Registrar's office. Please stay tuned for further updates regarding the room assignment.
                 </div>
               </div>
             </div>

           </div>
            </div>
     </div>
      <!-- End Account Issues -->

     </div>

   </div>
 </section>

 

<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>