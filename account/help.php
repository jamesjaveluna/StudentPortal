<?php

session_start();

if(isset($_SESSION['user_id']) && isset($_SESSION['token'])){
  header('Location: ../');
}

$page_title = "Login";

ob_start();

require_once './../class/config/config.php';

?>

<div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="../" class="logo d-flex align-items-center w-auto">
                  <img src="../assets/img/SCC.png" alt="">
                  <span class="d-none d-lg-block text-danger">Student Portal</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Contact Us</h5>
                    <p class="text-center small">Technical assistance for student account issues and inquiries.</p>
                  </div>

                  <div id="response">
                    
                  </div>
                  <form id="login" class="row g-3 needs-validation" novalidate>

                      <div class="col-12">
                          <label for="studentID" class="form-label">Full Name <span class="small fst-italic"></span></label>
                          <input type="text" name="studentID" class="form-control" id="studentID" value="" required>
                          <div id="studentID-error" class="invalid-feedback">Please enter a valid Student ID.</div>
                      </div>

                      <div class="col-12">
                          <label for="studentID" class="form-label">Email</label>
                          <input type="email" name="email" class="form-control" id="email" required>
                          <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                      </div>

                      <div class="col-12">
                        <label for="studentID" class="form-label">Student ID <span class="small fst-italic">(Optional)</span></label>
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">SCC-</span>
                          <input type="text" class="form-control">
                        </div>
                      </div>

                      <div class="col-12">
                          <label for="yourUsername" class="form-label">Subject</label>
                          <input type="email" name="email" class="form-control" id="email" required>
                          <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                      </div>

                      <div class="col-12">
                          <label for="studentID" class="form-label">Message</label>
                          <textarea class="form-control" name="message" rows="6" required="" spellcheck="false"></textarea>
                      </div>

                      

                    <?php
                    if(RECAPTCHA_ENABLED === true){
                        echo '<div class="col-12">
                          <label for="yourPassword" class="form-label">Recaptcha</label>
                          <div id="g-recaptcha" class="g-recaptcha" data-sitekey="'.RECAPTCHA_SECRET_KEY_HTML.'"></div>
                        </div>';
                    } else {
                        echo '
                          <div id="g-recaptcha" class="g-recaptcha hidden" data-sitekey="NULL"></div>';
                    }

                    ?>

                    <div class="col-12 mb-2">
                      <button class="btn btn-primary w-100" type="submit">Send Message</button>
                    </div>
                    
                    <?php 
                    //    echo '<div class="col-12">
                    //  <p class="small mb-0 text-center">- OR -</p>
                    //</div>
                    //<div class="col-12 text-center">
                    //  <button type="button" class="btn btn-primary w-100 mb-2"><i class="bi bi-facebook me-1"></i> Sign in with Facebook</button>
                    //  <button type="button" class="btn btn-danger w-100"><i class="bi bi-google me-1"></i> Sign in with Google</button>
                    //</div>';

                    ?>
                    <div class="col-12 mb-2">
                        <a href="login">
                          <button type="button" class="btn btn-secondary w-100"><i class="bi bi-arrow-left-short me-1"></i>Back to Login</button>
                        </a>
                    </div>

                  </form>

                </div>
              </div>


            </div>
          </div>
        </div>

      </section>

</div>


<?php
$content = ob_get_contents();

ob_end_clean();

include('./../template/account_default.php');
?>