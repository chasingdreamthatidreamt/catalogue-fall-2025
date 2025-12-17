<?php
require_once __DIR__ . '/../private/connect.php';
require_once __DIR__ . '/../private/functions.php';

$conn = db_connect();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$title = "Street Food Details";
$introduction = "Full item details.";

include __DIR__ . '/includes/header.php';

$item = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM catalogue_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
}

/**
 * Normalize DB image values to a filename only.
 * Example inputs:
 *   public/images/churros.jpg
 *   images/churros.jpg
 *   churros.jpg
 * Output:
 *   churros.jpg
 */
function image_filename_only(?string $path): ?string
{
    if (empty($path))
        return null;
    $path = str_replace('\\', '/', $path);
    return basename($path);
}
?>

<style>
    .object-fit-cover {
        object-fit: cover;
    }
</style>

<p>
    <a href="browse.php" class="btn btn-sm btn-outline-secondary mb-3">← Back to Browse</a>
</p>

<?php if ($item): ?>
    <?php
    $imgRaw = $item['image'] ?? null;
    $imgFile = image_filename_only($imgRaw);

    // Your folder is "fullsize" (not "full-size")
    $fullSrc = $imgFile ? "images/fullsize/" . e($imgFile) : "images/no-image.png";
    ?>

    <div class="row g-4">
        <div class="col-md-5">
            <!-- Consistent image size -->
            <div class="ratio ratio-4x3 rounded shadow-sm overflow-hidden bg-light">
                <img src="<?= $fullSrc; ?>" alt="<?= e($item['title']); ?>" class="w-100 h-100 object-fit-cover">
            </div>
        </div>

        <div class="col-md-7">
            <h2 class="text-danger"><?= e($item['title']); ?></h2>
            <p class="text-muted mb-2"><strong>Country:</strong> <?= e($item['country']); ?></p>

            <?php if (!empty($item['rating'])): ?>
                <p class="mb-2"><strong>Rating:</strong> <?= e((string) $item['rating']); ?>/5</p>
            <?php endif; ?>

            <p><?= nl2br(e($item['description'])); ?></p>

            <hr>

            <dl class="row mb-0">
                <dt class="col-sm-4">Region</dt>
                <dd class="col-sm-8"><?= e($item['region'] ?? 'N/A'); ?></dd>

                <dt class="col-sm-4">Food Type</dt>
                <dd class="col-sm-8"><?= e($item['foodType'] ?? 'N/A'); ?></dd>

                <dt class="col-sm-4">Cooking Method</dt>
                <dd class="col-sm-8"><?= e($item['cookingMethod'] ?? 'N/A'); ?></dd>

                <dt class="col-sm-4">Spice Level</dt>
                <dd class="col-sm-8"><?= e($item['spiceLevel'] ?? 'N/A'); ?></dd>

                <dt class="col-sm-4">Price Range</dt>
                <dd class="col-sm-8"><?= e($item['priceRange'] ?? 'N/A'); ?></dd>

                <dt class="col-sm-4">Main Ingredients</dt>
                <dd class="col-sm-8">
                    <?= !empty($item['mainIngredients']) ? nl2br(e($item['mainIngredients'])) : 'N/A'; ?>
                </dd>
            </dl>

            <?php if (!empty($_SESSION['user_id'])): ?>
                <div class="mt-4 d-flex gap-2 flex-wrap">
                    <a href="edit.php?id=<?= (int) $item['id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="delete-confirmation.php?id=<?= (int) $item['id']; ?>" class="btn btn-danger">Delete</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>
    <div class="alert alert-warning">Item not found.</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>