<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item switchPanels">
        <a class="nav-link collapsed" href="javascript:void(0);">
          <i class="bi bi-arrow-left-short"></i>
          <span>Switch to Default</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'admin_dashboard.php') echo 'collapsed'; ?>" href="./../../../admin/dashboard">
          <i class="bx bxs-dashboard"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <?php
      $user_permission = isset(json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel']) ? json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel'] : null;

      if(in_array('user_view', $user_permission)){
      ?>
      <li class="nav-item">
        <a class="nav-link <?php if($page != 'admin_users.php' && $page != 'profile.php') echo 'collapsed'; ?>" href="./../../../admin/users">
          <i class="bi bi-people"></i>
          <span>Users</span>
        </a>
      </li>

      <?php
      }

      if(in_array('student_view', $user_permission)){
      ?>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'admin_students.php') echo 'collapsed'; ?>" href="./../../../admin/students">
          <i class="bx bxs-user-detail"></i>
          <span>Students</span>
        </a>
      </li>

      <?php
      }

      if(in_array('subject_view', $user_permission)){
      ?>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'admin_courses.php' && $page != 'courses_preview.php') echo 'collapsed'; ?>" href="./../../../admin/courses">
          <i class="ri-book-2-line"></i>
          <span>Courses</span>
        </a>
      </li>

      <?php
      }

      if(in_array('calendar_view', $user_permission)){
      ?>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'admin_calendar.php') echo 'collapsed'; ?>" href="./../../../admin/calendar">
          <i class="bx bxs-calendar"></i>
          <span>Calendar</span>
        </a>
      </li>

      <?php
      }

      if(in_array('organization_view', $user_permission)){
      ?>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'admin_organization.php' && $page != 'admin_organization_view.php') echo 'collapsed'; ?>" href="./../../../admin/organization">
          <i class="ri-group-line"></i>
          <span>Organizations</span>
        </a>
      </li>

      <?php
      }

      if(in_array('support_view', $user_permission)){
      ?>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'admin_support.php' && $page != 'admin_support_view.php') echo 'collapsed'; ?>" href="./../../../admin/support">
          <i class="bi bi-life-preserver"></i>
          <span>Support
          
          <?php

          require_once 'class/Admin.php';
          $admin = new Admin();

          $openCount_raw = json_decode($admin->getOpenCount(), true);
          $openCount = $openCount_raw['data']['OpenCount'];


         if($openCount > 0) {
           echo '<span class="badge bg-danger badge-number">'.$openCount.'</span>';
         }
         ?>
          </span>
        </a>
      </li>
      <?php
      }

      if(in_array('organization_view', $user_permission)){
      ?>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'admin_system_settings.php') echo 'collapsed'; ?>" href="./../../../admin/system_settings">
          <i class="ri ri-settings-3-line"></i>
          <span>System Settings</span>
        </a>
      </li>

      <?php
      }

      ?>
    </ul>

  </aside>