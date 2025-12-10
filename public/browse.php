<?php
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

<?php include 'includes/footer.php'; ?>
