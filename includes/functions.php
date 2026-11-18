<?php
// ============================================================
// FoodBook - Helper Functions
// Student: Ishara Nishshanka | ITT/2021/076
// ============================================================

/**
 * Sanitize user input
 */
function sanitize($conn, $input) {
    return htmlspecialchars($conn->real_escape_string(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redirect to login if not authenticated
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . (strpos($_SERVER['PHP_SELF'], '/auth/') !== false ? 'login.php' : 'auth/login.php'));
        exit();
    }
}

/**
 * Format a MySQL DATETIME string to a human-readable date
 */
function formatDate($date) {
    if (empty($date)) return 'N/A';
    return date('F j, Y', strtotime($date));
}

/**
 * Get all recipes by a specific user
 */
function getRecipesByUser($conn, $user_id) {
    $user_id = (int)$user_id;
    $result = $conn->query("SELECT * FROM recipes WHERE user_id = $user_id ORDER BY created_at DESC");
    $recipes = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }
    }
    return $recipes;
}

/**
 * Get all recipes, optionally limited
 */
function getAllRecipes($conn, $limit = null) {
    $sql = "SELECT r.*, u.username FROM recipes r LEFT JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC";
    if ($limit !== null) {
        $limit = (int)$limit;
        $sql .= " LIMIT $limit";
    }
    $result = $conn->query($sql);
    $recipes = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }
    }
    return $recipes;
}

/**
 * Get a single recipe by its ID
 */
function getRecipeById($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT r.*, u.username FROM recipes r LEFT JOIN users u ON r.user_id = u.id WHERE r.id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

/**
 * Returns Bootstrap badge color class based on category
 */
function getCategoryBadgeClass($category) {
    $map = [
        'Breakfast' => 'bg-warning text-dark',
        'Lunch'     => 'bg-success',
        'Dinner'    => 'bg-primary',
        'Desserts'  => 'bg-danger',
        'Snacks'    => 'bg-info text-dark',
        'Drinks'    => 'bg-secondary',
    ];
    return $map[$category] ?? 'bg-dark';
}

/**
 * Returns HTML star icons for a given rating
 */
function displayStars($rating) {
    $rating = (float)$rating;
    $full  = floor($rating);
    $half  = ($rating - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    $html  = '';
    for ($i = 0; $i < $full; $i++) {
        $html .= '<i class="fas fa-star text-warning"></i>';
    }
    if ($half) {
        $html .= '<i class="fas fa-star-half-alt text-warning"></i>';
    }
    for ($i = 0; $i < $empty; $i++) {
        $html .= '<i class="far fa-star text-warning"></i>';
    }
    return $html;
}

/**
 * Truncate text to a given length with an ellipsis
 */
function truncateText($text, $length = 100) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

/**
 * Count the number of recipes for a user
 */
function countUserRecipes($conn, $user_id) {
    $user_id = (int)$user_id;
    $result = $conn->query("SELECT COUNT(*) AS total FROM recipes WHERE user_id = $user_id");
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
    return 0;
}
?>
