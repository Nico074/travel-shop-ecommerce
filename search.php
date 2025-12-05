<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

$basePath = $_ENV['APP_BASE_PATH'] ?? '/lpa_travel_shop/';
$homeUrl  = $basePath . 'index.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($query === '') {
    header("Location: {$homeUrl}");
    exit;
}

$target = $homeUrl;

// Map categories to their pages
$categoryPages = [
    'backpack' => $basePath . 'products/backpacks.php',
    'tent'     => $basePath . 'products/tents.php',
    'jacket'   => $basePath . 'products/jackets.php',
];

// Try to find a matching product name
$like = '%' . $query . '%';
$stmt = $conn->prepare("SELECT category FROM products WHERE name LIKE ? ORDER BY id LIMIT 1");
$stmt->bind_param('s', $like);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!empty($result['category']) && isset($categoryPages[$result['category']])) {
    $target = $categoryPages[$result['category']];
} else {
    // If no product match, try matching category keywords
    $term = strtolower($query);
    foreach ($categoryPages as $cat => $page) {
        if (strpos($cat, $term) !== false || strpos($term, $cat) !== false) {
            $target = $page;
            break;
        }
    }
}

header("Location: {$target}");
exit;
