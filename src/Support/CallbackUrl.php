<?php

declare(strict_types=1);

namespace PaymentEngine\Moodle\Support;

final class CallbackUrl
{
    private static function getBaseUrl(): string
    {
        global $CFG;
        if (isset($CFG) && isset($CFG->wwwroot)) {
            return $CFG->wwwroot;
        }
        return rtrim((string) (getenv('MOODLE_URL') ?: getenv('APP_URL')), '/');
    }

    public static function forGateway(): string
    {
        return self::getBaseUrl() . '/enrol/paymentengine/callback.php';
    }

    public static function forPlugin(): string
    {
        return self::forGateway();
    }

    public static function courseUrl(
        int $courseId,
        string $status = ''
    ): string {

        $url = self::getBaseUrl() . '/course/view.php?id=' . $courseId;

        if ($status !== '') {
            $url .= '&payment_status=' . urlencode($status);
        }

        return $url;
    }
}
