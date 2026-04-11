<!-- Navbar -->
      <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
          <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base ri ri-menu-line icon-md"></i>
          </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
          <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
              <i class="icon-base ri ri-search-line icon-lg lh-0"></i>
              <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
            </div>
          </div>

          <ul class="navbar-nav flex-row align-items-center ms-md-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
              <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    <?php
                      $profile_pic = !empty($_SESSION['profile'])
                          ? '../../../storage/profiles/' . htmlspecialchars($_SESSION['profile'])
                          : '../../../public/assets/img/avatars/1.png';
                    ?>
                    <img
                        src="<?php echo $profile_pic; ?>"
                        alt="Profile Picture"
                        class="rounded-circle mb-3"
                    />
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="#">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar avatar-online">
                        <?php
                          $profile_pic = !empty($_SESSION['profile'])
                            ? '../../../storage/profiles/' . htmlspecialchars($_SESSION['profile'])
                            : '../../../public/assets/img/avatars/1.png';
                        ?>
                        <img
                            src="<?php echo $profile_pic; ?>"
                            alt="Profile Picture"
                            class="rounded-circle mb-3"
                        />
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0"><?php echo htmlspecialchars($_SESSION['full_name']); ?></h6>
                        <small class="text-body-secondary">
                          <?php echo htmlspecialchars($_SESSION['email']); ?>
                        </small>
                      </div>
                    </div>
                  </a>
                </li>
                <li><div class="dropdown-divider my-1"></div></li>
                <li>
                  <a class="dropdown-item" href="#">
                    <i class="icon-base ri ri-user-line icon-md me-3"></i>
                    <span>Activity Log</span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="settings.php">
                    <i class="icon-base ri ri-settings-4-line icon-md me-3"></i>
                    <span>Settings</span>
                  </a>
                </li>
                <li><div class="dropdown-divider my-1"></div></li>
                <li>
                  <div class="d-grid px-4 pt-2 pb-1">
                    <a class="btn btn-danger d-flex" href="../../../controllers/logout.php" onclick="return confirm('Are you sure you want to logout?')">
                      <small class="align-middle">Logout</small>
                      <i class="ri ri-logout-box-r-line ms-2 ri-xs"></i>
                    </a>
                  </div>
                </li>
              </ul>
            </li>
            <!--/ User -->
          </ul>
        </div>
      </nav>
      <!-- / Navbar -->

      <!-- Content wrapper -->
      <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">