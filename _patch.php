<?php
$file = 'C:/Users/steve/Desktop/benson gloobim/app/Http/Controllers/MarketplaceController.php';
$c = file_get_contents($file);

$method = '
    public function cartCount(): Response
    {
        $user = \Core\Auth::user();
        $count = 0;
        if ($user) {
            $r = \Core\Database::queryOne("SELECT COUNT(*) AS c FROM cart_items WHERE user_id = ?", [$user["id"]]);
            $count = (int)($r["c"] ?? 0);
        }
        return $this->json(["count" => $count]);
    }';

$c = str_replace('public function cart(): Response', $method . "\n\n    public function cart(): Response", $c);
file_put_contents($file, $c);
echo 'Done';
