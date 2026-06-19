<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;

class MarketController extends Controller
{
    public function index(): Response
    {
        $user = \Core\Auth::user();
        $type = $_GET['type'] ?? '';
        $category = $_GET['category'] ?? '';
        $search = trim($_GET['search'] ?? '');

        try {
            $sql = "SELECT mi.*, u.username, u.name AS seller_name, u.avatar AS seller_avatar, u.is_verified
                    FROM market_items mi
                    INNER JOIN users u ON mi.user_id = u.id
                    WHERE mi.status = 'active'";
            $params = [];

            if ($type && in_array($type, ['digital', 'service'])) {
                $sql .= " AND mi.type = ?";
                $params[] = $type;
            }

            if ($category && $category !== 'all') {
                $sql .= " AND mi.category = ?";
                $params[] = $category;
            }

            if ($search) {
                $sql .= " AND (mi.title LIKE ? OR mi.description LIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }

            $sql .= " ORDER BY mi.is_featured DESC, mi.created_at DESC LIMIT 30";

            $items = Database::query($sql, $params);
        } catch (\Exception $e) {
            $items = [];
        }

        $categories = [];
        try {
            $categories = Database::query(
                "SELECT category, type, COUNT(*) AS count FROM market_items WHERE status = 'active' GROUP BY category ORDER BY count DESC"
            );
        } catch (\Exception $e) {}

        $userItems = [];
        if ($user) {
            try {
                $userItems = Database::query(
                    "SELECT * FROM market_items WHERE user_id = ? AND status != 'deleted' ORDER BY created_at DESC LIMIT 5",
                    [$user['id']]
                );
            } catch (\Exception $e) {}
        }

        return $this->view('market.index', [
            'items' => $items,
            'categories' => $categories,
            'userItems' => $userItems,
            'activeType' => $type,
            'activeCategory' => $category,
            'search' => $search,
            'currencyInfo' => $user ? $this->getUserCurrency($user) : ['code' => 'USD', 'symbol' => '$', 'rate' => 1],
        ]);
    }

    public function show($id): Response
    {
        $item = null;

        try {
            $results = Database::query(
                "SELECT mi.*, u.username, u.name AS seller_name, u.avatar AS seller_avatar, u.is_verified, u.bio AS seller_bio
                 FROM market_items mi
                 INNER JOIN users u ON mi.user_id = u.id
                 WHERE mi.id = ? AND mi.status != 'deleted'
                 LIMIT 1",
                [$id]
            );
            $item = $results[0] ?? null;

            if ($item) {
                Database::execute("UPDATE market_items SET views = views + 1 WHERE id = ?", [$id]);
            }
        } catch (\Exception $e) {
            $item = null;
        }

        if (!$item) {
            return $this->redirect('/market');
        }

        $moreItems = [];
        if ($item) {
            try {
                $moreItems = Database::query(
                    "SELECT mi.*, u.username, u.name AS seller_name, u.avatar AS seller_avatar
                     FROM market_items mi INNER JOIN users u ON mi.user_id = u.id
                     WHERE mi.type = ? AND mi.status = 'active' AND mi.id != ?
                     ORDER BY mi.orders_count DESC LIMIT 4",
                    [$item['type'], $id]
                );
            } catch (\Exception $e) {}
        }

        $user = \Core\Auth::user();
        $currencyInfo = $user ? $this->getUserCurrency($user) : ['code' => 'USD', 'symbol' => '$', 'rate' => 1];

        return $this->view('market.show', [
            'item' => $item,
            'moreItems' => $moreItems,
            'currencyInfo' => $currencyInfo,
        ]);
    }

    public function create(): Response
    {
        $user = \Core\Auth::user();
        $currencyInfo = $user ? $this->getUserCurrency($user) : ['code' => 'USD', 'symbol' => '$', 'rate' => 1];
        return $this->view('market.create', ['currencyInfo' => $currencyInfo]);
    }

    public function store(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $title = trim($_POST['title'] ?? '');

        if (empty($title)) {
            return $this->json(['error' => 'Title is required'], 422);
        }

        $price = (float)($_POST['price'] ?? 0);
        if ($price <= 0) {
            return $this->json(['error' => 'Valid price is required'], 422);
        }

        $type = $_POST['type'] ?? 'digital';
        if (!in_array($type, ['digital', 'service'])) {
            return $this->json(['error' => 'Invalid type'], 422);
        }

        $thumbnail = null;
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $thumbnail = $this->uploadImage($_FILES['thumbnail']);
        } elseif (!empty($_POST['thumbnail'])) {
            $thumbnail = trim($_POST['thumbnail']);
        }

        $file_url = null;
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file_url = $this->uploadFile($_FILES['file']);
        } elseif (!empty($_POST['file_url'])) {
            $file_url = trim($_POST['file_url']);
        }

