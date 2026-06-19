<?php

namespace App\Models;

use Core\Database;

class Wallet
{
    protected string $table = 'wallets';

    public static function findByUser(int $userId): ?array
    {
        return Database::queryOne("SELECT * FROM wallets WHERE user_id = ? LIMIT 1", [$userId]);
    }

    public static function create(int $userId): int
    {
        return Database::insert('wallets', [
            'user_id' => $userId,
            'balance' => 0,
            'currency' => 'KES',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function deposit(int $userId, float $amount): bool
    {
        return Database::execute(
            "UPDATE wallets SET balance = balance + ?, updated_at = ? WHERE user_id = ?",
            [$amount, date('Y-m-d H:i:s'), $userId]
        ) > 0;
    }

    public static function withdraw(int $userId, float $amount): bool
    {
        $wallet = static::findByUser($userId);
        if (!$wallet || $wallet['balance'] < $amount) {
            return false;
        }
        return Database::execute(
            "UPDATE wallets SET balance = balance - ?, updated_at = ? WHERE user_id = ?",
            [$amount, date('Y-m-d H:i:s'), $userId]
        ) > 0;
    }

    public static function transactions(int $userId, int $limit = 50): array
    {
        return Database::query(
            "SELECT * FROM wallet_transactions WHERE wallet_id = (SELECT id FROM wallets WHERE user_id = ?) ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    public static function logTransaction(int $walletId, string $type, float $amount, string $description = ''): int
    {
        return Database::insert('wallet_transactions', [
            'wallet_id' => $walletId,
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
