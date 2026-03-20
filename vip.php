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
        margin-bottom: 16px;
    }
    .plan-membership-box {
        border: 1px solid var(--card-border);
        border-radius: 4px;
        padding: 12px 18px;
        margin-bottom: 16px;
        color: #c8c8c8;
        font-size: 14px;
    }
    .plan-subtitle {
        color: #8899aa;
        font-size: 14px;
        margin-bottom: 24px;
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
        border: 1px solid var(--card-border);
        border-radius: 6px;
        padding: 28px;
        position: relative;
    }
    .plan-card h2 {
        color: #c8c8c8;
        font-size: 18px;
        margin-bottom: 16px;
        display: inline-block;
        border: 1px solid var(--card-border);
        padding: 4px 16px;
        border-radius: 4px;
        background: rgba(6, 10, 16, 0.95);
        position: relative;
        top: -42px;
        margin-bottom: -24px;
    }
    .plan-card .plan-type {
        color: #c8c8c8;
        font-size: 14px;
        margin-bottom: 20px;
    }
    .plan-card .plan-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 22px;
    }
    .plan-btn-yellow {
        background: #e6a800;
        color: #0b1a2a;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-family: "JetBrains Mono", monospace;
        font-size: 13px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.15s;
    }
    .plan-btn-yellow:hover { background: #ffbf00; }
    .plan-btn-green {
        background: #00e68a;
        color: #0b1a2a;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-family: "JetBrains Mono", monospace;
        font-size: 13px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.15s;
    }
    .plan-btn-green:hover { background: #33ffaa; }

    .plan-card .plan-label {
        color: #c8c8c8;
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 12px;
    }
    .plan-card ul {
        list-style: disc;
        padding-left: 22px;
    }
    .plan-card ul li {
        color: #c8c8c8;
        font-size: 14px;
        padding: 3px 0;
    }
    .plan-card ul ul {
        list-style: circle;
        padding-left: 22px;
        margin-top: 4px;
    }
    .plan-card ul ul li {
        font-size: 13px;
    }
    .vip-wrapper {
        max-width: 1000px;
        margin: 0 auto;
    }
</style>';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <div class="vip-wrapper">
        <h1 class="plan-heading">Choose Plan</h1>
        <div class="plan-membership-box">Current Membership: <?= e($current_user['role_display']) ?></div>
        <p class="plan-subtitle">Choose VIP or VIP+ and continue to your payment method.</p>

        <div class="plan-columns">
            <!-- VIP Plan -->
            <div class="plan-card">
                <h2>VIP</h2>
                <p class="plan-type">Standard paid access plan.</p>

                <div class="plan-buttons">
                    <button class="plan-btn-yellow" onclick="alert('Payment integration coming soon.')">Pay with Card (VIP)</button>
                    <button class="plan-btn-green" onclick="alert('Payment integration coming soon.')">Pay with Crypto (VIP)</button>
                </div>

                <p class="plan-label">VIP Features</p>
                <ul>
                    <li>More releases shown</li>
                    <li>Downloads: up to 600 per day</li>
                    <li>API/RSS access: up to 5,000 queries per day</li>
                    <li>Theme selection</li>
                    <li>Top and recent Movie/TV pages</li>
                    <li>Trending Movies, TV, Adult</li>
                    <li>Popular Anime Quicklook page</li>
                    <li>Enhanced UI (hover cards, info, posters, images, previews)</li>
                    <li>MyShows and MyMovies</li>
                    <li>Invites when registration is closed</li>
                    <li>UI preferences (views)</li>
                    <li>SABnzbd, NZBGet, NZBVortex integrations</li>
                    <li>View all categories</li>
                    <li>Exclude categories you don&rsquo;t want to see</li>
                </ul>
            </div>

            <!-- VIP+ Plan -->
            <div class="plan-card">
                <h2>VIP+</h2>
                <p class="plan-type">Higher limits and all advanced features.</p>

                <div class="plan-buttons">
                    <button class="plan-btn-yellow" onclick="alert('Payment integration coming soon.')">Pay with Card (VIP+)</button>
                    <button class="plan-btn-green" onclick="alert('Payment integration coming soon.')">Pay with Crypto (VIP+)</button>
                </div>

                <p class="plan-label">VIP+ Features</p>
                <ul>
                    <li>Everything in VIP</li>
                    <li>Downloads: up to 1,200 per day</li>
                    <li>API/RSS access: up to 10,000 queries per day</li>
                    <li>API Query Builder access (API Help page)</li>
                    <li>RSS Query Builder access (RSS Help page)</li>
                    <li>Advanced Search access (web UI), including:
                        <ul>
                            <li>Release name and filename matching</li>
                            <li>Category and resolution filters</li>
                            <li>Posted date range filtering</li>
                            <li>Minimum grabs filtering</li>
                            <li>Season/episode match filtering</li>
                            <li>Size range filtering</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
