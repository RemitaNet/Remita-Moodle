<?php

declare(strict_types=1);

namespace PaymentEngine\Moodle\Support;

final class PaymentIdentifier
{
    public static function build(
        int $userId,
        int $courseId
    ): string {

        return sprintf(
            'MDL-%d-%d-%d',
            $userId,
            $courseId,
            time()
        );
    }

    public static function extractCourseId(
        string $paymentIdentifier
    ): ?int {

        $parts = explode(
            '-',
            $paymentIdentifier
        );

        return isset($parts[2])
            ? (int) $parts[2]
            : null;
    }
}