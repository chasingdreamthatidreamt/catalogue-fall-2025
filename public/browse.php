<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../private/connect.php';
require_once __DIR__ . '/../private/functions.php';

$conn = db_connect();

$title = "Browse Foods";
$introduction = "Browse street foods — click a card to see full details.";

include __DIR__ . '/includes/header.php';

/*
Person 2: I’ve finished pagination and wired it to work automatically with
any search/filter logic you add below.

Build your WHERE clause, $types, and $params from $_GET here.
Do NOT touch pagination or LIMIT/OFFSET — it already works.
Clear filters = link back to browse.php with no query string.

Once your logic is in, pagination will carry filters across pages.
*/
$whereSql = "";
$types = "";
$params = [];

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

<h2 class="mb-4">Browse Foods</h2>

<?php if ($total === 0): ?>
    <div class="alert alert-warning">
        No results found. Try clearing filters or searching something else.
    </div>

    <!-- PERSON 1: If you build filters/search UI above, please include a clear button like:
         <a href="browse.php" class="btn btn-outline-secondary">Clear</a>
    -->
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