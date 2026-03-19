<?php
/**
 * NZB.life - Contact Page
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/sendgrid.php';

$page_title = 'Contact ' . SITE_NAME;
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
    .contact-heading {
        font-size: 24px;
        color: #c8c8c8;
        margin-bottom: 14px;
    }
    .contact-subtitle {
        color: #8899aa;
        font-size: 14px;
        margin-bottom: 30px;
    }
    .contact-subtitle a { color: #00e68a; }
    .contact-form-heading {
        font-size: 18px;
        color: #c8c8c8;
        margin-bottom: 24px;
    }
    .contact-group {
        margin-bottom: 20px;
    }
    .contact-group label {
        display: block;
        color: #8899aa;
        font-size: 14px;
        margin-bottom: 8px;
    }
    .contact-group label .hint {
        color: #5a6a7a;
        font-size: 12px;
        font-style: italic;
    }
    .contact-group input,
    .contact-group textarea {
        width: 100%;
        background: #0a1525;
        border: 1px solid #1a3a4a;
        color: #c8c8c8;
        padding: 12px 14px;
        font-family: "Share Tech Mono", monospace;
        font-size: 14px;
        border-radius: 4px;
        outline: none;
        transition: border-color 0.2s;
    }
    .contact-group input:focus,
    .contact-group textarea:focus {
        border-color: #00e68a;
    }
    .contact-group input::placeholder,
    .contact-group textarea::placeholder { color: #3a4a5a; }
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
        color: #00e68a;
        font-size: 14px;
    }
    .email-input-wrap input {
        padding-left: 38px;
    }
    .submit-btn {
        display: inline-block;
        padding: 10px 28px;
        background: #0d2137;
        border: 1px solid #1a3a4a;
        border-radius: 4px;
        color: #c8c8c8;
        font-family: "Share Tech Mono", monospace;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .submit-btn:hover {
        background: rgba(0,230,138,0.1);
        border-color: #00e68a;
        color: #00e68a;
    }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
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

<?php include __DIR__ . '/includes/footer.php'; ?>
