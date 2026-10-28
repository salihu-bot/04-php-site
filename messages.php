<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Messages';
$messagesAuthenticated = ($_SESSION['messages_authenticated'] ?? false) === true;
$formError = '';
$csrfKey = 'messages_csrf_token';

if (!isset($_SESSION[$csrfKey]) || !is_string($_SESSION[$csrfKey])) {
    $_SESSION[$csrfKey] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';

    if (!is_string($postedToken) || !hash_equals($_SESSION[$csrfKey], $postedToken)) {
        $formError = 'Your form session expired. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'logout' && $messagesAuthenticated) {
        unset($_SESSION['messages_authenticated']);
        session_regenerate_id(true);
        header('Location: messages.php', true, 303);
        exit;
    } elseif (($_POST['action'] ?? '') === 'login' && !$messagesAuthenticated) {
        $postedPassword = $_POST['password'] ?? '';

        if (is_string($postedPassword) && hash_equals($messagesPassword, $postedPassword)) {
            if (session_regenerate_id(true)) {
                $_SESSION['messages_authenticated'] = true;
                $_SESSION[$csrfKey] = bin2hex(random_bytes(32));
                header('Location: messages.php', true, 303);
                exit;
            }

            error_log('Unable to regenerate session ID for messages page login.');
            $formError = 'Login could not be completed. Please try again.';
        } else {
            $formError = 'Incorrect password. Please try again.';
        }
    } else {
        $formError = 'That request could not be processed.';
    }
}

$messages = [];
$readError = '';

if ($messagesAuthenticated) {
    $messagesFile = __DIR__ . '/storage/messages.jsonl';

    if (is_file($messagesFile)) {
        $lines = file($messagesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            error_log('Unable to read contact submissions from: ' . $messagesFile);
            $readError = 'Messages could not be loaded right now.';
        } else {
            foreach ($lines as $lineNumber => $line) {
                try {
                    $record = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
                } catch (JsonException $exception) {
                    error_log('Invalid JSON in contact submissions file at line ' . ($lineNumber + 1) . ': ' . $exception->getMessage());
                    continue;
                }

                if (!is_array($record)
                    || !isset($record['timestamp'], $record['name'], $record['email'], $record['phone'], $record['service'], $record['message'])
                    || !is_string($record['timestamp'])
                    || !is_string($record['name'])
                    || !is_string($record['email'])
                    || !is_string($record['phone'])
                    || !is_string($record['service'])
                    || !is_string($record['message'])) {
                    error_log('Invalid contact submission structure at line ' . ($lineNumber + 1) . '.');
                    continue;
                }

                $timestamp = strtotime($record['timestamp']);
                if ($timestamp === false) {
                    error_log('Invalid timestamp in contact submissions file at line ' . ($lineNumber + 1) . '.');
                    continue;
                }

                $messages[] = [
                    'timestamp' => $record['timestamp'],
                    'sort_timestamp' => $timestamp,
                    'name' => $record['name'],
                    'email' => $record['email'],
                    'phone' => $record['phone'],
                    'service' => $record['service'],
                    'message' => $record['message'],
                ];
            }

            usort(
                $messages,
                static fn (array $first, array $second): int => $second['sort_timestamp'] <=> $first['sort_timestamp']
            );
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Private inbox</p>
        <h1>Contact submissions</h1>
        <p>Review messages sent through the website contact form.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!$messagesAuthenticated): ?>
            <div class="auth-card">
                <h2>Sign in to view messages</h2>
                <p>This page is for local site administration.</p>
                <?php if ($formError !== ''): ?>
                    <p class="form-alert form-alert-error" role="alert"><?= e($formError) ?></p>
                <?php endif; ?>
                <form class="contact-form auth-form" method="post" action="messages.php">
                    <input type="hidden" name="action" value="login">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION[$csrfKey]) ?>">
                    <div class="form-field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                    <button class="button" type="submit">View messages</button>
                </form>
            </div>
        <?php else: ?>
            <div class="messages-toolbar">
                <p class="count-badge"><span><?= e((string) count($messages)) ?></span> messages</p>
                <form method="post" action="messages.php">
                    <input type="hidden" name="action" value="logout">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION[$csrfKey]) ?>">
                    <button class="button button-secondary" type="submit">Sign out</button>
                </form>
            </div>

            <?php if ($formError !== ''): ?>
                <p class="form-alert form-alert-error" role="alert"><?= e($formError) ?></p>
            <?php endif; ?>
            <?php if ($readError !== ''): ?>
                <p class="form-alert form-alert-error" role="alert"><?= e($readError) ?></p>
            <?php elseif ($messages === []): ?>
                <p class="empty-state">There are no messages yet.</p>
            <?php else: ?>
                <div class="messages-table-wrap">
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th scope="col">Received</th>
                                <th scope="col">Name</th>
                                <th scope="col">Contact</th>
                                <th scope="col">Service</th>
                                <th scope="col">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $message): ?>
                                <tr>
                                    <td><?= e(date('M j, Y H:i', $message['sort_timestamp'])) ?></td>
                                    <td><?= e($message['name']) ?></td>
                                    <td>
                                        <a href="mailto:<?= e($message['email']) ?>"><?= e($message['email']) ?></a>
                                        <?php if ($message['phone'] !== ''): ?>
                                            <br><?= e($message['phone']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($message['service']) ?></td>
                                    <td class="message-cell"><?= e($message['message']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
