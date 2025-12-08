<?php

require_once '../private/authentication.php';
require_login();

$title = "Admin Dashboard";
$introduction = "Welcome to the admin area of Sidewalk Cravings – Accessible only to logged in users.";

include 'includes/header.php';

?>

<h2 class="fw-light my-3">Admin Area</h2>
<p class="text-muted">You are logged in as <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>.</p>
<p>From here, you can manage street food items (add, edit and delete).</p>

<?php include 'includes/footer.php'; ?>