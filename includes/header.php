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
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0b1a2a;
            font-family: 'Share Tech Mono', 'Courier New', monospace;
            font-size: 14px;
            color: #c8c8c8;
            min-height: 100vh;
        }
        a { color: #00e68a; text-decoration: none; }
        a:hover { color: #33ffaa; }

        /* Navbar */
        .navbar {
            background: #0b1a2a;
            border-bottom: 1px solid #0f2a3a;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 50px;
        }
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .navbar-logo {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }
        .navbar-logo img {
            height: 32px;
        }
        .nav-items {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .nav-item {
            position: relative;
            padding: 14px 14px;
            color: #8899aa;
            font-size: 14px;
            cursor: pointer;
            transition: color 0.15s, background 0.15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
        }
        .nav-item i.fa-caret-down { font-size: 10px; }
        .nav-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #0d2137;
            border: 1px solid #1a3a4a;
            border-radius: 4px;
            min-width: 180px;
            z-index: 999;
            box-shadow: 0 6px 20px rgba(0,0,0,0.5);
        }
        .nav-dropdown.open { display: block; }
        .nav-dropdown a {
            display: block;
            padding: 10px 16px;
            color: #c8c8c8;
            font-size: 13px;
            transition: background 0.15s;
        }
        .nav-dropdown a:hover {
            background: rgba(0,230,138,0.1);
            color: #00e68a;
        }
        .nav-dropdown a + a { border-top: 1px solid #1a3a4a; }

        /* User avatar */
        .nav-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #00e68a;
            color: #0b1a2a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            margin-left: 6px;
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
            background: #0d2137;
            color: #8899aa;
            border: 1px solid #1a3a4a;
            padding: 6px 10px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 13px;
            border-radius: 4px 0 0 4px;
            outline: none;
            height: 34px;
        }
        .nav-search input {
            background: #0d2137;
            color: #c8c8c8;
            border: 1px solid #1a3a4a;
            border-left: none;
            padding: 6px 12px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 13px;
            width: 180px;
            outline: none;
            height: 34px;
        }
        .nav-search input::placeholder { color: #5a6a7a; }
        .nav-search button {
            background: #00e68a;
            color: #0b1a2a;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 0 4px 4px 0;
            font-size: 14px;
            height: 34px;
            display: flex;
            align-items: center;
        }
        .nav-search button:hover { background: #33ffaa; }

        /* Auth buttons (public pages) */
        .nav-auth {
            display: flex;
            gap: 8px;
        }
        .nav-auth a {
            padding: 6px 18px;
            font-size: 13px;
            border-radius: 4px;
            font-family: 'Share Tech Mono', monospace;
        }
        .btn-login {
            background: #00e68a;
            color: #0b1a2a !important;
            font-weight: bold;
        }
        .btn-login:hover { background: #33ffaa; }
        .btn-register {
            background: transparent;
            border: 1px solid #00e68a;
            color: #00e68a !important;
        }
        .btn-register:hover { background: rgba(0,230,138,0.1); }

        /* Notification bar */
        .notification-bar {
            background: rgba(0,230,138,0.05);
            border: 1px solid #1a5a3a;
            border-radius: 4px;
            padding: 14px 20px;
            margin: 16px 20px 0;
            color: #00e68a;
            font-size: 13px;
            line-height: 1.6;
        }
        .notification-bar a { color: #00e68a; text-decoration: underline; }
        .notification-bar .dismiss {
            display: block;
            margin-top: 4px;
            color: #00e68a;
            cursor: pointer;
            text-decoration: underline;
        }

        /* Content wrapper */
        .page-content {
            max-width: 1200px;
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
            border: 1px solid #5a2020;
            color: #ff6666;
        }
        .flash-success {
            background: rgba(0, 230, 138, 0.1);
            border: 1px solid #1a5a3a;
            color: #00e68a;
        }

        /* Headings */
        h1, h2, h3 {
            font-family: 'Share Tech Mono', monospace;
            font-weight: normal;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 8px 22px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            transition: background 0.15s;
        }
        .btn-green {
            background: #00e68a;
            color: #0b1a2a;
            font-weight: bold;
        }
        .btn-green:hover { background: #33ffaa; }
        .btn-outline {
            background: transparent;
            border: 1px solid #00e68a;
            color: #00e68a;
        }
        .btn-outline:hover { background: rgba(0,230,138,0.1); }
        .btn-dark {
            background: #1a2a3a;
            border: 1px solid #2a3a4a;
            color: #c8c8c8;
        }
        .btn-dark:hover { background: #2a3a4a; }

        /* Form inputs */
        .form-input {
            width: 100%;
            background: #0a1525;
            border: 1px solid #1a3a4a;
            color: #c8c8c8;
            padding: 10px 14px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 14px;
            border-radius: 4px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-input:focus { border-color: #00e68a; }
        .form-input::placeholder { color: #4a5a6a; }
        .input-with-icon {
            position: relative;
        }
        .input-with-icon .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #00e68a;
            font-size: 14px;
        }
        .input-with-icon .form-input {
            padding-left: 38px;
        }

        /* Footer */
        .site-footer {
            text-align: center;
            padding: 30px 20px;
            color: #5a6a7a;
            font-size: 12px;
            border-top: 1px solid #0f2a3a;
            margin-top: 40px;
        }
        .site-footer a { color: #00e68a; }
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
                    <i class="fas fa-bars"></i> Menu <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="dashboard.php"><i class="fas fa-home"></i>&nbsp; Home</a>
                        <a href="browse.php"><i class="fas fa-list"></i>&nbsp; Browse</a>
                        <a href="contact.php"><i class="fas fa-envelope"></i>&nbsp; Contact</a>
                    </div>
                </div>
                <div class="nav-item" onclick="toggleDropdown(this)">
                    <i class="fas fa-film"></i> Movies <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="browse.php?cat=movies">All Movies</a>
                        <a href="browse.php?cat=movies-hd">HD</a>
                        <a href="browse.php?cat=movies-sd">SD</a>
                        <a href="browse.php?cat=movies-4k">4K/UHD</a>
                    </div>
                </div>
                <div class="nav-item" onclick="toggleDropdown(this)">
                    <i class="fas fa-tv"></i> TV <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="browse.php?cat=tv">All TV</a>
                        <a href="browse.php?cat=tv-hd">HD</a>
                        <a href="browse.php?cat=tv-sd">SD</a>
                    </div>
                </div>
                <div class="nav-item" onclick="toggleDropdown(this)">
                    <i class="fas fa-book"></i> Books <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="browse.php?cat=books">All Books</a>
                        <a href="browse.php?cat=books-ebook">eBooks</a>
                        <a href="browse.php?cat=books-audio">Audiobooks</a>
                    </div>
                </div>
                <div class="nav-item" onclick="toggleDropdown(this)">
                    <i class="fas fa-th"></i> Misc <i class="fas fa-caret-down"></i>
                    <div class="nav-dropdown">
                        <a href="browse.php?cat=misc">All Misc</a>
                        <a href="browse.php?cat=misc-games">Games</a>
                        <a href="browse.php?cat=misc-apps">Apps</a>
                        <a href="browse.php?cat=misc-audio">Music</a>
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
