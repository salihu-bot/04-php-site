<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $amount): string
{
    return '₦' . number_format($amount, 2);
}

function renderServiceCard(array $service): string
{
    $slug = e((string) $service['slug']);
    $title = e((string) $service['title']);
    $description = e((string) $service['description']);
    $price = e(formatPrice((float) $service['price']));
    $icon = e((string) $service['icon']);

    return '<article class="card service-card">'
        . '<span class="service-icon" aria-hidden="true">' . $icon . '</span>'
        . '<h2>' . $title . '</h2>'
        . '<p>' . $description . '</p>'
        . '<p class="service-price">' . $price . '</p>'
        . '<a class="service-link" href="service.php?slug=' . rawurlencode((string) $service['slug']) . '">View service</a>'
        . '</article>';
}
