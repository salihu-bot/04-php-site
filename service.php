<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';

$requestedSlug = $_GET['slug'] ?? '';
$service = null;

if (is_string($requestedSlug)) {
    foreach ($services as $serviceItem) {
        if ($serviceItem['slug'] === $requestedSlug) {
            $service = $serviceItem;
            break;
        }
    }
}

$isNotFound = $service === null;
if ($isNotFound) {
    http_response_code(404);
}

$pageTitle = $isNotFound ? 'Service not found' : $service['title'];
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow"><?= $isNotFound ? 'Not found' : 'Our services' ?></p>
        <h1><?= $isNotFound ? 'We couldn’t find that service.' : e($service['title']) ?></h1>
        <p><?= $isNotFound
            ? 'That service may have moved or the link may be incorrect. Browse our services to find what you need.'
            : e($service['description']) ?></p>
    </div>
</section>

<section class="section">
    <div class="container content-narrow">
        <?php if ($isNotFound): ?>
            <a class="button" href="services.php">Browse our services</a>
        <?php else: ?>
            <p class="eyebrow">Service price</p>
            <p class="detail-price"><?= e(formatPrice((float) $service['price'])) ?></p>
            <a class="button" href="contact.php">Ask us about this service</a>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
