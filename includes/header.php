<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= htmlspecialchars($tagline, ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> | <?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="index.php">
                <span class="brand-name"><?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="brand-tagline"><?= htmlspecialchars($tagline, ENT_QUOTES, 'UTF-8') ?></span>
            </a>
            <nav class="site-nav" aria-label="Main navigation">
                <ul>
                    <?php foreach ($navItems as $label => $fileName): ?>
                        <?php $isCurrentPage = $currentPage === $fileName; ?>
                        <li>
                            <a href="<?= htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8') ?>"
                               class="<?= $isCurrentPage ? 'active' : '' ?>"
                               <?= $isCurrentPage ? 'aria-current="page"' : '' ?>>
                                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
