<?php
/**
 * NZB.life - Choose Plan / VIP Upgrade Page
 */
require_once __DIR__ . '/auth.php';

$page_title = 'Choose Plan';

$extra_css = '<style>
    .plan-heading {
        font-size: 24px;
        color: #c8c8c8;
        margin-bottom: 8px;
    }
    .plan-membership {
        color: #8899aa;
        font-size: 14px;
        margin-bottom: 6px;
    }
    .plan-membership strong { color: #00e68a; }
    .plan-subtitle {
        color: #5a6a7a;
        font-size: 13px;
        margin-bottom: 30px;
    }

    .plan-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    @media (max-width: 768px) {
        .plan-columns { grid-template-columns: 1fr; }
    }

    .plan-card {
        background: #0d2137;
        border: 1px solid #1a3a4a;
        border-radius: 6px;
        padding: 28px;
    }
    .plan-card h2 {
        color: #c8c8c8;
        font-size: 20px;
        margin-bottom: 6px;
        text-align: center;
    }
    .plan-card .plan-type {
        text-align: center;
        color: #5a6a7a;
        font-size: 12px;
        margin-bottom: 20px;
    }
    .plan-card .plan-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        margin-bottom: 22px;
    }
    .plan-btn-yellow {
        background: #e6a800;
        color: #0b1a2a;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        font-family: "Share Tech Mono", monospace;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
    }
    .plan-btn-yellow:hover { background: #ffbf00; }
    .plan-btn-green {
        background: #00e68a;
        color: #0b1a2a;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        font-family: "Share Tech Mono", monospace;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
    }
    .plan-btn-green:hover { background: #33ffaa; }

    .plan-card .plan-label {
        color: #8899aa;
        font-size: 13px;
        margin-bottom: 12px;
    }
    .plan-card ul {
        list-style: none;
        padding: 0;
    }
    .plan-card ul li {
        color: #8899aa;
        font-size: 13px;
        padding: 4px 0;
        padding-left: 20px;
        position: relative;
    }
    .plan-card ul li::before {
        content: "\\2713";
        color: #00e68a;
        position: absolute;
        left: 0;
    }
    .plan-card .plan-section-title {
        color: #c8c8c8;
        font-size: 14px;
        margin: 16px 0 10px;
        border-bottom: 1px solid #1a3a4a;
        padding-bottom: 6px;
    }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <h1 class="plan-heading">Choose Plan</h1>
        <p class="plan-membership">Current Membership: <strong><?= e($current_user['role_display']) ?></strong></p>
        <p class="plan-subtitle">Choose VIP or VIPs and continue to your payment method.</p>

        <div class="plan-columns">
            <!-- VIP Plan -->
            <div class="plan-card">
                <h2>VIP</h2>
                <p class="plan-type">Standard paid access plan.</p>

                <div class="plan-buttons">
                    <button class="plan-btn-yellow" onclick="alert('Payment integration coming soon.')">Buy with Card (USD)</button>
                    <button class="plan-btn-green" onclick="alert('Payment integration coming soon.')">Buy with Crypto (USD)</button>
                </div>

                <p class="plan-label">VIP Features:</p>
                <ul>
                    <li>More releases shown</li>
                    <li>NZB/RSS: access up to 1,000 queries per day</li>
                    <li>Top 10 curated Movies/TV pages</li>
                    <li>Trending Movies, TV, music</li>
                    <li>Popular Online Quickbox page</li>
                    <li>Enhanced UI (Cover cards, info, posters, images, previews)</li>
                    <li>Add/use on Webhooks</li>
                    <li>Invites when registration is closed</li>
                    <li>All processing Credits</li>
                    <li>VeNoMz, bObOoZ, FiXie/FaN integrations</li>
                    <li>View comments in list</li>
                    <li>Exclude categories you don't want to see</li>
                </ul>
            </div>

            <!-- VIPs Plan -->
            <div class="plan-card">
                <h2>VIPs</h2>
                <p class="plan-type">Higher limits and all advanced features.</p>

                <div class="plan-buttons">
                    <button class="plan-btn-yellow" onclick="alert('Payment integration coming soon.')">Buy with Card (USD)</button>
                    <button class="plan-btn-green" onclick="alert('Payment integration coming soon.')">Buy with Crypto (USD)</button>
                </div>

                <p class="plan-label">VIPs Features:</p>
                <ul>
                    <li>Everything in VIP</li>
                    <li>Bandwidth: no 1,300 NZB max</li>
                    <li>NZB/RSS: access up to 10,000 queries per day</li>
                    <li>VIPs Query Builder access (API help page)</li>
                    <li>Advanced Search All/TV, Including:</li>
                </ul>

                <p class="plan-section-title">Advanced Search Features</p>
                <ul>
                    <li>Category and release filtering</li>
                    <li>Partial date filtering</li>
                    <li>Include group filtering</li>
                    <li>VIPs only categories</li>
                    <li>Mass/multiple downloading</li>
                    <li>View Comments</li>
                </ul>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
