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

$extra_css = '<style>
    .profile-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 22px;
        color: #c8c8c8;
        margin-bottom: 28px;
    }
    .profile-heading i { color: #00e68a; }
    .profile-table {
        width: 100%;
        border-collapse: collapse;
    }
    .profile-table tr {
        border-bottom: 1px solid #0f2a3a;
    }
    .profile-table tr:nth-child(even) {
        background: rgba(0,230,138,0.02);
    }
    .profile-table td {
        padding: 12px 16px;
        font-size: 14px;
    }
    .profile-table .p-label {
        color: #8899aa;
        font-weight: bold;
        width: 240px;
        white-space: nowrap;
    }
    .profile-table .p-value {
        color: #c8c8c8;
    }
    .profile-table .p-value a { color: #00e68a; }
    .profile-table .p-value .vip-link {
        color: #00e68a;
    }
    .profile-edit-link {
        display: inline-block;
        margin-top: 20px;
        padding: 8px 22px;
        background: #0d2137;
        border: 1px solid #1a3a4a;
        border-radius: 4px;
        color: #00e68a;
        font-size: 14px;
    }
    .profile-edit-link:hover {
        background: rgba(0,230,138,0.1);
        border-color: #00e68a;
    }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <?= render_flash() ?>

        <h1 class="profile-heading">
            <i class="fas fa-user"></i> Profile for <?= e($current_user['username']) ?>
        </h1>

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
                        <span style="color:#00e68a;"><?= e($vip_display) ?></span>
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

        <a href="profile_edit.php" class="profile-edit-link"><i class="fas fa-pen"></i>&nbsp; Edit</a>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
