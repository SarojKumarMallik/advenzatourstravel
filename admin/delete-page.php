<?php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['page'])) {
    die("Invalid request");
}

$page_name = $_GET['page'];

try {
    // DELETE from correct table
    $stmt = $pdo->prepare("DELETE FROM page_seo WHERE page_name = ?");
    $stmt->execute([$page_name]);

    header("Location: pages.php?msg=deleted");
    exit;

} catch (PDOException $e) {
    die("Error deleting: " . $e->getMessage());
}