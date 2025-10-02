<?php
require 'admin/db_connect.php'; 
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_time = time();
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM user_info WHERE (email = ? OR first_name = ?) LIMIT 1");
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_attempts'] = 0;

        if ($user['role'] === 'admin') {
            header("Location: admin/index.php"); 
        } else {
            header("Location: index.php"); 
        }
        exit();
    } else {
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = $current_time;
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Premium Dining Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
      @import 'tailwindcss';
      
      :root {
        --color-primary: #1a1a1a;
        --color-secondary: #2d2d2d;
        --color-accent: #c9a961;
        --color-accent-dark: #a88b4f;
        --color-background: #faf8f5;
        --color-surface: #ffffff;
        --color-muted: #8b6f47;
        --color-text: #1a1a1a;
        --color-text-light: #6b6b6b;
        --color-border: #e5e0d8;
        --color-error: #8b4513;
        --color-error-bg: #fef3f2;
        
        --font-display: 'Cormorant Garamond', serif;
        --font-body: 'Montserrat', sans-serif;
      }
      
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
      
      body {
        font-family: var(--font-body);
        background-color: var(--color-background);
        color: var(--color-text);
        line-height: 1.6;
      }
      
      .font-display {
        font-family: var(--font-display);
        letter-spacing: 0.02em;
      }
      
      /* Premium background with subtle texture */
      .premium-bg {
        position: relative;
        background: linear-gradient(135deg, #faf8f5 0%, #f5f3ef 100%);
      }
      
      .premium-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: 
          radial-gradient(circle at 20% 50%, rgba(201, 169, 97, 0.03) 0%, transparent 50%),
          radial-gradient(circle at 80% 80%, rgba(139, 111, 71, 0.03) 0%, transparent 50%);
        pointer-events: none;
      }
      
      /* Elegant card with premium shadow */
      .premium-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        box-shadow: 
          0 4px 6px -1px rgba(0, 0, 0, 0.05),
          0 10px 25px -5px rgba(0, 0, 0, 0.08),
          0 0 0 1px rgba(201, 169, 97, 0.05);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      }
      
      .premium-card:hover {
        box-shadow: 
          0 8px 12px -2px rgba(0, 0, 0, 0.08),
          0 20px 40px -10px rgba(0, 0, 0, 0.12),
          0 0 0 1px rgba(201, 169, 97, 0.1);
        transform: translateY(-2px);
      }
      
      /* Decorative divider */
      .decorative-divider {
        position: relative;
        text-align: center;
        margin: 2rem 0;
      }
      
      .decorative-divider::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background: linear-gradient(to right, transparent, var(--color-border), transparent);
      }
      
      .decorative-divider span {
        position: relative;
        background: var(--color-surface);
        padding: 0 1rem;
        color: var(--color-accent);
        font-size: 1.25rem;
      }
      
      /* Premium input styling */
      .premium-input {
        background: var(--color-surface);
        border: 1.5px solid var(--color-border);
        color: var(--color-text);
        transition: all 0.3s ease;
        font-family: var(--font-body);
      }
      
      .premium-input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.1);
        background: var(--color-surface);
      }
      
      .premium-input::placeholder {
        color: var(--color-text-light);
        opacity: 0.6;
      }
      
      /* Premium button with gold accent */
      .premium-button {
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-dark) 100%);
        color: var(--color-surface);
        font-weight: 500;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(201, 169, 97, 0.3);
        position: relative;
        overflow: hidden;
        /* Ensure minimum touch target of 44px on all devices */
        min-height: 44px;
      }
      
      .premium-button::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
      }
      
      .premium-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(201, 169, 97, 0.4);
      }
      
      .premium-button:hover::before {
        opacity: 1;
      }
      
      .premium-button:active {
        transform: translateY(0);
      }
      
      /* Icon styling */
      .icon-accent {
        color: var(--color-accent);
      }
      
      /* Link styling */
      .premium-link {
        color: var(--color-accent);
        text-decoration: none;
        position: relative;
        transition: color 0.3s ease;
        font-weight: 500;
      }
      
      .premium-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 1px;
        background: var(--color-accent);
        transition: width 0.3s ease;
      }
      
      .premium-link:hover {
        color: var(--color-accent-dark);
      }
      
      .premium-link:hover::after {
        width: 100%;
      }
      
      /* Error message styling */
      .error-message {
        background: var(--color-error-bg);
        border: 1px solid var(--color-error);
        color: var(--color-error);
        animation: slideDown 0.3s ease;
      }
      
      @keyframes slideDown {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      /* Fade in animation */
      .fade-in {
        animation: fadeIn 0.6s ease;
      }
      
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      /* Label styling */
      .premium-label {
        color: var(--color-text);
        font-weight: 500;
        font-size: 0.875rem;
        letter-spacing: 0.025em;
        text-transform: uppercase;
      }
      
      /* Comprehensive responsive styles for all devices */
      
      /* Extra small devices (320px - 480px) */
      @media (max-width: 480px) {
        body {
          padding: 0.5rem;
        }
        
        .premium-card {
          padding: 1.5rem 1.25rem;
          margin: 0.5rem;
          border-radius: 0.25rem;
        }
        
        .premium-card:hover {
          transform: none;
        }
        
        /* Header adjustments */
        .icon-accent {
          font-size: 2.5rem;
        }
        
        h1 {
          font-size: 2rem;
          line-height: 1.2;
          margin-bottom: 0.5rem;
        }
        
        .text-center p {
          font-size: 0.8125rem;
        }
        
        /* Decorative divider - smaller on mobile */
        .decorative-divider {
          margin: 1.25rem 0;
        }
        
        .decorative-divider span {
          font-size: 1rem;
          padding: 0 0.75rem;
        }
        
        /* Form elements */
        .premium-label {
          font-size: 0.75rem;
          margin-bottom: 0.5rem;
        }
        
        .premium-input {
          padding: 0.875rem 1rem 0.875rem 2.75rem;
          font-size: 0.875rem;
        }
        
        .premium-input::placeholder {
          font-size: 0.8125rem;
        }
        
        /* Input icons */
        .premium-input + span,
        .relative > span {
          padding-left: 0.875rem;
        }
        
        /* Password toggle button - ensure 44px touch target */
        #togglePassword {
          padding-right: 0.875rem;
          min-width: 44px;
          min-height: 44px;
        }
        
        /* Button */
        .premium-button {
          padding: 1rem 1.25rem;
          font-size: 0.8125rem;
          letter-spacing: 0.04em;
        }
        
        /* Error message */
        .error-message {
          padding: 0.75rem 1rem;
          font-size: 0.8125rem;
        }
        
        /* Footer links */
        .text-center.mt-8 {
          margin-top: 1.5rem;
        }
        
        .text-center p,
        .text-center a {
          font-size: 0.8125rem;
        }
      }
      
      /* Small devices (481px - 640px) */
      @media (min-width: 481px) and (max-width: 640px) {
        .premium-card {
          padding: 2rem 1.75rem;
          margin: 0.75rem;
        }
        
        .icon-accent {
          font-size: 3rem;
        }
        
        h1 {
          font-size: 2.25rem;
        }
        
        .decorative-divider {
          margin: 1.5rem 0;
        }
        
        .premium-input {
          padding: 1rem 1rem 1rem 3rem;
        }
        
        .premium-button {
          padding: 1rem 1.5rem;
        }
      }
      
      /* Medium devices (641px - 768px) */
      @media (min-width: 641px) and (max-width: 768px) {
        .premium-card {
          padding: 2.5rem 2rem;
        }
        
        h1 {
          font-size: 2.5rem;
        }
        
        .icon-accent {
          font-size: 3.5rem;
        }
      }
      
      /* Large devices (769px - 1024px) */
      @media (min-width: 769px) and (max-width: 1024px) {
        .premium-card {
          padding: 3rem 2.5rem;
        }
        
        h1 {
          font-size: 2.75rem;
        }
      }
      
      /* Extra large devices (1025px+) */
      @media (min-width: 1025px) {
        .premium-card {
          padding: 3.5rem 3rem;
        }
        
        h1 {
          font-size: 3rem;
        }
        
        .icon-accent {
          font-size: 4rem;
        }
      }
      
      /* Landscape orientation adjustments for mobile */
      @media (max-width: 768px) and (orientation: landscape) {
        body {
          padding: 1rem 0.5rem;
        }
        
        .premium-card {
          padding: 1.5rem 2rem;
          max-height: 90vh;
          overflow-y: auto;
        }
        
        .icon-accent {
          font-size: 2rem;
        }
        
        h1 {
          font-size: 1.75rem;
          margin-bottom: 0.25rem;
        }
        
        .decorative-divider {
          margin: 1rem 0;
        }
        
        .text-center.mb-10 {
          margin-bottom: 1.5rem;
        }
        
        .text-center.mt-8 {
          margin-top: 1.5rem;
        }
      }
      
      /* Touch device optimizations */
      @media (hover: none) and (pointer: coarse) {
        /* Ensure all interactive elements have proper touch targets */
        .premium-button,
        .premium-link,
        #togglePassword {
          min-height: 44px;
          min-width: 44px;
        }
        
        /* Remove hover effects on touch devices */
        .premium-card:hover {
          transform: none;
          box-shadow: 
            0 4px 6px -1px rgba(0, 0, 0, 0.05),
            0 10px 25px -5px rgba(0, 0, 0, 0.08),
            0 0 0 1px rgba(201, 169, 97, 0.05);
        }
        
        .premium-button:hover {
          transform: none;
        }
        
        /* Add active states for better touch feedback */
        .premium-button:active {
          transform: scale(0.98);
          box-shadow: 0 2px 8px rgba(201, 169, 97, 0.3);
        }
        
        .premium-link:active {
          opacity: 0.7;
        }
      }
    </style>
