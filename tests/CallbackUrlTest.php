<?php

declare(strict_types=1);

namespace PaymentEngine\Moodle\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Moodle\Support\CallbackUrl;

final class CallbackUrlTest extends TestCase
{
    public function testCourseUrl(): void
    {
        putenv('MOODLE_URL=https://moodle.test');

        $url = CallbackUrl::courseUrl(
            12,
            'success'
        );

        $this->assertSame(
            'https://moodle.test/course/view.php?id=12&payment_status=success',
            $url
        );
    }
}