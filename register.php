<?php
/**
 * NZB.life - Create Account Page
 */
require_once __DIR__ . '/config.php';
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
        $turnstile_token = $_POST['cf-turnstile-response'] ?? '';
        if (!verify_turnstile($turnstile_token)) {
            $error = 'CAPTCHA verification failed. Please try again.';
        } else {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $email = trim($_POST['email'] ?? '');

            // Validation
            if ($username === '' || $password === '' || $confirm === '' || $email === '') {
                $error = 'All fields are required.';
            } elseif (strlen($username) < 3) {
                $error = 'Username must be at least 3 characters.';
            } elseif (!preg_match('/^[a-zA-Z]/', $username)) {
                $error = 'Username must start with a letter.';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $error = 'Username can only contain letters, numbers, and underscores.';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } else {
                $db = get_db();

                // Check duplicates
                $check = $db->prepare('SELECT id FROM users WHERE username = ?');
                $check->execute([$username]);
                if ($check->fetch()) {
                    $error = 'This username is already taken.';
                } else {
                    $check = $db->prepare('SELECT id FROM users WHERE email = ?');
                    $check->execute([$email]);
                    if ($check->fetch()) {
                        $error = 'This email is already registered.';
                    } else {
                        // Create user with "Registered" role (id=1)
                        $hash = password_hash($password, PASSWORD_BCRYPT);
                        $insert = $db->prepare('
                            INSERT INTO users (username, email, password_hash, role_id, registered_at, last_login_at)
                            VALUES (?, ?, ?, 1, NOW(), NOW())
                        ');
                        $insert->execute([$username, $email, $hash]);

                        // Auto-login
                        $_SESSION['user_id'] = $db->lastInsertId();
                        set_flash('success', 'Welcome! Your account has been created successfully.');
                        header('Location: dashboard.php');
                        exit;
                    }
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
    <title>Create Account - <?= e(SITE_NAME) ?></title>
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
            padding: 40px 20px;
        }
        a { color: #00e68a; text-decoration: none; }
        a:hover { color: #33ffaa; }

        .auth-card {
            background: #0d2137;
            border: 1px solid #1a3a4a;
            border-radius: 8px;
            padding: 35px 40px;
            width: 100%;
            max-width: 480px;
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
            margin-bottom: 4px;
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

        .field-hint {
            color: #5a6a7a;
            font-size: 11px;
            margin-bottom: 16px;
            padding-left: 2px;
        }

        .btn-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
            margin-top: 6px;
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
        .back-link {
            color: #6a7a8a;
            font-size: 13px;
        }
        .back-link:hover { color: #00e68a; }

        .turnstile-row {
            margin-bottom: 18px;
        }

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
            <i class="fas fa-user-plus"></i>
            <h1>Create Account</h1>
        </div>
        <p class="auth-subtitle">Set up your account to access search, feeds, and downloads.</p>

        <?php if ($error): ?>
            <div class="flash flash-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <?= csrf_input() ?>

            <label class="form-label">Username</label>
            <div class="input-with-icon">
                <i class="fas fa-user icon"></i>
                <input type="text" name="username" placeholder="Choose your user name" required
                       value="<?= e($_POST['username'] ?? '') ?>">
            </div>
            <p class="field-hint">Would be at least three characters and start with a letter.</p>

            <label class="form-label">Password</label>
            <div class="input-with-icon">
                <i class="fas fa-key icon"></i>
                <input type="password" name="password" placeholder="Select a password to use" required>
            </div>
            <p class="field-hint">Would be at least six characters long.</p>

            <label class="form-label">Confirm Password</label>
            <div class="input-with-icon">
                <i class="fas fa-key icon"></i>
                <input type="password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <p class="field-hint">&nbsp;</p>

            <label class="form-label">Email Address</label>
            <div class="input-with-icon">
                <i class="fas fa-at icon"></i>
                <input type="email" name="email" placeholder="Your email address" required
                       value="<?= e($_POST['email'] ?? '') ?>">
            </div>
            <p class="field-hint">&nbsp;</p>

            <div class="btn-row">
                <button type="submit" class="btn btn-green">Register</button>
                <a href="login.php" class="back-link">Back to Login</a>
            </div>

            <?php if (TURNSTILE_SITE_KEY): ?>
            <div class="turnstile-row">
                <div class="cf-turnstile" data-sitekey="<?= e(TURNSTILE_SITE_KEY) ?>" data-theme="dark"></div>
            </div>
            <?php endif; ?>
        </form>
    </div>

</body>
</html>
