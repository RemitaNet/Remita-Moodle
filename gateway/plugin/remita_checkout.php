<?php

declare(strict_types=1);

use PaymentEngine\Moodle\Support\CallbackUrl;
use PaymentEngine\Moodle\Support\MoodleBootstrap;

require_once dirname(__DIR__, 2) . '/src/Support/MoodleBootstrap.php';

MoodleBootstrap::registerAutoload();

return [
    'name' => 'Remita Checkout',

    'version' => '1.0.0',

    'configuration' => [

        'base_url' => [
            'label' => 'API Base URL',
            'type' => 'text',
            'default' => 'https://api-checkout-qa.systemspecsng.com',
        ],

        'secret_key' => [
            'label' => 'Secret Key',
            'type' => 'password',
        ],

        'callback_url' => [
            'label' => 'Callback URL',
            'type' => 'text',
            'default' => CallbackUrl::forGateway(),
        ],
    ],
];
