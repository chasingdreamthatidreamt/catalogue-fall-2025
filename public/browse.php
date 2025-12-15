<?php
<<<<<<< HEAD
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../private/connect.php';
require_once __DIR__ . '/../private/functions.php';

$conn = db_connect();

$title = "Browse Foods";
$introduction = "Browse street foods — click a card to see full details.";

include __DIR__ . '/includes/header.php';

$result = $conn->query("SELECT * FROM catalogue_items ORDER BY id DESC");
?>

<style>
    .catalogue-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<h2 class="mb-4">Browse Foods</h2>

<div class="row">
    <?php while ($row = $result->fetch_assoc()): ?>
        <?php
        $id = (int) ($row['id'] ?? 0);
        $img = $row['image'] ?? null;


        $thumbSrc = $img ? "images/thumbs/" . e($img) : "images/no-image.png";
        ?>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm position-relative">

                <div class="ratio ratio-4x3">
                    <img src="<?= $thumbSrc; ?>" class="catalogue-thumb" alt="<?= e($row['title'] ?? ''); ?>">
                </div>
                <div class="card-body">
                    <a href="view.php?id=<?= $id; ?>" class="stretched-link"
                        aria-label="View <?= e($row['title'] ?? ''); ?>"></a>

                    <h5 class="card-title mb-1"><?= e($row['title']); ?></h5>

                    <p class="card-text text-muted mb-1">
                        <?= e($row['country']); ?>
                    </p>

                    <p class="card-text small text-secondary mb-3">
                        <?= e($row['foodType'] ?? ''); ?>
                    </p>


                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <div class="d-flex gap-2 position-relative" style="z-index: 2;">
                            <a href="edit.php?id=<?= $id; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete-confirmation.php?id=<?= $id; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </div>
                    <?php else: ?>
                        <span class="text-muted small">Click card to view</span>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>