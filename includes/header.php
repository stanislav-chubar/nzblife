<?php
/**
 * NZB.life - Shared Header (Navbar + Notification)
 * Variables expected: $page_title, $current_user (if logged in), $hide_notification (optional)
 */
if (!isset($page_title)) $page_title = SITE_NAME;
$is_logged_in = isset($current_user) && $current_user;
$show_notification = $is_logged_in && !($hide_notification ?? false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #3dff9e;
            --bg: #060a10;
            --card-border: rgba(93, 255, 180, 0.24);
            --text: #d7fbe6;
            --text-muted: #8899aa;
            --link: #5ad7ff;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--bg);
            background: linear-gradient(160deg, #060a10 0%, #071009 50%, #060a10 80%, #070d0b 100%);
            background-attachment: fixed;
            font-family: "JetBrains Mono", "Fira Code", "SFMono-Regular", Menlo, Consolas, "Liberation Mono", monospace !important;
            font-size: 14px;
            color: #c8c8c8;
            min-height: 100vh;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: #0b140d; }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(75, 178, 89, 0.9) 0%, rgba(55, 138, 68, 0.9) 100%);
            border: 2px solid #0b140d;
            border-radius: 10px;
        }

        a { color: var(--link); text-decoration: none; }
        a:hover {
            color: var(--accent);
            text-shadow: 0 0 12px rgba(61, 255, 158, 0.35);
        }

        /* Navbar */
        .navbar {
            background: rgba(6, 10, 16, 0.95);
            border-bottom: 1px solid rgba(93, 255, 180, 0.1);
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 52px;
        }
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .navbar-logo {
            display: flex;
            align-items: center;
            margin-right: 14px;
        }
        .navbar-logo img {
            height: 34px;
        }
        .nav-items {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .nav-item {
            position: relative;
            padding: 14px 14px;
            color: var(--link);
            font-size: 14px;
            cursor: pointer;
            transition: color 0.15s, background 0.15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .nav-item:hover {
            color: var(--accent);
            text-shadow: 0 0 12px rgba(61, 255, 158, 0.35);
        }
        /* Open state for dropdown toggle */
        .nav-item.open {
            background: linear-gradient(90deg, rgba(64, 210, 118, 0.26) 0%, rgba(44, 154, 92, 0.26) 100%);
            color: #e8fff1;
            border-color: rgba(120, 236, 141, 0.5);
        }
        .nav-item i.fa-caret-down { font-size: 10px; }
        .nav-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: rgba(8, 16, 24, 0.98);
            border: 1px solid var(--card-border);
            border-radius: 4px;
            min-width: 180px;
            z-index: 999;
            box-shadow: 0 6px 20px rgba(0,0,0,0.6);
            padding: 4px 0;
        }
        .nav-dropdown.open { display: block; }
        .nav-dropdown a {
            display: block;
            padding: 10px 16px;
            color: #c8c8c8;
            font-size: 13px;
            transition: background 0.15s, color 0.15s, border-color 0.15s, text-shadow 0.15s;
        }
        .nav-dropdown a:hover {
            background: linear-gradient(90deg, rgba(61, 255, 158, 0.22) 0%, rgba(90, 215, 255, 0.20) 100%);
            color: #c9fff4;
            border-color: rgba(93, 255, 180, 0.34);
            text-shadow: 0 0 10px rgba(61, 255, 158, 0.25);
        }
        .nav-dropdown a + a { border-top: 1px solid rgba(93, 255, 180, 0.1); }

        /* User avatar */
        .nav-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent);
            color: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
        }

        /* Search bar */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .nav-search {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .nav-search select {
            background: rgba(6, 14, 7, 0.8);
            color: var(--text-muted);
            border: 1px solid var(--card-border);
            padding: 6px 10px;
            font-family: "JetBrains Mono", monospace;
            font-size: 13px;
            border-radius: 4px 0 0 4px;
            outline: none;
            height: 34px;
            cursor: pointer;
        }
        .nav-search input {
            background: rgba(6, 14, 7, 0.6);
            color: #c8c8c8;
            border: 1px solid var(--card-border);
            border-left: none;
            padding: 6px 12px;
            font-family: "JetBrains Mono", monospace;
            font-size: 13px;
            width: 180px;
            outline: none;
            height: 34px;
        }
        .nav-search input::placeholder { color: #4a5a6a; }
        .nav-search input:focus { border-color: var(--accent); }
        .nav-search button {
            background: var(--accent);
            color: var(--bg);
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 0 4px 4px 0;
            font-size: 14px;
            height: 34px;
            display: flex;
            align-items: center;
            transition: background 0.15s;
        }
        .nav-search button:hover { background: #66ffbb; }

        /* Auth buttons (public pages) */
        .nav-auth {
            display: flex;
            gap: 8px;
        }
        .nav-auth a {
            padding: 6px 18px;
            font-size: 13px;
            border-radius: 4px;
            font-family: "JetBrains Mono", monospace;
            transition: all 0.15s;
        }
        .nav-auth .btn-login {
            background: linear-gradient(180deg, rgba(17, 26, 39, 0.95), rgba(11, 17, 26, 0.96));
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #5ad7ff !important;
        }
        .nav-auth .btn-login:hover {
            background: linear-gradient(180deg, rgba(16, 40, 20, 0.96) 0%, rgba(10, 26, 13, 0.96) 100%);
            border-color: rgba(100, 210, 114, 0.42);
            color: var(--accent) !important;
            text-shadow: none;
        }
        .nav-auth .btn-register {
            background: linear-gradient(180deg, rgba(17, 26, 39, 0.95), rgba(11, 17, 26, 0.96));
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #c8c8c8 !important;
        }
        .nav-auth .btn-register:hover {
            background: linear-gradient(180deg, rgba(16, 40, 20, 0.96) 0%, rgba(10, 26, 13, 0.96) 100%);
            border-color: rgba(100, 210, 114, 0.42);
            color: var(--accent) !important;
            text-shadow: none;
        }

        /* Notification bar */
        .notification-bar {
            background: linear-gradient(180deg, rgba(18, 44, 28, 0.96) 0%, rgba(12, 30, 19, 0.96) 100%);
            border: 1px solid rgba(112, 233, 140, 0.42);
            border-radius: 4px;
            padding: 14px 20px;
            margin: 16px 20px 0;
            color: #cbffd7;
            font-size: 14px;
            line-height: 1.7;
        }
        .notification-bar a {
            color: var(--link);
        }
        .notification-bar a:hover {
            color: var(--accent);
        }
        .notification-bar .dismiss {
            display: block;
            margin-top: 4px;
            color: var(--link);
            cursor: pointer;
            font-size: 13px;
        }
        .notification-bar .dismiss:hover {
            text-shadow: 0 0 12px rgba(61, 255, 158, 0.35);
        }

        /* Content wrapper */
        .page-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px 40px;
        }

        /* Flash Messages */
        .flash {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .flash-error {
            background: rgba(255, 60, 60, 0.1);
            border: 1px solid rgba(255, 60, 60, 0.3);
            color: #ff6666;
        }
        .flash-success {
            background: rgba(61, 255, 158, 0.06);
            border: 1px solid rgba(93, 255, 180, 0.3);
            color: var(--accent);
        }

        /* Headings */
        h1, h2, h3 {
            font-family: "JetBrains Mono", monospace;
        }

        /* Footer */
        .site-footer {
            text-align: center;
            padding: 30px 20px;
            color: #4a5a6a;
            font-size: 12px;
            border-top: 1px solid rgba(93, 255, 180, 0.1);
            margin-top: 40px;
        }
        .site-footer a { color: var(--link); }
        .site-footer p { margin-bottom: 4px; }
    </style>
    <?php if (isset($extra_css)) echo $extra_css; ?>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-left">
            <a href="<?= $is_logged_in ? 'dashboard.php' : 'index.php' ?>" class="navbar-logo">
                <img src="assets/nzblogo2.png" alt="<?= e(SITE_NAME) ?>">
            </a>
            <?php if ($is_logged_in): ?>
            <div class="nav-items">
                <div class="nav-item" onclick="toggleDropdown(this)">
                    Menu <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="dashboard.php"><i class="fas fa-home"></i>&nbsp; Home</a>
                        <a href="browse.php"><i class="fas fa-list"></i>&nbsp; Browse</a>
                        <a href="contact.php"><i class="fas fa-envelope"></i>&nbsp; Contact</a>
                    </div>
                </div>
                <div class="nav-item" onclick="toggleDropdown(this)">
                    <i class="fas fa-th"></i> Movies <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="browse.php?cat=movies">Browse All</a>
                        <a href="browse.php?cat=movies-sd">SD</a>
                        <a href="browse.php?cat=movies-hd">HD</a>
                        <a href="browse.php?cat=movies-uhd">UHD</a>
                        <a href="browse.php?cat=movies-foreign">Foreign</a>
                        <a href="browse.php?cat=movies-other">Other</a>
                    </div>
                </div>
                <div class="nav-item" onclick="toggleDropdown(this)">
                    <i class="fas fa-desktop"></i> TV <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="browse.php?cat=tv">Browse All</a>
                        <a href="browse.php?cat=tv-sd">SD</a>
                        <a href="browse.php?cat=tv-hd">HD</a>
                        <a href="browse.php?cat=tv-uhd">UHD</a>
                        <a href="browse.php?cat=tv-foreign">Foreign</a>
                        <a href="browse.php?cat=tv-other">Other</a>
                    </div>
                </div>
                <div class="nav-item" onclick="toggleDropdown(this)">
                    <i class="fas fa-book"></i> Books <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="browse.php?cat=books">Browse All</a>
                        <a href="browse.php?cat=books-ebook">eBooks</a>
                        <a href="browse.php?cat=books-comics">Comics</a>
                        <a href="browse.php?cat=books-mags">Magazines</a>
                        <a href="browse.php?cat=books-other">Other</a>
                    </div>
                </div>
                <div class="nav-item" onclick="toggleDropdown(this)">
                    <i class="fas fa-th-large"></i> Misc <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="browse.php?cat=misc">Browse All</a>
                        <a href="browse.php?cat=misc-games">Games</a>
                        <a href="browse.php?cat=misc-apps">Apps</a>
                        <a href="browse.php?cat=misc-audio">Music</a>
                        <a href="browse.php?cat=misc-other">Other</a>
                    </div>
                </div>
                <div class="nav-item" onclick="toggleDropdown(this)">
                    <span class="nav-avatar"><?= e($current_user['initial']) ?></span> <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="profile.php"><i class="fas fa-user"></i>&nbsp; Profile</a>
                        <a href="profile_edit.php"><i class="fas fa-pen"></i>&nbsp; Edit Profile</a>
                        <a href="vip.php"><i class="fas fa-star"></i>&nbsp; Upgrade VIP</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>&nbsp; Logout</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="navbar-right">
            <?php if ($is_logged_in): ?>
            <form class="nav-search" action="browse.php" method="GET">
                <select name="cat">
                    <option value="all">All</option>
                    <option value="movies">Movies</option>
                    <option value="tv">TV</option>
                    <option value="books">Books</option>
                    <option value="misc">Misc</option>
                </select>
                <input type="text" name="q" placeholder="Keyword Search">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <?php else: ?>
            <div class="nav-auth">
                <a href="login.php" class="btn-login">Login</a>
                <a href="register.php" class="btn-register">Register</a>
            </div>
            <?php endif; ?>
        </div>
    </nav>

    <?php if ($show_notification): ?>
    <div class="notification-bar" id="notificationBar">
        Thank you for registering, please feel free to look around. You cannot download or use the API without an upgraded account (<a href="vip.php">upgrade your account</a>). Free accounts are automatically removed after a few days.
        <span class="dismiss" onclick="document.getElementById('notificationBar').style.display='none'">Dismiss</span>
    </div>
    <?php endif; ?>
