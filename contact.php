<?php
/*
  FoodBook - Contact Page
  Student: Ishara Nishshanka (ITT/2021/076)
  Course: ICT 1209 - Web Technologies Mini Project
*/
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';

// Flash messages from contact_handler.php
$contactSuccess = $_SESSION['contact_success'] ?? '';
$contactErrors = $_SESSION['contact_errors'] ?? [];
$contactData = $_SESSION['contact_data'] ?? [];

// Clear flash messages after reading
unset($_SESSION['contact_success'], $_SESSION['contact_errors'], $_SESSION['contact_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - FoodBook</title>

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

  <!-- ============================================================
       NAVIGATION BAR
       ============================================================ -->
  <nav class="navbar navbar-expand-lg fixed-top navbar-custom shadow-sm bg-white">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
        <span class="brand-icon">🍳</span>
        <span class="fw-bold tracking-tight text-brand">FoodBook</span>
      </a>

      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#contactNav" aria-controls="contactNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="contactNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#recipes-section">Explore Recipes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#features-section">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="contact.php">Contact</a>
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
       CONTACT HERO BANNER
       ============================================================ -->
  <section class="py-5 bg-dark text-white text-center" style="margin-top: 60px;">
    <div class="container py-4">
      <span class="badge bg-danger rounded-pill px-3 py-2 mb-2 text-uppercase tracking-wider">Get in Touch</span>
      <h1 class="display-4 fw-bold mb-2">We'd Love to Hear From You</h1>
      <p class="lead text-light max-w-600 mx-auto">
        Have questions, feedback, or a partnership inquiry? Reach out and our community support team will assist you.
      </p>
    </div>
  </section>

  <!-- ============================================================
       CONTACT INFO CARDS
       ============================================================ -->
  <section class="py-5">
    <div class="container">
      <div class="row g-4 mb-5">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
            <div class="feature-icon-wrapper bg-danger-subtle text-danger rounded-circle mx-auto mb-3 p-3">
              <i class="bi bi-geo-alt-fill fs-3"></i>
            </div>
            <h5 class="fw-bold mb-1">Campus Location</h5>
            <p class="text-muted small mb-0">Department of ICT, Faculty of Technology<br>Rajarata University of Sri Lanka, Mihintale</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
            <div class="feature-icon-wrapper bg-primary-subtle text-primary rounded-circle mx-auto mb-3 p-3">
              <i class="bi bi-envelope-fill fs-3"></i>
            </div>
            <h5 class="fw-bold mb-1">Email Inquiry</h5>
            <p class="text-muted small mb-0">
              <a href="mailto:itt2021076@tec.rjt.ac.lk" class="text-decoration-none text-muted">itt2021076@tec.rjt.ac.lk</a>
            </p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
            <div class="feature-icon-wrapper bg-success-subtle text-success rounded-circle mx-auto mb-3 p-3">
              <i class="bi bi-clock-fill fs-3"></i>
            </div>
            <h5 class="fw-bold mb-1">Support Hours</h5>
            <p class="text-muted small mb-0">Monday – Friday: 8:30 AM – 5:00 PM<br>Weekends: Community Forums Only</p>
          </div>
        </div>
      </div>

      <!-- ============================================================
           CONTACT FORM & FAQ SECTION
           ============================================================ -->
      <div class="row g-5" id="contact-form-section">
        <!-- Contact Form (3.4 Contact Form & Data Handling) -->
        <div class="col-lg-7">
          <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
            <h3 class="fw-bold text-dark mb-2">Send Us a Message</h3>
            <p class="text-muted small mb-4">Complete the form below. All messages are securely recorded in our database.</p>

            <!-- Alerts -->
            <?php if (!empty($contactSuccess)): ?>
              <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div><?= htmlspecialchars($contactSuccess) ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <?php if (!empty($contactErrors)): ?>
              <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center mb-1">
                  <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                  <strong>Please correct the following:</strong>
                </div>
                <ul class="mb-0 ps-3 small">
                  <?php foreach ($contactErrors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                  <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <form action="includes/contact_handler.php" method="POST" id="contactForm" onsubmit="return validateContactForm();">
              <div class="row g-3">
                <!-- Full Name -->
                <div class="col-md-6">
                  <label for="contact_name" class="form-label fw-semibold small">Your Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="contact_name" name="name" 
                         value="<?= htmlspecialchars($contactData['name'] ?? ($isLoggedIn ? $username : '')) ?>" 
                         placeholder="e.g. Ishara Nishshanka" required>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                  <label for="contact_email" class="form-label fw-semibold small">Your Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="contact_email" name="email" 
                         value="<?= htmlspecialchars($contactData['email'] ?? '') ?>" 
                         placeholder="e.g. name@example.com" required>
                </div>

                <!-- Subject -->
                <div class="col-12">
                  <label for="contact_subject" class="form-label fw-semibold small">Subject</label>
                  <input type="text" class="form-control" id="contact_subject" name="subject" 
                         value="<?= htmlspecialchars($contactData['subject'] ?? '') ?>" 
                         placeholder="e.g. Question regarding recipe publishing">
                </div>

                <!-- Message -->
                <div class="col-12">
                  <label for="contact_message" class="form-label fw-semibold small">Your Message <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="contact_message" name="message" rows="5" 
                            placeholder="Write your feedback, question, or suggestions here..." required><?= htmlspecialchars($contactData['message'] ?? '') ?></textarea>
                </div>

                <div class="col-12 pt-2">
                  <button type="submit" class="btn btn-danger btn-lg rounded-pill px-5 shadow-sm w-100 fw-bold">
                    <i class="bi bi-send-fill me-2"></i> Submit Inquiry
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- FAQ Accordion -->
        <div class="col-lg-5">
          <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h4 class="fw-bold text-dark mb-3"><i class="bi bi-question-circle text-danger me-2"></i> Frequently Asked Questions</h4>
            <p class="text-muted small mb-4">Quick answers to common questions about using FoodBook.</p>

            <div class="accordion accordion-flush" id="faqAccordion">
              <div class="accordion-item border-bottom">
                <h2 class="accordion-header" id="faqOne">
                  <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    Is FoodBook completely free to use?
                  </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                  <div class="accordion-body small text-muted">
                    Yes! Creating an account, browsing hundreds of recipes, and publishing your own culinary creations is 100% free of charge.
                  </div>
                </div>
              </div>

              <div class="accordion-item border-bottom">
                <h2 class="accordion-header" id="faqTwo">
                  <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    How do I submit a new recipe?
                  </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                  <div class="accordion-body small text-muted">
                    Simply register an account, navigate to your <strong>Dashboard</strong>, and complete the "Add New Recipe" form with ingredients and instructions.
                  </div>
                </div>
              </div>

              <div class="accordion-item border-bottom">
                <h2 class="accordion-header" id="faqThree">
                  <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Can I edit or delete recipes I created?
                  </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                  <div class="accordion-body small text-muted">
                    Yes, your Dashboard lists every recipe you have authored along with an instantaneous Delete button protected by security confirmation.
                  </div>
                </div>
              </div>

              <div class="accordion-item border-bottom">
                <h2 class="accordion-header" id="faqFour">
                  <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    How does the search and filter work?
                  </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                  <div class="accordion-body small text-muted">
                    The search bar uses client-side JavaScript to filter recipes by title or ingredient in real-time as you type, without reloading the page.
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header" id="faqFive">
                  <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    What technologies power FoodBook?
                  </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="faqFive" data-bs-parent="#faqAccordion">
                  <div class="accordion-body small text-muted">
                    FoodBook is constructed using HTML5, CSS3, Bootstrap 5.3, Vanilla JavaScript (ES6+), PHP 8.x backend logic, and MySQL relational database.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

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
