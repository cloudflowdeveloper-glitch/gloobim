<?php

namespace App\Http\Controllers;

use Core\Response;
use Core\Database;
use Core\Controller;
use Core\Auth;

class SupportController extends Controller
{
    public function tickets(): Response
    {
        $user = Auth::user();
        if (!$user) return $this->redirect('/login');

        $tickets = Database::query(
            "SELECT st.*, 
                (SELECT COUNT(*) FROM support_ticket_messages WHERE ticket_id = st.id) AS msg_count,
                (SELECT created_at FROM support_ticket_messages WHERE ticket_id = st.id ORDER BY created_at DESC LIMIT 1) AS last_reply_at
             FROM support_tickets st 
             WHERE st.user_id = ? 
             ORDER BY st.updated_at DESC",
            [$user['id']]
        );

        return $this->view('support.tickets', ['tickets' => $tickets]);
    }

    public function index(): Response
    {
        $contacts = Database::query("SELECT * FROM support_contacts WHERE is_active = 1 ORDER BY sort_order ASC");
        $categories = Database::query("SELECT * FROM support_categories WHERE is_active = 1 ORDER BY sort_order ASC");
        $user = Auth::user();

        $tickets = [];
        if ($user) {
            $tickets = Database::query(
                "SELECT * FROM support_tickets WHERE user_id = ? ORDER BY updated_at DESC LIMIT 20",
                [$user['id']]
            );
        }

        return $this->view('support.index', [
            'contacts' => $contacts,
            'categories' => $categories,
            'tickets' => $tickets,
        ]);
    }

    public function createTicket(): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $subject = trim($input['subject'] ?? '');
        $category = $input['category'] ?? 'general';
        $priority = $input['priority'] ?? 'medium';
        $message = trim($input['message'] ?? '');

        if (empty($subject) || empty($message)) {
            return $this->json(['error' => 'Subject and message are required'], 400);
        }

        $ticketNumber = 'TKT-' . strtoupper(substr(md5(uniqid()), 0, 8));

        try {
            Database::beginTransaction();

            $ticketId = Database::insert('support_tickets', [
                'ticket_number' => $ticketNumber,
                'user_id' => $user['id'],
                'subject' => $subject,
                'category' => $category,
                'priority' => $priority,
                'status' => 'open',
            ]);

            Database::insert('support_ticket_messages', [
                'ticket_id' => $ticketId,
                'user_id' => $user['id'],
                'is_admin' => 0,
                'message' => $message,
            ]);

            Database::commit();

            return $this->json([
                'success' => true,
                'ticket_id' => $ticketId,
                'ticket_number' => $ticketNumber,
                'message' => 'Ticket created successfully',
            ]);
        } catch (\Exception $e) {
            Database::rollback();
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showTicket($id): Response
    {
        $user = Auth::user();
        if (!$user) return $this->redirect('/login');

        $ticket = Database::queryOne(
            "SELECT * FROM support_tickets WHERE id = ? AND user_id = ?",
            [$id, $user['id']]
        );

        if (!$ticket) return $this->redirect('/support');

        $messages = Database::query(
            "SELECT m.*, u.username, u.name, u.avatar
             FROM support_ticket_messages m
             LEFT JOIN users u ON m.user_id = u.id
             WHERE m.ticket_id = ?
             ORDER BY m.created_at ASC",
            [$id]
        );

        return $this->view('support.ticket', [
            'ticket' => $ticket,
            'messages' => $messages,
        ]);
    }

    public function reply($id): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        $ticket = Database::queryOne(
            "SELECT * FROM support_tickets WHERE id = ? AND user_id = ?",
            [$id, $user['id']]
        );

        if (!$ticket) return $this->json(['error' => 'Ticket not found'], 404);
        if (in_array($ticket['status'], ['resolved', 'closed'])) {
            return $this->json(['error' => 'This ticket is ' . $ticket['status']], 400);
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $message = trim($input['message'] ?? '');

        if (empty($message)) {
            return $this->json(['error' => 'Message is required'], 400);
        }

        Database::insert('support_ticket_messages', [
            'ticket_id' => $id,
            'user_id' => $user['id'],
            'message' => $message,
        ]);

        Database::execute(
            "UPDATE support_tickets SET status = 'waiting', last_reply_by = 'user', updated_at = NOW() WHERE id = ?",
            [$id]
        );

        return $this->json(['success' => true, 'message' => 'Reply sent']);
    }

    public function closeTicket($id): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        Database::execute(
            "UPDATE support_tickets SET status = 'closed', updated_at = NOW() WHERE id = ? AND user_id = ?",
            [$id, $user['id']]
        );

        return $this->json(['success' => true, 'message' => 'Ticket closed']);
    }
}
