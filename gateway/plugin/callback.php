<?php

declare(strict_types=1);

use PaymentEngine\Moodle\Support\CallbackUrl;
use PaymentEngine\Moodle\Support\MoodleBootstrap;
use PaymentEngine\Moodle\Support\PaymentIdentifier;
use PaymentEngine\Moodle\Support\PaymentStatusMapper;
use PaymentEngine\Moodle\Support\TransactionVerifier;

require_once dirname(__DIR__, 2) . '/src/Support/MoodleBootstrap.php';

MoodleBootstrap::registerAutoload();

$paymentIdentifier = trim((string) ($_GET['paymentIdentifier'] ?? ''));

if ($paymentIdentifier === '') {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Missing payment identifier']);
    exit;
}

$response = TransactionVerifier::verify(
    (string) getenv('PAYMENT_ENGINE_BASE_URL'),
    (string) getenv('PAYMENT_ENGINE_SECRET_KEY'),
    $paymentIdentifier
);

$status = PaymentStatusMapper::mapQueryResponse($response);
$courseId = PaymentIdentifier::extractCourseId($paymentIdentifier) ?? 0;

$accept = (string) ($_SERVER['HTTP_ACCEPT'] ?? '');

if (str_contains($accept, 'application/json')) {
    header('Content-Type: application/json');
    echo json_encode([
        'paymentIdentifier' => $paymentIdentifier,
        'status' => $status,
        'transaction' => $response['data'] ?? [],
    ]);
    exit;
}

if ($courseId > 0) {
    header('Location: ' . CallbackUrl::courseUrl($courseId, $status));
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
echo '<!DOCTYPE html><html lang="en"><body><h1>Payment ' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '</h1><p>Reference: ' . htmlspecialchars($paymentIdentifier, ENT_QUOTES, 'UTF-8') . '</p></body></html>';
