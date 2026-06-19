<?php

namespace App\Http\Controllers\Api;

use Core\Controller;
use Core\Response;

class CreatorApiController extends Controller
{
    public function dashboard(): Response
    {
        return $this->json(['stats' => ['views' => 0, 'subscribers' => 0, 'earnings' => 0]]);
    }

    public function analytics(): Response
    {
        return $this->json(['analytics' => []]);
    }

    public function profile($username): Response
    {
        return $this->json(['creator' => ['username' => $username]]);
    }
}
