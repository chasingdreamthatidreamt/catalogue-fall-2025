<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Sidewalk Cravings'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .main-photo {
            min-height: 75vh;
            background-image:
                linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
                url("images/main-streetfood.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hero-content {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 0.75rem;
        }

        nav a {
            white-space: nowrap;
        }
        .title{
            color: aliceblue;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light main-photo ">

    <header class="container py-4" >
        <nav class="d-flex gap-2">
            <a href="index.php" class="btn btn-dark">Home</a>
            <a href="browse.php" class="btn btn-outline-primary" style="background-color: blue; color: aliceblue;">Browse</a>

            <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="admin.php" class="btn btn-outline-secondary" style="background-color: green; color: aliceblue;">Admin</a>
                <a href="logout.php" class="btn btn-outline-danger" style="background-color: green; color: aliceblue;">Log out</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-success" style="background-color: green; color: aliceblue;">Log in</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="container my-5">
        <?php if (!empty($title)): ?>
            <section class="mb-4">
                <h1 class="fw-light text-white"><?= htmlspecialchars($title); ?></h1>
                <?php if (!empty($introduction)): ?>
                    <p class=" fw-light mb-0 text-white"><?= htmlspecialchars($introduction); ?></p>
                <?php endif; ?>
            </section>
        <?php endif; ?>