<?php
/*
  FoodBook - User Dashboard
  Student: Ishara Nishshanka (ITT/2021/076)
  Course: ICT 1209 - Web Technologies Mini Project
*/
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Protect page
requireLogin();

$user_id = (int)$_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Chef';

// Fetch current user details
$userStmt = $conn->prepare("SELECT email, created_at FROM users WHERE id = ?");
$userStmt->bind_param('i', $user_id);
$userStmt->execute();
$userData = $userStmt->get_result()->fetch_assoc();
$userStmt->close();

$joinedDate = formatDate($userData['created_at'] ?? date('Y-m-d'));

// Fetch recipes created by this user
$myRecipes = getRecipesByUser($conn, $user_id);
$totalRecipes = count($myRecipes);

// Calculate total views & average rating across user's recipes
$totalViews = 0;
$avgRating = 0;
if ($totalRecipes > 0) {
    $sumRating = 0;
    foreach ($myRecipes as $r) {
        $totalViews += (int)($r['views'] ?? 0);
        $sumRating += (float)($r['rating'] ?? 4.5);
    }
    $avgRating = number_format($sumRating / $totalRecipes, 1);
}

// Success or error alerts
$successMessage = $_GET['success'] ?? '';
$errorMessage = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard - FoodBook</title>

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

  <!-- ============================================================
       NAVBAR
       ============================================================ -->
  <nav class="navbar navbar-expand-lg fixed-top navbar-custom shadow-sm bg-white">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
        <span class="brand-icon">🍳</span>
        <span class="fw-bold tracking-tight text-brand">FoodBook</span>
      </a>

      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#dashNav" aria-controls="dashNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="dashNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#recipes-section">Browse All Recipes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact</a>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-medium">
            <i class="bi bi-person-fill me-1"></i> <?= htmlspecialchars($username) ?>
          </span>
          <a href="auth/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- ============================================================
       DASHBOARD CONTENT
       ============================================================ -->
  <div class="container" style="margin-top: 100px; margin-bottom: 60px;">
    
    <!-- Welcome Header Banner -->
    <div class="bg-white rounded-4 p-4 shadow-sm border mb-4">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
          <span class="badge bg-danger text-white rounded-pill px-3 py-1 mb-2">My Account</span>
          <h2 class="fw-bold text-dark mb-1">Welcome, <?= htmlspecialchars($username) ?></h2>
          <p class="text-muted small mb-0">
            Manage your recipes or submit a new recipe below.
          </p>
        </div>
        <div>
          <a href="#addRecipeCard" class="btn btn-danger rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Add New Recipe
          </a>
        </div>
      </div>
    </div>

    <!-- Feedback Alerts -->
    <?php if (!empty($successMessage)): ?>
      <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
        <div><?= htmlspecialchars($successMessage) ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
      <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
        <div>
          <?php 
            $errList = explode('|', $errorMessage);
            if (count($errList) > 1) {
              echo '<ul class="mb-0 ps-3">';
              foreach ($errList as $e) { echo '<li>' . htmlspecialchars($e) . '</li>'; }
              echo '</ul>';
            } else {
              echo htmlspecialchars($errorMessage);
            }
          ?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- User Stats Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
          <div class="feature-icon-wrapper bg-danger-subtle text-danger rounded-circle mx-auto mb-2 p-2">
            <i class="bi bi-book-half fs-4"></i>
          </div>
          <h3 class="fw-bold mb-0 text-dark"><?= $totalRecipes ?></h3>
          <small class="text-muted">My Recipes</small>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
          <div class="feature-icon-wrapper bg-warning-subtle text-warning-emphasis rounded-circle mx-auto mb-2 p-2">
            <i class="bi bi-eye-fill fs-4"></i>
          </div>
          <h3 class="fw-bold mb-0 text-dark"><?= $totalViews ?></h3>
          <small class="text-muted">Total Views</small>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
          <div class="feature-icon-wrapper bg-info-subtle text-info-emphasis rounded-circle mx-auto mb-2 p-2">
            <i class="bi bi-star-fill fs-4"></i>
          </div>
          <h3 class="fw-bold mb-0 text-dark"><?= $totalRecipes > 0 ? $avgRating : '4.5' ?></h3>
          <small class="text-muted">Avg Rating</small>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
          <div class="feature-icon-wrapper bg-success-subtle text-success rounded-circle mx-auto mb-2 p-2">
            <i class="bi bi-calendar-check-fill fs-4"></i>
          </div>
          <h6 class="fw-bold mb-0 text-dark pt-1"><?= $joinedDate ?></h6>
          <small class="text-muted">Member Since</small>
        </div>
      </div>
    </div>

    <!-- ============================================================
         MY RECIPES LISTING
         ============================================================ -->
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-5">
      <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <div>
          <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-text me-2 text-danger"></i> My Published Recipes</h5>
          <small class="text-muted">Showing all recipes created under your account</small>
        </div>
        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
          <?= $totalRecipes ?> Recipe<?= $totalRecipes === 1 ? '' : 's' ?>
        </span>
      </div>

      <div class="card-body p-4 pt-1">
        <?php if ($totalRecipes === 0): ?>
          <div class="text-center py-5">
            <div class="display-3 text-muted mb-3 opacity-50">🥣</div>
            <h5 class="fw-bold text-dark">No Recipes Submitted Yet</h5>
            <p class="text-muted small max-w-500 mx-auto">
              You haven't added any recipes to your cookbook yet. Share your secret ingredients and cooking techniques with our food community!
            </p>
            <a href="#addRecipeCard" class="btn btn-outline-danger btn-sm rounded-pill px-3">
              <i class="bi bi-plus-lg me-1"></i> Add Your First Recipe
            </a>
          </div>
        <?php else: ?>
          <div class="row g-4">
            <?php foreach ($myRecipes as $recipe): ?>
              <div class="col-lg-4 col-md-6">
                <div class="card h-100 border rounded-4 overflow-hidden shadow-sm transition-hover">
                  <div class="position-relative">
                    <img src="<?= htmlspecialchars($recipe['image_url'] ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600') ?>" 
                         class="card-img-top object-fit-cover" 
                         style="height: 190px;" 
                         alt="<?= htmlspecialchars($recipe['title']) ?>">
                    <span class="position-absolute top-0 start-0 m-2 badge rounded-pill <?= getCategoryBadgeClass($recipe['category']) ?>">
                      <?= htmlspecialchars($recipe['category']) ?>
                    </span>
                    <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75 text-warning rounded-pill">
                      ★ <?= htmlspecialchars($recipe['rating'] ?? '4.5') ?>
                    </span>
                  </div>

                  <div class="card-body p-3 d-flex flex-column">
                    <h6 class="fw-bold text-dark mb-1 text-truncate"><?= htmlspecialchars($recipe['title']) ?></h6>
                    <p class="small text-muted mb-2 flex-grow-1">
                      <?= htmlspecialchars(strlen($recipe['description']) > 75 ? substr($recipe['description'], 0, 75) . '...' : $recipe['description']) ?>
                    </p>

                    <div class="d-flex justify-content-between align-items-center text-muted small border-top pt-2 mb-3">
                      <span><i class="bi bi-clock me-1 text-danger"></i> <?= htmlspecialchars($recipe['cooking_time']) ?> mins</span>
                      <span><i class="bi bi-people me-1 text-primary"></i> <?= htmlspecialchars($recipe['servings']) ?> serv</span>
                    </div>

                    <div class="d-flex gap-2">
                      <a href="index.php#recipes-section" class="btn btn-sm btn-outline-secondary rounded-pill flex-fill">
                        <i class="bi bi-eye"></i> View
                      </a>
                      <a href="includes/recipe_handler.php?action=delete&id=<?= $recipe['id'] ?>" 
                         class="btn btn-sm btn-outline-danger rounded-pill flex-fill"
                         onclick="return confirm('Are you sure you want to permanently delete \'<?= htmlspecialchars(addslashes($recipe['title'])) ?>\'?');">
                        <i class="bi bi-trash"></i> Delete
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ============================================================
         ADD NEW RECIPE FORM (Requirement 2.2, 3.2, 3.5)
         ============================================================ -->
    <div id="addRecipeCard" class="card border-0 shadow-sm rounded-4 bg-white">
      <div class="card-header bg-white border-bottom py-3 px-4">
        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
          <i class="bi bi-pencil-square me-2 text-danger"></i> Create & Publish a New Recipe
        </h5>
        <small class="text-muted">Fill out the details below. Real-time JavaScript validation ensures clean submissions.</small>
      </div>

      <div class="card-body p-4">
        <form action="includes/recipe_handler.php" method="POST" id="newRecipeForm" onsubmit="return validateRecipeForm();">
          <input type="hidden" name="action" value="add">

          <div class="row g-3">
            <!-- Title -->
            <div class="col-md-8">
              <label for="recipe_title" class="form-label fw-semibold small">Recipe Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="recipe_title" name="title" placeholder="e.g. Grandma's Apple Cinnamon Pie" required>
            </div>

            <!-- Category -->
            <div class="col-md-4">
              <label for="recipe_category" class="form-label fw-semibold small">Meal Category <span class="text-danger">*</span></label>
              <select class="form-select" id="recipe_category" name="category" required>
                <option value="" selected disabled>Select Category</option>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
                <option value="Desserts">Desserts</option>
                <option value="Snacks">Snacks</option>
                <option value="Drinks">Drinks</option>
              </select>
            </div>

            <!-- Short Description -->
            <div class="col-12">
              <label for="recipe_description" class="form-label fw-semibold small">Short Summary / Story</label>
              <input type="text" class="form-control" id="recipe_description" name="description" placeholder="A brief one or two-sentence description of this appetizing dish...">
            </div>

            <!-- Ingredients -->
            <div class="col-md-6">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="recipe_ingredients" class="form-label fw-semibold small mb-0">Ingredients List <span class="text-danger">*</span></label>
                <small class="text-muted small">One per line</small>
              </div>
              <textarea class="form-control" id="recipe_ingredients" name="ingredients" rows="6" placeholder="• 200g All-purpose flour&#10;• 2 large eggs&#10;• 1 tbsp olive oil" required></textarea>
            </div>

            <!-- Instructions -->
            <div class="col-md-6">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="recipe_instructions" class="form-label fw-semibold small mb-0">Preparation Steps <span class="text-danger">*</span></label>
                <small id="instructions_counter" class="text-muted small">0 characters</small>
              </div>
              <textarea class="form-control" id="recipe_instructions" name="instructions" rows="6" data-counter="instructions_counter" placeholder="1. Preheat the oven to 180°C...&#10;2. Whisk eggs and flour until smooth...&#10;3. Bake for 25 minutes..." required></textarea>
            </div>

            <!-- Prep Time -->
            <div class="col-md-4">
              <label for="recipe_cooking_time" class="form-label fw-semibold small">Cooking Time (minutes) <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="number" class="form-control" id="recipe_cooking_time" name="cooking_time" value="30" min="1" max="600" required>
                <span class="input-group-text bg-light text-muted">mins</span>
              </div>
            </div>

            <!-- Servings -->
            <div class="col-md-4">
              <label for="recipe_servings" class="form-label fw-semibold small">Servings (people) <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="number" class="form-control" id="recipe_servings" name="servings" value="4" min="1" max="50" required>
                <span class="input-group-text bg-light text-muted">people</span>
              </div>
            </div>

            <!-- Image URL -->
            <div class="col-md-4">
              <label for="recipe_image_url" class="form-label fw-semibold small">Food Image URL</label>
              <input type="url" class="form-control" id="recipe_image_url" name="image_url" placeholder="https://images.unsplash.com/...">
            </div>

            <!-- Form Actions -->
            <div class="col-12 mt-4 pt-2 border-top d-flex justify-content-end gap-2">
              <button type="reset" class="btn btn-outline-secondary rounded-pill px-4">Reset</button>
              <button type="submit" class="btn btn-danger rounded-pill px-5 shadow-sm fw-bold">
                <i class="bi bi-cloud-arrow-up me-1"></i> Save & Publish Recipe
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>

  <!-- ============================================================
       FOOTER
       ============================================================ -->
  <footer class="bg-dark text-white py-4 border-top border-secondary">
    <div class="container text-center small text-secondary">
      <p class="mb-0">&copy; <?= date('Y') ?> FoodBook | ICT 1209 Mini Project by Ishara Nishshanka (ITT/2021/076). Faculty of Technology, Rajarata University of Sri Lanka.</p>
    </div>
  </footer>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Validation & Main JS -->
  <script src="js/validation.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
