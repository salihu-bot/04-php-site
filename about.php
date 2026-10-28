<?php
declare(strict_types=1);

$pageTitle = 'About';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">About us</p>
        <h1>People first. Cars second.</h1>
        <p>We believe finding your next car should be a clear, comfortable experience.</p>
    </div>
</section>

<section class="section">
    <div class="container content-narrow">
        <h2>A local team with a simple promise</h2>
        <p>Daabu Car Vendor Ltd was created to make car buying feel more personal. We take time to understand your budget, your everyday needs, and the journeys ahead before helping you explore suitable vehicles.</p>
        <p>Our approach is straightforward: answer your questions honestly, explain your choices clearly, and give you the space to make the decision that is right for you.</p>
        <section class="team-section" aria-labelledby="team-heading">
            <h2 id="team-heading">Meet our team</h2>
            <div class="card-grid">
                <?php foreach ($team as $member): ?>
                    <article class="card team-card">
                        <h3><?= e($member['name']) ?></h3>
                        <p class="team-role"><?= e($member['role']) ?></p>
                        <p><?= e($member['bio']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <div class="values-panel">
            <h3>What matters to us</h3>
            <ul class="check-list">
                <li>Clear, helpful communication</li>
                <li>Thoughtful recommendations tailored to you</li>
                <li>Friendly support throughout your car-buying journey</li>
            </ul>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