</head>
<body class="min-h-screen premium-bg flex items-center justify-center p-4">

  <div class="w-full max-w-md fade-in">
    <!-- Premium Login Card -->
    <div class="premium-card rounded-sm px-8 py-10 sm:px-12 sm:py-14">
      
      <!-- Header Section -->
      <div class="text-center mb-10">
        <div class="mb-6">
          <i class="fas fa-utensils icon-accent text-5xl"></i>
        </div>
        <h1 class="text-4xl sm:text-5xl font-display font-semibold text-primary mb-3 tracking-wide">
          Welcome
        </h1>
        <p class="text-text-light text-sm tracking-wide">
          Sign in to continue your ordering journey
        </p>
      </div>

      <!-- Decorative Divider -->
      <div class="decorative-divider">
        <span><i class="fas fa-leaf"></i></span>
      </div>

      <!-- Error Message -->
      <?php if (isset($error_message)): ?>
        <div class="error-message px-4 py-3 rounded-sm mb-6 flex items-start text-sm">
          <i class="fas fa-exclamation-circle mr-3 mt-0.5"></i>
          <span><?php echo htmlspecialchars($error_message); ?></span>
        </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form method="POST" action="login.php" class="space-y-6">
        
        <!-- Username Field -->
        <div>
          <label for="username" class="premium-label block mb-3">
            Username or Email
          </label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-muted">
              <i class="fas fa-user text-sm"></i>
            </span>
            <input 
              type="text" 
              id="username" 
              name="username" 
              required
              class="premium-input w-full pl-12 pr-4 py-3.5 rounded-sm text-sm"
              placeholder="Enter your username or email" 
              autocomplete="username"
            >
          </div>
        </div>

        <!-- Password Field -->
        <div>
          <label for="password" class="premium-label block mb-3">
            Password
          </label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-muted">
              <i class="fas fa-lock text-sm"></i>
            </span>
            <input 
              type="password" 
              id="password" 
              name="password" 
              required
              class="premium-input w-full pl-12 pr-12 py-3.5 rounded-sm text-sm"
              placeholder="Enter your password" 
              autocomplete="current-password"
            >
            <button 
              type="button" 
              id="togglePassword" 
              tabindex="-1"
              class="absolute inset-y-0 right-0 pr-4 flex items-center text-muted hover:text-accent transition-colors duration-300 focus:outline-none"
              aria-label="Toggle password visibility"
            >
              <i class="fas fa-eye text-sm" id="togglePasswordIcon"></i>
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <button 
          type="submit"
          class="premium-button w-full py-4 px-6 rounded-sm flex items-center justify-center gap-3 mt-8"
        >
          <span>Sign In</span>
          <i class="fas fa-arrow-right text-sm"></i>
        </button>

      </form>

      <!-- Decorative Divider -->
      <div class="decorative-divider mt-10">
        <span><i class="fas fa-leaf"></i></span>
      </div>

      <!-- Footer Links -->
      <div class="text-center mt-8 space-y-4">
        <p class="text-text-light text-sm">
          Don't have an account?
          <a href="register.php" class="premium-link ml-1">Create Account</a>
        </p>
        <div class="pt-4">
          <a href="index.php" class="text-text-light hover:text-accent transition-colors duration-300 text-sm inline-flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Return to Home</span>
          </a>
        </div>
      </div>
      
    </div>
  </div>

  <script>
    // Password toggle functionality
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordInput = document.getElementById('password');
      const icon = document.getElementById('togglePasswordIcon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  </script>
</body>
</html>
