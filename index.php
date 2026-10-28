<?php
declare(strict_types=1);

$pageTitle = 'Home';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="container hero-content">
        <p class="eyebrow">Your next journey starts here</p>
        <h1>Find a car that feels like yours.</h1>
        <p class="hero-copy">Explore carefully selected vehicles, get straightforward advice, and drive away with confidence.</p>
        <a class="button button-light" href="services.php">Explore our services</a>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">A better way to buy</p>
            <h2>Good cars. Honest guidance.</h2>
            <p>Whether you are buying your first car or looking for an upgrade, our team makes every step feel simple.</p>
        </div>
        <div class="card-grid">
            <article class="card">
                <span class="card-number">01</span>
                <h3>Cars for real life</h3>
                <p>Browse a considered selection of dependable cars for commuting, family life, and weekend adventures.</p>
            </article>
            <article class="card">
                <span class="card-number">02</span>
                <h3>Advice you can trust</h3>
                <p>We listen to what you need and help you compare your options without pressure.</p>
            </article>
            <article class="card">
                <span class="card-number">03</span>
                <h3>Support that continues</h3>
                <p>From your first question to the day you collect your keys, we are here to help.</p>
            </article>
        </div>
    </div>
</section>

<section class="callout">
    <div class="container callout-inner">
        <div>
            <p class="eyebrow">Let’s get you moving</p>
            <h2>Tell us what you’re looking for.</h2>
        </div>
        <a class="button" href="contact.php">Talk to our team</a>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
