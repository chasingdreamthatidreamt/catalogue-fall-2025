<?php
require '../private/connect.php';
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("DELETE FROM catalogue_items WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: browse.php");
    exit;
}
?>
