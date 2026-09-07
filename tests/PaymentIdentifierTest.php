<?php

declare(strict_types=1);

namespace PaymentEngine\Moodle\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Moodle\Support\PaymentIdentifier;

final class PaymentIdentifierTest extends TestCase
{
    public function testBuildIdentifier(): void
    {
        $identifier =
            PaymentIdentifier::build(
                45,
                100
            );

        $this->assertStringStartsWith(
            'MDL-45-100-',
            $identifier
        );
    }
}