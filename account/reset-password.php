<?php

session_start();

if(isset($_SESSION['user_id']) && isset($_SESSION['token'])){
  header('Location: ../');
}

$page_title = "Forgot Password";
$code = isset($_GET['code']) ? $_GET['code'] : 'invalid_verification_code';

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
                    <h5 class="card-title text-center pb-0 fs-4">Forgot Password</h5>
                    <p class="text-center small">Enter email address to reset password.</p>
                  </div>

                  <div id="response">
                    
                  </div>

                  <form id="reset" class="row g-3 needs-validation" novalidate>

                    <div class="col-12">
                      <label for="email" class="form-label">Email Address</label>
                      <input type="email" name="email" class="form-control" id="email" required>
                      <div id="email-error" class="invalid-feedback">Please enter a valid Email adddress.</div>
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
                      <button class="btn btn-primary w-100" type="submit">Reset Password</button>
                    </div>

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