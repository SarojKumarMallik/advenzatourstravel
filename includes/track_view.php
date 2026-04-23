<?php
/**
 * track_view.php
 * Include this at the top of any front-end page (after config/db.php).
 * Increments the `views` column in page_seo once per session per page.
 * If the page doesn't exist in page_seo, it inserts a new row automatically.
 */

if (!isset($pdo)) return; // safety – no DB, skip

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Map actual PHP filenames → page_name label
$page_name_map = [
    // Root pages
    'index.php'                   => 'Home',
    'about.php'                   => 'About',
    'blog.php'                    => 'Blog',
    'contact.php'                 => 'Contact',
    'doctors.php'                    => 'Doctors',
    'service.php'                 => 'service',
];

$current_file = basename($_SERVER['PHP_SELF']);
$page_name    = $page_name_map[$current_file] ?? null;

if (!$page_name) return; // page not in map, skip

// Track once per session per page to avoid refresh inflation
$session_key = 'viewed_' . md5($page_name);
if (!empty($_SESSION[$session_key])) return;

try {
    // Ensure views column exists
    $pdo->exec("ALTER TABLE page_seo ADD COLUMN IF NOT EXISTS views INT NOT NULL DEFAULT 0");
} catch (Exception $e) { /* ignore */ }

try {
    // Try to increment existing row
    $stmt = $pdo->prepare("UPDATE page_seo SET views = views + 1 WHERE page_name = ?");
    $stmt->execute([$page_name]);

    // If no row was updated, insert a new one
    if ($stmt->rowCount() === 0) {
        $ins = $pdo->prepare("INSERT INTO page_seo (page_name, views) VALUES (?, 1)");
        $ins->execute([$page_name]);
    }

    $_SESSION[$session_key] = true;
} catch (Exception $e) { /* silently fail */ }
?>
