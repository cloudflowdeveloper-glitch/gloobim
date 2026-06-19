<?php

namespace App\Http\Controllers\Api;

use Core\Controller;
use Core\Response;

class VideoApiController extends Controller
{
    public function index(): Response
    {
        return $this->json(['videos' => []]);
    }

    public function store(): Response
    {
        return $this->json(['message' => 'Video uploaded'], 201);
    }

    public function show($id): Response
    {
        return $this->json(['video' => ['id' => $id]]);
    }

    public function update($id): Response
    {
        return $this->json(['message' => 'Video updated', 'id' => $id]);
    }

    public function destroy($id): Response
    {
        return $this->json(['message' => 'Video deleted', 'id' => $id]);
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
        return $this->json(['comments' => [], 'video_id' => $id]);
    }
}
