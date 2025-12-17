<?php
require_once __DIR__ . '/../private/connect.php';
require_once __DIR__ . '/../private/functions.php';

$conn = db_connect();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errors = [];

$stmt = $conn->prepare("SELECT * FROM catalogue_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    $title = "Edit Item";
    $introduction = "Item not found.";
    include __DIR__ . '/includes/header.php';
    echo '<div class="alert alert-warning">Item not found.</div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titleVal = trim($_POST['title'] ?? '');
    $descVal = trim($_POST['description'] ?? '');
    $priceVal = trim($_POST['priceRange'] ?? '');
    $spiceVal = $_POST['spiceLevel'] ?? '';
    $ratingVal = $_POST['rating'] ?? null;

    if ($titleVal === '' || strlen($titleVal) > 50)
        $errors[] = "Title is required and must be ≤ 50 characters.";
    if ($descVal === '')
        $errors[] = "Description is required.";
    if ($ratingVal !== null && $ratingVal !== '' && (!is_numeric($ratingVal) || $ratingVal < 1.0 || $ratingVal > 5.0))
        $errors[] = "Rating must be between 1.0 and 5.0.";
    if ($spiceVal !== '' && !in_array($spiceVal, ['Mild', 'Medium', 'Hot'], true))
        $errors[] = "Spice level must be Mild, Medium, or Hot.";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE catalogue_items SET title=?, description=?, priceRange=?, spiceLevel=?, rating=? WHERE id=?");
        $stmt->bind_param("ssssdi", $titleVal, $descVal, $priceVal, $spiceVal, $ratingVal, $id);
        $stmt->execute();
        header("Location: view.php?id=" . $id);
        exit;
    }

    // keep form values on error
    $item['title'] = $titleVal;
    $item['description'] = $descVal;
    $item['priceRange'] = $priceVal;
    $item['spiceLevel'] = $spiceVal;
    $item['rating'] = $ratingVal;
}

$title = "Edit Item";
$introduction = "Update this street food item.";
include __DIR__ . '/includes/header.php';
?>

<h2 class="mb-3">Edit Street Food</h2>

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
        <input class="form-control" type="text" name="title" required value="<?= e($item['title']); ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="5" required><?= e($item['description']); ?></textarea>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Price Range</label>
            <input class="form-control" type="text" name="priceRange" value="<?= e($item['priceRange'] ?? ''); ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Spice Level</label>
            <select class="form-select" name="spiceLevel">
                <?php $s = $item['spiceLevel'] ?? ''; ?>
                <option value="">—</option>
                <option value="Mild" <?= $s === 'Mild' ? 'selected' : ''; ?>>Mild</option>
                <option value="Medium" <?= $s === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="Hot" <?= $s === 'Hot' ? 'selected' : ''; ?>>Hot</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Rating</label>
            <input class="form-control" type="number" step="0.1" min="1" max="5" name="rating"
                value="<?= e((string) ($item['rating'] ?? '')); ?>">
        </div>
    </div>

    <button class="btn btn-warning mt-4" type="submit">Save Changes</button>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>