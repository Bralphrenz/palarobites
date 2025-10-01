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
            header("Location: admin/index1.php"); 
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
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
      @import 'tailwindcss';
      
      @theme inline {
        --font-serif: 'Playfair Display', serif;
        --font-body: 'Lora', serif;
      }
      
      body {
        font-family: var(--font-body);
      }
      
      .font-display {
        font-family: var(--font-serif);
      }
      
      /* Monochrome grayscale filter for background */
      .monochrome-bg {
        filter: grayscale(100%);
      }
      
      /* Elegant shadow in grayscale */
      .elegant-shadow {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      }
      
      /* Smooth transitions */
      .transition-elegant {
        transition: all 0.3s ease;
      }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center relative overflow-hidden">

  <!-- Updated background with monochrome styling -->
  <div class="absolute inset-0 z-0 pointer-events-none">
    <img src="assets/img/restaurant-bg.jpg" alt="Restaurant" class="w-full h-full object-cover opacity-10 monochrome-bg">
    <div class="absolute inset-0 bg-white/70"></div>
  </div>

  <div class="relative z-10 w-full max-w-md mx-auto px-4">
    <!-- Black and white color scheme with elegant borders -->
    <div class="bg-white rounded-sm elegant-shadow border border-gray-300 px-10 py-12">
      
      <div class="flex flex-col items-center mb-10 border-b border-gray-300 pb-8">
        <div class="mb-4">
          <i class="fas fa-utensils text-black text-4xl"></i>
        </div>
        <h1 class="text-3xl font-display font-semibold text-black tracking-wide mb-2">Welcome Back</h1>
        <p class="text-gray-600 text-sm">Please sign in to continue</p>
      </div>

      <?php if (isset($error_message)): ?>
        <!-- Error message in grayscale -->
        <div class="bg-gray-100 border border-gray-400 text-gray-800 px-4 py-3 rounded-sm mb-6 flex items-center text-sm">
          <i class="fas fa-exclamation-circle mr-2"></i>
          <?php echo htmlspecialchars($error_message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php" class="space-y-6">
        <div>
          <label for="username" class="block text-black font-medium mb-2 text-sm">Username or Email</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
              <i class="fas fa-user text-sm"></i>
            </span>
            <input type="text" id="username" name="username" required
              class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 bg-white transition-elegant text-black"
              placeholder="Enter your username or email" autocomplete="username">
          </div>
        </div>

        <div>
          <label for="password" class="block text-black font-medium mb-2 text-sm">Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
              <i class="fas fa-lock text-sm"></i>
            </span>
            <input type="password" id="password" name="password" required
              class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 bg-white transition-elegant text-black"
              placeholder="Enter your password" autocomplete="current-password">
            <button type="button" id="togglePassword" tabindex="-1"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 focus:outline-none hover:text-black transition-elegant"
              aria-label="Show password">
              <i class="fas fa-eye text-sm" id="togglePasswordIcon"></i>
            </button>
          </div>
        </div>

        <!-- Black button with white text -->
        <button type="submit"
          class="w-full bg-black text-white py-3 px-4 rounded-sm font-medium text-base shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-elegant flex items-center justify-center gap-2">
          <i class="fas fa-sign-in-alt text-sm"></i> Log In
        </button>

        <div class="text-center text-sm mt-6 pt-6 border-t border-gray-300">
          <p class="text-gray-600">Don't have an account?
            <a href="register.php" class="font-medium text-black hover:text-gray-700 transition-elegant underline">Register here</a>
          </p>
        </div>
      </form>
      
      <div class="text-center mt-4">
        <a href="index.php" class="text-gray-600 hover:text-black font-medium transition-elegant text-sm">
          <i class="fas fa-arrow-left mr-2 text-xs"></i> Back to Home
        </a>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
      const pwd = document.getElementById('password');
      const icon = document.getElementById('togglePasswordIcon');
      if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        pwd.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  </script>
</body>
</html>
