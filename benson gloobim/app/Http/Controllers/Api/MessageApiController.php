<?php

namespace App\Http\Controllers\Api;

use Core\Controller;
use Core\Response;

class MessageApiController extends Controller
{
    public function index(): Response
    {
        return $this->json(['conversations' => []]);
    }

    public function show($id): Response
    {
        return $this->json(['messages' => [], 'conversation_id' => $id]);
    }

    public function send(): Response
    {
        return $this->json(['message' => 'Message sent'], 201);
    }
}
