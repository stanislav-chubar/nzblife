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

$hide_notification = true;
$extra_css = '<style>
    .edit-card {
        max-width: 900px;
        margin: 0 auto;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        padding: 30px 36px;
    }
    .edit-card .notification-bar {
        margin: 0 0 24px;
    }
    .edit-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 22px;
        color: #c8c8c8;
        margin-bottom: 28px;
    }
    .edit-heading i { color: var(--accent); }
    .edit-fieldset {
        border: 1px solid var(--card-border);
        border-radius: 4px;
        padding: 24px 20px;
        margin-bottom: 24px;
    }
    .edit-fieldset legend {
        color: #c8c8c8;
        font-size: 16px;
        font-weight: bold;
        padding: 0 10px;
    }
    .form-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 0;
        padding: 12px 0;
        gap: 16px;
        border-bottom: 1px solid rgba(93, 255, 180, 0.08);
    }
    .form-row:last-child { border-bottom: none; }
    .form-row label {
        color: #c8c8c8;
        font-size: 14px;
        font-weight: bold;
        min-width: 160px;
        text-align: right;
        padding-top: 10px;
    }
    .form-row .input-wrap {
        flex: 1;
    }
    .form-row input {
        width: 100%;
        background: rgba(6, 14, 7, 0.6);
        border: 1px solid var(--card-border);
        color: #c8c8c8;
        padding: 10px 14px;
        font-family: "JetBrains Mono", monospace;
        font-size: 14px;
        border-radius: 4px;
        outline: none;
        transition: border-color 0.2s;
    }
    .form-row input:focus { border-color: var(--accent); }
    .form-row input::placeholder { color: #4a5a6a; }
    .form-row .input-icon {
        position: relative;
    }
    .form-row .input-icon i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
        font-size: 14px;
    }
    .form-row .input-icon input { padding-left: 38px; }
    .field-hint {
        color: #5a6a7a;
        font-size: 12px;
        margin-top: 6px;
    }
    .site-prefs-notice {
        background: rgba(61, 255, 158, 0.06);
        border: 1px solid rgba(93, 255, 180, 0.3);
        border-radius: 4px;
        padding: 14px 18px;
        color: var(--accent);
        font-size: 13px;
    }
    .site-prefs-notice a { color: var(--link); }
    .save-btn {
        display: inline-block;
        padding: 10px 30px;
        background: linear-gradient(180deg, rgba(17, 26, 39, 0.95), rgba(11, 17, 26, 0.96));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 4px;
        color: var(--text);
        font-family: "JetBrains Mono", monospace;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .save-btn:hover {
        background: linear-gradient(180deg, rgba(16, 40, 20, 0.96) 0%, rgba(10, 26, 13, 0.96) 100%);
        border-color: rgba(100, 210, 114, 0.42);
        color: var(--accent);
    }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <div class="edit-card">
            <?php if (isset($current_user) && $current_user): ?>
            <div class="notification-bar" id="notificationBar">
                Thank you for registering, please feel free to look around. You cannot download or use the API without an upgraded account (<a href="vip.php">upgrade your account</a>). Free accounts are automatically removed after a few days.
                <span class="dismiss" onclick="document.getElementById('notificationBar').style.display='none'">Dismiss</span>
            </div>
            <?php endif; ?>

            <?= render_flash() ?>

            <h1 class="edit-heading">
                <i class="fas fa-user"></i> Edit your profile
            </h1>

            <?php if ($error): ?>
                <div class="flash flash-error"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="flash flash-success"><?= e($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="profile_edit.php">
                <?= csrf_input() ?>

                <fieldset class="edit-fieldset">
                    <legend>User Details</legend>

                    <div class="form-row">
                        <label>Username</label>
                        <div class="input-wrap">
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
                        <div class="input-wrap">
                            <div class="input-icon">
                                <i class="fas fa-asterisk"></i>
                                <input type="password" name="password">
                            </div>
                            <div class="field-hint">Only enter your password if you want to change it.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Confirm Password</label>
                        <div class="input-wrap input-icon">
                            <i class="fas fa-asterisk"></i>
                            <input type="password" name="confirm_password">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="edit-fieldset">
                    <legend>Site Preferences</legend>
                    <div class="site-prefs-notice">
                        Your profile is restricted until you <a href="vip.php">upgrade your account</a>.
                    </div>
                </fieldset>

                <button type="submit" class="save-btn">Save Profile</button>
            </form>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
