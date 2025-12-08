<?php

require_once '../private/authentication.php';

if (is_logged_in()) {
    header("Location: admin.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Please enter both a username and password.";
    } else {

        if (authenticate($username, $password)) {
            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}

$title = "Admin Login";
$introduction = "Please log in with your admin credentials to manage the street food catalogue.";

include 'includes/header.php';
?>

<h2 class="fw-light my-3 text-center">Login</h2>

<?php if ($error !== "") : ?>
    <p class="text-center text-danger"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="mx-auto" style="max-width: 480px;">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input
            type="text"
            id="username"
            name="username"
            class="form-control"
            value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            required
        >
    </div>

    <input type="submit" id="submit" name="submit" value="Log In" class="btn btn-success mt-3">
</form>

<?php include 'includes/footer.php'; ?>