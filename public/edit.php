<?php
require '../private/connect.php';
$id = $_GET['id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $priceRange = trim($_POST['priceRange']);
    $spiceLevel = $_POST['spiceLevel'];
    $rating = $_POST['rating'];

    // Validation
    if (empty($title) || strlen($title) > 50) {
        $errors[] = "Title is required and must be ≤ 50 characters.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    }
    if (!is_numeric($rating) || $rating < 1.0 || $rating > 5.0) {
        $errors[] = "Rating must be a number between 1.0 and 5.0.";
    }
    if (!in_array($spiceLevel, ['Mild','Medium','Hot'])) {
        $errors[] = "Spice level must be Mild, Medium, or Hot.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE catalogue_items SET title=?, description=?, priceRange=?, spiceLevel=?, rating=? WHERE id=?");
        $stmt->bind_param("ssssdi", $title, $description, $priceRange, $spiceLevel, $rating, $id);
        $stmt->execute();
        header("Location: view.php?id=$id");
        exit;
    }
}

$result = $conn->query("SELECT * FROM catalogue_items WHERE id=$id");
$item = $result->fetch_assoc();
?>
