<?php
require_once '../private/authentication.php';
require_login();

require_once __DIR__ . '/../private/connect.php';
$conn = db_connect();

$title = "Admin Dashboard";
$introduction = "Welcome to the admin area of Sidewalk Cravings – Accessible only to logged in users.";

include 'includes/header.php';

$total = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM catalogue_items");
if ($result) {
    $row = $result->fetch_assoc();
    $total = (int)($row['total'] ?? 0);
}
?>

<h2 class="fw-light my-3">Admin Area</h2>
<p class="text-muted">You are logged in as <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>.</p>

<div class="mt-3 mb-4">
    <p class="mb-2">Total items in catalogue: <strong><?= $total; ?></strong></p>
</div>

<div class="d-flex flex-wrap gap-2">
    <a href="browse.php" class="btn btn-primary">Manage Items</a>
    <a href="add.php" class="btn btn-outline-success">Add New Item</a>
    <a href="logout.php" class="btn btn-outline-danger">Log out</a>
</div>

<?php include 'includes/footer.php'; ?>
