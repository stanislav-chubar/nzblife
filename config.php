<?php
/**
 * NZB.life - Central Configuration
 */
if (basename($_SERVER['PHP_SELF']) === 'config.php') {
    http_response_code(403);
    exit('Forbidden');
}

// --- Load .env ---
$env_path = __DIR__ . '/.env';
if (!file_exists($env_path)) {
    die('.env file not found. Copy .env.example to .env and fill in your credentials.');
}
foreach (file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with(trim($line), '#')) continue;
    if (strpos($line, '=') === false) continue;
    [$key, $value] = explode('=', $line, 2);
    $_ENV[trim($key)] = trim($value);
}

// --- Database Configuration ---
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_CHARSET', $_ENV['DB_CHARSET']);
define('SENDGRID_API_KEY', $_ENV['SENDGRID_API_KEY']);
define('SENDGRID_FROM_EMAIL', $_ENV['SENDGRID_FROM_EMAIL']);
define('SENDGRID_FROM_NAME', $_ENV['SENDGRID_FROM_NAME']);
define('TURNSTILE_SITE_KEY', $_ENV['TURNSTILE_SITE_KEY'] ?? '');
define('TURNSTILE_SECRET_KEY', $_ENV['TURNSTILE_SECRET_KEY'] ?? '');

// --- Site Configuration ---
define('SITE_NAME', 'NZB.life');
define('SITE_URL', $_ENV['SITE_URL'] ?? 'https://nzb.life');
define('CONTACT_EMAIL', $_ENV['CONTACT_EMAIL'] ?? 'root@nzb.life');

define('SESSION_LIFETIME', 86400); // 24 hours

function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

// --- Session ---
function ensure_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// --- CSRF Token Helpers ---
function generate_csrf_token(): string {
    ensure_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token(string $token): bool {
    ensure_session();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrf_input(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(generate_csrf_token()) . '">';
}

// --- Flash Message Helpers ---
function set_flash(string $type, string $message): void {
    ensure_session();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    ensure_session();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function render_flash(): string {
    $flash = get_flash();
    if (!$flash) return '';
    $cls = $flash['type'] === 'error' ? 'flash-error' : 'flash-success';
    return '<div class="flash ' . $cls . '">' . e($flash['message']) . '</div>';
}

// --- Cloudflare Turnstile Verification ---
function verify_turnstile(string $token): bool {
    if (TURNSTILE_SECRET_KEY === '') return true;
    $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'secret' => TURNSTILE_SECRET_KEY,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    if (!$response) return false;
    $result = json_decode($response, true);
    return $result['success'] ?? false;
}

// --- Output Escaping ---
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// --- Time Helpers ---
function time_ago(string $datetime): string {
    $now = new DateTime('now', new DateTimeZone('UTC'));
    $then = new DateTime($datetime, new DateTimeZone('UTC'));
    $diff = $now->diff($then);
    $parts = [];
    if ($diff->days > 0) $parts[] = $diff->days . ' day' . ($diff->days !== 1 ? 's' : '');
    if ($diff->h > 0) $parts[] = $diff->h . ' hour' . ($diff->h !== 1 ? 's' : '');
    if ($diff->i > 0) $parts[] = $diff->i . ' min' . ($diff->i !== 1 ? 's' : '');
    return implode(', ', $parts) ?: 'just now';
}

function format_date(string $datetime): string {
    $dt = new DateTime($datetime, new DateTimeZone('UTC'));
    return $dt->format('M d, Y \a\t h:i A');
}

function format_date_long(string $datetime): string {
    $dt = new DateTime($datetime, new DateTimeZone('UTC'));
    return $dt->format('l jS F Y \@ h:i:sa');
}
