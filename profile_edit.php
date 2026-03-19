<?php
/**
 * NZB.life - Edit Profile Page
 */
require_once __DIR__ . '/auth.php';

$page_title = 'Edit your profile';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        $new_username = trim($_POST['username'] ?? '');
        $new_email = trim($_POST['email'] ?? '');
        $new_password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        $db = get_db();

        // Validate username
        if ($new_username === '' || $new_email === '') {
            $error = 'Username and email are required.';
        } elseif (strlen($new_username) < 3) {
            $error = 'Username must be at least 3 characters.';
        } elseif (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $new_username)) {
            $error = 'Username must start with a letter and contain only letters, numbers, and underscores.';
        } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            // Check username uniqueness
            if ($new_username !== $current_user['username']) {
                $check = $db->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
                $check->execute([$new_username, $current_user['id']]);
                if ($check->fetch()) {
                    $error = 'This username is already taken.';
                }
            }
            // Check email uniqueness
            if (!$error && $new_email !== $current_user['email']) {
                $check = $db->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
                $check->execute([$new_email, $current_user['id']]);
                if ($check->fetch()) {
                    $error = 'This email is already in use.';
                }
            }
        }

        // Password change (optional)
        if (!$error && $new_password !== '') {
            if (strlen($new_password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } elseif ($new_password !== $confirm_password) {
                $error = 'Passwords do not match.';
            }
        }

        if (!$error) {
            // Update user
            if ($new_password !== '') {
                $hash = password_hash($new_password, PASSWORD_BCRYPT);
                $update = $db->prepare('UPDATE users SET username = ?, email = ?, password_hash = ? WHERE id = ?');
                $update->execute([$new_username, $new_email, $hash, $current_user['id']]);
            } else {
                $update = $db->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
                $update->execute([$new_username, $new_email, $current_user['id']]);
            }

            $current_user['username'] = $new_username;
            $current_user['email'] = $new_email;
            $_SESSION['username'] = $new_username;
            $success = 'Profile updated successfully.';
        }
    }
}

$extra_css = '<style>
    .edit-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 22px;
        color: #c8c8c8;
        margin-bottom: 28px;
    }
    .edit-heading i { color: #00e68a; }
    .edit-section {
        margin-bottom: 30px;
    }
    .edit-section-header {
        color: #c8c8c8;
        font-size: 16px;
        border-bottom: 1px solid #1a3a4a;
        padding-bottom: 8px;
        margin-bottom: 20px;
    }
    .form-row {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
        gap: 16px;
    }
    .form-row label {
        color: #8899aa;
        font-size: 14px;
        min-width: 160px;
        text-align: right;
    }
    .form-row .input-wrap {
        flex: 1;
        max-width: 400px;
    }
    .form-row input {
        width: 100%;
        background: #0a1525;
        border: 1px solid #1a3a4a;
        color: #c8c8c8;
        padding: 10px 14px;
        font-family: "Share Tech Mono", monospace;
        font-size: 14px;
        border-radius: 4px;
        outline: none;
        transition: border-color 0.2s;
    }
    .form-row input:focus { border-color: #00e68a; }
    .form-row input::placeholder { color: #3a4a5a; }
    .form-row .input-icon {
        position: relative;
    }
    .form-row .input-icon i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #00e68a;
        font-size: 14px;
    }
    .form-row .input-icon input { padding-left: 38px; }
    .field-hint {
        color: #5a6a7a;
        font-size: 11px;
        margin-top: 4px;
    }
    .site-prefs-notice {
        background: rgba(0,230,138,0.08);
        border: 1px solid #1a5a3a;
        border-radius: 4px;
        padding: 14px 18px;
        color: #00e68a;
        font-size: 13px;
    }
    .save-btn {
        display: inline-block;
        padding: 10px 30px;
        background: #0d2137;
        border: 1px solid #1a3a4a;
        border-radius: 4px;
        color: #c8c8c8;
        font-family: "Share Tech Mono", monospace;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .save-btn:hover {
        background: rgba(0,230,138,0.1);
        border-color: #00e68a;
        color: #00e68a;
    }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <?= render_flash() ?>

        <h1 class="edit-heading">
            <i class="fas fa-pen"></i> Edit your profile
        </h1>

        <?php if ($error): ?>
            <div class="flash flash-error"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="flash flash-success"><?= e($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="profile_edit.php">
            <?= csrf_input() ?>

            <div class="edit-section">
                <h2 class="edit-section-header">User Details</h2>

                <div class="form-row">
                    <label>Username</label>
                    <div class="input-wrap input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" value="<?= e($current_user['username']) ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Email</label>
                    <div class="input-wrap input-icon">
                        <i class="fas fa-at"></i>
                        <input type="email" name="email" value="<?= e($current_user['email']) ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Password</label>
                    <div class="input-wrap input-icon">
                        <i class="fas fa-key"></i>
                        <input type="password" name="password" placeholder="Only enter your password if you want to change it.">
                    </div>
                </div>

                <div class="form-row">
                    <label>Confirm Password</label>
                    <div class="input-wrap input-icon">
                        <i class="fas fa-key"></i>
                        <input type="password" name="confirm_password" placeholder="">
                    </div>
                </div>
            </div>

            <div class="edit-section">
                <h2 class="edit-section-header">Site Preferences</h2>
                <div class="site-prefs-notice">
                    Your profile is restricted until you upgrade your account.
                </div>
            </div>

            <button type="submit" class="save-btn">Save Profile</button>
        </form>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
