<?php
require_once 'connect.php'; 

function get_all_items($filters = []) {
    global $connection;

    $sql = "SELECT id, image, title, country, priceRange FROM catalogue_items";
    $params = [];
    
    if (!empty($filters)) {
        $conditions = [];
        if (!empty($filters['country'])) {
            $placeholders = implode(',', array_fill(0, count($filters['country']), '?'));
            $conditions[] = "country IN ($placeholders)";
            $params = array_merge($params, $filters['country']);
        }
        // Add more filters here (e.g., foodType, spiceLevel)
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
    }

    $sql .= " ORDER BY title";

    $stmt = $connection->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function generate_table($filters = []) {
    $items = get_all_items($filters);

    if (!empty($items)) {
        echo '<div class="row row-cols-1 row-cols-md-2 g-4">';
        foreach ($items as $item) {
            $id = $item['id'];
            $title = htmlspecialchars($item['title']);
            $country = htmlspecialchars($item['country']);
            $price = htmlspecialchars($item['priceRange']);
            $thumbnail = htmlspecialchars($item['image']); // thumbnail path
            
            echo '<div class="col">';
            echo '<div class="card h-100">';
            echo "<img src='$thumbnail' class='card-img-top' alt='$title'>";
            echo '<div class="card-body">';
            echo "<h5 class='card-title'>$title</h5>";
            echo "<p class='card-text'><strong>Country:</strong> $country</p>";
            echo "<p class='card-text'><strong>Price:</strong> $price</p>";
            echo "<a href='single_item.php?id=$id' class='btn btn-danger'>View More</a>";
            echo '</div></div></div>';
        }
        echo '</div>';
    } else {
        echo "<h2 class='fw-light'>Nothing found</h2>";
        echo "<p>Try adjusting your filters – no matches in the current results.</p>";
    }
}
?>
