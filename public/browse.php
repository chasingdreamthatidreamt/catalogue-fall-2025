<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../private/connect.php';
require_once __DIR__ . '/../private/functions.php';

$conn = db_connect();

$title = "Browse Foods";
$introduction = "Browse street foods — click a card to see full details.";

include __DIR__ . '/includes/header.php';


$whereSql = "";
$types = "";
$params = [];


// Collect search + filter values from GET
$search     = $_GET['search']     ?? '';
$country    = $_GET['country']    ?? '';
$foodType   = $_GET['foodType']   ?? '';
$spiceLevel = $_GET['spiceLevel'] ?? '';
$priceRange = $_GET['priceRange'] ?? '';

// Clear filters
if (isset($_GET['clear'])) {
    $search = $country = $foodType = $spiceLevel = $priceRange = '';
}

// Build WHERE clause dynamically
$whereParts = [];
$types = '';
$params = [];

if ($search !== '') {
    $whereParts[] = "(title LIKE ? OR description LIKE ? OR country LIKE ? OR mainIngredients LIKE ? OR cookingMethod LIKE ?)";
    $types .= 'sssss';
    $kw = "%$search%";
    $params = array_merge($params, [$kw, $kw, $kw, $kw, $kw]);
}

if ($country !== '') {
    $whereParts[] = "country = ?";
    $types .= 's';
    $params[] = $country;
}

if ($foodType !== '') {
    $whereParts[] = "foodType = ?";
    $types .= 's';
    $params[] = $foodType;
}

if ($spiceLevel !== '') {
    $whereParts[] = "spiceLevel = ?";
    $types .= 's';
    $params[] = $spiceLevel;
}

if ($priceRange !== '') {
    $whereParts[] = "priceRange = ?";
    $types .= 's';
    $params[] = $priceRange;
}

// Combine into WHERE SQL
$whereSql = '';
if (!empty($whereParts)) {
    $whereSql = 'WHERE ' . implode(' AND ', $whereParts);
}

$perPage = 9;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

$countSql = "SELECT COUNT(*) AS total FROM catalogue_items $whereSql";
$countStmt = $conn->prepare($countSql);
if (!$countStmt) {
    die("Prepare failed (COUNT): " . $conn->error);
}
if ($types !== "") {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$total = (int) $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();

$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}

$dataSql = "SELECT * FROM catalogue_items $whereSql ORDER BY id DESC LIMIT ? OFFSET ?";
$dataStmt = $conn->prepare($dataSql);
if (!$dataStmt) {
    die("Prepare failed (DATA): " . $conn->error);
}

$bindTypes = $types . "ii";
$bindParams = array_merge($params, [$perPage, $offset]);
$dataStmt->bind_param($bindTypes, ...$bindParams);

$dataStmt->execute();
$result = $dataStmt->get_result();

function build_page_url(int $p): string
{
    $q = $_GET;
    $q['page'] = $p;
    return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($q));
}
?>

<style>
    .catalogue-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<form method="get" class="mb-4">
  <div class="row g-2 align-items-end">
    <div class="col-md-2">
      <input type="text" name="search" class="form-control" placeholder="Search street food..."
             value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    </div>

    <div class="col-md-2">
      <select name="country" class="form-select">
        <option value="">All Countries</option>
        <option value="India" <?= ($_GET['country'] ?? '') === 'India' ? 'selected' : '' ?>>India</option>
        <option value="Mexico" <?= ($_GET['country'] ?? '') === 'Mexico' ? 'selected' : '' ?>>Mexico</option>
        <option value="Japan" <?= ($_GET['country'] ?? '') === 'Japan' ? 'selected' : '' ?>>Japan</option>
      </select>
    </div>

    <div class="col-md-2">
      <select name="foodType" class="form-select">
        <option value="">All Types</option>
        <option value="Snack" <?= ($_GET['foodType'] ?? '') === 'Snack' ? 'selected' : '' ?>>Snack</option>
        <option value="Dessert" <?= ($_GET['foodType'] ?? '') === 'Dessert' ? 'selected' : '' ?>>Dessert</option>
        <option value="Main Course" <?= ($_GET['foodType'] ?? '') === 'Main Course' ? 'selected' : '' ?>>Main Course</option>
      </select>
    </div>

    <div class="col-md-2">
      <select name="spiceLevel" class="form-select">
        <option value="">Any Spice</option>
        <option value="Mild" <?= ($_GET['spiceLevel'] ?? '') === 'Mild' ? 'selected' : '' ?>>Mild</option>
        <option value="Medium" <?= ($_GET['spiceLevel'] ?? '') === 'Medium' ? 'selected' : '' ?>>Medium</option>
        <option value="Hot" <?= ($_GET['spiceLevel'] ?? '') === 'Hot' ? 'selected' : '' ?>>Hot</option>
      </select>
    </div>

    <div class="col-md-2">
      <select name="priceRange" class="form-select">
        <option value="">Any Price</option>
        <option value="$" <?= ($_GET['priceRange'] ?? '') === '$' ? 'selected' : '' ?>>$</option>
        <option value="$$" <?= ($_GET['priceRange'] ?? '') === '$$' ? 'selected' : '' ?>>$$</option>
        <option value="$$$" <?= ($_GET['priceRange'] ?? '') === '$$$' ? 'selected' : '' ?>>$$$</option>
      </select>
    </div>

    <div class="col-md-2 d-flex gap-2">
  <button type="submit" class="btn btn-primary">Search</button>
  <a href="browse.php" class="btn btn-secondary">Clear Filters</a>
</div>

  </div>
</form>

<h2 class="mb-4" style="color: aliceblue;">Browse Foods</h2>

<?php if ($total === 0): ?>
    <div class="alert alert-warning">
        No results found. Try clearing filters or searching something else.
    </div>

   
<?php else: ?>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            $id = (int) ($row['id'] ?? 0);
            $img = $row['image'] ?? null;

            $filename = $img ? basename($img) : null;

            $thumbSrc = $filename ? "images/thumbs/" . e($filename) : "images/no-image.png";
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

    <?php $dataStmt->close(); ?>

    <!-- Pagination UI -->
    <nav aria-label="Catalogue pagination" class="mt-4">
        <ul class="pagination justify-content-center flex-wrap">

            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= build_page_url($page - 1) ?>" aria-label="Previous">
                    &laquo;
                </a>
            </li>

            <?php
            $window = 2;
            $start = max(1, $page - $window);
            $end = min($totalPages, $page + $window);

            if ($start > 1) {
                echo '<li class="page-item"><a class="page-link" href="' . build_page_url(1) . '">1</a></li>';
                if ($start > 2) {
                    echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                }
            }

            for ($i = $start; $i <= $end; $i++):
                ?>
                <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= build_page_url($i) ?>"><?= $i ?></a>
                </li>
            <?php endfor;

            if ($end < $totalPages) {
                if ($end < $totalPages - 1) {
                    echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                }
                echo '<li class="page-item"><a class="page-link" href="' . build_page_url($totalPages) . '">' . $totalPages . '</a></li>';
            }
            ?>

            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= build_page_url($page + 1) ?>" aria-label="Next">
                    &raquo;
                </a>
            </li>

        </ul>

        <p class="text-center text-muted small mb-0">
            Showing <?= min($total, $offset + 1) ?>–<?= min($total, $offset + $perPage) ?> of <?= $total ?>
        </p>
    </nav>

<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>