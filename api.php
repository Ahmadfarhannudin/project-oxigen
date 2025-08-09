<?php
header('Content-Type: application/json');

// === Konfigurasi FatSecret ===
$client_id = '0823db1fe73641b5a3f547cc46c842b7';
$client_secret = '2385b33ca86c4ddfac3eb54286b6dd01';

// === Konfigurasi Nutritionix ===
$nutritionix_app_id = '
08ceda55f691309fc7aac72abd9f96f2';       // Ganti dengan app_id Nutritionix
$nutritionix_app_key = '5bf8747e5b11dce1033a9c1ba674da05';     // Ganti dengan app_key Nutritionix

$barcode = $_GET['barcode'] ?? '';
$nama = $_GET['nama'] ?? '';

function format_nutrient($value, $unit = 'g') {
    return is_numeric($value) ? round($value, 2) . " $unit" : '-';
}

// === OPEN FOOD FACTS ===
function search_openfood($barcode) {
    $regions = ['world', 'id', 'asia', 'us', 'jp', 'fr', 'de'];
    foreach ($regions as $region) {
        $url = "https://{$region}.openfoodfacts.org/api/v0/product/" . urlencode($barcode) . ".json";
        $res = @file_get_contents($url);
        if ($res) {
            $data = json_decode($res, true);
            if (!empty($data['product']) && $data['status'] == 1) {
                return $data['product'];
            }
        }
    }
    return null;
}

function search_openfood_by_name($name) {
    $matches = [];
    $keyword = strtolower(trim($name));
    $page_size = 1000; // Batas aman per request
    $page = 1;

    while (true) {
        $url = "https://world.openfoodfacts.org/cgi/search.pl?" . http_build_query([
            'search_terms' => $name,
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'page_size' => $page_size,
            'page' => $page
        ]);

        $res = @file_get_contents($url);
        if (!$res) break;

        $data = json_decode($res, true);

        if (empty($data['products']) || !is_array($data['products'])) {
            break;
        }

        foreach ($data['products'] as $product) {
            $product_name = strtolower($product['product_name'] ?? '');
            if (strpos($product_name, $keyword) !== false || $keyword === '') {
                $matches[] = $product;
            }
        }

        // Kalau sudah sampai halaman terakhir
        if (count($data['products']) < $page_size) {
            break;
        }

        $page++;
    }

    return $matches;
}




function parse_openfood_nutrients($product) {
    $n = $product['nutriments'] ?? [];
    return [
        'Calories' => format_nutrient($n['energy-kcal_serving'] ?? $n['energy-kcal_100g'] ?? null, 'kcal'),
        'Carbohydrate' => format_nutrient($n['carbohydrates_serving'] ?? $n['carbohydrates_100g'] ?? null),
        'Protein' => format_nutrient($n['proteins_serving'] ?? $n['proteins_100g'] ?? null),
        'Fat' => format_nutrient($n['fat_serving'] ?? $n['fat_100g'] ?? null),
        'Sugar' => format_nutrient($n['sugars_serving'] ?? $n['sugars_100g'] ?? null),
        'Fiber' => format_nutrient($n['fiber_serving'] ?? $n['fiber_100g'] ?? null),
        'Sodium' => format_nutrient(($n['sodium_serving'] ?? $n['sodium_100g'] ?? null) * 1000, 'mg'),
        'Serving Size' => $product['serving_size'] ?? '-'
    ];
}

// === FATSECRET ===
function get_fatsecret_token($client_id, $client_secret) {
    $ch = curl_init("https://oauth.fatsecret.com/connect/token");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&scope=basic");
    curl_setopt($ch, CURLOPT_USERPWD, "$client_id:$client_secret");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $res = curl_exec($ch);
    curl_close($ch);
    $token_data = json_decode($res, true);
    return $token_data['access_token'] ?? null;
}

