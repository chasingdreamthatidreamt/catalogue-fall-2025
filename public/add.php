<?php
require_once __DIR__ . '/../private/connect.php';
require_once __DIR__ . '/../private/functions.php';

$conn = db_connect();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priceRange = trim($_POST['priceRange'] ?? '');
    $spiceLevel = $_POST['spiceLevel'] ?? '';
    $rating = $_POST['rating'] ?? null;

    if ($title === '' || strlen($title) > 50)
        $errors[] = "Title is required and must be ≤ 50 characters.";
    if ($country === '' || strlen($country) > 100)
        $errors[] = "Country is required and must be ≤ 100 characters.";
    if ($description === '')
        $errors[] = "Description is required.";
    if ($rating !== null && $rating !== '' && (!is_numeric($rating) || $rating < 1.0 || $rating > 5.0))
        $errors[] = "Rating must be between 1.0 and 5.0.";
    if ($spiceLevel !== '' && !in_array($spiceLevel, ['Mild', 'Medium', 'Hot'], true))
        $errors[] = "Spice level must be Mild, Medium, or Hot.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO catalogue_items (title, country, description, priceRange, spiceLevel, rating) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssd", $title, $country, $description, $priceRange, $spiceLevel, $rating);
        $stmt->execute();
        header("Location: browse.php");
        exit;
    }
}

$titlePage = "Add Item";
$title = $titlePage;
$introduction = "Add a new street food item.";

include __DIR__ . '/includes/header.php';
?>

<h2 class="mb-3">Add New Street Food</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= e($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input class="form-control" type="text" name="title" required value="<?= e($_POST['title'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Country</label>
        <input class="form-control" type="text" name="country" required value="<?= e($_POST['country'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="5"
            required><?= e($_POST['description'] ?? '') ?></textarea>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Price Range</label>
            <input class="form-control" type="text" name="priceRange" value="<?= e($_POST['priceRange'] ?? '') ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Spice Level</label>
            <select class="form-select" name="spiceLevel">
                <?php $s = $_POST['spiceLevel'] ?? ''; ?>
                <option value="">—</option>
                <option value="Mild" <?= $s === 'Mild' ? 'selected' : ''; ?>>Mild</option>
                <option value="Medium" <?= $s === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="Hot" <?= $s === 'Hot' ? 'selected' : ''; ?>>Hot</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Rating (1–5)</label>
            <input class="form-control" type="number" step="0.1" min="1" max="5" name="rating"
                value="<?= e((string) ($_POST['rating'] ?? '')) ?>">
        </div>
    </div>

    <button class="btn btn-success mt-4" type="submit">Add Item</button>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>