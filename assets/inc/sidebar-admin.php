<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item switchPanels">
        <a class="nav-link collapsed" href="javascript:void(0);">
          <i class="bi bi-arrow-left-short"></i>
          <span>Switch to Default</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'users.php') echo 'collapsed'; ?>" href="./../users">
          <i class="bi bi-people"></i>
          <span>Users</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'students.php') echo 'collapsed'; ?>" href="./../students">
          <i class="bx bxs-user-detail"></i>
          <span>Students</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'courses.php' && $page != 'courses_preview.php') echo 'collapsed'; ?>" href="./../courses">
          <i class="ri-book-2-line"></i>
          <span>Courses</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'activity.php') echo 'collapsed'; ?>" href="./../activity">
          <i class="bx bxs-user-detail"></i>
          <span>Calendar of Activity</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'working.php') echo 'collapsed'; ?>" href="./../working">
          <i class="bi bi-life-preserver"></i>
          <span>Support</span>
        </a>
      </li>

    </ul>

  </aside>