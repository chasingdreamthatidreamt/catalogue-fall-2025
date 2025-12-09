<?php
require '../private/connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // inputs
    $title = trim($_POST['title']);
    $country = trim($_POST['country']);
    $description = trim($_POST['description']);
    $priceRange = trim($_POST['priceRange']);
    $spiceLevel = $_POST['spiceLevel'];
    $rating = $_POST['rating'];

    // Validation
    if (empty($title) || strlen($title) > 50) {
        $errors[] = "Title is required and must be ≤ 50 characters.";
    }
    if (empty($country) || strlen($country) > 100) {
        $errors[] = "Country is required and must be ≤ 100 characters.";
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

    // If no errors, insert
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO catalogue_items (title, country, description, priceRange, spiceLevel, rating) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssd", $title, $country, $description, $priceRange, $spiceLevel, $rating);
        $stmt->execute();
        header("Location: browse.php");
        exit;
    }
}
?>

<?php include('includes/header.php'); ?>
<h2>Add New Street Food</h2>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    <label>Title:</label><input type="text" name="title" required><br>
    <label>Country:</label><input type="text" name="country" required><br>
    <label>Description:</label><textarea name="description" required></textarea><br>
    <label>Price Range:</label><input type="text" name="priceRange"><br>
    <label>Spice Level:</label>
    <select name="spiceLevel">
        <option>Mild</option><option>Medium</option><option>Hot</option>
    </select><br>
    <label>Rating:</label><input type="number" step="0.1" min="1" max="5" name="rating"><br>
    <button type="submit">Add Item</button>
</form>
<?php include('includes/footer.php'); ?>
