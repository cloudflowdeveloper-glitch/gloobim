<?php

namespace App\Http\Controllers\Api;

use Core\Controller;
use Core\Response;

class ReelApiController extends Controller
{
    public function index(): Response
    {
        return $this->json(['reels' => []]);
    }

    public function store(): Response
    {
        return $this->json(['message' => 'Reel created'], 201);
    }

    public function show($id): Response
    {
        return $this->json(['reel' => ['id' => $id]]);
    }

    public function update($id): Response
    {
        return $this->json(['message' => 'Reel updated', 'id' => $id]);
    }

    public function destroy($id): Response
    {
        return $this->json(['message' => 'Reel deleted', 'id' => $id]);
    }

    public function like($id): Response
    {
        return $this->json(['message' => 'Liked', 'id' => $id]);
    }

    public function comment($id): Response
    {
        return $this->json(['message' => 'Comment added', 'id' => $id]);
    }

    public function share($id): Response
    {
        return $this->json(['message' => 'Shared', 'id' => $id]);
    }

    public function comments($id): Response
    {
        return $this->json(['comments' => [], 'reel_id' => $id]);
    }
}
