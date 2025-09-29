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
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 flex items-center justify-center relative overflow-hidden">

  <div class="absolute inset-0 z-0 pointer-events-none">
    <img src="assets/img/restaurant-bg.jpg" alt="Restaurant" class="w-full h-full object-cover opacity-20 blur-sm">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/80 via-blue-100/80 to-blue-200/80"></div>
    <span class="absolute top-10 left-10 w-24 h-24 bg-blue-200 rounded-full opacity-40 animate-pulse"></span>
    <span class="absolute bottom-10 right-10 w-32 h-32 bg-blue-300 rounded-full opacity-30 animate-ping"></span>
    <span class="absolute top-1/2 left-1/2 w-16 h-16 bg-blue-100 rounded-full opacity-30 animate-bounce" style="transform: translate(-50%, -50%);"></span>
  </div>

  <div class="relative z-10 w-full max-w-md mx-auto">
    <div class="bg-white/90 rounded-3xl shadow-2xl px-8 py-10 transition-transform duration-300 hover:scale-105 hover:shadow-blue-200/40 hover:shadow-2xl">
      <div class="flex flex-col items-center mb-8">
        <div class="bg-gradient-to-tr from-blue-500 to-blue-300 rounded-full p-5 shadow-lg mb-3 animate-glow">
          <i class="fas fa-utensils text-white text-5xl drop-shadow-lg"></i>
        </div>
        <h1 class="text-4xl font-extrabold text-blue-600 tracking-tight mb-1 drop-shadow">Welcome Back!</h1>
        <p class="text-gray-500 text-base">Sign in to order your favorite food</p>
      </div>

      <?php if (isset($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center animate-shake">
          <i class="fas fa-exclamation-circle mr-2"></i>
          <?php echo htmlspecialchars($error_message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php" class="space-y-6">
        <div>
          <label for="username" class="block text-gray-700 font-semibold mb-1">Username or Email</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-blue-400">
              <i class="fas fa-user"></i>
            </span>
            <input type="text" id="username" name="username" required
              class="w-full pl-10 pr-3 py-2 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 bg-blue-50 transition"
              placeholder="Enter your username or email" autocomplete="username">
          </div>
        </div>

        <div>
          <label for="password" class="block text-gray-700 font-semibold mb-1">Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-blue-400">
              <i class="fas fa-lock"></i>
            </span>
            <input type="password" id="password" name="password" required
              class="w-full pl-10 pr-10 py-2 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 bg-blue-50 transition"
              placeholder="Enter your password" autocomplete="current-password">
            <button type="button" id="togglePassword" tabindex="-1"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-400 focus:outline-none"
              aria-label="Show password">
              <i class="fas fa-eye" id="togglePasswordIcon"></i>
            </button>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-gradient-to-r from-blue-500 to-blue-400 text-white py-2 px-4 rounded-lg font-bold text-lg shadow-md hover:from-blue-600 hover:to-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition flex items-center justify-center gap-2">
          <i class="fas fa-sign-in-alt"></i> Log In
        </button>

        <div class="text-center text-sm mt-4">
          <p class="text-gray-600">Don't have an account?
            <a href="register.php" class="font-medium text-blue-600 hover:text-blue-800 transition">Register here</a>
          </p>
        </div>
      </form>
      <div class="text-center mt-6">
        <a href="index.php" class="text-blue-500 hover:text-blue-700 font-medium transition">
          <i class="fas fa-arrow-left mr-2"></i> Back to Home
        </a>
      </div>
    </div>
  </div>

  <style>
    @keyframes glow {
      0%, 100% { box-shadow: 0 0 0 0 #60a5fa, 0 0 0 0 #38bdf8; }
      50% { box-shadow: 0 0 32px 8px #60a5fa, 0 0 16px 4px #38bdf8; }
    }
    .animate-glow {
      animation: glow 2.5s infinite;
    }
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-8px); }
      40%, 80% { transform: translateX(8px); }
    }
    .animate-shake {
      animation: shake 0.4s;
    }
  </style>

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
