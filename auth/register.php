<?php
// ============================================================
// FoodBook - User Registration
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

$errors   = [];
$success  = '';
$formData = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username         = trim($_POST['username'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // --- Validation ---
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = 'Username must be between 3 and 50 characters.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Username may only contain letters, numbers, and underscores.';
    }

    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'An account with this email address already exists.';
        }
        $stmt->close();
    }

    // Check if username already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'This username is already taken. Please choose another.';
        }
        $stmt->close();
    }

    // --- Insert user ---
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $user_id = $conn->insert_id;
            $_SESSION['user_id']  = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email']    = $email;
            $stmt->close();
            header('Location: ../dashboard.php?welcome=1');
            exit();
        } else {
            $errors[] = 'Registration failed. Please try again later.';
            $stmt->close();
        }
    }

    // Keep form values on error
    $formData['username'] = htmlspecialchars($username);
    $formData['email']    = htmlspecialchars($email);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FoodBook</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { background: linear-gradient(135deg, #1d3557 0%, #457b9d 100%); min-height: 100vh; }
        .auth-card { border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .brand-logo { font-size: 2rem; color: #e63946; font-weight: 800; }
        .strength-bar { height: 5px; border-radius: 3px; transition: all 0.3s; }
    </style>
</head>
<body class="d-flex align-items-center py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card border-0">
                <div class="card-body p-5">
                    <!-- Brand -->
                    <div class="text-center mb-4">
                        <div class="brand-logo">🍴 FoodBook</div>
                        <h4 class="mt-2 fw-bold text-dark">Create Your Account</h4>
                        <p class="text-muted">Join our community of food lovers</p>
                    </div>

                    <!-- Errors -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form method="POST" action="" id="registerForm" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username"
                                       value="<?= $formData['username'] ?>"
                                       placeholder="johndoe" required minlength="3" maxlength="50">
                            </div>
                            <div class="invalid-feedback" id="username-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?= $formData['email'] ?>"
                                       placeholder="you@example.com" required>
                            </div>
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Min. 8 characters" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-1">
                                <div class="d-flex gap-1">
                                    <div class="strength-bar flex-fill bg-secondary" id="str1"></div>
                                    <div class="strength-bar flex-fill bg-secondary" id="str2"></div>
                                    <div class="strength-bar flex-fill bg-secondary" id="str3"></div>
                                    <div class="strength-bar flex-fill bg-secondary" id="str4"></div>
                                </div>
                                <small class="text-muted" id="strengthText">Password strength</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="confirm_password"
                                       name="confirm_password" placeholder="Repeat your password" required>
                            </div>
                            <div class="invalid-feedback" id="confirm-error"></div>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 py-2 fw-bold fs-6">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center text-muted mb-0">
                        Already have an account?
                        <a href="login.php" class="text-danger fw-semibold text-decoration-none">Sign In</a>
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
        const pwd = document.getElementById('password');
        const icon = this.querySelector('i');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });

    // Password strength meter
    document.getElementById('password').addEventListener('input', function() {
        const val = this.value;
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors = ['bg-danger', 'bg-warning', 'bg-info', 'bg-success'];
        const labels = ['Weak', 'Fair', 'Good', 'Strong'];
        const bars   = ['str1','str2','str3','str4'];

        bars.forEach((id, i) => {
            const el = document.getElementById(id);
            el.className = 'strength-bar flex-fill ' + (i < score ? colors[score - 1] : 'bg-secondary');
        });
        document.getElementById('strengthText').textContent = score > 0 ? labels[score - 1] : 'Password strength';
    });

    // Client-side form validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        if (typeof validateRegisterForm === 'function' && !validateRegisterForm()) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>
