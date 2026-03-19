<?php
/**
 * NZB.life - SendGrid Email Helper
 */
if (basename($_SERVER['PHP_SELF']) === 'sendgrid.php') {
    http_response_code(403);
    exit('Forbidden');
}

function send_email(string $to_email, string $to_name, string $subject, string $html_body): bool {
    $payload = [
        'personalizations' => [[
            'to' => [['email' => $to_email, 'name' => $to_name]],
            'subject' => $subject,
        ]],
        'from' => [
            'email' => SENDGRID_FROM_EMAIL,
            'name'  => SENDGRID_FROM_NAME,
        ],
        'content' => [[
            'type'  => 'text/html',
            'value' => $html_body,
        ]],
    ];

    $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . SENDGRID_API_KEY,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $http_code >= 200 && $http_code < 300;
}

function send_password_reset_email(string $email, string $username, string $token): bool {
    $reset_url = SITE_URL . '/reset_password.php?token=' . urlencode($token);
    $subject = SITE_NAME . ' - Password Reset Request';
    $html = '
    <div style="font-family: monospace; background: #0b1a2a; color: #c8c8c8; padding: 30px; max-width: 600px;">
        <h2 style="color: #00e68a;">Password Reset</h2>
        <p>Hi ' . htmlspecialchars($username) . ',</p>
        <p>We received a request to reset your password. Click the link below to set a new password:</p>
        <p><a href="' . htmlspecialchars($reset_url) . '" style="color: #00e68a; font-weight: bold;">Reset My Password</a></p>
        <p>This link will expire in 1 hour.</p>
        <p>If you did not request this, you can safely ignore this email.</p>
        <br>
        <p style="color: #5a6a7a;">- ' . htmlspecialchars(SITE_NAME) . '</p>
    </div>';

    return send_email($email, $username, $subject, $html);
}

function send_contact_email(string $from_name, string $from_email, string $message): bool {
    $subject = SITE_NAME . ' - Contact Form: ' . $from_name;
    $html = '
    <div style="font-family: monospace; background: #0b1a2a; color: #c8c8c8; padding: 30px; max-width: 600px;">
        <h2 style="color: #00e68a;">Contact Form Submission</h2>
        <p><strong>From:</strong> ' . htmlspecialchars($from_name) . ' (' . htmlspecialchars($from_email) . ')</p>
        <hr style="border-color: #1a3a4a;">
        <p>' . nl2br(htmlspecialchars($message)) . '</p>
    </div>';

    return send_email(CONTACT_EMAIL, 'Admin', $subject, $html);
}
