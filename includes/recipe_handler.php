<?php
// ============================================================
// FoodBook - Recipe CRUD Handler
// Student: Ishara Nishshanka | ITT/2021/076
// ============================================================
session_start();
require_once 'db.php';
require_once 'functions.php';

requireLogin();

$user_id = (int)$_SESSION['user_id'];

// ----------------------------------------------------------------
// ADD RECIPE (POST action=add)
// ----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $title        = trim($_POST['title'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $category     = trim($_POST['category'] ?? '');
    $ingredients  = trim($_POST['ingredients'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');
    $cooking_time = (int)($_POST['cooking_time'] ?? 30);
    $servings     = (int)($_POST['servings'] ?? 4);
    $image_url    = trim($_POST['image_url'] ?? '');
    $rating       = 4.0;

    // Validation
    $errors = [];
    if (empty($title))        $errors[] = 'Title is required.';
    if (empty($category))     $errors[] = 'Category is required.';
    if (empty($ingredients))  $errors[] = 'Ingredients are required.';
    if (empty($instructions) || strlen($instructions) < 20)
        $errors[] = 'Instructions must be at least 20 characters.';
    if ($cooking_time <= 0)   $cooking_time = 30;
    if ($servings <= 0)       $servings = 4;
    if (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
        $image_url = '';
    }

    if (!empty($errors)) {
        $errorStr = urlencode(implode('|', $errors));
        header("Location: ../dashboard.php?error=$errorStr");
        exit();
    }

    // Insert using prepared statement
    $stmt = $conn->prepare(
        "INSERT INTO recipes (user_id, title, description, category, ingredients, instructions, cooking_time, servings, image_url, rating)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('isssssiisd',
        $user_id, $title, $description, $category,
        $ingredients, $instructions, $cooking_time, $servings, $image_url, $rating
    );

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: ../dashboard.php?success=Recipe+added+successfully!');
    } else {
        $stmt->close();
        header('Location: ../dashboard.php?error=Failed+to+add+recipe.+Please+try+again.');
    }
    exit();
}

// ----------------------------------------------------------------
// DELETE RECIPE (GET action=delete)
// ----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'delete') {
    $recipe_id = (int)($_GET['id'] ?? 0);

    if ($recipe_id <= 0) {
        header('Location: ../dashboard.php?error=Invalid+recipe+ID.');
        exit();
    }

    // Verify ownership using prepared statement
    $stmt = $conn->prepare("SELECT id FROM recipes WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $recipe_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        header('Location: ../dashboard.php?error=Recipe+not+found+or+permission+denied.');
        exit();
    }
    $stmt->close();

    // Delete
    $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $recipe_id, $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: ../dashboard.php?success=Recipe+deleted+successfully.');
    } else {
        $stmt->close();
        header('Location: ../dashboard.php?error=Failed+to+delete+recipe.');
    }
    exit();
}

// ----------------------------------------------------------------
// EDIT RECIPE (POST action=edit)
// ----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    $recipe_id    = (int)($_POST['recipe_id'] ?? 0);
    $title        = trim($_POST['title'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $category     = trim($_POST['category'] ?? '');
    $ingredients  = trim($_POST['ingredients'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');
    $cooking_time = (int)($_POST['cooking_time'] ?? 30);
    $servings     = (int)($_POST['servings'] ?? 4);
    $image_url    = trim($_POST['image_url'] ?? '');

    if ($recipe_id <= 0) {
        header('Location: ../dashboard.php?error=Invalid+recipe+ID.');
        exit();
    }

    // Verify ownership
    $stmt = $conn->prepare("SELECT id FROM recipes WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $recipe_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        header('Location: ../dashboard.php?error=Recipe+not+found+or+permission+denied.');
        exit();
    }
    $stmt->close();

    // Validation
    $errors = [];
    if (empty($title))       $errors[] = 'Title is required.';
    if (empty($category))    $errors[] = 'Category is required.';
    if (empty($ingredients)) $errors[] = 'Ingredients are required.';
    if (empty($instructions) || strlen($instructions) < 20)
        $errors[] = 'Instructions must be at least 20 characters.';
    if ($cooking_time <= 0) $cooking_time = 30;
    if ($servings <= 0)     $servings = 4;
    if (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
        $image_url = '';
    }

    if (!empty($errors)) {
        $errorStr = urlencode(implode('|', $errors));
        header("Location: ../dashboard.php?error=$errorStr");
        exit();
    }

    // Update using prepared statement
    $stmt = $conn->prepare(
        "UPDATE recipes SET title=?, description=?, category=?, ingredients=?, instructions=?,
         cooking_time=?, servings=?, image_url=? WHERE id=? AND user_id=?"
    );
    $stmt->bind_param('sssssiisii',
        $title, $description, $category, $ingredients, $instructions,
        $cooking_time, $servings, $image_url, $recipe_id, $user_id
    );

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: ../dashboard.php?success=Recipe+updated+successfully!');
    } else {
        $stmt->close();
        header('Location: ../dashboard.php?error=Failed+to+update+recipe.+Please+try+again.');
    }
    exit();
}

// Fallback
header('Location: ../dashboard.php');
exit();
?>
