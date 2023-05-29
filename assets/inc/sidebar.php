<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'dashboard.php') echo 'collapsed'; ?>" href="./../dashboard">
          <i class="bx bxs-dashboard"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'schedule.php') echo 'collapsed'; ?>" href="./../schedule">
          <i class="bx bx-calendar-week"></i>
          <span>Class Schedule</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'calendar.php') echo 'collapsed'; ?>" href="./../calendar">
          <i class="bx bxs-calendar"></i>
          <span>Calendar</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'organization_view.php' && $page != 'organization.php') { echo 'collapsed'; } ?>" href="./../organization">
          <i class="ri ri-group-2-line"></i>
          <span>Organizations</span>
        </a>
      </li>
      

      <li class="nav-heading">Services</li>
        
      <li class="nav-item">
        <a class="nav-link collapsed" href="./../working">
          <i class="bx bxs-user-account"></i>
          <span>Accounting</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="./../working">
          <i class="bx bx-library"></i>
          <span>Library</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="./../working">
          <i class="bi bi-card-list"></i>
          <span>Registrar</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="./../working">
          <i class="bx bxs-upvote"></i>
          <span>Voting</span>
        </a>
      </li>

      
    <li class="nav-heading">Others</li>
    <li class="nav-item">
        <a class="nav-link <?php if($page != 'support.php' && $page != 'support_preview.php') { echo 'collapsed'; } ?>" href="./../support">
          <i class="bi bi-life-preserver"></i>
          <span>Support  
          
          <?php

          require_once 'class/Support.php';
          $support = new Support();

          $pendingCount_raw = json_decode($support->getPendingCount(), true);
          $pendingCount = $pendingCount_raw['data']['PendingCount'];


         if($pendingCount > 0) {
           echo '<span class="badge bg-danger badge-number">'.$pendingCount.'</span>';
         }
         ?>
          </span>
        </a>
      </li>
    </ul>


  </aside>