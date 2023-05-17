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
        <a class="nav-link <?php if($page != 'activity.php') echo 'collapsed'; ?>" href="./../activity">
          <i class="bx bxs-user-detail"></i>
          <span>Calendar of Activity</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'working.php') echo 'collapsed'; ?>" href="./../working">
          <i class="bx ri-facebook-fill"></i>
          <span>Facebook Pages</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'newsfeed.php') echo 'collapsed'; ?>" href="./../newsfeed">
          <i class="bx bx-news"></i>
          <span>Content Posts</span>
        </a>
      </li>

    </ul>

  </aside>