<?php

defined('MOODLE_INTERNAL') || die();

require_once dirname(__DIR__, 2) . '/src/Support/MoodleBootstrap.php';

\PaymentEngine\Moodle\Support\MoodleBootstrap::registerAutoload();

if ($hassiteconfig) {

    $settings = new admin_settingpage(
        'enrol_paymentengine',
        'Remita Checkout'
    );

    $settings->add(
        new admin_setting_configtext(
            'enrol_paymentengine/base_url',
            'API Base URL',
            'Remita Checkout API Base URL',
            'https://api-checkout-qa.systemspecsng.com'
        )
    );

    $settings->add(
        new admin_setting_configpasswordunmask(
            'enrol_paymentengine/secret_key',
            'Secret Key',
            'Merchant Secret Key',
            ''
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'enrol_paymentengine/callback_url',
            'Callback URL',
            'Remita Checkout callback URL',
            \PaymentEngine\Moodle\Support\CallbackUrl::forGateway()
        )
    );

    $ADMIN->add(
        'enrolments',
        $settings
    );
}
