<?php
/**
 * NZB.life - Contact Page
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/sendgrid.php';

$page_title = 'Contact ' . SITE_NAME;
$hide_notification = true;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($name === '' || $email === '' || $message === '') {
            $error = 'All fields are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            try {
                send_contact_email($name, $email, $message);
                $success = 'Your message has been sent. We will get back to you shortly.';
            } catch (\Throwable $e) {
                $error = 'Failed to send message. Please try again later.';
            }
        }
    }
}

$extra_css = '<style>
    .contact-card .notification-bar {
        margin: 0 0 20px;
    }
    .contact-card {
        max-width: 900px;
        margin: 0 auto;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        padding: 30px 36px;
    }
    .contact-heading {
        font-size: 24px;
        color: #c8c8c8;
        margin-bottom: 10px;
    }
    .contact-subtitle {
        color: #8899aa;
        font-size: 14px;
        margin-bottom: 28px;
    }
    .contact-subtitle a { color: var(--link); }
    .contact-form-heading {
        font-size: 18px;
        color: #c8c8c8;
        margin-bottom: 20px;
        font-weight: bold;
    }
    .contact-group {
        margin-bottom: 18px;
    }
    .contact-group label {
        display: block;
        color: #c8c8c8;
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .contact-group label .hint {
        color: #5a6a7a;
        font-size: 12px;
        font-weight: normal;
        font-style: italic;
    }
    .contact-group input,
    .contact-group textarea {
        width: 100%;
        background: rgba(6, 14, 7, 0.6);
        border: 1px solid var(--card-border);
        color: #c8c8c8;
        padding: 12px 14px;
        font-family: "JetBrains Mono", monospace;
        font-size: 14px;
        border-radius: 4px;
        outline: none;
        transition: border-color 0.2s;
    }
    .contact-group input:focus,
    .contact-group textarea:focus {
        border-color: var(--accent);
    }
    .contact-group input::placeholder,
    .contact-group textarea::placeholder { color: #4a5a6a; }
    .contact-group textarea {
        min-height: 200px;
        resize: vertical;
    }
    .email-input-wrap {
        position: relative;
    }
    .email-input-wrap .at-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
        font-size: 14px;
    }
    .email-input-wrap input {
        padding-left: 38px;
    }
    .submit-btn {
        display: inline-block;
        padding: 10px 28px;
        background: rgba(6, 14, 7, 0.6);
        border: 1px solid var(--card-border);
        border-radius: 4px;
        color: #c8c8c8;
        font-family: "JetBrains Mono", monospace;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .submit-btn:hover {
        background: rgba(61, 255, 158, 0.1);
        border-color: var(--accent);
        color: var(--accent);
    }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <div class="contact-card">
            <?php if (isset($current_user) && $current_user): ?>
            <div class="notification-bar" id="notificationBar">
                Thank you for registering, please feel free to look around. You cannot download or use the API without an upgraded account (<a href="vip.php">upgrade your account</a>). Free accounts are automatically removed after a few days.
                <span class="dismiss" onclick="document.getElementById('notificationBar').style.display='none'">Dismiss</span>
            </div>
            <?php endif; ?>

            <?= render_flash() ?>

            <h1 class="contact-heading">Contact <?= e(SITE_NAME) ?></h1>
            <p class="contact-subtitle">Please send any questions or comments you have in an email to <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>.</p>

            <?php if ($error): ?>
                <div class="flash flash-error"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="flash flash-success"><?= e($success) ?></div>
            <?php endif; ?>

            <h2 class="contact-form-heading">Contact form</h2>

            <form method="POST" action="contact.php">
                <?= csrf_input() ?>

                <div class="contact-group">
                    <label>Your name</label>
                    <input type="text" name="name" value="<?= e($_POST['name'] ?? $current_user['username']) ?>" required>
                </div>

                <div class="contact-group">
                    <label>Your email address <span class="hint">(This is your email in your profile)</span></label>
                    <div class="email-input-wrap">
                        <i class="fas fa-at at-icon"></i>
                        <input type="email" name="email" value="<?= e($_POST['email'] ?? $current_user['email']) ?>" required>
                    </div>
                </div>

                <div class="contact-group">
                    <label>Your comment or review</label>
                    <textarea name="message" required><?= e($_POST['message'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
