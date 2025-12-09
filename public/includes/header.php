<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Sidewalk Cravings'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <header class="container py-4">
        <nav class="d-flex gap-2">
            <a href="index.php" class="btn btn-dark">Home</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="admin.php" class="btn btn-outline-secondary">Admin</a>
                <a href="logout.php" class="btn btn-outline-danger">Log out</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-success">Log in</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="container my-5"></main>

    <?php if (!empty($title)): ?>
        <section class="mb-4">
            <h1 class="fw-light"><?= htmlspecialchars($title); ?></h1>
        </section>
    <?php endif; 
    ?>