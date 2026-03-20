<?php
/**
 * NZB.life - Profile View Page
 */
require_once __DIR__ . '/auth.php';

$page_title = 'Profile for ' . $current_user['username'];

// Calculate VIP status display
$vip_display = 'Get VIP Access NOW!';
if ($current_user['vip_active']) {
    $vip_exp = new DateTime($current_user['vip_expires_at'], new DateTimeZone('UTC'));
    $vip_display = 'VIP (expires ' . $vip_exp->format('M d, Y') . ')';
}

$reg_date = format_date($current_user['registered_at']);
$reg_ago = time_ago($current_user['registered_at']);
$last_login = $current_user['last_login_at'] ? format_date($current_user['last_login_at']) : 'Never';
$last_login_ago = $current_user['last_login_at'] ? time_ago($current_user['last_login_at']) : '';

$hide_notification = true;
$extra_css = '<style>
    .profile-card {
        max-width: 900px;
        margin: 0 auto;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        padding: 30px 36px;
    }
    .profile-card .notification-bar {
        margin: 0 0 24px;
    }
    .profile-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 22px;
        color: #c8c8c8;
        margin-bottom: 28px;
    }
    .profile-heading i { color: var(--accent); }
    .profile-fieldset {
        border: 1px solid var(--card-border);
        border-radius: 4px;
        padding: 24px 20px;
        margin-bottom: 24px;
    }
    .profile-fieldset legend {
        color: #c8c8c8;
        font-size: 16px;
        font-weight: bold;
        padding: 0 10px;
    }
    .profile-table {
        width: 100%;
        border-collapse: collapse;
    }
    .profile-table tr {
        border-bottom: 1px solid rgba(93, 255, 180, 0.08);
    }
    .profile-table tr:last-child { border-bottom: none; }
    .profile-table td {
        padding: 12px 16px;
        font-size: 14px;
    }
    .profile-table .p-label {
        color: #c8c8c8;
        font-weight: bold;
        width: 240px;
        white-space: nowrap;
    }
    .profile-table .p-value {
        color: #c8c8c8;
    }
    .profile-table .p-value a { color: var(--accent); }
    .profile-table .p-value .vip-link {
        color: var(--accent);
    }
    .profile-edit-link {
        display: inline-block;
        padding: 10px 28px;
        background: linear-gradient(180deg, rgba(17, 26, 39, 0.95), rgba(11, 17, 26, 0.96));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 4px;
        color: var(--text);
        font-size: 14px;
        transition: all 0.15s;
    }
    .profile-edit-link:hover {
        background: linear-gradient(180deg, rgba(16, 40, 20, 0.96) 0%, rgba(10, 26, 13, 0.96) 100%);
        border-color: rgba(100, 210, 114, 0.42);
        color: var(--accent);
    }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <div class="profile-card">
            <?php if (isset($current_user) && $current_user): ?>
            <div class="notification-bar" id="notificationBar">
                Thank you for registering, please feel free to look around. You cannot download or use the API without an upgraded account (<a href="vip.php">upgrade your account</a>). Free accounts are automatically removed after a few days.
                <span class="dismiss" onclick="document.getElementById('notificationBar').style.display='none'">Dismiss</span>
            </div>
            <?php endif; ?>

            <?= render_flash() ?>

            <h1 class="profile-heading">
                <i class="fas fa-user"></i> Profile for <?= e($current_user['username']) ?>
            </h1>

            <fieldset class="profile-fieldset">
                <legend>User Details</legend>
                <table class="profile-table">
                    <tr>
                        <td class="p-label">Username:</td>
                        <td class="p-value"><?= e($current_user['username']) ?></td>
                    </tr>
                    <tr>
                        <td class="p-label">Email:</td>
                        <td class="p-value"><?= e($current_user['email']) ?></td>
                    </tr>
                    <tr>
                        <td class="p-label">Registered:</td>
                        <td class="p-value"><?= e($reg_date) ?> (<?= e($reg_ago) ?> ago)</td>
                    </tr>
                    <tr>
                        <td class="p-label">Last Login:</td>
                        <td class="p-value"><?= e($last_login) ?><?php if ($last_login_ago): ?> (<?= e($last_login_ago) ?> ago)<?php endif; ?></td>
                    </tr>
                    <tr>
                        <td class="p-label">Role:</td>
                        <td class="p-value"><?= e($current_user['role_display']) ?></td>
                    </tr>
                    <tr>
                        <td class="p-label">VIP Status:</td>
                        <td class="p-value">
                            <?php if ($current_user['vip_active']): ?>
                                <span style="color:var(--accent);"><?= e($vip_display) ?></span>
                            <?php else: ?>
                                <a href="vip.php" class="vip-link"><?= e($vip_display) ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-label">API Hits Today:</td>
                        <td class="p-value"><?= (int)$current_user['api_hits_today'] ?> / 5 (5 is the max)</td>
                    </tr>
                    <tr>
                        <td class="p-label">Grabs Total:</td>
                        <td class="p-value"><?= (int)$current_user['grabs_total'] ?></td>
                    </tr>
                    <tr>
                        <td class="p-label">Logout Session On IP Change:</td>
                        <td class="p-value"><?= $current_user['logout_session_on_ip_change'] ? 'Yes' : 'No' ?></td>
                    </tr>
                </table>
            </fieldset>

            <a href="profile_edit.php" class="profile-edit-link"><i class="fas fa-pen"></i>&nbsp; Edit</a>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
