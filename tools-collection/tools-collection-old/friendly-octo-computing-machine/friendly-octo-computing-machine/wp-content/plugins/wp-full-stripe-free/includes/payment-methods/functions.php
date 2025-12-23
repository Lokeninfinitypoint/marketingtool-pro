<?php

function register_payment_methods() {
    $available_payment_methods = [];
    $payment_method_folders = glob(WP_FULL_STRIPE_DIR . 'includes/payment-methods/*', GLOB_ONLYDIR);

    foreach ($payment_method_folders as $folder) {
        $payment_method_file = $folder . '/config.json';
        if (file_exists($payment_method_file)) {
            $payment_method_config = json_decode(file_get_contents($payment_method_file), true);
            if ($payment_method_config['enabled'] === true) {
                $available_payment_methods[] = $payment_method_config;
            }
        }
    }

    return array_reverse($available_payment_methods);
}

function get_payment_method_with_name($name) {
    $payment_methods = register_payment_methods();
    foreach ($payment_methods as $payment_method) {
        if ($payment_method['id'] === $name) {
            return $payment_method;
        }
    }
    return null;
}

class MM_WPFS_PaymentMethods
{
    public static function get_payment_methods(): array
    {
        return register_payment_methods();
    }

    public static function get_payment_method_with_name($name): ?array
    {
        return get_payment_method_with_name($name);
    }

    public static function support_recurring($name): bool
    {
        $paymentMethod = get_payment_method_with_name($name);
        if ($paymentMethod === null) {
            return true; // default to support recurring as forms without payment methods are currently mostly recurring
        }

        return $paymentMethod['recurring'];
    }

    public static function is_supported_currency($payment_method, $currency): bool
    {
        $paymentMethod = get_payment_method_with_name($payment_method);
        if ($paymentMethod === null) {
            return false;
        }

        $supported_currencies = $paymentMethod['currencies'];

        if (empty($supported_currencies)) {
            return true;
        }

        return in_array($currency, $supported_currencies);
    }
}

