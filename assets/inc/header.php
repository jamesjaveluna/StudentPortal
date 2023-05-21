<header id="header" class="header fixed-top d-flex align-items-center">

    
    
    <div class="d-flex align-items-center justify-content-between">
      <a href="
      
    <?php 
    
    $panel = $_SESSION['user']['panel'];

    if($panel === 'admin'){
        echo '../students';
    } else {
        echo '../';
    }
    
    ?>" class="logo d-flex align-items-center">
        <img src="./../assets/img/SCC.png" alt="">
        <span class="d-none d-lg-block">Cecilian Portal</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">2</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 2 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>System</h4>
                <p>Your have verified your account successfully.</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>System</h4>
                <p>This portal is in beta mode, bugs are expected.</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->
        <?php
        /*
        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">1</span>
          </a><!-- End Messages Icon -->
          
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 1 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="../assets/img/profile/james.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>James Javeluna</h4>
                  <p>Hi, this is a test message.</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>
          
          </ul><!-- End Messages Dropdown Items -->
          
        </li><!-- End Messages Nav -->
        */
        ?>

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <?php
                $avatar = isset($_SESSION['user']['avatar']) ? $_SESSION['user']['avatar'] : 'default-profile.png';   

                echo '<img src="../assets/img/profile/'.$avatar.'" alt="Profile" class="rounded-circle">';
            ?>
          
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $utility->abbreviateName($_SESSION['user']['fname']); ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $_SESSION['user']['fname']; ?></h6>
              <span><?php echo $_SESSION['user']['Course']. ' '. $_SESSION['user']['Section']; ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?php echo '../profile/'.$_SESSION['user']['id']; ?>">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>


            <li>
              <a class="dropdown-item d-flex align-items-center" href="../settings">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            
            <?php

            if($_SESSION['user']['type'] === 'admin' || $_SESSION['user']['type'] === 'moderator' || $_SESSION['user']['type'] === 'officer'){
                if($_SESSION['user']['panel'] === 'admin'){
                    echo '<li class="switchPanels">
                      <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                        <i class="bi bi-toggle-on text-danger"></i>
                        <span>Admin Panel</span>
                      </a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>';
                } else {
                    echo '<li class="switchPanels">
                      <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                        <i class="bi bi-toggle-off"></i>
                        <span>Admin Panel</span>
                      </a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>';
                }
                
            }

            ?>

            <li>
              <a id="logout" class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header>