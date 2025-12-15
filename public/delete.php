<?php
require_once __DIR__ . '/../private/connect.php';

$conn = db_connect();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM catalogue_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: browse.php");
    exit;
}

header("Location: browse.php");
exit;
