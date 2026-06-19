<?php

namespace App\Http\Controllers\Admin;

use Core\Response;
use Core\Database;
use Core\Controller;

class AdminSupportController extends Controller
{
    // ==================== CATEGORIES ====================

    public function index(): Response
    {
        $categories = Database::query("SELECT * FROM support_categories ORDER BY sort_order ASC");
        $contacts = Database::query("SELECT * FROM support_contacts ORDER BY sort_order ASC");

        return $this->view('admin.support', [
            'categories' => $categories,
            'contacts' => $contacts,
        ]);
    }

    public function addCategory(): Response
    {
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $name = trim($input['name'] ?? '');
        $slug = trim($input['slug'] ?? '');
        $icon = $input['icon'] ?? 'help';

        if (empty($name) || empty($slug)) {
            return $this->json(['error' => 'Name and slug required'], 400);
        }

        $id = Database::insert('support_categories', [
            'name' => $name,
            'slug' => $slug,
            'icon' => $icon,
        ]);

        return $this->json(['success' => true, 'id' => $id]);
    }

    public function toggleCategory($id): Response
    {
        $cat = Database::queryOne("SELECT * FROM support_categories WHERE id = ?", [$id]);
        if (!$cat) return $this->json(['error' => 'Not found'], 404);

        $newState = $cat['is_active'] ? 0 : 1;
        Database::execute("UPDATE support_categories SET is_active = ? WHERE id = ?", [$newState, $id]);

        return $this->json(['success' => true, 'is_active' => $newState]);
    }

    public function deleteCategory($id): Response
    {
        Database::execute("DELETE FROM support_categories WHERE id = ?", [$id]);
        return $this->json(['success' => true]);
    }

    // ==================== CONTACTS ====================

    public function addContact(): Response
    {
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $type = $input['type'] ?? 'email';
        $label = trim($input['label'] ?? '');
        $value = trim($input['value'] ?? '');
        $icon = $input['icon'] ?? 'info';

        if (empty($label) || empty($value)) {
            return $this->json(['error' => 'Label and value required'], 400);
        }

        $id = Database::insert('support_contacts', [
            'type' => $type,
            'label' => $label,
            'value' => $value,
            'icon' => $icon,
        ]);

        return $this->json(['success' => true, 'id' => $id]);
    }

    public function toggleContact($id): Response
    {
        $c = Database::queryOne("SELECT * FROM support_contacts WHERE id = ?", [$id]);
        if (!$c) return $this->json(['error' => 'Not found'], 404);

        $newState = $c['is_active'] ? 0 : 1;
        Database::execute("UPDATE support_contacts SET is_active = ? WHERE id = ?", [$newState, $id]);

        return $this->json(['success' => true, 'is_active' => $newState]);
    }

    public function deleteContact($id): Response
    {
        Database::execute("DELETE FROM support_contacts WHERE id = ?", [$id]);
        return $this->json(['success' => true]);
    }
}
