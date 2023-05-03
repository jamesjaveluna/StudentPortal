<?php

session_start();

if(isset($_SESSION['user_id']) && isset($_SESSION['token'])){
  header('Location: ../');
}

$page_title = "Verify";
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
                    <h5 class="card-title text-center pb-0 fs-4">Create a Password</h5>
                    <p class="text-center small">Enter a secure password to your account.</p>
                  </div>

                  <div id="response">
                    
                  </div>

                  <form id="verify" class="row g-3 needs-validation" novalidate>
                    <div class="col-12">
                        <label for="code" class="form-label">Verification Code</label>
                        <input type="text" name="code" class="form-control" id="code" value="<?php echo $code; ?>" disabled>
                    </div>

                    <div class="col-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                        <div class="invalid-feedback">Please enter a valid password.</div>
                    </div>

                    <div class="col-12 mb-3">
                      <label for="repassword" class="form-label">Re-type Password</label>
                      <input type="password" name="repassword" class="form-control" id="repassword" required>
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
                      <button class="btn btn-primary w-100" type="submit">Verify Account</button>
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