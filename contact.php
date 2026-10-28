<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'Contact';
$fields = ['name', 'email', 'phone', 'service', 'message'];
$values = array_fill_keys($fields, '');
$errors = [];
$submissionError = '';

if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $field) {
        $postedValue = $_POST[$field] ?? '';
        $values[$field] = is_string($postedValue) ? trim($postedValue) : '';
    }

    $postedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($postedToken) || !hash_equals($_SESSION['csrf_token'], $postedToken)) {
        $errors['csrf_token'] = 'Your form session expired. Please review the form and submit it again.';
    }

    if ($values['name'] === '') {
        $errors['name'] = 'Please enter your name.';
    }

    if ($values['email'] === '') {
        $errors['email'] = 'Please enter your email address.';
    } elseif (filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($values['service'] === '') {
        $errors['service'] = 'Please choose a service.';
    } elseif (!in_array($values['service'], array_column($services, 'slug'), true)) {
        $errors['service'] = 'Please choose a valid service.';
    }

    if ($values['message'] === '') {
        $errors['message'] = 'Please enter a message.';
    } else {
        $messageLength = mb_strlen($values['message'], 'UTF-8');
        if ($messageLength < 10 || $messageLength > 1000) {
            $errors['message'] = 'Your message must be between 10 and 1000 characters.';
        }
    }

    if ($errors === []) {
        $selectedService = null;
        foreach ($services as $serviceItem) {
            if ($serviceItem['slug'] === $values['service']) {
                $selectedService = $serviceItem;
                break;
            }
        }

        $messageRecord = [
            'timestamp' => date(DATE_ATOM),
            'name' => $values['name'],
            'email' => $values['email'],
            'phone' => $values['phone'],
            'service' => $selectedService['title'],
            'message' => $values['message'],
        ];
        $jsonLine = json_encode($messageRecord, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

        if ($jsonLine === false) {
            error_log('Unable to encode contact form submission as JSON.');
            $submissionError = 'We could not save your message right now. Please try again later.';
        } else {
            $storageDirectory = __DIR__ . '/storage';
            if (!is_dir($storageDirectory) && !mkdir($storageDirectory, 0750, true) && !is_dir($storageDirectory)) {
                error_log('Unable to create contact form storage directory: ' . $storageDirectory);
                $submissionError = 'We could not save your message right now. Please try again later.';
            } else {
                $messagesFile = $storageDirectory . '/messages.jsonl';
                $bytesWritten = file_put_contents($messagesFile, $jsonLine . PHP_EOL, FILE_APPEND | LOCK_EX);

                if ($bytesWritten === false) {
                    error_log('Unable to append contact form submission to: ' . $messagesFile);
                    $submissionError = 'We could not save your message right now. Please try again later.';
                } else {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    header('Location: contact.php?sent=1', true, 303);
                    exit;
                }
            }
        }
    }
}

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

            <?php if (isset($_GET['sent']) && $_GET['sent'] === '1'): ?>
                <p class="form-alert form-alert-success" role="status">Thanks for getting in touch. Your message has been sent.</p>
            <?php endif; ?>

            <?php if ($submissionError !== ''): ?>
                <p class="form-alert form-alert-error" role="alert"><?= e($submissionError) ?></p>
            <?php endif; ?>

            <?php if (isset($errors['csrf_token'])): ?>
                <p class="form-alert form-alert-error" role="alert"><?= e($errors['csrf_token']) ?></p>
            <?php endif; ?>

            <form class="contact-form" method="post" action="contact.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                <div class="form-field">
                    <label for="name">Name <span aria-hidden="true">*</span></label>
                    <input id="name" name="name" type="text" autocomplete="name" required
                           value="<?= e($values['name']) ?>"
                           <?= isset($errors['name']) ? 'aria-invalid="true" aria-describedby="name-error"' : '' ?>>
                    <?php if (isset($errors['name'])): ?>
                        <p class="field-error" id="name-error"><?= e($errors['name']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="email">Email <span aria-hidden="true">*</span></label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="<?= e($values['email']) ?>"
                           <?= isset($errors['email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>>
                    <?php if (isset($errors['email'])): ?>
                        <p class="field-error" id="email-error"><?= e($errors['email']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="phone">Phone <span class="optional">(optional)</span></label>
                    <input id="phone" name="phone" type="tel" autocomplete="tel"
                           value="<?= e($values['phone']) ?>">
                </div>

                <div class="form-field">
                    <label for="service">Service <span aria-hidden="true">*</span></label>
                    <select id="service" name="service" required
                            <?= isset($errors['service']) ? 'aria-invalid="true" aria-describedby="service-error"' : '' ?>>
                        <option value="">Choose a service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= e($service['slug']) ?>" <?= $values['service'] === $service['slug'] ? 'selected' : '' ?>>
                                <?= e($service['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['service'])): ?>
                        <p class="field-error" id="service-error"><?= e($errors['service']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="message">Message <span aria-hidden="true">*</span></label>
                    <textarea id="message" name="message" rows="6" minlength="10" maxlength="1000" required
                              <?= isset($errors['message']) ? 'aria-invalid="true" aria-describedby="message-error"' : '' ?>><?= e($values['message']) ?></textarea>
                    <?php if (isset($errors['message'])): ?>
                        <p class="field-error" id="message-error"><?= e($errors['message']) ?></p>
                    <?php endif; ?>
                </div>

                <button class="button" type="submit">Send message</button>
            </form>
        </div>
        <aside class="contact-card">
            <p class="eyebrow">Email</p>
            <a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a>
            <p class="contact-note">Please include your name and a few details about what you are looking for.</p>
        </aside>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
