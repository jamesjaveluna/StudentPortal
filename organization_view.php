<?php

$page_title = "Organization";
$department = $_GET['page'];

ob_start();

session_start();

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
        <div class="col-xl-4">
          
          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="../assets/img/SCO.png" alt="Profile" class="rounded-circle">
              <center><h2>Supreme Student Council</h2></center>
              <h3>@ssc</h3>
              <div class="social-links mt-2">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

          <!-- Budget Report -->
            <div class="card">
              <div class="card-body pb-0">
                <h5 class="card-title">Budget Report <span>| First Semester</span></h5>

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
            </div>
          <!-- End Budget Report -->

        </div>

        <div class="col-xl-8">

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
                  <h5 class="card-title">About</h5>
                  <p class="small fst-italic">The Supreme Student Council of St. Cecilia's College - Cebu, Inc. is a student-led organization representing and serving the student body. They organize events, promote student welfare, and ensure transparency in fund management.</p>

                  <h5 class="card-title">Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Administration</div>
                    <div class="col-lg-9 col-md-8">14th</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">School Year</div>
                    <div class="col-lg-9 col-md-8">2022-2023</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Funds</div>
                    <div class="col-lg-9 col-md-8">&#8369; 3,000.00</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-12 text-center mt-2 mb-1">
                        <button type="button" class="btn btn-danger"><i class="bx bx-notepad me-1"></i> Report Council</button>
                    </div>
                  </div>

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
                        <img src="../assets/img/profile/brigildo.jpg" alt="Member 2">
                        <h5 class="mt-2">Ericson Brigildo</h5>
                        <p><span class="badge bg-warning text-dark">SSC Consultant</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/kim.jpg" alt="Member 2">
                        <h5 class="mt-2">Kim Athena A. Roslinda</h5>
                        <p><span class="badge bg-warning text-dark">SSC Moderator</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/james-1.jpg" alt="Member 2">
                        <h5 class="mt-2"><a href="../../profile/41" class="text-danger">James Javeluna</a></h5>
                        <p><span class="badge bg-warning text-dark">SSC President</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/chuchie.jpg" alt="Member 2">
                        <h5 class="mt-2">Chuchie Mae Aparecio</h5>
                        <p><span class="badge bg-warning text-dark">SSC Vice President</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/jecah.jpg" alt="Member 2">
                        <h5 class="mt-2">Jecah Sotes</h5>
                        <p><span class="badge bg-warning text-dark">SSC Secretary</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/ynna.jpg" alt="Member 2">
                        <h5 class="mt-2">Ynna Lyn A. Naveo</h5>
                        <p><span class="badge bg-warning text-dark">SSC Treasurer</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/leah.jpg" alt="Member 2">
                        <h5 class="mt-2">Leah Mae Taburada</h5>
                        <p><span class="badge bg-warning text-dark">SSC Auditor</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/cyril.jpg" alt="Member 2">
                        <h5 class="mt-2">Djcyril Echavez</h5>
                        <p><span class="badge bg-warning text-dark">SSC MMO</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/vince.jpg" alt="Member 2">
                        <h5 class="mt-2"><a href="../../profile/63" class="text-danger">Vince Michael Bacarisas</a></h5>
                        <p><span class="badge bg-warning text-dark">SSC MMO</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/haide.jpg" alt="Member 2">
                        <h5 class="mt-2">Haide P. Loquinario</h5>
                        <p><span class="badge bg-warning text-dark">SSC Peace Officer</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/christopher.jpg" alt="Member 2">
                        <h5 class="mt-2">Christopher A. Aquino</h5>
                        <p><span class="badge bg-warning text-dark">SSC Peace Officer</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/jiezel.jpg" alt="Member 2">
                        <h5 class="mt-2"><a href="../../profile/47" class="text-danger">Jiezel Ann B. Oroc</a></h5>
                        <p><span class="badge bg-warning text-dark">SSC Activity Coordinator</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/gilbert.jpg" alt="Member 2">
                        <h5 class="mt-2">Gilbert Secuya</h5>
                        <p><span class="badge bg-warning text-dark">SSC Activity Coordinator</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/adel.jpg" alt="Member 2">
                        <h5 class="mt-2">Adelyn Bayawa</h5>
                        <p><span class="badge bg-warning text-dark">SSC BSCRIM Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/aida.jpg" alt="Member 2">
                        <h5 class="mt-2"><a href="../../profile/76" class="text-danger">Aida Sacil</a></h5>
                        <p><span class="badge bg-warning text-dark">SSC BSED Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/swine.jpg" alt="Member 2">
                        <h5 class="mt-2">Swine Ladylyn Bittrich</h5>
                        <p><span class="badge bg-warning text-dark">SSC BEED Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/mitchelle.jpg" alt="Member 2">
                        <h5 class="mt-2">Mitchelle P. Claro</h5>
                        <p><span class="badge bg-warning text-dark">SSC BSTM Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/ruvilyn.jpg" alt="Member 2">
                        <h5 class="mt-2">Ruvilyn V. Herbias</h5>
                        <p><span class="badge bg-warning text-dark">SSC BSBA Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/leann.jpg" alt="Member 2">
                        <h5 class="mt-2">Leann Pacana</h5>
                        <p><span class="badge bg-warning text-dark">SSC BSHM Rep</span></p>
                      </div>
                      <div class="member">
                        <img src="../assets/img/profile/berna.jpg" alt="Member 2">
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