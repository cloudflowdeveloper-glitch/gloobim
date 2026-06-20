<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Currency;

class CurrencyController extends Controller
{
    /**
     * List all currencies as JSON for the frontend.
     */
    public function index(): Response
    {
        return $this->json(Currency::all());
    }

    /**
     * Set the user's preferred currency.
     */
    public function set(): Response
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $code = strtoupper(trim($input['currency_code'] ?? ''));

        if (empty($code)) {
            return $this->json(['error' => 'Currency code is required'], 422);
        }

        // Validate the currency code exists
        $currencies = Currency::all();
        $found = false;
        foreach ($currencies as $c) {
            if ($c['currency_code'] === $code) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            return $this->json(['error' => 'Invalid currency code'], 422);
        }

        Currency::set($code);

        return $this->json([
            'message' => 'Currency updated',
            'currency' => Currency::getSelected(),
        ]);
    }

    /**
     * Get the current selected currency info.
     */
    public function current(): Response
    {
        return $this->json(Currency::getSelected());
    }

    /**
     * Convert an amount and return formatted result.
     */
    public function convert(): Response
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $amount = (float)($input['amount'] ?? 0);
        $from = strtoupper(trim($input['from'] ?? 'USD'));
        $to = strtoupper(trim($input['to'] ?? ''));

        if ($to === '') {
            $to = Currency::getSelected()['currency_code'];
        }

        $converted = Currency::convertBetween($amount, $from, $to);

        return $this->json([
            'amount' => $converted,
            'from' => $from,
            'to' => $to,
            'formatted' => Currency::format($amount / Currency::getSelected()['exchange_rate_usd'] * ($to === Currency::getSelected()['currency_code'] ? 1 : 1)),
        ]);
    }
}