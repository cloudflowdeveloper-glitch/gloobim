<?php

namespace App\Http\Controllers\Api;

use Core\Controller;
use Core\Response;

class FeedApiController extends Controller
{
    public function index(): Response
    {
        return $this->json(['feed' => [], 'next_page' => 1]);
    }

    public function trending(): Response
    {
        return $this->json(['trending' => []]);
    }

    public function forYou(): Response
    {
        return $this->json(['recommended' => []]);
    }

    public function subscriptions(): Response
    {
        return $this->json(['subscriptions' => []]);
    }
}
