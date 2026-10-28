<?php
declare(strict_types=1);

$pageTitle = 'Contact';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Contact us</p>
        <h1>Let’s talk cars.</h1>
        <p>Share what you need and our friendly team will be glad to help.</p>
    </div>
</section>

<section class="section">
    <div class="container contact-layout">
        <div class="content-narrow">
            <h2>Start a conversation</h2>
            <p>For vehicle enquiries, trade-in questions, or help choosing your next car, email us and we will get back to you.</p>
            <a class="button" href="mailto:<?= e($contactEmail) ?>">Email our team</a>
        </div>
        <aside class="contact-card">
            <p class="eyebrow">Email</p>
            <a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a>
            <p class="contact-note">Please include your name and a few details about what you are looking for.</p>
        </aside>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
