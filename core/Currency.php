<?php

namespace Core;

use Core\Database;
use Core\Session;

class Currency
{
    /**
     * Get all available currencies from the database.
     */
    public static function all(): array
    {
        static $cache = null;
        if ($cache !== null) return $cache;

        try {
            $cache = Database::query(
                "SELECT country_code, country_name, currency_code, currency_symbol, exchange_rate_usd
                 FROM country_currencies
                 ORDER BY country_name ASC"
            );
        } catch (\Exception $e) {
            // Fallback defaults
            $cache = [
                ['country_code' => 'KE', 'country_name' => 'Kenya', 'currency_code' => 'KES', 'currency_symbol' => 'KES', 'exchange_rate_usd' => 129.5],
                ['country_code' => 'NG', 'country_name' => 'Nigeria', 'currency_code' => 'NGN', 'currency_symbol' => '₦', 'exchange_rate_usd' => 1540.0],
                ['country_code' => 'GH', 'country_name' => 'Ghana', 'currency_code' => 'GHS', 'currency_symbol' => 'GH₵', 'exchange_rate_usd' => 14.5],
                ['country_code' => 'US', 'country_name' => 'United States', 'currency_code' => 'USD', 'currency_symbol' => '$', 'exchange_rate_usd' => 1.0],
            ];
        }

        return $cache;
    }

    /**
     * Get the user's selected currency from session, or default to KES.
     */
    public static function getSelected(): array
    {
        $selected = Session::get('selected_currency', 'KES');

        $currencies = self::all();
        foreach ($currencies as $c) {
            if ($c['currency_code'] === $selected) {
                return $c;
            }
        }

        // Default to first currency (KES)
        return $currencies[0] ?? [
            'country_code' => 'KE',
            'country_name' => 'Kenya',
            'currency_code' => 'KES',
            'currency_symbol' => 'KES',
            'exchange_rate_usd' => 129.5,
        ];
    }

    /**
     * Set the user's preferred currency.
     */
    public static function set(string $currencyCode): void
    {
        Session::set('selected_currency', $currencyCode);
    }

    /**
     * Convert an amount from USD to the selected currency.
     */
    public static function convert(float $usdAmount): float
    {
        $currency = self::getSelected();
        return round($usdAmount * (float) $currency['exchange_rate_usd'], 2);
    }

    /**
     * Convert an amount from one currency to another.
     */
    public static function convertBetween(float $amount, string $fromCode, string $toCode): float
    {
        $currencies = self::all();
        $fromRate = 1.0;
        $toRate = 1.0;

        foreach ($currencies as $c) {
            if ($c['currency_code'] === $fromCode) $fromRate = (float) $c['exchange_rate_usd'];
            if ($c['currency_code'] === $toCode) $toRate = (float) $c['exchange_rate_usd'];
        }

        // Convert to USD first, then to target
        $usdAmount = $amount / $fromRate;
        return round($usdAmount * $toRate, 2);
    }

    /**
     * Format an amount in the selected currency with symbol.
     */
    public static function format(float $usdAmount): string
    {
        $currency = self::getSelected();
        $converted = self::convert($usdAmount);
        $symbol = $currency['currency_symbol'] ?? $currency['currency_code'];

        // Large number formatting
        if ($converted >= 1000000) {
            return $symbol . ' ' . number_format($converted / 1000000, 1) . 'M';
        }
        if ($converted >= 100000) {
            return $symbol . ' ' . number_format($converted / 1000, 0) . 'K';
        }

        return $symbol . ' ' . number_format($converted, $converted >= 100 ? 0 : 2);
    }

    /**
     * Get the currency data as a JavaScript-friendly array for the frontend.
     */
    public static function forJs(): string
    {
        $currencies = self::all();
        $selected = self::getSelected();

        return json_encode([
            'currencies' => $currencies,
            'selected' => $selected,
        ]);
    }
}