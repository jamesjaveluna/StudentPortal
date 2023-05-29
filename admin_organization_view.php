<?php

$page_title = "Organization";
$slug_target = $_GET['page'];

ob_start();

session_start();


// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}

require_once 'class/Admin.php';
$crud = new Admin();

$user_type = $_SESSION['user']['type'];
$user_panel = $_SESSION['user']['panel'];
$user_permission = isset(json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel']) ? json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel'] : null;


if($user_panel !== 'admin' && in_array('organization_view', $user_permission) && $user_permission === null){
   include 'unauthorized.php';
    exit();
}

$sanitizedInput = htmlspecialchars($slug_target, ENT_QUOTES, 'UTF-8');

$orgs_raw = json_decode($crud->getOrganization($sanitizedInput), true);

if($orgs_raw['code'] === 10000){
    $orgs_data = $orgs_raw['data'];
}

//var_dump($orgs_raw);

?>

<div class="pagetitle">
      <h1>Supreme Student Council</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../">Home</a></li>
          <li class="breadcrumb-item"><a href="../organization">Organization</a></li>
          <li class="breadcrumb-item active">Supreme Student Council</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">

        <div class="col-xl-12">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fund-transparency">Fund Transparency</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Members</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <form>
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Album Photo</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="./.././../../assets/img/<?php echo $orgs_data['cover']; ?>" style="width: auto;height: 170px;object-fit: cover;" class="card-img-top" alt="...">
                        <div class="pt-2">
                          <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Logo</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="./.././../../assets/img/<?php echo $orgs_data['photo']; ?>" style="width: auto;height: 150px;object-fit: cover;" class="card-img-top" alt="...">
                        <div class="pt-2">
                          <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Organization Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="fullName" type="text" class="form-control" id="fullName" value="<?php echo $orgs_data['name']; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                      <div class="col-md-8 col-lg-9">
                        <textarea name="about" class="form-control" id="about" style="height: 100px"><?php echo $orgs_data['description']; ?></textarea>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="company" class="col-md-4 col-lg-3 col-form-label">Enable Funds</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php echo $orgs_data['fundEnabled'] == 'true' ? 'checked=""' : ''; ?>>
                            <label class="form-check-label" for="flexSwitchCheckDefault">Enables the "Fund Transparency".</label>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="company" class="col-md-4 col-lg-3 col-form-label">Enable Join</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" disabled="">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Allows students to join in this organization (With approval).</label>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="sco@stcecilia.edu.ph">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="facebook" type="text" class="form-control" id="Facebook" value="<?php echo isset($orgs_data['facebook_link']) ? $orgs_data['facebook_link'] : 'https://facebook.com/#'; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="instagram" type="text" class="form-control" id="Instagram" value="<?php echo isset($orgs_data['instagram_link']) ? $orgs_data['instagram_link'] : 'https://instagram.com/#'; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">YouTube</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="linkedin" type="text" class="form-control" id="Linkedin" value="<?php echo isset($orgs_data['youtube_link']) ? $orgs_data['youtube_link'] : 'https://youtube.com/#'; ?>">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form>
                  

                </div>

                <!-- Fund Transparency -->
                <div class="tab-pane fade fund-transparency" id="fund-transparency">
                 <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Event Name</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Start Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>Honesty Pantry (Restock)</td>
                    <td><span class="badge bg-danger"><span class="me-2">&#8369;</span><strong class="text-end">-2,532</strong></span></td>
                    <td>2016-05-25</td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Honesty Pantry (Income)</td>
                    <td><span class="badge bg-success"><span class="me-2">&#8369;</span><strong class="text-end">+5,000</strong></span></td>
                    <td>2016-05-25</td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td>Praise and Worship</td>
                    <td><span class="badge bg-danger"><span class="me-2">&#8369;</span><strong class="text-end">-1,523</strong></span></td>
                    <td>2016-05-25</td>
                  </tr>
                  <tr>
                    <th scope="row">4</th>
                    <td>College Day (Merch)</td>
                    <td><span class="badge bg-success"><span class="me-2">&#8369;</span><strong class="text-end">+9,235</strong></span></td>
                    <td>2016-05-25</td>
                  </tr>
                  <tr>
                    <th scope="row">5</th>
                    <td>College Day</td>
                    <td><span class="badge bg-danger"><span class="me-2">&#8369;</span><strong class="text-end">-2,592</strong></span></td>
                    <td>2016-05-25</td>
                  </tr>
                  <tr>
                    <th scope="row">6</th>
                    <td>Leadership Seminar</td>
                    <td><span class="badge bg-danger"><span class="me-2">&#8369;</span><strong class="text-end">-1,234</strong></span></td>
                    <td>2016-05-25</td>
                  </tr>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->
                </div>
                <!-- End Fund transparency -->

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                    <div class="members-grid">
                      <div class="member">
                        <img src="./../../assets/img/profile/brigildo.jpg" alt="Member 2">
                        <h5 class="mt-2">Ericson Brigildo</h5>
                        <p><span class="badge bg-warning text-dark">SSC Consultant</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/kim.jpg" alt="Member 2">
                        <h5 class="mt-2">Kim Athena A. Roslinda</h5>
                        <p><span class="badge bg-warning text-dark">SSC Moderator</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/james-1.jpg" alt="Member 2">
                        <h5 class="mt-2"><a href="../../profile/41" class="text-danger">James Javeluna</a></h5>
                        <p><span class="badge bg-warning text-dark">SSC President</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/chuchie.jpg" alt="Member 2">
                        <h5 class="mt-2">Chuchie Mae Aparecio</h5>
                        <p><span class="badge bg-warning text-dark">SSC Vice President</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/jecah.jpg" alt="Member 2">
                        <h5 class="mt-2">Jecah Sotes</h5>
                        <p><span class="badge bg-warning text-dark">SSC Secretary</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/ynna.jpg" alt="Member 2">
                        <h5 class="mt-2">Ynna Lyn A. Naveo</h5>
                        <p><span class="badge bg-warning text-dark">SSC Treasurer</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/leah.jpg" alt="Member 2">
                        <h5 class="mt-2">Leah Mae Taburada</h5>
                        <p><span class="badge bg-warning text-dark">SSC Auditor</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/cyril.jpg" alt="Member 2">
                        <h5 class="mt-2">Djcyril Echavez</h5>
                        <p><span class="badge bg-warning text-dark">SSC MMO</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/vince.jpg" alt="Member 2">
                        <h5 class="mt-2"><a href="../../profile/63" class="text-danger">Vince Michael Bacarisas</a></h5>
                        <p><span class="badge bg-warning text-dark">SSC MMO</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/haide.jpg" alt="Member 2">
                        <h5 class="mt-2">Haide P. Loquinario</h5>
                        <p><span class="badge bg-warning text-dark">SSC Peace Officer</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/christopher.jpg" alt="Member 2">
                        <h5 class="mt-2">Christopher A. Aquino</h5>
                        <p><span class="badge bg-warning text-dark">SSC Peace Officer</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/jiezel.jpg" alt="Member 2">
                        <h5 class="mt-2"><a href="../../profile/47" class="text-danger">Jiezel Ann B. Oroc</a></h5>
                        <p><span class="badge bg-warning text-dark">SSC Activity Coordinator</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/gilbert.jpg" alt="Member 2">
                        <h5 class="mt-2">Gilbert Secuya</h5>
                        <p><span class="badge bg-warning text-dark">SSC Activity Coordinator</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/adel.jpg" alt="Member 2">
                        <h5 class="mt-2">Adelyn Bayawa</h5>
                        <p><span class="badge bg-warning text-dark">SSC BSCRIM Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/aida.jpg" alt="Member 2">
                        <h5 class="mt-2"><a href="../../profile/76" class="text-danger">Aida Sacil</a></h5>
                        <p><span class="badge bg-warning text-dark">SSC BSED Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/swine.jpg" alt="Member 2">
                        <h5 class="mt-2">Swine Ladylyn Bittrich</h5>
                        <p><span class="badge bg-warning text-dark">SSC BEED Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/mitchelle.jpg" alt="Member 2">
                        <h5 class="mt-2">Mitchelle P. Claro</h5>
                        <p><span class="badge bg-warning text-dark">SSC BSTM Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/ruvilyn.jpg" alt="Member 2">
                        <h5 class="mt-2">Ruvilyn V. Herbias</h5>
                        <p><span class="badge bg-warning text-dark">SSC BSBA Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/leann.jpg" alt="Member 2">
                        <h5 class="mt-2">Leann Pacana</h5>
                        <p><span class="badge bg-warning text-dark">SSC BSHM Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="./../../assets/img/profile/berna.jpg" alt="Member 2">
                        <h5 class="mt-2">Bernadette Requinto</h5>
                        <p><span class="badge bg-warning text-dark">SSC BSIT Rep</span></p>
                      </div>
                      <!-- Add more member divs as needed -->
                    </div>

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>