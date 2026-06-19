<?php

namespace App\Http\Controllers;

use Core\Response;
use Core\Database;
use Core\Controller;

class AdminPaymentController extends Controller
{
    public function index(): Response
    {
        $methods = Database::query("SELECT * FROM payment_methods ORDER BY sort_order ASC");
        return $this->view('admin.payments', ['methods' => $methods]);
    }

    public function toggle($id): Response
    {
        $method = Database::queryOne("SELECT * FROM payment_methods WHERE id = ?", [$id]);
        if (!$method) return $this->json(['error' => 'Method not found'], 404);

        $newState = $method['is_enabled'] ? 0 : 1;
        Database::execute("UPDATE payment_methods SET is_enabled = ? WHERE id = ?", [$newState, $id]);

        return $this->json([
            'success' => true,
            'is_enabled' => $newState,
            'message' => $method['display_name'] . ' ' . ($newState ? 'enabled' : 'disabled'),
        ]);
    }
}
