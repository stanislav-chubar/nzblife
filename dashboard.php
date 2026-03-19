<?php
/**
 * NZB.life - Welcome / Dashboard Page
 */
require_once __DIR__ . '/auth.php';

$page_title = 'Welcome';
$extra_css = '<style>
    .welcome-heading {
        font-size: 28px;
        color: #c8c8c8;
        margin-bottom: 20px;
    }
    .welcome-text {
        color: #8899aa;
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 12px;
    }
    .welcome-text a { color: #00e68a; }
    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-top: 30px;
    }
    @media (max-width: 768px) {
        .two-col { grid-template-columns: 1fr; }
    }
    .info-box {
        background: #0d2137;
        border: 1px solid #1a3a4a;
        border-radius: 6px;
        padding: 24px;
    }
    .info-box h3 {
        color: #00e68a;
        font-size: 18px;
        margin-bottom: 16px;
    }
    .info-box p, .info-box li {
        color: #8899aa;
        font-size: 13px;
        line-height: 1.8;
    }
    .info-box ul {
        padding-left: 20px;
    }
    .info-box a { color: #00e68a; }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <?= render_flash() ?>

        <h1 class="welcome-heading">Welcome</h1>

        <p class="welcome-text">Thank you for registering, please feel free to look around. You cannot download or use the API without an upgraded account (<a href="vip.php">upgrade your account</a>).</p>

        <p class="welcome-text">Please report any errors, missing or broken features to <a href="contact.php"><?= e(CONTACT_EMAIL) ?></a></p>

        <p class="welcome-text">There's been a lot of changes, if you're having issues, please use the email above or post in the forum. We'll respond quickly to help resolve it.</p>

        <div class="two-col">
            <div class="info-box">
                <h3>Upgrade Your Access</h3>
                <p>Unlock more grabs, faster API limits, and premium tools with VIP or VIPs.</p>
                <ul>
                    <li>Higher API and grab limits</li>
                    <li>Access to advanced search features</li>
                    <li>Priority support</li>
                    <li>Category and release filtering</li>
                </ul>
                <p style="margin-top: 14px;"><a href="vip.php">Compare VIP and VIPs features &rarr;</a></p>
            </div>
            <div class="info-box">
                <h3>Recently Implemented</h3>
                <ul>
                    <li>Trending page for Movies and TV with keyword filters</li>
                    <li>Fix movie quicksort updates and active popular view improvements</li>
                    <li>API/RSS Query Builder with quick smart output</li>
                    <li>Improved search/browse/category/group filter UI</li>
                    <li>Expanded media metadata + previews merged</li>
                    <li>Revamped notifications system + preview model support</li>
                </ul>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
