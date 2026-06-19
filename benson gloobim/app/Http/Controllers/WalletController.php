<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;
use App\Services\MpesaService;

class WalletController extends Controller
{
    public function depositPage(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->redirect('/login');

        $wallets = Database::query("SELECT * FROM wallets WHERE user_id = ? LIMIT 1", [$user['id']]);
        $wallet = $wallets[0] ?? null;

        $paymentMethods = Database::query(
            "SELECT * FROM payment_methods WHERE is_enabled = 1 ORDER BY sort_order ASC"
        );

        return $this->view('wallet.deposit', [
            'wallet' => $wallet,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function withdrawPage(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->redirect('/login');

        $wallets = Database::query("SELECT * FROM wallets WHERE user_id = ? LIMIT 1", [$user['id']]);
        $wallet = $wallets[0] ?? null;

        return $this->view('wallet.withdraw', ['wallet' => $wallet]);
    }

    public function index(): Response
    {
        $user = \Core\Auth::user();
        $wallet = null;
        $transactions = [];

        if ($user) {
            try {
                $wallets = Database::query("SELECT * FROM wallets WHERE user_id = ? LIMIT 1", [$user['id']]);
                $wallet = $wallets[0] ?? null;

                if ($wallet) {
                    $transactions = Database::query(
                        "SELECT wt.* FROM wallet_transactions wt WHERE wt.wallet_id = ? ORDER BY wt.created_at DESC LIMIT 5",
                        [$wallet['id']]
                    );
                }
            } catch (\Exception $e) {}
        }

        return $this->view('wallet.index', [
            'wallet' => $wallet,
            'transactions' => $transactions,
        ]);
    }

    public function deposit(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true);
        $amount = (float)($input['amount'] ?? 0);
        $method = $input['method'] ?? 'mpesa';

        if ($amount <= 0) return $this->json(['error' => 'Invalid amount'], 422);

        try {
            $wallets = Database::query("SELECT * FROM wallets WHERE user_id = ? LIMIT 1", [$user['id']]);

            if (empty($wallets)) {
                $walletId = Database::insert('wallets', [
                    'user_id' => $user['id'],
                    'balance' => $amount,
                    'currency' => 'KES',
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                $walletId = $wallets[0]['id'];
                Database::execute(
                    "UPDATE wallets SET balance = balance + ?, updated_at = NOW() WHERE id = ?",
                    [$amount, $walletId]
                );
            }

            Database::insert('wallet_transactions', [
                'wallet_id' => $walletId,
                'type' => 'deposit',
                'amount' => $amount,
                'currency' => 'KES',
                'method' => $method,
                'status' => 'completed',
                'reference' => 'DEP-' . strtoupper(substr(md5(uniqid()), 0, 10)),
                'description' => 'Deposit via ' . strtoupper($method),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json(['message' => 'Deposit successful', 'amount' => $amount]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function withdraw(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true);
        $amount = (float)($input['amount'] ?? 0);
        $phone = $input['phone'] ?? '';

        if ($amount <= 0) return $this->json(['error' => 'Invalid amount'], 422);

        try {
            $wallets = Database::query("SELECT * FROM wallets WHERE user_id = ? LIMIT 1", [$user['id']]);

            if (empty($wallets) || (float)$wallets[0]['balance'] < $amount) {
                return $this->json(['error' => 'Insufficient balance'], 400);
            }

            $walletId = $wallets[0]['id'];

            Database::execute(
                "UPDATE wallets SET balance = balance - ?, updated_at = NOW() WHERE id = ?",
                [$amount, $walletId]
            );

            Database::insert('wallet_transactions', [
                'wallet_id' => $walletId,
                'type' => 'withdrawal',
                'amount' => $amount,
                'currency' => 'KES',
                'method' => 'mpesa',
                'status' => 'completed',
                'reference' => 'WDR-' . strtoupper(substr(md5(uniqid()), 0, 10)),
                'description' => 'Withdrawal to ' . ($phone ?: 'M-Pesa'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json(['message' => 'Withdrawal successful', 'amount' => $amount]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function transactions(): Response
    {
        $user = \Core\Auth::user();
        $transactions = [];

        if ($user) {
            try {
                $wallets = Database::query("SELECT id FROM wallets WHERE user_id = ? LIMIT 1", [$user['id']]);
                if (!empty($wallets)) {
                    $transactions = Database::query(
                        "SELECT * FROM wallet_transactions WHERE wallet_id = ? ORDER BY created_at DESC LIMIT 50",
                        [$wallets[0]['id']]
                    );
                }
            } catch (\Exception $e) {}
        }

        return $this->view('wallet.transactions', ['transactions' => $transactions]);
    }

    public function mpesaSTKPush(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $phone = $input['phone'] ?? '';
        $amount = (float)($input['amount'] ?? 0);

        if ($amount <= 0) return $this->json(['error' => 'Invalid amount'], 400);

        try {
            $mpesa = new MpesaService();
            $normalizedPhone = MpesaService::validatePhone($phone);
            $result = $mpesa->stkPush($normalizedPhone, $amount, 'WALLET-' . $user['id']);

            return $this->json([
                'success' => true,
                'message' => 'STK Push sent to ' . $normalizedPhone,
                'checkout_request_id' => $result['checkout_request_id'],
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    public function mpesaCallback(): Response
    {
        return $this->json(['message' => 'Callback received']);
    }
}
