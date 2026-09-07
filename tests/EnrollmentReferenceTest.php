<?php

declare(strict_types=1);

namespace PaymentEngine\Moodle\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Moodle\Support\EnrollmentReference;

final class EnrollmentReferenceTest extends TestCase
{
    public function testBuildReference(): void
    {
        $reference =
            EnrollmentReference::build(
                45,
                100
            );

        $this->assertSame(
            'ENR-45-100',
            $reference
        );
    }

    public function testExtractUserId(): void
    {
        $this->assertSame(
            45,
            EnrollmentReference::extractUserId(
                'ENR-45-100'
            )
        );
    }

    public function testExtractCourseId(): void
    {
        $this->assertSame(
            100,
            EnrollmentReference::extractCourseId(
                'ENR-45-100'
            )
        );
    }
}