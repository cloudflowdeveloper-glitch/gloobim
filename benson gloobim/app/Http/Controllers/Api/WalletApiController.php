<?php

namespace App\Http\Controllers\Api;

use Core\Controller;
use Core\Response;

class WalletApiController extends Controller
{
    public function index(): Response
    {
        return $this->json(['balance' => 0, 'currency' => 'KES']);
    }

    public function deposit(): Response
    {
        return $this->json(['message' => 'Deposit initiated']);
    }

    public function withdraw(): Response
    {
        return $this->json(['message' => 'Withdrawal initiated']);
    }

    public function transactions(): Response
    {
        return $this->json(['transactions' => []]);
    }

    public function mpesaSTKPush(): Response
    {
        return $this->json(['message' => 'M-Pesa STK push initiated', 'checkout_request_id' => 'ws_CO_123456789']);
    }

    public function sendGift(): Response
    {
        return $this->json(['message' => 'Gift sent']);
    }
}
