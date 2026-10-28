<?php
declare(strict_types=1);

$pageTitle = 'Services';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Our services</p>
        <h1>Helpful at every turn.</h1>
        <p>Practical support to help you choose, prepare for, and enjoy your next car.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="card-grid">
            <?php foreach ($services as $service): ?>
                <?= renderServiceCard($service) ?>
            <?php endforeach; ?>
        </div>
        <div class="service-note">
            <p>Have a particular make or model in mind? <a href="contact.php">Get in touch</a> and tell us what you are looking for.</p>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
