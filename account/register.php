<?php

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
                  <span class="d-none d-lg-block text-danger">Cecilian Portal</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                    <p class="text-center small">Enter your personal details to create account</p>
                  </div>

                  <div id="response">
                    
                  </div>
                  <form id="register" class="row g-3 needs-validation" novalidate>
                    <div class="col-12">
                      <label for="studentID" class="form-label">Student ID</label>
                      <input type="text" name="studentID" class="form-control" id="studentID" value="SCC-" required>
                      <div id="studentID-error" class="invalid-feedback">Please enter a valid Student ID.</div>
                    </div>

                    <div class="col-12">
                      <label for="username" class="form-label">Username</label>
                      <input type="text" name="username" class="form-control" id="username" required>
                      <div id="username-error" class="invalid-feedback">Please enter a valid username.</div>
                    </div>

                    <div class="col-12">
                      <label for="email" class="form-label">Email Address</label>
                      <input type="email" name="email" class="form-control" id="email" required>
                      <div id="email-error" class="invalid-feedback">Please enter a valid Email adddress.</div>
                    </div>

                    <div class="col-12">
                      <label for="birthdate" class="form-label">Birthdate</label>
                      <input type="date" name="birthdate" class="form-control" id="birthdate" required>
                      <div id="birthdate-error" class="invalid-feedback">Please enter a valid Birthdate!</div>
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

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                        <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
                        <div class="invalid-feedback">You must agree before submitting.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Create Account</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Already have an account? <a href="login.php">Log in</a></p>
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