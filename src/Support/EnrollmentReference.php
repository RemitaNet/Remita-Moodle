<?php

declare(strict_types=1);

namespace PaymentEngine\Moodle\Support;

final class EnrollmentReference
{
    public static function build(
        int $userId,
        int $courseId
    ): string {

        return sprintf(
            'ENR-%d-%d',
            $userId,
            $courseId
        );
    }

    public static function extractUserId(
        string $reference
    ): ?int {

        $parts = explode('-', $reference);

        return isset($parts[1])
            ? (int) $parts[1]
            : null;
    }

    public static function extractCourseId(
        string $reference
    ): ?int {

        $parts = explode('-', $reference);

        return isset($parts[2])
            ? (int) $parts[2]
            : null;
    }
}