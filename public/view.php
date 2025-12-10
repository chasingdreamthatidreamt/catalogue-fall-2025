<?php

require_once '../private/connect.php'; 

$item_details = null; 
$item_id = $_GET['id'] ?? null;
$title = "Street Food Details"; 


if (isset($item_id) && is_numeric($item_id)) {
    
    
    try {
        $sql = "SELECT * FROM catalogue_items WHERE id = :id";
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
        
        
        $stmt->execute();
        
        
        $item_details = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($item_details) {
            $title = htmlspecialchars($item_details['title']) . " Details";
        }
        
    } catch (PDOException $e) {
        $item_details = false; 
        error_log("Database error fetching item details: " . $e->getMessage());
    }
} 

include 'includes/header.php';
?>

<main class="container my-5">

    <div class="row">
        <div class="col-12">
            <p><a href="browse.php" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to Catalogue</a></p>
        </div>
    </div>

    <?php if ($item_details): ?>
        <article class="row g-5">
            
            <div class="col-md-6 col-lg-5 order-md-1 order-2">
                
                <?php if ($item_details['image']): ?>
                    <figure class="figure w-100 mt-4">
                        <img 
                            src="../images/fullsize/<?php echo htmlspecialchars($item_details['image']); ?>" 
                            class="figure-img img-fluid rounded shadow-lg" 
                            alt="Full view of <?php echo htmlspecialchars($item_details['title']); ?>"
                            style="max-width: 100%; height: auto; border: 1px solid #ddd;" 
                        >
                        </figure>
                <?php else: ?>
                    <div class="alert alert-light text-center border mt-4">
                        <i class="fas fa-image fa-2x text-secondary"></i>
                        <p class="mb-0 mt-2">No image available for this item.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-6 col-lg-7 order-md-2 order-1">
                
                <header>
                    <h1 class="display-4 mb-2 text-danger"><?php echo htmlspecialchars($item_details['title']); ?></h1>
                    
                    <?php if ($item_details['rating']): ?>
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark fs-5 py-2 px-3 shadow-sm">
                            ★ <?php echo htmlspecialchars($item_details['rating']); ?> / 5.0
                        </span>
                    </div>
                    <?php endif; ?>

                    <p class="lead text-muted">A street food favorite from 
                        <strong><?php echo htmlspecialchars($item_details['country']); ?></strong>.
                    </p>
                </header>

                <hr>

                <section class="mb-4">
                    <h2 class="h4 border-bottom pb-2 text-secondary">The Dish</h2>
                    <p class="text-dark"><?php echo nl2br(htmlspecialchars($item_details['description'])); ?></p>
                </section>
                
                <section>
                    <h2 class="h4 border-bottom pb-2 text-secondary">Food Facts</h2>
                    
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-nowrap fw-bold">Region / City</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($item_details['region'] ?? 'N/A'); ?></dd>

                        <dt class="col-sm-4 text-nowrap fw-bold">Food Type</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($item_details['foodType'] ?? 'N/A'); ?></dd>

                        <dt class="col-sm-4 text-nowrap fw-bold">Cooking Method</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($item_details['cookingMethod'] ?? 'N/A'); ?></dd>

                        <dt class="col-sm-4 text-nowrap fw-bold">Spice Level</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($item_details['spiceLevel'] ?? 'N/A'); ?></dd>

                        <dt class="col-sm-4 text-nowrap fw-bold">Price Range</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($item_details['priceRange'] ?? 'N/A'); ?></dd>
                    </dl>
                </section>
                
                <?php if ($item_details['mainIngredients']): ?>
                <section class="mt-4">
                    <h2 class="h4 border-bottom pb-2 text-secondary">Main Ingredients</h2>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($item_details['mainIngredients'])); ?></p>
                </section>
                <?php endif; ?>

                </div>
        </article>
    <?php elseif (!$item_id): ?>
        <div class="alert alert-info text-center py-5" role="alert">
            <h4 class="alert-heading">No Item Selected</h4>
            <p>Please select an item from the <a href="browse.php">catalogue page</a> to view its details.</p>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center py-5" role="alert">
            <h4 class="alert-heading">Item Not Found</h4>
            <p>The item you requested could not be found. It may have been deleted.</p>
            <hr>
            <a href="browse.php" class="btn btn-danger">Browse All Items</a>
        </div>
    <?php endif; ?>

</main>

<?php include 'includes/footer.php'; ?>