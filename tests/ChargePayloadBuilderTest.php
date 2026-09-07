<?php

declare(strict_types=1);

namespace PaymentEngine\Moodle\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Moodle\Support\ChargePayloadBuilder;

final class ChargePayloadBuilderTest extends TestCase
{
    public function testBuildPayload(): void
    {
        $payload =
            ChargePayloadBuilder::fromRequest(
                [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'email' => 'john@example.com',
                    'phoneNumber' => '08012345678',
                    'currency' => 'NGN',
                    'amount' => '15000',
                    'courseName' => 'Java Masterclass'
                ],
                'MDL-10-200-123456',
                'https://moodle.test/payment/gateway/paymentengine/callback.php'
            );

        $this->assertSame(
            'John',
            $payload['firstName']
        );

        $this->assertSame(
            'Doe',
            $payload['lastName']
        );

        $this->assertSame(
            'john@example.com',
            $payload['email']
        );

        $this->assertSame(
            '08012345678',
            $payload['phoneNumber']
        );

        $this->assertSame(
            'MDL-10-200-123456',
            $payload['paymentIdentifier']
        );

        $this->assertSame(
            'Java Masterclass',
            $payload['narration']
        );

        $this->assertSame(
            1500000,
            $payload['amount']
        );

        $this->assertSame(
            'https://moodle.test/payment/gateway/paymentengine/callback.php',
            $payload['returnUrl']
        );
    }

    public function testBuildPayloadWithDefaultNarration(): void
    {
        $payload =
            ChargePayloadBuilder::fromRequest(
                [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'email' => 'john@example.com',
                    'amount' => '5000'
                ],
                'MDL-10-200-123456',
                'https://moodle.test/payment/gateway/paymentengine/callback.php'
            );

        $this->assertSame(
            'Moodle Course Purchase',
            $payload['narration']
        );
    }
}