<?php
// ============================================================
// FoodBook - User Login
// Student: Ishara Nishshanka | ITT/2021/076
// ============================================================
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ../dashboard.php');
    exit();
}

$errors  = [];
$email   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    // --- Validation ---
    if (empty($email)) {
        $errors[] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    }

    // --- Authenticate ---
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email']    = $user['email'];

                if ($remember) {
                    $_SESSION['remember'] = true;
                    // Extend session lifetime for "remember me" (30 days)
                    ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
                }

                $stmt->close();
                header('Location: ../dashboard.php');
                exit();
            } else {
                $errors[] = 'Invalid email or password. Please try again.';
            }
        } else {
            $errors[] = 'Invalid email or password. Please try again.';
        }
        $stmt->close();
    }

    $email = htmlspecialchars($email);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FoodBook</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { background: linear-gradient(135deg, #1d3557 0%, #457b9d 100%); min-height: 100vh; }
        .auth-card { border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .brand-logo { font-size: 2rem; color: #e63946; font-weight: 800; }
        .food-icons { font-size: 1.5rem; letter-spacing: 8px; opacity: 0.6; }
    </style>
</head>
<body class="d-flex align-items-center py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card auth-card border-0">
                <div class="card-body p-5">
                    <!-- Brand -->
                    <div class="text-center mb-4">
                        <div class="brand-logo">🍴 FoodBook</div>
                        <h4 class="mt-2 fw-bold text-dark">Welcome Back!</h4>
                        <p class="text-muted">Sign in to your account</p>
                        <div class="food-icons">🍕 🍜 🍰</div>
                    </div>

                    <!-- Errors -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php foreach ($errors as $error): ?>
                                <div><?= $error ?></div>
                            <?php endforeach; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Success message from register redirect -->
                    <?php if (isset($_GET['registered'])): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>Account created! Please sign in.
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form method="POST" action="" id="loginForm" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?= $email ?>"
                                       placeholder="you@example.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label text-muted" for="remember">Remember me</label>
                            </div>
                            <a href="#" class="text-danger text-decoration-none small">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 py-2 fw-bold fs-6">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>

                    <!-- Demo credentials hint -->
                    <div class="mt-3 p-3 bg-light rounded small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Demo:</strong> ishara@foodbook.com / password
                    </div>

                    <hr class="my-4">
                    <p class="text-center text-muted mb-0">
                        Don't have an account?
                        <a href="register.php" class="text-danger fw-semibold text-decoration-none">Sign Up Free</a>
                    </p>
                </div>
            </div>
            <p class="text-center text-white-50 mt-3">
                <a href="../index.php" class="text-white-50"><i class="fas fa-arrow-left me-1"></i>Back to Home</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/validation.js"></script>
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const pwd  = document.getElementById('password');
        const icon = this.querySelector('i');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });

    // Client-side validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        if (typeof validateLoginForm === 'function' && !validateLoginForm()) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>
