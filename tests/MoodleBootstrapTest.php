<?php

declare(strict_types=1);

namespace PaymentEngine\Moodle\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Moodle\Support\MoodleBootstrap;

final class MoodleBootstrapTest extends TestCase
{
    public function testBootstrapExists(): void
    {
        $this->assertTrue(
            method_exists(
                MoodleBootstrap::class,
                'registerAutoload'
            )
        );
    }
}