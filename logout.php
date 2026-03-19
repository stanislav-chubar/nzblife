<?php
/**
 * NZB.life - Logout
 */
require_once __DIR__ . '/config.php';
ensure_session();

// Destroy session and redirect to login
session_unset();
session_destroy();
header('Location: login.php');
exit;
