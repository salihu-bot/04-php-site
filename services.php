<?php
$pageTitle = 'Services';
require_once __DIR__ . '/includes/config.php';
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
            <article class="card service-card">
                <span class="service-icon" aria-hidden="true">01</span>
                <h2>Vehicle sales</h2>
                <p>Explore a range of cars and get help finding an option that suits your needs and budget.</p>
            </article>
            <article class="card service-card">
                <span class="service-icon" aria-hidden="true">02</span>
                <h2>Personal guidance</h2>
                <p>Talk through your questions with our team and compare options with clear, useful information.</p>
            </article>
            <article class="card service-card">
                <span class="service-icon" aria-hidden="true">03</span>
                <h2>Trade-in enquiries</h2>
                <p>Thinking of changing your current car? Contact us to discuss a possible trade-in.</p>
            </article>
        </div>
        <div class="service-note">
            <p>Have a particular make or model in mind? <a href="contact.php">Get in touch</a> and tell us what you are looking for.</p>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
