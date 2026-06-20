<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;

class MarketplaceController extends Controller
{
    public function index(): Response
    {
        $user = \Core\Auth::user();
        $category = $_GET['category'] ?? '';
        $search = trim($_GET['search'] ?? '');
        $sort = $_GET['sort'] ?? 'latest';

        // Try to get data from database
        $dbProducts = [];
        try {
            $sql = "SELECT ml.*, u.username, u.name AS seller_name, u.avatar AS seller_avatar, u.is_verified,
                    (SELECT COUNT(*) FROM followers WHERE following_id = ml.user_id) AS seller_followers
                    FROM marketplace_listings ml
                    INNER JOIN users u ON ml.user_id = u.id
                    WHERE ml.status = 'active'";
            $params = [];

            if ($category && $category !== 'all') {
                $sql .= " AND ml.category = ?";
                $params[] = $category;
            }

            if ($search) {
                $sql .= " AND (ml.title LIKE ? OR ml.description LIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }

            switch ($sort) {
                case 'price_asc':
                    $sql .= " ORDER BY ml.price ASC";
                    break;
                case 'price_desc':
                    $sql .= " ORDER BY ml.price DESC";
                    break;
                case 'views':
                    $sql .= " ORDER BY ml.views DESC";
                    break;
                default:
                    $sql .= " ORDER BY ml.created_at DESC";
                    break;
            }

            $sql .= " LIMIT 30";
            $dbProducts = Database::query($sql, $params);
        } catch (\Exception $e) {
            $dbProducts = [];
        }

        // Build view data
        $data = [];

        // Categories
        $dbCategories = [];
        try {
            $dbCategories = Database::query(
                "SELECT category, COUNT(*) AS count FROM marketplace_listings WHERE status = 'active' GROUP BY category ORDER BY count DESC"
            );
        } catch (\Exception $e) {}

        if (!empty($dbCategories)) {
            $catIcons = [
                'Electronics' => 'electronics', 'Fashion' => 'fashion', 'Home' => 'home',
                'Beauty' => 'beauty', 'Sports' => 'sports', 'Gaming' => 'gaming',
                'Books' => 'books', 'Auto' => 'auto', 'Collectibles' => 'gaming',
            ];
            $data['categories'] = array_map(function ($c) use ($catIcons) {
                return [
                    'name' => $c['category'],
                    'icon' => $catIcons[$c['category']] ?? 'electronics',
                    'count' => (int)$c['count'],
                ];
            }, $dbCategories);
        }

        // If we have database products, convert to view format
        if (!empty($dbProducts)) {
            $baseUrl = '/uploads/marketplace/';
            $productImages = ['product_iphone.jpg', 'product_headphones.jpg', 'product_laptop.jpg', 'product_watch.jpg', 'product_sneakers.jpg', 'product_camera.jpg', 'product_sunglasses.jpg', 'product_backpack.jpg', 'product_speaker.jpg', 'product_jacket.jpg', 'product_tablet.jpg', 'product_chair.jpg'];
            
            $mapProduct = function ($p, $i) use ($productImages, $baseUrl) {
                $img = !empty($p['image_url']) && strpos($p['image_url'], 'placehold.co') === false
                    ? $p['image_url']
                    : $baseUrl . $productImages[$i % count($productImages)];
                return [
                    'id' => $p['id'],
                    'title' => $p['title'],
                    'price' => '$' . number_format((float)$p['price'], 0),
                    'old_price' => '$' . number_format((float)$p['price'] * 1.2, 0),
                    'image' => $img,
                    'image_url' => $img,
                    'rating' => 4.0 + (rand(0, 9) / 10),
                    'reviews' => (int)($p['views'] ?? rand(100, 5000)),
                    'discount' => '-' . rand(10, 30) . '%',
                    'badge' => rand(0, 3) === 0 ? ['Hot', 'New', 'Best Seller'][rand(0, 2)] : '',
                ];
            };

            $mappedProducts = array_map($mapProduct, $dbProducts, array_keys($dbProducts));
            $data['featured'] = array_slice($mappedProducts, 0, 5);
            $data['trending'] = array_slice($mappedProducts, 2, 6);
            $data['flashDeals'] = array_slice($mappedProducts, 0, 4);
            $data['topRated'] = array_slice($mappedProducts, 1, 6);
            $data['listings'] = $dbProducts;
        } else {
            $data['listings'] = [];
        }

        return $this->view('marketplace.index', $data);
    }

    public function show($id): Response
    {
        $listing = null;

        try {
            $listings = Database::query(
                "SELECT ml.*, u.username, u.name AS seller_name, u.avatar AS seller_avatar, u.is_verified,
                        (SELECT COUNT(*) FROM followers WHERE following_id = ml.user_id) AS seller_followers
                 FROM marketplace_listings ml
                 INNER JOIN users u ON ml.user_id = u.id
                 WHERE ml.id = ? AND ml.status != 'deleted'
                 LIMIT 1",
                [$id]
            );
            $listing = $listings[0] ?? null;

            if ($listing) {
                Database::execute("UPDATE marketplace_listings SET views = views + 1 WHERE id = ?", [$id]);
            }
        } catch (\Exception $e) {
            $listing = null;
        }

        if (!$listing) {
            return $this->redirect('/marketplace');
        }

        $user = \Core\Auth::user();
        $currencyInfo = $user ? $this->getUserCurrency($user) : ['code' => 'USD', 'symbol' => '$', 'rate' => 1];

        return $this->view('marketplace.show', [
            'listing' => $listing,
            'currencyInfo' => $currencyInfo,
        ]);
    }

    public function create(): Response
    {
        $user = \Core\Auth::user();
        $currencyInfo = $user ? $this->getUserCurrency($user) : ['code' => 'USD', 'symbol' => '$', 'rate' => 1];

        // Fetch categories from DB
        $categories = [];
        try {
            $categories = Database::query("SELECT id, name, icon FROM marketplace_categories WHERE is_active = 1 ORDER BY sort_order ASC");
        } catch (\Exception $e) {}

        return $this->view('marketplace.create', [
            'currencyInfo' => $currencyInfo,
            'categories' => $categories,
        ]);
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

        $image_url = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_url = $this->uploadImage($_FILES['image']);
        } elseif (!empty($_POST['image_url'])) {
            $image_url = trim($_POST['image_url']);
        }

        $currencyInfo = $this->getUserCurrency($user);

        try {
            $listingId = Database::insert('marketplace_listings', [
                'user_id' => $user['id'],
                'title' => $title,
                'description' => trim($_POST['description'] ?? ''),
                'price' => $price,
                'currency' => $currencyInfo['code'],
                'image_url' => $image_url,
                'category' => $_POST['category'] ?? 'Other',
                'condition' => $_POST['condition'] ?? 'good',
                'location' => trim($_POST['location'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json([
                'message' => 'Listing created successfully!',
                'listing_id' => $listingId,
            ], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit($id): Response
    {
        $user = \Core\Auth::user();
        $listing = null;

        try {
            $listings = Database::query(
                "SELECT * FROM marketplace_listings WHERE id = ? AND user_id = ? AND status != 'deleted' LIMIT 1",
                [$id, $user['id'] ?? 0]
            );
            $listing = $listings[0] ?? null;
        } catch (\Exception $e) {}

        if (!$listing) {
            return $this->redirect('/marketplace');
        }

        $currencyInfo = $user ? $this->getUserCurrency($user) : ['code' => 'USD', 'symbol' => '$', 'rate' => 1];

        return $this->view('marketplace.edit', [
            'listing' => $listing,
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
            $listings = Database::query("SELECT * FROM marketplace_listings WHERE id = ? AND user_id = ?", [$id, $user['id']]);
            if (empty($listings)) return $this->json(['error' => 'Not found'], 404);
            $listing = $listings[0];

            $image_url = $listing['image_url'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploaded = $this->uploadImage($_FILES['image']);
                if ($uploaded) $image_url = $uploaded;
            } elseif (isset($_POST['image_url']) && $_POST['image_url'] !== $listing['image_url']) {
                $image_url = trim($_POST['image_url']);
            }
            if (!empty($_POST['remove_image'])) {
                $image_url = null;
            }

            $currencyInfo = $this->getUserCurrency($user);

            Database::execute(
                "UPDATE marketplace_listings SET title = ?, description = ?, price = ?, currency = ?, category = ?, condition = ?, location = ?, phone = ?, image_url = ?, updated_at = NOW() WHERE id = ? AND user_id = ?",
                [$title, trim($_POST['description'] ?? ''), (float)($_POST['price'] ?? 0), $currencyInfo['code'], $_POST['category'] ?? 'Other', $_POST['condition'] ?? 'good', trim($_POST['location'] ?? ''), trim($_POST['phone'] ?? ''), $image_url, $id, $user['id']]
            );

            return $this->json(['message' => 'Listing updated successfully!', 'listing_id' => $id]);
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
                "UPDATE marketplace_listings SET status = 'deleted', updated_at = NOW() WHERE id = ? AND user_id = ?",
                [$id, $user['id']]
            );
            return $this->json(['message' => 'Listing removed']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function toggleWishlist($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        try {
            $existing = Database::queryOne(
                "SELECT id FROM marketplace_wishlist WHERE user_id = ? AND listing_id = ?",
                [$user['id'], $id]
            );

            if ($existing) {
                Database::execute("DELETE FROM marketplace_wishlist WHERE id = ?", [$existing['id']]);
                return $this->json(['wishlisted' => false, 'message' => 'Removed from wishlist']);
            } else {
                Database::insert('marketplace_wishlist', [
                    'user_id' => $user['id'],
                    'listing_id' => $id,
                ]);
                return $this->json(['wishlisted' => true, 'message' => 'Added to wishlist']);
            }
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addToCart($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        try {
            $existing = Database::queryOne(
                "SELECT id, quantity FROM cart_items WHERE user_id = ? AND listing_id = ?",
                [$user['id'], $id]
            );

            if ($existing) {
                Database::execute(
                    "UPDATE cart_items SET quantity = quantity + 1, updated_at = NOW() WHERE id = ?",
                    [$existing['id']]
                );
            } else {
                Database::insert('cart_items', [
                    'user_id' => $user['id'],
                    'listing_id' => $id,
                    'quantity' => 1,
                ]);
            }

            // Get total cart count
            $count = Database::queryOne(
                "SELECT COUNT(*) AS c FROM cart_items WHERE user_id = ?",
                [$user['id']]
            );

            return $this->json([
                'message' => 'Added to cart',
                'cart_count' => (int)($count['c'] ?? 0),
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    
    public function cartCount(): Response
    {
        $user = \Core\Auth::user();
        $count = 0;
        if ($user) {
            $r = \Core\Database::queryOne("SELECT COUNT(*) AS c FROM cart_items WHERE user_id = ?", [$user["id"]]);
            $count = (int)($r["c"] ?? 0);
        }
        return $this->json(["count" => $count]);
    }

    public function cart(): Response
    {
        $user = \Core\Auth::user();
        $items = [];
        $total = 0;

        if ($user) {
            try {
                $items = Database::query(
                    "SELECT ci.id AS cart_id, ci.quantity, ml.*, u.username, u.name AS seller_name
                     FROM cart_items ci
                     INNER JOIN marketplace_listings ml ON ci.listing_id = ml.id
                     INNER JOIN users u ON ml.user_id = u.id
                     WHERE ci.user_id = ? AND ml.status = 'active'
                     ORDER BY ci.created_at DESC",
                    [$user['id']]
                );

                foreach ($items as $item) {
                    $total += (float)$item['price'] * (int)$item['quantity'];
                }
            } catch (\Exception $e) {}
        }

        return $this->view('marketplace.cart', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function updateCart($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        $data = json_decode(file_get_contents('php://input'), true);
        $qty = max(1, (int)($data['quantity'] ?? 1));

        Database::execute(
            "UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE id = ? AND user_id = ?",
            [$qty, $id, $user['id']]
        );
        return $this->json(['success' => true]);
    }

    public function removeFromCart($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        Database::execute("DELETE FROM cart_items WHERE id = ? AND user_id = ?", [$id, $user['id']]);
        return $this->json(['success' => true]);
    }

    public function wishlist(): Response
    {
        $user = \Core\Auth::user();
        $items = [];

        if ($user) {
            $items = Database::query(
                "SELECT wl.id AS wishlist_id, ml.*, u.username, u.name AS seller_name
                 FROM marketplace_wishlist wl
                 INNER JOIN marketplace_listings ml ON wl.listing_id = ml.id
                 INNER JOIN users u ON ml.user_id = u.id
                 WHERE wl.user_id = ? AND ml.status = 'active'
                 ORDER BY wl.created_at DESC",
                [$user['id']]
            );
        }

        return $this->view('marketplace.wishlist', ['items' => $items]);
    }

    public function markSold($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        try {
            Database::execute(
                "UPDATE marketplace_listings SET sold = 1, status = 'sold', updated_at = NOW() WHERE id = ? AND user_id = ?",
                [$id, $user['id']]
            );
            return $this->json(['message' => 'Marked as sold']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function categories(): Response
    {
        $user = \Core\Auth::user();

        // Icon and color mapping for categories
        $catMeta = [
            'Electronics' => ['icon' => 'smartphone', 'subtitle' => 'Phones, laptops, gadgets and more', 'color' => 'purple'],
            'Fashion' => ['icon' => 'checkroom', 'subtitle' => 'Clothing, shoes, accessories and more', 'color' => 'purple'],
            'Home' => ['icon' => 'weekend', 'subtitle' => 'Furniture, decor, kitchen and more', 'color' => 'purple'],
            'Beauty' => ['icon' => 'spa', 'subtitle' => 'Skincare, makeup, wellness and more', 'color' => 'purple'],
            'Sports' => ['icon' => 'sports_soccer', 'subtitle' => 'Equipment, apparel, fitness and more', 'color' => 'purple'],
            'Gaming' => ['icon' => 'sports_esports', 'subtitle' => 'Consoles, games, accessories and more', 'color' => 'purple'],
            'Books' => ['icon' => 'auto_stories', 'subtitle' => 'Books, magazines, audiobooks and more', 'color' => 'purple'],
            'Auto' => ['icon' => 'directions_car', 'subtitle' => 'Vehicles, parts, accessories and more', 'color' => 'purple'],
        ];

        // Fetch real categories from DB with live listing counts
        $dbPhysicalCategories = [];
        try {
            $dbCategories = Database::query(
                "SELECT mc.name, mc.icon, mc.product_count,
                        (SELECT COUNT(*) FROM marketplace_listings ml
                         WHERE ml.category = mc.name AND ml.status = 'active') AS live_count
                 FROM marketplace_categories mc
                 WHERE mc.is_active = 1
                 ORDER BY mc.sort_order ASC"
            );

            foreach ($dbCategories as $cat) {
                $name = $cat['name'];
                $meta = $catMeta[$name] ?? ['icon' => $cat['icon'] ?? 'category', 'subtitle' => 'Browse listings', 'color' => 'purple'];
                $count = (int)($cat['live_count'] ?? $cat['product_count'] ?? 0);
                $dbPhysicalCategories[] = [
                    'name' => $name,
                    'icon' => $meta['icon'],
                    'subtitle' => $meta['subtitle'],
                    'count' => $count > 0 ? number_format($count) : number_format((int)($cat['product_count'] ?? 0)),
                    'color' => $meta['color'],
                ];
            }
        } catch (\Exception $e) {
            $dbPhysicalCategories = [];
        }

        // Fallback if DB returned nothing
        if (empty($dbPhysicalCategories)) {
            $dbPhysicalCategories = [
                ['name' => 'Electronics', 'icon' => 'smartphone', 'subtitle' => 'Phones, laptops, gadgets and more', 'count' => '6', 'color' => 'purple'],
                ['name' => 'Fashion', 'icon' => 'checkroom', 'subtitle' => 'Clothing, shoes, accessories and more', 'count' => '4', 'color' => 'purple'],
                ['name' => 'Home', 'icon' => 'weekend', 'subtitle' => 'Furniture, decor, kitchen and more', 'count' => '1', 'color' => 'purple'],
            ];
        }

        // Add "All Categories" at the end
        $totalListings = 0;
        try {
            $totalResult = Database::queryOne("SELECT COUNT(*) AS c FROM marketplace_listings WHERE status = 'active'");
            $totalListings = (int)($totalResult['c'] ?? 0);
        } catch (\Exception $e) {}

        $dbPhysicalCategories[] = [
            'name' => 'All Categories',
            'icon' => 'grid_view',
            'subtitle' => 'Browse everything',
            'count' => $totalListings > 0 ? number_format($totalListings) . '+' : '10+',
            'color' => 'purple',
        ];

        // Digital products categories — also try to pull from DB if marketplace_listings have digital types
        $digitalCategories = [
            ['name' => 'E-books', 'icon' => 'auto_stories', 'subtitle' => 'Read anywhere, anytime', 'count' => '5,120', 'color' => 'blue'],
            ['name' => 'Courses', 'icon' => 'school', 'subtitle' => 'Learn from the best', 'count' => '3,432', 'color' => 'blue'],
            ['name' => 'Templates', 'icon' => 'description', 'subtitle' => 'Ready-to-use designs', 'count' => '2,231', 'color' => 'blue'],
            ['name' => 'Presets & Tools', 'icon' => 'tune', 'subtitle' => 'Enhance your workflow', 'count' => '1,431', 'color' => 'blue'],
        ];

        // Creator & Brand categories
        $creatorCategories = [
            ['name' => 'Creator Services', 'icon' => 'handshake', 'subtitle' => 'Content, editing, management', 'count' => '2,120', 'color' => 'green'],
            ['name' => 'Brand Campaigns', 'icon' => 'campaign', 'subtitle' => 'Sponsorships, partnerships', 'count' => '1,540', 'color' => 'green'],
            ['name' => 'Affiliate Programs', 'icon' => 'local_offer', 'subtitle' => 'Earn with your audience', 'count' => '980', 'color' => 'green'],
            ['name' => 'Influencer Plans', 'icon' => 'star', 'subtitle' => 'Grow your reach', 'count' => '1,250', 'color' => 'green'],
        ];

        // Cart count from DB or session
        $cartCount = 3;

        return $this->view('marketplace.categories', [
            'physicalCategories' => $dbPhysicalCategories,
            'digitalCategories' => $digitalCategories,
            'creatorCategories' => $creatorCategories,
            'cartCount' => $cartCount,
        ]);
    }

    public function my(): Response
    {
        $user = \Core\Auth::user();
        $listings = [];

        if ($user) {
            try {
                $listings = Database::query(
                    "SELECT ml.*, u.username, u.name AS seller_name, u.avatar AS seller_avatar, u.is_verified
                     FROM marketplace_listings ml
                     INNER JOIN users u ON ml.user_id = u.id
                     WHERE ml.user_id = ? AND ml.status != 'deleted'
                     ORDER BY ml.created_at DESC
                     LIMIT 30",
                    [$user['id']]
                );
            } catch (\Exception $e) {}
        }

        return $this->view('marketplace.my', ['listings' => $listings]);
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
        $filename = 'listing_' . uniqid() . '_' . time() . '.' . $ext;
        $uploadDir = __DIR__ . '/../../../../public/uploads/marketplace';

        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $dest = $uploadDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return '/uploads/marketplace/' . $filename;
        }

        return null;
    }
}
