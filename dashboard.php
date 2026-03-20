<?php
/**
 * NZB.life - Welcome / Dashboard Page
 */
require_once __DIR__ . '/auth.php';

$page_title = 'Welcome';
$extra_css = '<style>
    .welcome-card {
        border: 1px solid var(--card-border);
        border-radius: 6px;
        padding: 30px 36px;
        margin-bottom: 24px;
    }
    .welcome-heading {
        font-size: 28px;
        color: var(--text);
        margin-bottom: 24px;
        font-weight: 400;
    }
    .welcome-text {
        color: var(--text);
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 16px;
    }
    .welcome-text a { color: var(--link); }
    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    @media (max-width: 768px) {
        .two-col { grid-template-columns: 1fr; }
    }
    .info-box {
        border: 1px solid var(--card-border);
        border-radius: 6px;
        padding: 28px 32px;
    }
    .info-box h3 {
        color: var(--text);
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 18px;
    }
    .info-box p {
        color: var(--text);
        font-size: 14px;
        line-height: 1.8;
    }
    .info-box .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .info-box .feature-list li {
        color: var(--text);
        font-size: 14px;
        line-height: 1.9;
        padding-left: 16px;
        position: relative;
    }
    .info-box .feature-list li::before {
        content: "-";
        position: absolute;
        left: 0;
        color: var(--text-muted);
    }
    .info-box a { color: var(--link); }
    .compare-btn {
        display: inline-block;
        margin-top: 18px;
        padding: 8px 22px;
        background: linear-gradient(180deg, rgba(17, 26, 39, 0.95), rgba(11, 17, 26, 0.96));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 4px;
        color: var(--link);
        font-family: "JetBrains Mono", monospace;
        font-size: 13px;
        transition: all 0.15s;
    }
    .compare-btn:hover {
        background: linear-gradient(180deg, rgba(16, 40, 20, 0.96) 0%, rgba(10, 26, 13, 0.96) 100%);
        border-color: rgba(100, 210, 114, 0.42);
        color: var(--accent);
        text-shadow: none;
    }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <?= render_flash() ?>

        <div class="welcome-card">
            <h1 class="welcome-heading">Welcome</h1>

            <p class="welcome-text">Please report any errors, missing or broken features to <?= e(CONTACT_EMAIL) ?></p>

            <p class="welcome-text">There's been a lot of changes, if you're having issues, please use the email above or post in the forum. We'll respond quickly to help resolve it.</p>
        </div>

        <div class="two-col">
            <div class="info-box">
                <h3>Upgrade Your Access</h3>
                <p>Unlock more grabs, higher API limits, and premium tools with VIP or VIP+.</p>
                <a href="vip.php" class="compare-btn">Compare VIP and VIP+ Features</a>
            </div>
            <div class="info-box">
                <h3>Recently Implemented</h3>
                <ul class="feature-list">
                    <li>Trending pages for Movies and TV with day/week filters</li>
                    <li>Top Grabs quicklook updates and anime popular view improvements</li>
                    <li>API/RSS query builders with guided summary output</li>
                    <li>Forum updates with better category/thread flow and voting improvements</li>
                    <li>Expanded media metadata refresh + preview modal support</li>
                </ul>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