        $currencyInfo = $this->getUserCurrency($user);

        try {
            $itemId = Database::insert('market_items', [
                'user_id' => $user['id'],
                'title' => $title,
                'description' => trim($_POST['description'] ?? ''),
                'type' => $type,
                'price' => $price,
                'currency' => $currencyInfo['code'],
                'file_url' => $file_url,
                'preview_url' => !empty($_POST['preview_url']) ? trim($_POST['preview_url']) : null,
                'thumbnail' => $thumbnail,
                'category' => $_POST['category'] ?? 'Other',
                'tags' => !empty($_POST['tags']) ? json_encode(explode(',', $_POST['tags'])) : null,
                'delivery_time' => $_POST['delivery_time'] ?? null,
                'requirements' => $_POST['requirements'] ?? null,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json([
                'message' => ucfirst($type) . ' created successfully!',
                'item_id' => $itemId,
            ], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function my(): Response
    {
        $user = \Core\Auth::user();
        $items = [];

        if ($user) {
            try {
                $items = Database::query(
                    "SELECT mi.*, u.username, u.name AS seller_name, u.avatar AS seller_avatar, u.is_verified
                     FROM market_items mi
                     INNER JOIN users u ON mi.user_id = u.id
                     WHERE mi.user_id = ? AND mi.status != 'deleted'
                     ORDER BY mi.created_at DESC
                     LIMIT 30",
                    [$user['id']]
                );
            } catch (\Exception $e) {}
        }

        $currencyInfo = $user ? $this->getUserCurrency($user) : ['code' => 'USD', 'symbol' => '$', 'rate' => 1];

        return $this->view('market.my', ['items' => $items, 'currencyInfo' => $currencyInfo]);
    }

    public function edit($id): Response
    {
        $user = \Core\Auth::user();
        $item = null;

        try {
            $results = Database::query(
                "SELECT * FROM market_items WHERE id = ? AND user_id = ? AND status != 'deleted' LIMIT 1",
                [$id, $user['id'] ?? 0]
            );
            $item = $results[0] ?? null;
        } catch (\Exception $e) {}

        if (!$item) {
            return $this->redirect('/market');
        }

        $user = \Core\Auth::user();
        $currencyInfo = $user ? $this->getUserCurrency($user) : ['code' => 'USD', 'symbol' => '$', 'rate' => 1];

        return $this->view('market.edit', [
            'item' => $item,
            'currencyInfo' => $currencyInfo,
        ]);
    }

    public function update($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $title = trim($_POST['title'] ?? '');

        if (empty($title)) {
            return $this->json(['error' => 'Title is required'], 422);
        }

        try {
            $results = Database::query("SELECT * FROM market_items WHERE id = ? AND user_id = ?", [$id, $user['id']]);
            if (empty($results)) return $this->json(['error' => 'Not found'], 404);
            $item = $results[0];

            $thumbnail = $item['thumbnail'];
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $uploaded = $this->uploadImage($_FILES['thumbnail']);
                if ($uploaded) $thumbnail = $uploaded;
            } elseif (isset($_POST['thumbnail']) && $_POST['thumbnail'] !== $item['thumbnail']) {
                $thumbnail = trim($_POST['thumbnail']);
            }
            if (!empty($_POST['remove_thumbnail'])) {
                $thumbnail = null;
            }

            $file_url = $item['file_url'];
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploaded = $this->uploadFile($_FILES['file']);
                if ($uploaded) $file_url = $uploaded;
            } elseif (isset($_POST['file_url']) && $_POST['file_url'] !== $item['file_url']) {
                $file_url = trim($_POST['file_url']);
            }

            $currencyInfo = $this->getUserCurrency($user);

            Database::execute(
                "UPDATE market_items SET title = ?, description = ?, price = ?, currency = ?, category = ?, file_url = ?, preview_url = ?, thumbnail = ?, delivery_time = ?, requirements = ?, updated_at = NOW() WHERE id = ? AND user_id = ?",
                [
                    $title,
                    trim($_POST['description'] ?? ''),
                    (float)($_POST['price'] ?? 0),
                    $currencyInfo['code'],
                    $_POST['category'] ?? 'Other',
                    $file_url,
                    $_POST['preview_url'] ?? null,
                    $thumbnail,
                    $_POST['delivery_time'] ?? null,
                    $_POST['requirements'] ?? null,
                    $id,
                    $user['id'],
                ]
            );

            return $this->json(['message' => 'Item updated successfully!', 'item_id' => $id]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        try {
            Database::execute(
                "UPDATE market_items SET status = 'deleted', updated_at = NOW() WHERE id = ? AND user_id = ?",
                [$id, $user['id']]
            );
            return $this->json(['message' => 'Item removed']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        try {
            $results = Database::query(
                "SELECT status FROM market_items WHERE id = ? AND user_id = ? LIMIT 1",
                [$id, $user['id']]
            );
            $item = $results[0] ?? null;

            if (!$item) {
                return $this->json(['error' => 'Item not found'], 404);
            }

            $newStatus = $item['status'] === 'active' ? 'inactive' : 'active';
            Database::execute(
                "UPDATE market_items SET status = ?, updated_at = NOW() WHERE id = ? AND user_id = ?",
                [$newStatus, $id, $user['id']]
            );

            return $this->json(['message' => "Item {$newStatus}", 'status' => $newStatus]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function getUserCurrency($user): array
    {
        $countryCode = $user['country_code'] ?? 'KE';
        try {
            $currencies = Database::query(
                "SELECT currency_code, currency_symbol, exchange_rate_usd FROM country_currencies WHERE country_code = ?",
                [$countryCode]
            );
            if (!empty($currencies)) {
                return [
                    'code' => $currencies[0]['currency_code'],
                    'symbol' => $currencies[0]['currency_symbol'],
                    'rate' => (float)$currencies[0]['exchange_rate_usd'],
                ];
            }
        } catch (\Exception $e) {}
        return ['code' => 'KES', 'symbol' => 'KES', 'rate' => 129.50];
    }

    protected function uploadImage(array $file): ?string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;

        if (!in_array($file['type'], $allowedTypes)) return null;
        if ($file['size'] > $maxSize) return null;

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'item_' . uniqid() . '_' . time() . '.' . $ext;
        $uploadDir = __DIR__ . '/../../../../public/uploads/market';

        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $dest = $uploadDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return '/uploads/market/' . $filename;
        }

        return null;
    }

    protected function uploadFile(array $file): ?string
    {
        $allowedTypes = ['application/zip', 'application/x-zip-compressed', 'application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'audio/mpeg', 'audio/wav', 'application/x-rar-compressed', 'application/x-7z-compressed'];
        $maxSize = 50 * 1024 * 1024;

        if ($file['size'] > $maxSize) return null;

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'file_' . uniqid() . '_' . time() . '.' . $ext;
        $uploadDir = __DIR__ . '/../../../../public/uploads/market/files';

        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $dest = $uploadDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return '/uploads/market/files/' . $filename;
        }

        return null;
    }
}
