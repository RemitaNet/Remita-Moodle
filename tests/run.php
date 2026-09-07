<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/Support/MoodleBootstrap.php';

\PaymentEngine\Moodle\Support\MoodleBootstrap::registerAutoload();

use PaymentEngine\Moodle\Support\CallbackUrl;
use PaymentEngine\Moodle\Support\ChargePayloadBuilder;
use PaymentEngine\Moodle\Support\EnrollmentReference;
use PaymentEngine\Moodle\Support\PaymentIdentifier;
use PaymentEngine\Moodle\Support\PaymentStatusMapper;
use PaymentEngine\Moodle\Support\TransactionVerifier;

$tests = [];

$assertSame = static function (mixed $expected, mixed $actual, string $message): void {
    if ($expected !== $actual) {
        throw new RuntimeException($message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true));
    }
};

$assertTrue = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};

$tests['callback_url_and_course_url'] = static function () use ($assertSame): void {
    putenv('MOODLE_URL=https://moodle.test');
    $assertSame('https://moodle.test/enrol/paymentengine/callback.php', CallbackUrl::forGateway(), 'Unexpected Moodle callback URL.');
    $assertSame('https://moodle.test/course/view.php?id=12&payment_status=success', CallbackUrl::courseUrl(12, 'success'), 'Unexpected Moodle course URL.');
};

$tests['payment_identifier_and_reference'] = static function () use ($assertTrue, $assertSame): void {
    $identifier = PaymentIdentifier::build(45, 100);
    $assertTrue(str_starts_with($identifier, 'MDL-45-100-'), 'Unexpected Moodle payment identifier format.');
    $assertSame(100, PaymentIdentifier::extractCourseId('MDL-45-100-1718012345'), 'Unexpected Moodle course extraction.');
    $assertSame('ENR-45-100', EnrollmentReference::build(45, 100), 'Unexpected Moodle enrollment reference.');
};

$tests['charge_payload_builder'] = static function () use ($assertSame): void {
    $payload = ChargePayloadBuilder::fromRequest([
        'firstName' => 'John',
        'lastName' => 'Doe',
        'email' => 'john@example.com',
        'phoneNumber' => '08012345678',
        'currency' => 'NGN',
        'amount' => '15000',
        'courseName' => 'Java Masterclass',
    ], 'MDL-10-200-123456', 'https://moodle.test/enrol/paymentengine/callback.php');

    $assertSame('Java Masterclass', $payload['narration'], 'Unexpected Moodle narration.');
    $assertSame(1500000, $payload['amount'], 'Unexpected Moodle amount conversion.');

    $defaultPayload = ChargePayloadBuilder::fromRequest([
        'amount' => '5000',
    ], 'MDL-10-200-123456', 'https://moodle.test/enrol/paymentengine/callback.php');

    $assertSame('Moodle Course Purchase', $defaultPayload['narration'], 'Unexpected Moodle default narration.');
};

$tests['payment_status_mapping'] = static function () use ($assertSame): void {
    $assertSame(PaymentStatusMapper::STATUS_SUCCESS, PaymentStatusMapper::mapQueryResponse(['status' => '00']), 'Unexpected Moodle success mapping.');
    $assertSame(PaymentStatusMapper::STATUS_PENDING, PaymentStatusMapper::mapQueryResponse(['status' => '01']), 'Unexpected Moodle pending mapping.');
    $assertSame(PaymentStatusMapper::STATUS_FAILED, PaymentStatusMapper::mapQueryResponse(['status' => '99']), 'Unexpected Moodle failed mapping.');
};

$tests['transaction_verifier_with_injected_query'] = static function () use ($assertSame): void {
    $response = TransactionVerifier::verify('https://api-checkout-qa.systemspecsng.com', 'secret', 'MDL-45-100-123456', static fn (string $paymentIdentifier): array => [
        'status' => '00',
        'data' => [
            'paymentIdentifier' => $paymentIdentifier,
            'paymentState' => 'APPROVED',
        ],
    ]);

    $assertSame('MDL-45-100-123456', $response['data']['paymentIdentifier'], 'Unexpected injected Moodle verifier response.');
};

$executed = 0;

foreach ($tests as $name => $test) {
    $test();
    $executed++;
    echo "[PASS] {$name}\n";
}

echo "\nAll {$executed} Moodle tests passed.\n";
