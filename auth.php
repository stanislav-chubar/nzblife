<?php
/**
 * NZB.life - Authentication Middleware
 * Include this file at the top of any protected page.
 */
require_once __DIR__ . '/config.php';
ensure_session();

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch current user from database
$db = get_db();
$stmt = $db->prepare('
    SELECT u.*, r.name AS role_name, r.display_name AS role_display
    FROM users u
    JOIN roles r ON u.role_id = r.id
    WHERE u.id = ?
');
$stmt->execute([$_SESSION['user_id']]);
$current_user = $stmt->fetch();

if (!$current_user) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Determine VIP status display
$current_user['vip_status'] = 'Not VIP Access NOW';
if ($current_user['vip_expires_at']) {
    $now = new DateTime('now', new DateTimeZone('UTC'));
    $vip_exp = new DateTime($current_user['vip_expires_at'], new DateTimeZone('UTC'));
    if ($vip_exp > $now) {
        $current_user['vip_status'] = 'Get VIP Access NOW!';
        $current_user['vip_active'] = true;
    } else {
        $current_user['vip_status'] = 'VIP Expired';
        $current_user['vip_active'] = false;
    }
} else {
    $current_user['vip_active'] = false;
}

// User initial for avatar
$current_user['initial'] = strtoupper(substr($current_user['username'], 0, 1));
