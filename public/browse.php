<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../private/connect.php';
$conn = db_connect();

$title = "Browse Foods";
include 'includes/header.php';

$result = $conn->query("SELECT * FROM catalogue_items ORDER BY id DESC");
?>

<main class="container my-5">
    <h2 class="mb-4">Browse Foods</h2>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">

                    <?php
                    $item_id = $row['item_id'];

                    // FIXED QUERY
                    $imgQuery = $conn->query("
                        SELECT filename 
                        FROM group2_item_images 
                        WHERE item_id = $item_id 
                        LIMIT 1
                    ");

                    if ($imgQuery && $imgQuery->num_rows > 0) {
                        $imageFile = $imgQuery->fetch_assoc()['filename'];
                        echo "<img src='images/thumbs/$imageFile' class='card-img-top'>";
                    } else {
                        echo "<img src='images/no-image.png' class='card-img-top'>";
                    }
                    ?>

                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['title']); ?></h5>
                        <p class="card-text"><?= htmlspecialchars($row['country']); ?></p>

                        <a href="view.php?id=<?= $row['item_id']; ?>" class="btn btn-primary">View</a>
                        <a href="edit.php?id=<?= $row['item_id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?= $row['item_id']; ?>" class="btn btn-danger">Delete</a>
                    </div>

                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
