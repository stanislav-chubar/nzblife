<?php
/**
 * NZB.life - Reset Password Page
 */
require_once __DIR__ . '/config.php';
ensure_session();

$error = '';
$success = '';
$valid_token = false;
$token = $_GET['token'] ?? '';

if ($token !== '') {
    $db = get_db();
    $stmt = $db->prepare('SELECT id, username FROM users WHERE reset_token = ? AND reset_token_expires > NOW()');
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    if ($user) {
        $valid_token = true;
    } else {
        $error = 'Invalid or expired reset link. Please request a new one.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $db = get_db();
            $update = $db->prepare('UPDATE users SET password_hash = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?');
            $update->execute([$hash, $user['id']]);
            set_flash('success', 'Your password has been reset. Please sign in.');
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0b1a2a;
            font-family: 'Share Tech Mono', 'Courier New', monospace;
            font-size: 14px;
            color: #c8c8c8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        a { color: #00e68a; text-decoration: none; }
        a:hover { color: #33ffaa; }
        .auth-card {
            background: #0d2137; border: 1px solid #1a3a4a; border-radius: 8px;
            padding: 35px 40px; width: 100%; max-width: 440px;
        }
        .auth-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .auth-header i { color: #00e68a; font-size: 22px; }
        .auth-header h1 { color: #fff; font-size: 22px; font-weight: normal; }
        .form-label { display: block; color: #8899aa; font-size: 13px; margin-bottom: 8px; }
        .input-with-icon { position: relative; margin-bottom: 18px; }
        .input-with-icon .icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #00e68a; font-size: 14px; }
        .input-with-icon input {
            width: 100%; background: #0a1525; border: 1px solid #1a3a4a; color: #c8c8c8;
            padding: 11px 14px 11px 38px; font-family: 'Share Tech Mono', monospace; font-size: 14px;
            border-radius: 4px; outline: none;
        }
        .input-with-icon input:focus { border-color: #00e68a; }
        .input-with-icon input::placeholder { color: #3a4a5a; }
        .btn { padding: 10px 24px; font-family: 'Share Tech Mono', monospace; font-size: 14px; border-radius: 4px; cursor: pointer; border: none; }
        .btn-green { background: #00e68a; color: #0b1a2a; font-weight: bold; }
        .btn-green:hover { background: #33ffaa; }
        .flash { padding: 10px 14px; border-radius: 4px; margin-bottom: 18px; font-size: 13px; }
        .flash-error { background: rgba(255,60,60,0.1); border: 1px solid #5a2020; color: #ff6666; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <i class="fas fa-key"></i>
            <h1>Reset Password</h1>
        </div>

        <?php if ($error): ?>
            <div class="flash flash-error"><?= e($error) ?></div>
            <?php if (!$valid_token): ?>
                <p><a href="forgot_password.php">Request a new reset link</a></p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($valid_token): ?>
        <form method="POST" action="reset_password.php?token=<?= e($token) ?>">
            <?= csrf_input() ?>
            <label class="form-label">New Password</label>
            <div class="input-with-icon">
                <i class="fas fa-key icon"></i>
                <input type="password" name="password" placeholder="Minimum 6 characters" required>
            </div>
            <label class="form-label">Confirm Password</label>
            <div class="input-with-icon">
                <i class="fas fa-key icon"></i>
                <input type="password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="btn btn-green">Set New Password</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
