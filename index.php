<?php
// ============================================================
// FoodBook - Digital Recipe Book
// Student Name : Ishara Nishshanka
// Registration : ITT/2021/076
// Course       : ICT 1209 – Web Technologies Mini Project
// Home Page (index.php)
// ============================================================
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';

// Include DB & helper functions safely
$dbConnected = false;
$recipes = [];
$stats = ['recipes' => 6, 'users' => 120, 'categories' => 6];

if (file_exists('includes/db.php') && file_exists('includes/functions.php')) {
    require_once 'includes/db.php';
    require_once 'includes/functions.php';
    if (isset($conn) && !$conn->connect_error) {
        $dbConnected = true;
        $dbRecipes = getAllRecipes($conn);
        if (!empty($dbRecipes)) {
            $recipes = $dbRecipes;
        }
        
        // Fetch real counts if available
        $resR = $conn->query("SELECT COUNT(*) AS total FROM recipes");
        if ($resR) { $stats['recipes'] = (int)$resR->fetch_assoc()['total']; }
        $resU = $conn->query("SELECT COUNT(*) AS total FROM users");
        if ($resU) { $stats['users'] = (int)$resU->fetch_assoc()['total'] + 50; }
    }
}

// Fallback dummy recipes if database is not yet populated
if (empty($recipes)) {
    $recipes = [
        [
            'id' => 1,
            'title' => 'Creamy Pasta Carbonara',
            'description' => 'A classic Italian pasta delicacy with a rich silky egg emulsion, crisp pancetta, and freshly cracked black pepper.',
            'category' => 'Dinner',
            'ingredients' => "400g spaghetti\n200g smoked pancetta\n4 egg yolks\n100g Pecorino Romano\nBlack pepper\nSea salt",
            'instructions' => "1. Boil salted water and cook pasta al dente.\n2. Crisp pancetta in pan.\n3. Whisk yolks and cheese with pepper.\n4. Toss hot pasta with pancetta, then fold in egg mixture off heat.\n5. Serve immediately with extra cheese.",
            'cooking_time' => 25,
            'servings' => 4,
            'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600',
            'rating' => 4.9,
            'username' => 'Ishara Chef'
        ],
        [
            'id' => 2,
            'title' => 'Molten Chocolate Lava Cake',
            'description' => 'Decadent individual chocolate cakes with rich flowing warm chocolate centers, baked to utter perfection.',
            'category' => 'Desserts',
            'ingredients' => "150g dark chocolate\n100g butter\n2 eggs + 2 yolks\n60g caster sugar\n35g flour\nCocoa powder",
            'instructions' => "1. Butter ramekins and dust with cocoa.\n2. Melt chocolate and butter.\n3. Whisk eggs and sugar until fluffy.\n4. Fold together, add flour, and bake at 200C for 12 mins.\n5. Serve hot with vanilla ice cream.",
            'cooking_time' => 20,
            'servings' => 4,
            'image_url' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600',
            'rating' => 4.9,
            'username' => 'Ishara Chef'
        ],
        [
            'id' => 3,
            'title' => 'Sourdough Avocado Toast & Egg',
            'description' => 'Artisanal breakfast staple featuring crushed citrus avocado, soft poached eggs, and chili flakes on rustic toasted sourdough.',
            'category' => 'Breakfast',
            'ingredients' => "2 slices artisan sourdough\n2 ripe avocados\n2 fresh eggs\n1 tbsp lemon juice\nChili flakes\nSea salt & olive oil",
            'instructions' => "1. Toast sourdough until golden.\n2. Mash avocado with lemon, salt, and pepper.\n3. Poach eggs in simmering water for 3 mins.\n4. Top toast with avocado, egg, chili flakes, and olive oil.",
            'cooking_time' => 15,
            'servings' => 2,
            'image_url' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600',
            'rating' => 4.8,
            'username' => 'Ishara Chef'
        ],
        [
            'id' => 4,
            'title' => 'Authentic Chicken Tikka Masala',
            'description' => 'Succulent marinated spiced chicken pieces simmered gently in a vibrant, creamy aromatic tomato and fenugreek gravy.',
            'category' => 'Dinner',
            'ingredients' => "800g chicken thigh\n1 cup Greek yogurt\n2 tbsp ginger-garlic paste\n400g crushed tomatoes\n200ml heavy cream\nGaram masala & spices",
            'instructions' => "1. Marinate chicken in spices and yogurt, then broil until charred.\n2. Sauté onions, garlic, ginger, and spices.\n3. Simmer tomatoes, then blend with heavy cream.\n4. Add chicken and simmer for 10 minutes.\n5. Serve with warm naan bread.",
            'cooking_time' => 45,
            'servings' => 6,
            'image_url' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600',
            'rating' => 4.7,
            'username' => 'Chef Kamal'
        ],
        [
            'id' => 5,
            'title' => 'Crisp Mediterranean Greek Salad',
            'description' => 'Crisp garden vegetables, Kalamata olives, and creamy sheep feta cheese tossed in red wine oregano vinaigrette.',
            'category' => 'Lunch',
            'ingredients' => "4 ripe tomatoes\n1 crisp cucumber\n1 red onion\n150g Kalamata olives\n200g Greek feta block\nOlive oil & red vinegar\nOregano",
            'instructions' => "1. Chop fresh vegetables into rustic chunks.\n2. Whisk vinaigrette of olive oil, vinegar, and oregano.\n3. Toss salad, top with thick block of feta, and drizzle olive oil.",
            'cooking_time' => 15,
            'servings' => 4,
            'image_url' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=600',
            'rating' => 4.6,
            'username' => 'Chef Kamal'
        ],
        [
            'id' => 6,
            'title' => 'Tropical Mango Passion Smoothie',
            'description' => 'Refreshing sun-ripened tropical mango and tangy passionfruit blended with chilled coconut water and Greek yogurt.',
            'category' => 'Drinks',
            'ingredients' => "2 ripe mangoes\n2 passionfruits\n1 frozen banana\n150g Greek yogurt\n200ml coconut milk\n1 tbsp raw honey\nIce cubes",
            'instructions' => "1. Add sliced fruits, yogurt, coconut milk, and honey to blender.\n2. Blend on high speed for 60 seconds until silky.\n3. Pour into tall glasses and garnish with mint.",
            'cooking_time' => 10,
            'servings' => 2,
            'image_url' => 'https://images.unsplash.com/photo-1553530979-3b6eca8abad9?w=600',
            'rating' => 4.7,
            'username' => 'Ishara Chef'
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodBook - Discover, Cook & Share Handcrafted Recipes</title>
  
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- ============================================================
       NAVIGATION BAR
       ============================================================ -->
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
            <a class="nav-link" href="#recipes-section">Explore Recipes</a>
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
              <i class="bi bi-person-plus me-1"></i> Join Free
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <!-- ============================================================
       HERO SECTION WITH INTERACTIVE SLIDER
       ============================================================ -->
  <section class="hero-section position-relative overflow-hidden">
    <div class="hero-slider">
      <!-- Slide 1 -->
      <div class="slide-item active" style="background-image: linear-gradient(rgba(15, 23, 42, 0.65), rgba(15, 23, 42, 0.65)), url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=1600');">
        <div class="container h-100 d-flex align-items-center justify-content-center text-center">
          <div class="hero-content text-white max-w-700 animate-fade-in">
            <span class="badge bg-danger rounded-pill px-3 py-2 mb-3 text-uppercase tracking-wider">Culinary Community</span>
            <h1 class="display-3 fw-bold mb-3">Cook, Savor & Share Culinary Delights</h1>
            <p class="lead mb-4 text-light">Explore hundreds of chef-tested recipes, create your personal cookbook, and inspire food enthusiasts worldwide.</p>
            <div class="d-flex justify-content-center gap-3">
              <a href="#recipes-section" class="btn btn-danger btn-lg rounded-pill px-4 shadow">Explore Recipes</a>
              <a href="<?= $isLoggedIn ? 'dashboard.php' : 'auth/register.php' ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">Submit Recipe</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Slide 2 -->
      <div class="slide-item" style="background-image: linear-gradient(rgba(15, 23, 42, 0.65), rgba(15, 23, 42, 0.65)), url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1600');">
        <div class="container h-100 d-flex align-items-center justify-content-center text-center">
          <div class="hero-content text-white max-w-700">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3 text-uppercase tracking-wider">Fast & Easy</span>
            <h1 class="display-3 fw-bold mb-3">Quick & Nutritious 15-Minute Meals</h1>
            <p class="lead mb-4 text-light">Busy workday? Find delicious, health-focused recipes made from ingredients right inside your pantry.</p>
            <div class="d-flex justify-content-center gap-3">
              <a href="#recipes-section" class="btn btn-warning btn-lg rounded-pill px-4 text-dark shadow">Browse Fast Meals</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Slide 3 -->
      <div class="slide-item" style="background-image: linear-gradient(rgba(15, 23, 42, 0.65), rgba(15, 23, 42, 0.65)), url('https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=1600');">
        <div class="container h-100 d-flex align-items-center justify-content-center text-center">
          <div class="hero-content text-white max-w-700">
            <span class="badge bg-info text-dark rounded-pill px-3 py-2 mb-3 text-uppercase tracking-wider">Sweet Tooth</span>
            <h1 class="display-3 fw-bold mb-3">Irresistible Desserts & Pastries</h1>
            <p class="lead mb-4 text-light">From molten lava cakes to fruit smoothies, indulge in decadent recipes designed for your sweet moments.</p>
            <div class="d-flex justify-content-center gap-3">
              <a href="#recipes-section" class="btn btn-info btn-lg rounded-pill px-4 text-dark shadow">Indulge Now</a>
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

  <!-- ============================================================
       SEARCH & DYNAMIC FILTER BAR
       ============================================================ -->
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

        <!-- Result Counter & Quick Action -->
        <div class="col-md-3 text-md-end text-center">
          <span id="filterResultCount" class="badge bg-secondary-subtle text-secondary py-2 px-3 rounded-pill fw-medium">
            Showing <?= count($recipes) ?> recipes
          </span>
        </div>
      </div>

      <!-- Quick Category Filter Pills -->
      <div class="category-pills-container d-flex flex-wrap gap-2 mt-3 pt-2 border-top">
        <button class="category-filter-pill active" data-category="all">All</button>
        <button class="category-filter-pill" data-category="Breakfast">🍳 Breakfast</button>
        <button class="category-filter-pill" data-category="Lunch">🥗 Lunch</button>
        <button class="category-filter-pill" data-category="Dinner">🍝 Dinner</button>
        <button class="category-filter-pill" data-category="Desserts">🍰 Desserts</button>
        <button class="category-filter-pill" data-category="Drinks">🍹 Drinks</button>
      </div>
    </div>
  </section>

  <!-- ============================================================
       FEATURED RECIPES SECTION
       ============================================================ -->
  <section id="recipes-section" class="py-5 bg-light">
    <div class="container py-3">
      <div class="text-center mb-5 animate-on-scroll">
        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 fw-semibold">Handpicked Selection</span>
        <h2 class="display-5 fw-bold mt-2">Discover Delicious Recipes</h2>
        <p class="text-muted max-w-600 mx-auto">Explore easy-to-follow, delicious recipes created by our vibrant home cook community.</p>
      </div>

      <!-- Recipes Grid -->
      <div id="recipesContainer" class="row g-4">
        <?php foreach ($recipes as $r): ?>
          <div class="col-lg-4 col-md-6 recipe-card-item animate-on-scroll" 
               data-title="<?= htmlspecialchars($r['title']) ?>" 
               data-category="<?= htmlspecialchars($r['category']) ?>" 
               data-time="<?= htmlspecialchars($r['cooking_time']) ?>" 
               data-servings="<?= htmlspecialchars($r['servings']) ?>" 
               data-rating="<?= htmlspecialchars($r['rating'] ?? '4.5') ?>" 
               data-author="<?= htmlspecialchars($r['username'] ?? 'Chef') ?>" 
               data-image="<?= htmlspecialchars($r['image_url']) ?>" 
               data-description="<?= htmlspecialchars($r['description']) ?>" 
               data-ingredients="<?= htmlspecialchars($r['ingredients']) ?>" 
               data-instructions="<?= htmlspecialchars($r['instructions']) ?>">
            
            <div class="card recipe-card h-100 border-0 shadow-sm overflow-hidden">
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
                    <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($r['username'] ?? 'FoodBook Chef') ?>
                  </span>
                  <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 btn-view-recipe">
                    Details
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- No Results Container (Toggled via JS) -->
      <div id="noRecipesMessage" class="text-center py-5" style="display: none;">
        <i class="bi bi-search display-1 text-muted opacity-50"></i>
        <h4 class="mt-3 fw-bold">No Recipes Found</h4>
        <p class="text-muted">Try adjusting your search criteria or explore another category.</p>
        <button class="btn btn-outline-danger btn-sm rounded-pill mt-2" onclick="document.getElementById('recipeSearchInput').value=''; document.getElementById('recipeCategoryFilter').value='all'; document.getElementById('recipeCategoryFilter').dispatchEvent(new Event('change'));">
          Clear Filters
        </button>
      </div>
    </div>
  </section>

  <!-- ============================================================
       FEATURES SECTION (2.1 & 2.2 Requirements)
       ============================================================ -->
  <section id="features-section" class="py-5 bg-white">
    <div class="container py-4">
      <div class="text-center mb-5 animate-on-scroll">
        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-semibold">Why FoodBook</span>
        <h2 class="display-6 fw-bold mt-2">Engineered For Culinary Creatives</h2>
        <p class="text-muted max-w-600 mx-auto">Experience seamless recipe management, instant dynamic search, and community feedback.</p>
      </div>

      <div class="row g-4 text-center">
        <!-- Feature 1 -->
        <div class="col-md-4 animate-on-scroll">
          <div class="p-4 rounded-4 bg-light h-100 feature-box transition-hover">
            <div class="feature-icon-wrapper bg-danger text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
              <i class="bi bi-lightning-charge-fill fs-3"></i>
            </div>
            <h5 class="fw-bold mb-2">Live Dynamic Search</h5>
            <p class="text-muted small">Filter recipes by category or instant ingredient search without reloads using fast asynchronous JavaScript.</p>
          </div>
        </div>

        <!-- Feature 2 -->
        <div class="col-md-4 animate-on-scroll">
          <div class="p-4 rounded-4 bg-light h-100 feature-box transition-hover">
            <div class="feature-icon-wrapper bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
              <i class="bi bi-shield-check fs-3"></i>
            </div>
            <h5 class="fw-bold mb-2">Secure User Authentication</h5>
            <p class="text-muted small">Dual client and server-side validation with bcrypt password hashing protects your user dashboard and submissions.</p>
          </div>
        </div>

        <!-- Feature 3 -->
        <div class="col-md-4 animate-on-scroll">
          <div class="p-4 rounded-4 bg-light h-100 feature-box transition-hover">
            <div class="feature-icon-wrapper bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
              <i class="bi bi-journal-plus fs-3"></i>
            </div>
            <h5 class="fw-bold mb-2">Personal Recipe Book</h5>
            <p class="text-muted small">Publish and manage your signature recipes, update preparation times, and edit ingredients directly in your personal portal.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================================
       STATS SECTION (INTERACTIVE COUNTERS)
       ============================================================ -->
  <section class="py-5 bg-dark text-white">
    <div class="container py-3">
      <div class="row g-4 text-center">
        <div class="col-6 col-md-3">
          <h2 class="display-4 fw-bold text-danger stat-counter" data-target="1500">0</h2>
          <p class="text-secondary text-uppercase tracking-wider small mb-0">Tested Recipes</p>
        </div>
        <div class="col-6 col-md-3">
          <h2 class="display-4 fw-bold text-warning stat-counter" data-target="450">0</h2>
          <p class="text-secondary text-uppercase tracking-wider small mb-0">Active Chefs</p>
        </div>
        <div class="col-6 col-md-3">
          <h2 class="display-4 fw-bold text-info stat-counter" data-target="2800">0</h2>
          <p class="text-secondary text-uppercase tracking-wider small mb-0">Positive Reviews</p>
        </div>
        <div class="col-6 col-md-3">
          <h2 class="display-4 fw-bold text-success stat-counter" data-target="100">0</h2>
          <p class="text-secondary text-uppercase tracking-wider small mb-0">% Free & Open</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================================
       ABOUT THE PROJECT SECTION (2.4 Creativity)
       ============================================================ -->
  <section id="about-section" class="py-5 bg-white">
    <div class="container py-4">
      <div class="row align-items-center g-5">
        <div class="col-lg-6 animate-on-scroll">
          <div class="position-relative">
            <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800" alt="Cooking Preparation" class="img-fluid rounded-4 shadow-lg">
            <div class="position-absolute bottom-0 end-0 bg-white p-3 rounded-3 shadow m-3 border-start border-4 border-danger">
              <span class="fw-bold d-block text-dark">Rajarata University of Sri Lanka</span>
              <small class="text-muted">Faculty of Technology • Department of ICT</small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 animate-on-scroll">
          <span class="badge bg-danger rounded-pill px-3 py-2 mb-3">Academic Mini Project</span>
          <h2 class="display-6 fw-bold mb-3">Crafted with Passion for Web Technologies</h2>
          <p class="text-muted leading-relaxed">
            <strong>FoodBook</strong> was designed and developed by <strong>Ishara Nishshanka (ITT/2021/076)</strong> for the course module <strong>ICT 1209: Web Technologies</strong>.
          </p>
          <p class="text-muted">
            The platform showcases clean, responsive UI with Bootstrap 5.3, interactive JavaScript widgets (dynamic search, image slider, modal viewing, and live field validation), coupled with a robust relational PHP and MySQL backend architecture.
          </p>
          <div class="row g-3 mt-2">
            <div class="col-6">
              <div class="border rounded-3 p-3 text-center">
                <i class="bi bi-code-slash text-danger fs-3 mb-1"></i>
                <h6 class="fw-bold mb-0">Frontend</h6>
                <small class="text-muted">HTML5, CSS3, JS, Bootstrap</small>
              </div>
            </div>
            <div class="col-6">
              <div class="border rounded-3 p-3 text-center">
                <i class="bi bi-database text-primary fs-3 mb-1"></i>
                <h6 class="fw-bold mb-0">Backend</h6>
                <small class="text-muted">PHP 8 & MySQL DB</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================================
       RECIPE DETAIL MODAL (JavaScript Interactive Popup)
       ============================================================ -->
  <div class="modal fade" id="recipeDetailModal" tabindex="-1" aria-labelledby="modalRecipeTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header border-bottom bg-light">
          <div>
            <span id="modalRecipeCategory" class="badge bg-danger rounded-pill mb-1">Category</span>
            <h4 class="modal-title fw-bold" id="modalRecipeTitle">Recipe Title</h4>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <img id="modalRecipeImage" src="" alt="Recipe Image" class="img-fluid rounded-3 shadow-sm w-100 object-fit-cover" style="max-height: 280px;">
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-between">
              <div>
                <p id="modalRecipeDescription" class="text-muted lead fs-6"></p>
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="badge bg-light text-dark border p-2"><i class="bi bi-clock me-1 text-danger"></i> <span id="modalRecipeTime"></span></span>
                  <span class="badge bg-light text-dark border p-2"><i class="bi bi-people me-1 text-primary"></i> <span id="modalRecipeServings"></span></span>
                  <span class="badge bg-light text-warning border p-2"><span id="modalRecipeRating"></span></span>
                </div>
              </div>
              <small id="modalRecipeAuthor" class="text-muted font-monospace"></small>
            </div>
          </div>

          <!-- Ingredients List -->
          <div class="mb-4">
            <h5 class="fw-bold border-bottom pb-2"><i class="bi bi-basket me-2 text-danger"></i> Ingredients</h5>
            <ul id="modalRecipeIngredients" class="list-group list-group-flush small"></ul>
          </div>

          <!-- Preparation Steps -->
          <div>
            <h5 class="fw-bold border-bottom pb-2"><i class="bi bi-list-ol me-2 text-danger"></i> Preparation Steps</h5>
            <ol id="modalRecipeInstructions" class="small ps-3"></ol>
          </div>
        </div>
        <div class="modal-footer bg-light border-0">
          <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ============================================================
       FOOTER
       ============================================================ -->
  <footer class="bg-dark text-white pt-5 pb-4 border-top border-secondary">
    <div class="container">
      <div class="row g-4 mb-4">
        <div class="col-lg-4">
          <h4 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <span>🍳</span> <span class="text-white">FoodBook</span>
          </h4>
          <p class="text-secondary small">
            A comprehensive, interactive web application developed for culinary enthusiasts to discover, craft, and organize delightful cooking recipes.
          </p>
          <p class="small text-secondary mb-0">
            <strong>Developer:</strong> Ishara Nishshanka<br>
            <strong>Registration:</strong> ITT/2021/076<br>
            <strong>University:</strong> Rajarata University of Sri Lanka
          </p>
        </div>

        <div class="col-lg-2 col-6">
          <h6 class="fw-bold text-uppercase tracking-wider text-light mb-3">Explore</h6>
          <ul class="list-unstyled small d-flex flex-column gap-2">
            <li><a href="index.php" class="text-secondary text-decoration-none hover-white">Home</a></li>
            <li><a href="#recipes-section" class="text-secondary text-decoration-none hover-white">Recipes</a></li>
            <li><a href="#features-section" class="text-secondary text-decoration-none hover-white">Features</a></li>
            <li><a href="#about-section" class="text-secondary text-decoration-none hover-white">About Project</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-6">
          <h6 class="fw-bold text-uppercase tracking-wider text-light mb-3">Account</h6>
          <ul class="list-unstyled small d-flex flex-column gap-2">
            <li><a href="auth/login.php" class="text-secondary text-decoration-none hover-white">Sign In</a></li>
            <li><a href="auth/register.php" class="text-secondary text-decoration-none hover-white">Register</a></li>
            <li><a href="dashboard.php" class="text-secondary text-decoration-none hover-white">My Dashboard</a></li>
            <li><a href="contact.php" class="text-secondary text-decoration-none hover-white">Support & Contact</a></li>
          </ul>
        </div>

        <div class="col-lg-4">
          <h6 class="fw-bold text-uppercase tracking-wider text-light mb-3">Newsletter</h6>
          <p class="text-secondary small">Get weekly curated recipes sent directly to your inbox.</p>
          <form class="d-flex gap-2" onsubmit="event.preventDefault(); alert('Thank you for subscribing to FoodBook!');">
            <input type="email" class="form-control form-control-sm rounded-pill" placeholder="Enter your email" required>
            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">Join</button>
          </form>
        </div>
      </div>

      <div class="border-top border-secondary pt-3 text-center small text-secondary">
        <p class="mb-0">&copy; <?= date('Y') ?> FoodBook | ICT 1209 Mini Project by Ishara Nishshanka (ITT/2021/076). All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom Validation & Main JavaScript -->
  <script src="js/validation.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
