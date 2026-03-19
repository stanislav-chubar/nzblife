<?php
/**
 * NZB.life - Sign In Page
 */
require_once __DIR__ . '/config.php';
ensure_session();

// If already logged in, redirect
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        // Verify Turnstile
        $turnstile_token = $_POST['cf-turnstile-response'] ?? '';
        if (!verify_turnstile($turnstile_token)) {
            $error = 'CAPTCHA verification failed. Please try again.';
        } else {
            $identifier = trim($_POST['identifier'] ?? '');
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);

            if ($identifier === '' || $password === '') {
                $error = 'Please fill in all fields.';
            } else {
                $db = get_db();
                $stmt = $db->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
                $stmt->execute([$identifier, $identifier]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['id'];

                    // Update last login
                    $update = $db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?');
                    $update->execute([$user['id']]);

                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = 'Invalid username/email or password.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
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
        .auth-header i {
            color: #00e68a;
            font-size: 22px;
        }
        .auth-header h1 {
            color: #fff;
            font-size: 22px;
            font-weight: normal;
        }
        .auth-subtitle {
            color: #6a7a8a;
            font-size: 12px;
            margin-bottom: 24px;
        }
        .form-label {
            display: block;
            color: #8899aa;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .input-with-icon {
            position: relative;
            margin-bottom: 18px;
        }
        .input-with-icon .icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #00e68a;
            font-size: 14px;
        }
        .input-with-icon input {
            width: 100%;
            background: #0a1525;
            border: 1px solid #1a3a4a;
            color: #c8c8c8;
            padding: 11px 14px 11px 38px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 14px;
            border-radius: 4px;
            outline: none;
            transition: border-color 0.2s;
        }
        .input-with-icon input:focus { border-color: #00e68a; }
        .input-with-icon input::placeholder { color: #3a4a5a; }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .remember-row input[type="checkbox"] {
            accent-color: #00e68a;
        }
        .remember-row label {
            color: #8899aa;
            font-size: 13px;
        }

        .btn-row {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }
        .btn {
            padding: 10px 24px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
        }
        .btn-green {
            background: #00e68a;
            color: #0b1a2a;
            font-weight: bold;
        }
        .btn-green:hover { background: #33ffaa; }
        .btn-outline {
            background: transparent;
            border: 1px solid #00e68a;
            color: #00e68a;
        }
        .btn-outline:hover { background: rgba(0,230,138,0.1); }

        .turnstile-row {
            margin-bottom: 18px;
        }

        .forgot-link {
            display: block;
            text-align: center;
            color: #6a7a8a;
            font-size: 12px;
            margin-top: 14px;
        }
        .forgot-link:hover { color: #00e68a; }

        .flash {
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 18px;
            font-size: 13px;
        }
        .flash-error {
            background: rgba(255, 60, 60, 0.1);
            border: 1px solid #5a2020;
            color: #ff6666;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="auth-header">
            <i class="fas fa-lock"></i>
            <h1>Sign In</h1>
        </div>
        <p class="auth-subtitle">Use your username/email and password.</p>

        <?php if ($error): ?>
            <div class="flash flash-error"><?= e($error) ?></div>
        <?php endif; ?>
        <?= render_flash() ?>

        <form method="POST" action="login.php">
            <?= csrf_input() ?>

            <label class="form-label">Username or Email</label>
            <div class="input-with-icon">
                <i class="fas fa-user icon"></i>
                <input type="text" name="identifier" placeholder="Enter username or email" required
                       value="<?= e($_POST['identifier'] ?? '') ?>">
            </div>

            <label class="form-label">Password</label>
            <div class="input-with-icon">
                <i class="fas fa-key icon"></i>
                <input type="password" name="password" placeholder="Your password" required>
            </div>

            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember Me</label>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-green">Login</button>
                <a href="register.php" class="btn btn-outline">Register</a>
            </div>

            <?php if (TURNSTILE_SITE_KEY): ?>
            <div class="turnstile-row">
                <div class="cf-turnstile" data-sitekey="<?= e(TURNSTILE_SITE_KEY) ?>" data-theme="dark"></div>
            </div>
            <?php endif; ?>
        </form>

        <a href="forgot_password.php" class="forgot-link">Forgotten your password?</a>
    </div>

</body>
</html>
