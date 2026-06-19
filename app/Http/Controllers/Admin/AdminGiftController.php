<?php

namespace App\Http\Controllers\Admin;

use Core\Controller;
use Core\Response;
use Core\Database;

class AdminGiftController extends Controller
{
    public function index(): Response
    {
        try {
            $gifts = Database::query(
                "SELECT * FROM stream_gifts ORDER BY sort_order ASC"
            );
        } catch (\Exception $e) {
            $gifts = [];
        }

        return $this->view('admin.gifts.index', [
            'gifts' => $gifts,
        ]);
    }

    public function create(): Response
    {
        return $this->view('admin.gifts.create');
    }

    public function store(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return \Core\Response::redirect('/login');

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'card_giftcard');
        $price_usd = (float)($_POST['price_usd'] ?? 1.00);
        $color_class = trim($_POST['color_class'] ?? 'text-amber-400');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = !empty($_POST['is_active']) ? 1 : 0;
        $is_animated = !empty($_POST['is_animated']) ? 1 : 0;
        $image_url = null;

        if (empty($name)) {
            return $this->view('admin.gifts.create', [
                'error' => 'Gift name is required',
                'old' => $_POST,
            ]);
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_url = $this->uploadImage($_FILES['image']);
            if (!$image_url) {
                return $this->view('admin.gifts.create', [
                    'error' => 'Failed to upload image. Allowed: JPG, PNG, GIF, WEBP under 2MB',
                    'old' => $_POST,
                ]);
            }
        }

        try {
            Database::insert('stream_gifts', [
                'name' => $name,
                'description' => $description ?: null,
                'icon' => $icon,
                'image_url' => $image_url,
                'price_usd' => $price_usd,
                'color_class' => $color_class,
                'sort_order' => $sort_order,
                'is_active' => $is_active,
                'is_animated' => $is_animated,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->redirect('/admin/gifts');
        } catch (\Exception $e) {
            return $this->view('admin.gifts.create', [
                'error' => 'Database error: ' . $e->getMessage(),
                'old' => $_POST,
            ]);
        }
    }

    public function edit($id): Response
    {
        try {
            $gifts = Database::query("SELECT * FROM stream_gifts WHERE id = ?", [$id]);
            if (empty($gifts)) {
                return $this->redirect('/admin/gifts');
            }
            $gift = $gifts[0];
        } catch (\Exception $e) {
            return $this->redirect('/admin/gifts');
        }

        return $this->view('admin.gifts.edit', [
            'gift' => $gift,
        ]);
    }

    public function update($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return \Core\Response::redirect('/login');

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'card_giftcard');
        $price_usd = (float)($_POST['price_usd'] ?? 1.00);
        $color_class = trim($_POST['color_class'] ?? 'text-amber-400');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = !empty($_POST['is_active']) ? 1 : 0;
        $is_animated = !empty($_POST['is_animated']) ? 1 : 0;

        if (empty($name)) {
            return $this->redirect('/admin/gifts/' . $id . '/edit');
        }

        try {
            $gift = Database::query("SELECT * FROM stream_gifts WHERE id = ?", [$id]);
            if (empty($gift)) return $this->redirect('/admin/gifts');
            $gift = $gift[0];

            $image_url = $gift['image_url'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $newImage = $this->uploadImage($_FILES['image']);
                if ($newImage) {
                    $image_url = $newImage;
                }
            }

            Database::execute(
                "UPDATE stream_gifts SET name=?, description=?, icon=?, image_url=?, price_usd=?, color_class=?, sort_order=?, is_active=?, is_animated=?, updated_at=NOW() WHERE id=?",
                [$name, $description ?: null, $icon, $image_url, $price_usd, $color_class, $sort_order, $is_active, $is_animated, $id]
            );

            return $this->redirect('/admin/gifts');
        } catch (\Exception $e) {
            return $this->redirect('/admin/gifts/' . $id . '/edit');
        }
    }

    public function destroy($id): Response
    {
        try {
            Database::execute("DELETE FROM stream_gifts WHERE id = ?", [$id]);
            return $this->json(['success' => true]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function uploadImage(array $file): ?string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($file['type'], $allowedTypes)) return null;
        if ($file['size'] > $maxSize) return null;

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'gift_' . uniqid() . '_' . time() . '.' . $ext;
        $uploadDir = __DIR__ . '/../../../../public/uploads/gifts';

        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $dest = $uploadDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return '/uploads/gifts/' . $filename;
        }

        return null;
    }
}
