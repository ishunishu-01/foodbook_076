<?php
// Contact form submission handler
// Student: Ishara Nishshanka (ITT/2021/076)
session_start();
require_once 'db.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    if (empty($name)) {
        $errors[] = 'Please enter your name.';
    } elseif (strlen($name) < 2) {
        $errors[] = 'Name must be at least 2 characters.';
    }

    if (empty($email)) {
        $errors[] = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($subject)) {
        $subject = 'General Inquiry';
    }

    if (empty($message)) {
        $errors[] = 'Please enter your message.';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters.';
    }

    if (!empty($errors)) {
        $_SESSION['contact_errors'] = $errors;
        $_SESSION['contact_data'] = [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ];
        header('Location: ../contact.php#contact-form-section');
        exit();
    }

    // Insert into messages table
    $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param('ssss', $name, $email, $subject, $message);
        if ($stmt->execute()) {
            $_SESSION['contact_success'] = 'Thank you! Your message has been sent successfully. We will get back to you shortly.';
            unset($_SESSION['contact_data']);
        } else {
            $_SESSION['contact_errors'] = ['Database error: Unable to save your message. Please try again later.'];
        }
        $stmt->close();
    } else {
        $_SESSION['contact_errors'] = ['Server error: Failed to prepare database query.'];
    }

    header('Location: ../contact.php#contact-form-section');
    exit();
}

// Redirect if accessed directly
header('Location: ../contact.php');
exit();
?>