function search_fatsecret($token, $query) {
    $url = "https://platform.fatsecret.com/rest/server.api";
    $params = [
        'method' => 'foods.search',
        'search_expression' => $query,
        'format' => 'json',
        'max_results' => 1
    ];
    $queryString = http_build_query($params);
    $ch = curl_init("$url?$queryString");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($res, true);

    $food = $data['foods']['food'][0] ?? null;
    if (!$food) return null;

    $food_id = $food['food_id'];
    $detail_url = "$url?method=food.get&food_id=$food_id&format=json";
    $ch = curl_init($detail_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    $detail = json_decode($res, true);
    $food_data = $detail['food'] ?? [];

    $serving = $food_data['servings']['serving'][0] ?? null;
    if (!$serving) return null;

    return [
        'product_name' => $food_data['food_name'] ?? $query,
        'image_url' => '',
        'nutrients' => [
            'Calories' => format_nutrient($serving['calories'] ?? null, 'kcal'),
            'Carbohydrate' => format_nutrient($serving['carbohydrate'] ?? null),
            'Protein' => format_nutrient($serving['protein'] ?? null),
            'Fat' => format_nutrient($serving['fat'] ?? null),
            'Sugar' => format_nutrient($serving['sugar'] ?? null),
            'Fiber' => format_nutrient($serving['fiber'] ?? null),
            'Sodium' => format_nutrient($serving['sodium'] ?? null, 'mg'),
            'Serving Size' => $serving['metric_serving_amount'] . ' ' . $serving['metric_serving_unit']
        ],
        'fallback' => 'FatSecret'
    ];
}

// === NUTRITIONIX ===
function search_nutritionix($query, $app_id, $app_key) {
    $url = "https://trackapi.nutritionix.com/v2/search/item?upc=" . urlencode($query);
    $headers = [
        "x-app-id: $app_id",
        "x-app-key: $app_key"
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($res, true);
    $item = $data['foods'][0] ?? null;
    if (!$item) return null;

    return [
        'product_name' => $item['food_name'] ?? $query,
        'image_url' => $item['photo']['thumb'] ?? '',
        'nutrients' => [
            'Calories' => format_nutrient($item['nf_calories'] ?? null, 'kcal'),
            'Carbohydrate' => format_nutrient($item['nf_total_carbohydrate'] ?? null),
            'Protein' => format_nutrient($item['nf_protein'] ?? null),
            'Fat' => format_nutrient($item['nf_total_fat'] ?? null),
            'Sugar' => format_nutrient($item['nf_sugars'] ?? null),
            'Fiber' => format_nutrient($item['nf_dietary_fiber'] ?? null),
            'Sodium' => format_nutrient($item['nf_sodium'] ?? null, 'mg'),
            'Serving Size' => $item['serving_qty'] . ' ' . $item['serving_unit']
        ],
        'fallback' => 'Nutritionix'
    ];
}

// === PROSES ===
if ($barcode) {
    if ($product = search_openfood($barcode)) {
        echo json_encode([
            'product_name' => $product['product_name'] ?? 'Produk',
            'image_url' => $product['image_url'] ?? '',
            'nutrients' => parse_openfood_nutrients($product),
            'fallback' => 'Open Food Facts (barcode)'
        ]);
        exit;
    }

    if ($nutritionix = search_nutritionix($barcode, $nutritionix_app_id, $nutritionix_app_key)) {
        echo json_encode($nutritionix);
        exit;
    }

    $token = get_fatsecret_token($client_id, $client_secret);
    if ($token && $fat_product = search_fatsecret($token, $barcode)) {
        echo json_encode($fat_product);
        exit;
    }

    echo json_encode(['error' => '❌ Produk tidak ditemukan di Open Food, Nutritionix, maupun FatSecret.']);
    exit;
}

if ($nama) {
    $products = search_openfood_by_name($nama);
    if (count($products) > 0) {
        $results = [];

        foreach ($products as $product) {
            $results[] = [
                'product_name' => $product['product_name'] ?? $nama,
                'image_url' => $product['image_url'] ?? '',
                'nutrients' => parse_openfood_nutrients($product),
                'fallback' => 'Open Food Facts (nama produk)'
            ];
        }

        echo json_encode($results);
        exit;
    }

    // Fallback ke FatSecret jika tidak ada hasil OpenFood
    $token = get_fatsecret_token($client_id, $client_secret);
    if ($token && $fat_product = search_fatsecret($token, $nama)) {
        echo json_encode([$fat_product]); // Bungkus dengan array agar frontend tetap konsisten
        exit;
    }

    echo json_encode(['error' => '❌ Produk tidak ditemukan di FatSecret maupun Open Food Facts.']);
    exit;
}



echo json_encode(['error' => '❌ Parameter barcode atau nama produk harus diisi.']);