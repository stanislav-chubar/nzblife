<?php
/**
 * NZB.life - Forgot Password Page
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/sendgrid.php';
ensure_session();

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $db = get_db();
            $stmt = $db->prepare('SELECT id, username, email FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = new DateTime('now', new DateTimeZone('UTC'));
                $expires->modify('+1 hour');

                $update = $db->prepare('UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE id = ?');
                $update->execute([$token, $expires->format('Y-m-d H:i:s'), $user['id']]);

                try {
                    send_password_reset_email($user['email'], $user['username'], $token);
                } catch (\Throwable $e) {
                    // Silent fail
                }
            }
            // Always show success to prevent email enumeration
            $success = 'If an account exists with that email, a password reset link has been sent.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - <?= e(SITE_NAME) ?></title>
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
            background: #0d2137;
            border: 1px solid #1a3a4a;
            border-radius: 8px;
            padding: 35px 40px;
            width: 100%;
            max-width: 440px;
        }
        .auth-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }
        .auth-header i { color: #00e68a; font-size: 22px; }
        .auth-header h1 { color: #fff; font-size: 22px; font-weight: normal; }
        .auth-subtitle { color: #6a7a8a; font-size: 12px; margin-bottom: 24px; }
        .form-label { display: block; color: #8899aa; font-size: 13px; margin-bottom: 8px; }
        .input-with-icon { position: relative; margin-bottom: 18px; }
        .input-with-icon .icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #00e68a; font-size: 14px; }
        .input-with-icon input {
            width: 100%; background: #0a1525; border: 1px solid #1a3a4a; color: #c8c8c8;
            padding: 11px 14px 11px 38px; font-family: 'Share Tech Mono', monospace; font-size: 14px;
            border-radius: 4px; outline: none; transition: border-color 0.2s;
        }
        .input-with-icon input:focus { border-color: #00e68a; }
        .input-with-icon input::placeholder { color: #3a4a5a; }
        .btn-row { display: flex; align-items: center; gap: 14px; margin-top: 6px; }
        .btn { padding: 10px 24px; font-family: 'Share Tech Mono', monospace; font-size: 14px; border-radius: 4px; cursor: pointer; border: none; }
        .btn-green { background: #00e68a; color: #0b1a2a; font-weight: bold; }
        .btn-green:hover { background: #33ffaa; }
        .flash { padding: 10px 14px; border-radius: 4px; margin-bottom: 18px; font-size: 13px; }
        .flash-error { background: rgba(255,60,60,0.1); border: 1px solid #5a2020; color: #ff6666; }
        .flash-success { background: rgba(0,230,138,0.1); border: 1px solid #1a5a3a; color: #00e68a; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <i class="fas fa-envelope"></i>
            <h1>Forgot Password</h1>
        </div>
        <p class="auth-subtitle">Enter your email to receive a password reset link.</p>

        <?php if ($error): ?>
            <div class="flash flash-error"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="flash flash-success"><?= e($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="forgot_password.php">
            <?= csrf_input() ?>
            <label class="form-label">Email Address</label>
            <div class="input-with-icon">
                <i class="fas fa-at icon"></i>
                <input type="email" name="email" placeholder="Your registered email" required
                       value="<?= e($_POST['email'] ?? '') ?>">
            </div>
            <div class="btn-row">
                <button type="submit" class="btn btn-green">Send Reset Link</button>
                <a href="login.php" style="color:#6a7a8a; font-size:13px;">Back to Login</a>
            </div>
        </form>
    </div>
</body>
</html>
