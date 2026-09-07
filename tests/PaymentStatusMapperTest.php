<?php

declare(strict_types=1);

namespace PaymentEngine\Moodle\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Moodle\Support\PaymentStatusMapper;

final class PaymentStatusMapperTest extends TestCase
{
    public function testSuccessMapping(): void
    {
        $response = [
            'status' => '00'
        ];

        $this->assertSame(
            PaymentStatusMapper::STATUS_SUCCESS,
            PaymentStatusMapper::mapQueryResponse(
                $response
            )
        );
    }

    public function testPendingMapping(): void
    {
        $response = [
            'status' => '01'
        ];

        $this->assertSame(
            PaymentStatusMapper::STATUS_PENDING,
            PaymentStatusMapper::mapQueryResponse(
                $response
            )
        );
    }

    public function testFailedMapping(): void
    {
        $response = [
            'status' => '99'
        ];

        $this->assertSame(
            PaymentStatusMapper::STATUS_FAILED,
            PaymentStatusMapper::mapQueryResponse(
                $response
            )
        );
    }
}