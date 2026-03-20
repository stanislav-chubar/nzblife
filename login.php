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
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <style>
        :root {
            --accent: #3dff9e;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #060a10;
            background: linear-gradient(160deg, #060a10 0%, #071009 50%, #060a10 80%, #070d0b 100%);
            background-attachment: fixed;
            font-family: "JetBrains Mono", "Fira Code", "SFMono-Regular", Menlo, Consolas, "Liberation Mono", monospace;
            font-size: 14px;
            color: #c8c8c8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: #0b140d; }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(75, 178, 89, 0.9) 0%, rgba(55, 138, 68, 0.9) 100%);
            border: 2px solid #0b140d;
            border-radius: 10px;
        }

        a { color: #5ad7ff; text-decoration: none; }
        a:hover {
            color: var(--accent);
            text-shadow: 0 0 12px rgba(61, 255, 158, 0.35);
        }

        .auth-card {
            border: 1px solid rgba(93, 255, 180, 0.24);
            border-radius: 6px;
            padding: 35px 40px;
            width: 100%;
            max-width: 520px;
        }
        .auth-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }
        .auth-header i {
            color: var(--accent);
            font-size: 28px;
        }
        .auth-header h1 {
            color: #d7fbe6;
            font-size: 26px;
            font-weight: 700;
        }
        .auth-subtitle {
            color: #8899aa;
            font-size: 13px;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        .form-label {
            display: block;
            color: #d7fbe6;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .input-with-icon {
            display: flex;
            align-items: stretch;
            margin-bottom: 18px;
        }
        .input-with-icon .icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            min-width: 42px;
            background: rgba(6, 14, 7, 0.8);
            border: 1px solid rgba(93, 255, 180, 0.24);
            border-right: none;
            border-radius: 4px 0 0 4px;
            color: var(--accent);
            font-size: 14px;
        }
        .input-with-icon input {
            width: 100%;
            background: rgba(6, 14, 7, 0.6);
            border: 1px solid rgba(93, 255, 180, 0.24);
            border-left: none;
            color: #c8c8c8;
            padding: 11px 14px;
            font-family: "JetBrains Mono", "Fira Code", monospace;
            font-size: 14px;
            border-radius: 0 4px 4px 0;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-with-icon:focus-within .icon-box {
            border-color: var(--accent);
        }
        .input-with-icon input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 8px rgba(61, 255, 158, 0.15);
        }
        .input-with-icon input::placeholder { color: #4a5a6a; }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .remember-row input[type="checkbox"] {
            accent-color: #5ad7ff;
            width: 16px;
            height: 16px;
        }
        .remember-row label {
            color: #d7fbe6;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-row {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }
        .btn {
            padding: 8px 20px;
            font-family: "JetBrains Mono", "Fira Code", monospace;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-login {
            background: linear-gradient(180deg, rgba(17, 26, 39, 0.95), rgba(11, 17, 26, 0.96));
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #c8c8c8;
        }
        .btn-login:hover {
            background: linear-gradient(180deg, rgba(16, 40, 20, 0.96) 0%, rgba(10, 26, 13, 0.96) 100%);
            border: 1px solid rgba(100, 210, 114, 0.42);
            color: var(--accent);
        }
        .btn-register {
            background: linear-gradient(180deg, rgba(17, 26, 39, 0.95), rgba(11, 17, 26, 0.96));
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #5ad7ff;
        }
        .btn-register:hover {
            background: linear-gradient(180deg, rgba(16, 40, 20, 0.96) 0%, rgba(10, 26, 13, 0.96) 100%);
            border: 1px solid rgba(100, 210, 114, 0.42);
            color: var(--accent);
            text-shadow: none;
        }

        .turnstile-row {
            margin-bottom: 14px;
        }

        .forgot-link {
            display: block;
            color: #5ad7ff;
            font-size: 13px;
            margin-top: 4px;
        }
        .forgot-link:hover {
            color: var(--accent);
            text-shadow: 0 0 12px rgba(61, 255, 158, 0.35);
        }

        .flash {
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 18px;
            font-size: 13px;
        }
        .flash-error {
            background: rgba(255, 60, 60, 0.1);
            border: 1px solid rgba(255, 60, 60, 0.3);
            color: #ff6666;
        }
        .flash-success {
            background: rgba(0, 230, 138, 0.1);
            border: 1px solid rgba(93, 255, 180, 0.3);
            color: var(--accent);
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
                <span class="icon-box"><i class="fas fa-user"></i></span>
                <input type="text" name="identifier" placeholder="Enter username or email" required
                       value="<?= e($_POST['identifier'] ?? '') ?>">
            </div>

            <label class="form-label">Password</label>
            <div class="input-with-icon">
                <span class="icon-box"><i class="fas fa-asterisk"></i></span>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember" checked>
                <label for="remember">Remember Me</label>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-login">Login</button>
                <a href="register.php" class="btn btn-register">Register</a>
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
