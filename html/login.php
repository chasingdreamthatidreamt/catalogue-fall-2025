<?php

require_once '../private/authentication.php';

// If the user is already logged in, don't let them see the login page.
if (is_logged_in()) {
    header("Location: admin.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? "");
    $password = $_POST['password'] ?? "";

    if ($username === "" || $password === "") {
        $error = "Please enter both username and password.";
    } elseif (authenticate($username, $password)) {
        // Login successful → send them to admin area.
        header("Location: admin.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}

$title = "Login";
$introduction = "Log in with your admin credentials to manage the Street Food catalogue.";

include 'includes/header.php';

?>

<h2 class="fw-light my-3">Admin Login</h2>

<?php if ($error !== "") : ?>
    <p class="text-danger text-center"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

    <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input
            type="text"
            id="username"
            name="username"
            class="form-control"
            value="<?= isset($username) ? htmlspecialchars($username) : '';?>"
            required
        >
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            required
        >
    </div>

    <input type="submit" id="submit" name="submit" value="Log In" class="btn btn-success my-3">
</form>

<?php include 'includes/footer.php'; ?>
