<?php
/*
  FoodBook - Digital Recipe Book
  Student Name: Ishara Nishshanka
  Registration: ITT/2021/076
  Course: ICT 1209 - Web Technologies Mini Project
*/
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';

// Include DB & helper functions
$dbConnected = false;
$recipes = [];
$stats = ['recipes' => 6, 'users' => 12, 'categories' => 6];

if (file_exists('includes/db.php') && file_exists('includes/functions.php')) {
    require_once 'includes/db.php';
    require_once 'includes/functions.php';
    if (isset($conn) && !$conn->connect_error) {
        $dbConnected = true;
        $dbRecipes = getAllRecipes($conn);
        if (!empty($dbRecipes)) {
            $recipes = $dbRecipes;
        }
        
        // Count total recipes & users
        $resR = $conn->query("SELECT COUNT(*) AS total FROM recipes");
        if ($resR) { $stats['recipes'] = (int)$resR->fetch_assoc()['total']; }
        $resU = $conn->query("SELECT COUNT(*) AS total FROM users");
        if ($resU) { $stats['users'] = (int)$resU->fetch_assoc()['total']; }
    }
}

// Fallback recipes if database is empty
if (empty($recipes)) {
    $recipes = [
        [
            'id' => 1,
            'title' => 'Creamy Pasta Carbonara',
            'description' => 'A simple and popular Italian pasta dish made with eggs, cheese, and bacon.',
            'category' => 'Dinner',
            'ingredients' => "• 350g spaghetti\n• 150g bacon or pancetta\n• 3 eggs\n• 50g grated cheese (Parmesan)\n• 2 cloves garlic\n• Salt and black pepper\n• 1 tbsp cooking oil",
            'instructions' => "1. Boil spaghetti in salted water for 8-10 minutes until soft.\n2. In a pan, fry chopped garlic and bacon in a little oil until crispy.\n3. In a bowl, beat eggs with grated cheese, salt, and black pepper.\n4. Drain the pasta and immediately add to the pan with bacon.\n5. Turn off the heat and quickly stir in the egg mixture so it forms a smooth sauce.\n6. Serve warm with extra black pepper on top.",
            'cooking_time' => 25,
            'servings' => 3,
            'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600',
            'rating' => 4.8,
            'username' => 'Ishara'
        ],
        [
            'id' => 2,
            'title' => 'Chocolate Lava Cake',
            'description' => 'Delicious homemade chocolate cake with a warm melted chocolate center.',
            'category' => 'Desserts',
            'ingredients' => "• 120g dark chocolate\n• 80g butter\n• 2 eggs\n• 50g sugar\n• 30g flour\n• Butter and cocoa powder for ramekins",
            'instructions' => "1. Preheat oven to 200°C. Grease small baking cups with butter and dust with cocoa powder.\n2. Melt dark chocolate and butter together in a heatproof bowl.\n3. Whisk eggs and sugar together until slightly thick.\n4. Mix melted chocolate into egg mixture, then gently fold in flour.\n5. Pour batter into cups and bake for 10-12 minutes.\n6. Serve warm with vanilla ice cream.",
            'cooking_time' => 20,
            'servings' => 2,
            'image_url' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600',
            'rating' => 4.9,
            'username' => 'Ishara'
        ],
        [
            'id' => 3,
            'title' => 'Avocado Toast with Poached Egg',
            'description' => 'Quick and healthy breakfast toast topped with fresh mashed avocado and egg.',
            'category' => 'Breakfast',
            'ingredients' => "• 2 slices brown or white bread\n• 1 ripe avocado\n• 2 eggs\n• 1 tsp lime juice\n• Salt and pepper\n• Pinch of chili flakes",
            'instructions' => "1. Toast bread slices until crispy.\n2. Mash avocado with lime juice, salt, and pepper.\n3. Poach eggs in simmering water for 3 minutes.\n4. Spread mashed avocado over toast and place eggs on top.\n5. Sprinkle chili flakes and serve.",
            'cooking_time' => 15,
            'servings' => 2,
            'image_url' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600',
            'rating' => 4.7,
            'username' => 'Ishara'
        ],
        [
            'id' => 4,
            'title' => 'Spicy Chicken Curry',
            'description' => 'Flavorful Sri Lankan style chicken curry cooked with coconut milk and spices.',
            'category' => 'Dinner',
            'ingredients' => "• 500g chicken cut into pieces\n• 1 onion, chopped\n• 2 green chilies\n• 1 tbsp ginger-garlic paste\n• 2 tbsp curry powder\n• 1/2 tsp turmeric powder\n• 1 cup coconut milk\n• Salt and cooking oil",
            'instructions' => "1. Marinate chicken with curry powder, turmeric, and salt for 15 minutes.\n2. Sauté onion, chilies, and ginger-garlic paste in oil.\n3. Add chicken and fry for 5 minutes.\n4. Add water, cover, and cook for 15 minutes.\n5. Stir in coconut milk and simmer until gravy thickens.\n6. Serve hot with rice.",
            'cooking_time' => 40,
            'servings' => 4,
            'image_url' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600',
            'rating' => 4.8,
            'username' => 'Kamal'
        ],
        [
            'id' => 5,
            'title' => 'Fresh Greek Salad',
            'description' => 'Crisp fresh cucumber, tomatoes, olives, and cheese tossed in olive oil.',
            'category' => 'Lunch',
            'ingredients' => "• 2 large tomatoes\n• 1 cucumber\n• 1 red onion\n• 100g black olives\n• 100g feta cheese\n• 2 tbsp olive oil\n• 1 tbsp lemon juice\n• Salt, pepper and oregano",
            'instructions' => "1. Chop tomatoes, cucumber, and onion.\n2. Mix vegetables and olives in a bowl.\n3. Whisk olive oil, lemon juice, salt, and pepper.\n4. Pour over vegetables and toss gently.\n5. Top with feta cheese and oregano.",
            'cooking_time' => 15,
            'servings' => 3,
            'image_url' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=600',
            'rating' => 4.5,
            'username' => 'Kamal'
        ],
        [
            'id' => 6,
            'title' => 'Tropical Mango Smoothie',
            'description' => 'Sweet and chilled fresh mango smoothie blended with yogurt and milk.',
            'category' => 'Drinks',
            'ingredients' => "• 1 ripe mango, diced\n• 1 small banana\n• 1/2 cup plain yogurt\n• 1/2 cup chilled milk\n• 1 tbsp honey\n• Ice cubes",
            'instructions' => "1. Place diced mango and banana in blender.\n2. Add yogurt, milk, and honey.\n3. Add ice cubes and blend for 1 minute until smooth.\n4. Pour into tall glasses and serve cold.",
            'cooking_time' => 10,
            'servings' => 2,
            'image_url' => 'https://images.unsplash.com/photo-1553530979-3b6eca8abad9?w=600',
            'rating' => 4.6,
            'username' => 'Ishara'
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodBook - Digital Recipe Book</title>
  
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg fixed-top navbar-custom">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
        <span class="brand-icon">🍳</span>
        <span class="fw-bold tracking-tight text-brand">FoodBook</span>
      </a>
      
      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#recipes-section">Recipes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#features-section">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about-section">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact</a>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-2">
          <?php if ($isLoggedIn): ?>
            <a href="dashboard.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
              <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <span class="badge bg-light text-dark px-2 py-1 rounded-pill border">
              <i class="bi bi-person-fill text-danger me-1"></i> <?= htmlspecialchars($username) ?>
            </span>
            <a href="auth/logout.php" class="btn btn-danger btn-sm rounded-pill px-3">
              <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
          <?php else: ?>
            <a href="auth/login.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
              <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
            </a>
            <a href="auth/register.php" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
              <i class="bi bi-person-plus me-1"></i> Register
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section with Image Slider -->
  <section class="hero-section position-relative overflow-hidden">
    <div class="hero-slider">
      <!-- Slide 1 -->
      <div class="slide-item active" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=1600');">
        <div class="container h-100 d-flex align-items-center justify-content-center text-center">
          <div class="hero-content text-white max-w-700 animate-fade-in">
            <span class="badge bg-danger rounded-pill px-3 py-2 mb-3 text-uppercase">Welcome to FoodBook</span>
            <h1 class="display-4 fw-bold mb-3">Discover & Share Delicious Recipes</h1>
            <p class="lead mb-4 text-light">Find tasty dishes, create your personal recipe book, and share your cooking ideas.</p>
            <div class="d-flex justify-content-center gap-3">
              <a href="#recipes-section" class="btn btn-danger btn-lg rounded-pill px-4 shadow">Explore Recipes</a>
              <a href="<?= $isLoggedIn ? 'dashboard.php' : 'auth/register.php' ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">Submit Recipe</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Slide 2 -->
      <div class="slide-item" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1600');">
        <div class="container h-100 d-flex align-items-center justify-content-center text-center">
          <div class="hero-content text-white max-w-700">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3 text-uppercase">Quick Meals</span>
            <h1 class="display-4 fw-bold mb-3">Easy Everyday Breakfast & Lunch</h1>
            <p class="lead mb-4 text-light">Looking for simple meals? Browse recipes with quick cooking times and easy ingredients.</p>
            <div class="d-flex justify-content-center gap-3">
              <a href="#recipes-section" class="btn btn-warning btn-lg rounded-pill px-4 text-dark shadow">Browse Meals</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Slide 3 -->
      <div class="slide-item" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=1600');">
        <div class="container h-100 d-flex align-items-center justify-content-center text-center">
          <div class="hero-content text-white max-w-700">
            <span class="badge bg-info text-dark rounded-pill px-3 py-2 mb-3 text-uppercase">Desserts</span>
            <h1 class="display-4 fw-bold mb-3">Homemade Cakes & Sweet Treats</h1>
            <p class="lead mb-4 text-light">Learn how to make chocolate cakes, fresh smoothies, and sweet snacks right at home.</p>
            <div class="d-flex justify-content-center gap-3">
              <a href="#recipes-section" class="btn btn-info btn-lg rounded-pill px-4 text-dark shadow">View Desserts</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Slider Controls -->
    <button class="slider-control slider-prev" aria-label="Previous slide">
      <i class="bi bi-chevron-left"></i>
    </button>
    <button class="slider-control slider-next" aria-label="Next slide">
      <i class="bi bi-chevron-right"></i>
    </button>
    
    <!-- Dots -->
    <div class="slider-dots d-flex justify-content-center gap-2"></div>
  </section>

  <!-- Recipe Search & Category Filter Section -->
  <section class="filter-section py-4 bg-white border-bottom shadow-sm">
    <div class="container">
      <div class="row g-3 align-items-center">
        <!-- Live Search Input -->
        <div class="col-md-5">
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" id="recipeSearchInput" class="form-control bg-light border-start-0" placeholder="Search by recipe name or ingredient...">
          </div>
        </div>

        <!-- Category Dropdown Filter -->
        <div class="col-md-4">
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-funnel"></i></span>
            <select id="recipeCategoryFilter" class="form-select bg-light border-start-0">
              <option value="all">All Categories</option>
              <option value="Breakfast">Breakfast</option>
              <option value="Lunch">Lunch</option>
              <option value="Dinner">Dinner</option>
              <option value="Desserts">Desserts</option>
              <option value="Snacks">Snacks</option>
              <option value="Drinks">Drinks</option>
            </select>
          </div>
        </div>

        <!-- Result Counter -->
        <div class="col-md-3 text-md-end text-center">
          <span id="filterResultCount" class="badge bg-secondary-subtle text-secondary py-2 px-3 rounded-pill">
            Showing <?= count($recipes) ?> recipes
          </span>
        </div>
      </div>

      <!-- Quick Category Filter Pills -->
      <div class="category-pills-container d-flex flex-wrap gap-2 mt-3 pt-2 border-top">
        <button class="category-filter-pill active" data-category="all">All</button>
        <button class="category-filter-pill" data-category="Breakfast">Breakfast</button>
        <button class="category-filter-pill" data-category="Lunch">Lunch</button>
        <button class="category-filter-pill" data-category="Dinner">Dinner</button>
        <button class="category-filter-pill" data-category="Desserts">Desserts</button>
        <button class="category-filter-pill" data-category="Drinks">Drinks</button>
      </div>
    </div>
  </section>

  <!-- Recipes Showcase Section -->
  <section id="recipes-section" class="py-5 bg-light">
    <div class="container py-3">
      <div class="text-center mb-4">
        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 fw-semibold">Recipe Collection</span>
        <h2 class="fw-bold mt-2">Latest Recipes</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Click on any recipe to see full ingredients and preparation steps.</p>
      </div>

      <!-- Recipes Grid -->
      <div id="recipesContainer" class="row g-4">
        <?php foreach ($recipes as $r): ?>
          <div class="col-lg-4 col-md-6 recipe-card-item" 
               data-title="<?= htmlspecialchars($r['title']) ?>" 
               data-category="<?= htmlspecialchars($r['category']) ?>" 
               data-time="<?= htmlspecialchars($r['cooking_time']) ?>" 
               data-servings="<?= htmlspecialchars($r['servings']) ?>" 
               data-rating="<?= htmlspecialchars($r['rating'] ?? '4.5') ?>" 
               data-author="<?= htmlspecialchars($r['username'] ?? 'User') ?>" 
               data-image="<?= htmlspecialchars($r['image_url']) ?>" 
               data-description="<?= htmlspecialchars($r['description']) ?>" 
               data-ingredients="<?= htmlspecialchars($r['ingredients']) ?>" 
               data-instructions="<?= htmlspecialchars($r['instructions']) ?>">
            
            <div class="card recipe-card h-100 border-0 shadow-sm overflow-hidden bg-white">
              <!-- Image with Category Badge & Hover Overlay -->
              <div class="recipe-card-img-wrap position-relative">
                <img src="<?= htmlspecialchars($r['image_url']) ?>" class="card-img-top recipe-img" alt="<?= htmlspecialchars($r['title']) ?>" loading="lazy">
                <span class="position-absolute top-0 start-0 m-3 badge rounded-pill category-badge <?= function_exists('getCategoryBadgeClass') ? getCategoryBadgeClass($r['category']) : 'bg-primary' ?>">
                  <?= htmlspecialchars($r['category']) ?>
                </span>
                <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-75 rounded-pill text-warning">
                  ★ <?= htmlspecialchars($r['rating'] ?? '4.5') ?>
                </span>
                <div class="recipe-card-overlay d-flex align-items-center justify-content-center">
                  <button type="button" class="btn btn-light btn-sm rounded-pill px-3 fw-bold btn-view-recipe">
                    <i class="bi bi-eye-fill me-1 text-danger"></i> Quick View
                  </button>
                </div>
              </div>

              <!-- Card Body -->
              <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                  <span><i class="bi bi-clock me-1 text-danger"></i> <?= htmlspecialchars($r['cooking_time']) ?> mins</span>
                  <span><i class="bi bi-people me-1 text-primary"></i> <?= htmlspecialchars($r['servings']) ?> servings</span>
                </div>

                <h5 class="card-title fw-bold text-dark mb-2"><?= htmlspecialchars($r['title']) ?></h5>
                
                <p class="card-text text-muted small flex-grow-1">
                  <?= htmlspecialchars(strlen($r['description']) > 90 ? substr($r['description'], 0, 90) . '...' : $r['description']) ?>
                </p>

                <div class="border-top pt-3 mt-2 d-flex align-items-center justify-content-between">
                  <span class="small text-muted">
                    <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($r['username'] ?? 'User') ?>
                  </span>
                  <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 btn-view-recipe">
                    View Recipe
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- No Results Container (Toggled via JS) -->
      <div id="noRecipesMessage" class="text-center py-5" style="display: none;">
        <i class="bi bi-search display-3 text-muted"></i>
        <h4 class="mt-3 fw-bold">No Recipes Found</h4>
        <p class="text-muted">Try typing a different recipe name or select another category.</p>
        <button class="btn btn-outline-danger btn-sm rounded-pill mt-2" onclick="document.getElementById('recipeSearchInput').value=''; document.getElementById('recipeCategoryFilter').value='all'; document.getElementById('recipeCategoryFilter').dispatchEvent(new Event('change'));">
          Clear Search
        </button>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features-section" class="py-5 bg-white">
    <div class="container py-3">
      <div class="text-center mb-5">
        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-semibold">Features</span>
        <h2 class="fw-bold mt-2">What FoodBook Offers</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Key features developed for this web technologies mini-project.</p>
      </div>

      <div class="row g-4 text-center">
        <!-- Feature 1 -->
        <div class="col-md-4">
          <div class="p-4 rounded-4 bg-light h-100 feature-box">
            <div class="feature-icon-wrapper bg-danger text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
              <i class="bi bi-search fs-4"></i>
            </div>
            <h5 class="fw-bold mb-2">Live Search & Filter</h5>
            <p class="text-muted small">Instant search by title or ingredients without refreshing the page, powered by JavaScript.</p>
          </div>
        </div>

        <!-- Feature 2 -->
        <div class="col-md-4">
          <div class="p-4 rounded-4 bg-light h-100 feature-box">
            <div class="feature-icon-wrapper bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
              <i class="bi bi-person-check fs-4"></i>
            </div>
            <h5 class="fw-bold mb-2">User Registration & Login</h5>
            <p class="text-muted small">Secure authentication using PHP sessions and password hashing with password_hash().</p>
          </div>
        </div>

        <!-- Feature 3 -->
        <div class="col-md-4">
          <div class="p-4 rounded-4 bg-light h-100 feature-box">
            <div class="feature-icon-wrapper bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
              <i class="bi bi-journal-plus fs-4"></i>
            </div>
            <h5 class="fw-bold mb-2">Recipe Management</h5>
            <p class="text-muted small">Registered users can add their own recipes, specify cooking time, ingredients, and delete recipes.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Project Details Section -->
  <section id="about-section" class="py-5 bg-light">
    <div class="container py-3">
      <div class="row align-items-center g-4">
        <div class="col-lg-6">
          <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800" alt="Cooking" class="img-fluid rounded-4 shadow-sm">
        </div>
        <div class="col-lg-6">
          <span class="badge bg-danger rounded-pill px-3 py-2 mb-2">About The Project</span>
          <h2 class="fw-bold mb-3">FoodBook - Web Technologies Mini Project</h2>
          <p class="text-muted">
            This interactive web application was designed and developed by <strong>Ishara Nishshanka (ITT/2021/076)</strong> for the course module <strong>ICT 1209: Web Technologies</strong> at Rajarata University of Sri Lanka.
          </p>
          <p class="text-muted">
            The frontend is developed using HTML5, CSS3, Bootstrap 5, and JavaScript for interactive controls and live form validation. The backend uses PHP 8 with MySQL for storing users, recipes, and contact queries.
          </p>
          <div class="card p-3 border-0 shadow-sm bg-white mt-3">
            <ul class="list-unstyled mb-0 small text-muted">
              <li><strong>Student Name:</strong> Ishara Nishshanka</li>
              <li><strong>Registration No:</strong> ITT/2021/076</li>
              <li><strong>Department:</strong> Department of ICT</li>
              <li><strong>Faculty:</strong> Faculty of Technology, Rajarata University of Sri Lanka</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Recipe Detail Modal -->
  <div class="modal fade" id="recipeDetailModal" tabindex="-1" aria-labelledby="modalRecipeTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
        <div class="modal-header border-bottom bg-light">
          <div>
            <span id="modalRecipeCategory" class="badge bg-danger rounded-pill mb-1">Category</span>
            <h5 class="modal-title fw-bold" id="modalRecipeTitle">Recipe Title</h5>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <img id="modalRecipeImage" src="" alt="Recipe Image" class="img-fluid rounded-3 shadow-sm w-100 object-fit-cover" style="max-height: 250px;">
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-between">
              <div>
                <p id="modalRecipeDescription" class="text-muted"></p>
                <div class="d-flex flex-wrap gap-2 mb-2">
                  <span class="badge bg-light text-dark border p-2"><i class="bi bi-clock me-1 text-danger"></i> <span id="modalRecipeTime"></span></span>
                  <span class="badge bg-light text-dark border p-2"><i class="bi bi-people me-1 text-primary"></i> <span id="modalRecipeServings"></span></span>
                  <span class="badge bg-light text-warning border p-2"><span id="modalRecipeRating"></span></span>
                </div>
              </div>
              <small id="modalRecipeAuthor" class="text-muted"></small>
            </div>
          </div>

          <!-- Ingredients List -->
          <div class="mb-4">
            <h6 class="fw-bold border-bottom pb-2"><i class="bi bi-basket me-2 text-danger"></i> Ingredients</h6>
            <ul id="modalRecipeIngredients" class="list-group list-group-flush small"></ul>
          </div>

          <!-- Preparation Steps -->
          <div>
            <h6 class="fw-bold border-bottom pb-2"><i class="bi bi-list-ol me-2 text-danger"></i> Instructions</h6>
            <ol id="modalRecipeInstructions" class="small ps-3"></ol>
          </div>
        </div>
        <div class="modal-footer bg-light border-0">
          <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white py-4 border-top">
    <div class="container text-center small text-secondary">
      <p class="mb-1">FoodBook - ICT 1209 Mini Project by Ishara Nishshanka (ITT/2021/076)</p>
      <p class="mb-0">Department of ICT, Faculty of Technology, Rajarata University of Sri Lanka</p>
    </div>
  </footer>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="js/validation.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
