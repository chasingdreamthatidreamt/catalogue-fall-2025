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
=======
$title = "Browse Catalogue";
include 'includes/header.php';
include 'public/includes/filters.php';
include 'private/functions.php';
?>

<main class="container">
    <section class="row my-5">
        <div class="col-md-10 col-lg-8 col-xxl-6 mb-4">
            <h2 class="display-4">Welcome to <span class="d-block text-danger">Global Flavors</span></h2>
            <p>Explore our catalogue of international dishes from around the world! Browse through a wide variety of foods, filter by country, type, spice level, or price range, and click on any item to view detailed information.</p>
        </div>
    </section>

    <section class="row my-5">
        <!-- Filters -->
        <aside class="col-lg-4 border border-secondary-subtle mb-3 mb-md-0 p-3 rounded">
            <h3 class="fw-light mt-4">Filter The Data</h3>
            <p class="muted">Select any combination of the buttons below to filter the catalogue.</p>
            <hr class="my-4">

            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                <!-- Example: Country Filter -->
                <h5>Country</h5>
                <?php
                $countries = ['Japan','Mexico','Italy','India','Spain','Canada','Turkey','Korea','France','Brazil','China','USA','Middle East','UK'];
                foreach ($countries as $country): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="country[]" value="<?= $country ?>" id="country-<?= $country ?>">
                        <label class="form-check-label" for="country-<?= $country ?>"><?= $country ?></label>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-danger">Apply Filters</button>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">Clear Filters</a>
                </div>
            </form>
        </aside>

        <!-- Catalogue Items -->
        <div class="col-lg-8">
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php
                require_once 'includes/functions.php';
                $items = get_all_items(); // fetch all items from DB, optionally apply filters

                foreach ($items as $item):
                    $thumbnail = htmlspecialchars($item['image']); // e.g., images/thumbs/sushi.jpg
                    $id = $item['id'];
                    $title = htmlspecialchars($item['title']);
                    $country = htmlspecialchars($item['country']);
                    $price = htmlspecialchars($item['priceRange']);
                ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="<?= $thumbnail ?>" class="card-img-top" alt="<?= $title ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $title ?></h5>
                            <p class="card-text"><strong>Country:</strong> <?= $country ?></p>
                            <p class="card-text"><strong>Price:</strong> <?= $price ?></p>
                            <a href="single_item.php?id=<?= $id ?>" class="btn btn-danger">View More</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
>>>>>>> 378471ddc1ab7a5cde78a36fd6364c937fc8d486

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