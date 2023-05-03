<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item switchPanels">
        <a class="nav-link collapsed" href="javascript:void(0);">
          <i class="bi bi-arrow-left-short"></i>
          <span>Switch to Default</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'users.php') echo 'collapsed'; ?>" href="users.php">
          <i class="bi bi-people"></i>
          <span>Users</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'students.php') echo 'collapsed'; ?>" href="students.php">
          <i class="bx bxs-user-detail"></i>
          <span>Students</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'working.php') echo 'collapsed'; ?>" href="working.php">
          <i class="bx ri-facebook-fill"></i>
          <span>Facebook Pages</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'newsfeed.php') echo 'collapsed'; ?>" href="newsfeed.php">
          <i class="bx bx-news"></i>
          <span>Content Posts</span>
        </a>
      </li>

    </ul>

  </aside>