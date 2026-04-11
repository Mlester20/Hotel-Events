<?php
require_once __DIR__ . '/../../../controllers/UpdateProfileController.php';
require_once __DIR__ . '/../../../middleware/auth.php';
allowOnly(['user']);
?>

<!doctype html>
<html lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="../../../public/assets/"
  data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Profile Settings | <?php require_once __DIR__ . '/../../../helpers/title.php'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../public/css/home.css">  
</head>
<body>

    <?php require_once 'layout/navbar.php'; ?>

    <?php showFlash(); ?>

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Account /</span> Profile Settings
        </h4>

        <div class="row">

            <!-- Left: Profile Info Card -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Profile Information</h5></div>
                    <div class="card-body text-center pt-4">

                        <?php
                            $profile_pic = !empty($user['profile'])
                                ? '../../../storage/profiles/' . htmlspecialchars($user['profile'])
                                : '../../../public/assets/img/avatars/1.png';
                        ?>
                        <img
                            src="<?php echo $profile_pic; ?>"
                            alt="Profile Picture"
                            class="rounded-circle mb-3"
                            style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #e0e0e0;"
                        />

                        <div class="text-start mt-3 px-2">
                            <div class="mb-3 pb-2 border-bottom">
                                <small class="text-muted d-block">Full Name</small>
                                <span><?php echo htmlspecialchars($user['full_name'] ?? '—'); ?></span>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <small class="text-muted d-block">Email Address</small>
                                <span><?php echo htmlspecialchars($user['email'] ?? '—'); ?></span>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <small class="text-muted d-block">Account Role</small>
                                <span class="badge bg-label-primary"><?php echo ucfirst($user['role'] ?? '—'); ?></span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted d-block">Member Since</small>
                                <span>
                                    <?php
                                        echo !empty($user['created_at'])
                                            ? date('M d, Y', strtotime($user['created_at']))
                                            : '—';
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Update Profile Form -->
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="mb-0">Update Profile</h5></div>
                    <div class="card-body">
                        <form method="POST" action="../../../controllers/UpdateProfileController.php" enctype="multipart/form-data">

                            <!-- Basic Information -->
                            <h6 class="fw-semibold text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">
                                Basic Information
                            </h6>

                            <!-- Profile Picture -->
                            <div class="mb-3">
                                <label class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" name="profile" accept=".jpg,.jpeg,.png,.gif">
                                <div class="form-text">JPG, PNG, or GIF (Max 2MB)</div>
                                <?php if (!empty($user['profile'])): ?>
                                    <div class="form-text">
                                        Current image: <span class="text-muted"><?php echo htmlspecialchars($user['profile']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="full_name"
                                    name="full_name"
                                    value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>"
                                    required
                                >
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                    required
                                >
                            </div>

                            <hr class="my-4">

                            <!-- Security Settings -->
                            <h6 class="fw-semibold text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">
                                Security Settings
                            </h6>

                            <!-- Current Password (required) -->
                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    Current Password <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="current_password"
                                    name="current_password"
                                    placeholder="Enter your current password"
                                    required 
                                >
                                <div class="form-text">Required to confirm changes</div>
                            </div>

                            <!-- New Password (optional) -->
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="new_password"
                                    name="new_password"
                                    placeholder="Leave empty to keep current password"
                                >
                                <div class="form-text">Minimum 8 characters</div>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="confirm_password"
                                    name="confirm_password"
                                    placeholder="Re-enter new password"
                                >
                            </div>

                            <button type="submit" name="updateProfile" class="btn btn-primary">
                                Save Changes
                            </button>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

<script src="../../../public/assets/vendor/libs/jquery/jquery.js"></script>
<script src="../../../public/assets/vendor/libs/popper/popper.js"></script>
<script src="../../../public/assets/vendor/js/bootstrap.js"></script>
<script src="../../../public/assets/vendor/libs/node-waves/node-waves.js"></script>
<script src="../../../public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../../../public/assets/vendor/js/menu.js"></script>
<script src="../../../public/assets/js/main.js"></script>
</body>
</html>