<?php
require 'admin/db_connect.php'; 

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password) || empty($mobile) || empty($address)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $errors[] = "Mobile number must be 10 digits.";
    }

    $stmt = $conn->prepare("SELECT * FROM user_info WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->fetch_assoc()) {
        $errors[] = "Email already exists.";
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO user_info (first_name, last_name, email, password, mobile, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $first_name, $last_name, $email, $password_hash, $mobile, $address);
        $stmt->execute();

        $_SESSION['success_message'] = "Registration successful! You can now log in.";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-green-100 to-green-200 flex items-center justify-center relative overflow-hidden">

  <div class="absolute inset-0 z-0 pointer-events-none">
    <img src="assets/img/restaurant-bg.jpg" alt="Restaurant" class="w-full h-full object-cover opacity-20 blur-sm">
    <div class="absolute inset-0 bg-gradient-to-br from-green-50/80 via-green-100/80 to-green-200/80"></div>
    <span class="absolute top-10 left-10 w-24 h-24 bg-green-200 rounded-full opacity-40 animate-pulse"></span>
    <span class="absolute bottom-10 right-10 w-32 h-32 bg-green-300 rounded-full opacity-30 animate-ping"></span>
    <span class="absolute top-1/2 left-1/2 w-16 h-16 bg-green-100 rounded-full opacity-30 animate-bounce" style="transform: translate(-50%, -50%);"></span>
    <span class="absolute bottom-1/3 left-1/4 w-20 h-20 bg-white/30 rounded-full opacity-20 animate-pulse"></span>
    <span class="absolute top-1/4 right-1/4 w-14 h-14 bg-green-100 rounded-full opacity-20 animate-bounce"></span>
  </div>

  <div class="relative z-10 w-full max-w-md mx-auto px-2 sm:px-0">
    <div class="bg-white/60 backdrop-blur-lg rounded-3xl shadow-2xl px-4 py-6 sm:px-8 sm:py-12 border border-green-200 transition-transform duration-500 hover:scale-105 hover:shadow-green-300/50 hover:border-green-400 animate-fadein max-h-[98vh] overflow-y-auto">
      <div class="flex flex-col items-center mb-8 relative">
        <span class="absolute -top-12 left-1/2 -translate-x-1/2 bg-gradient-to-tr from-green-500 to-green-300 rounded-full p-6 shadow-xl border-4 border-white animate-glow">
          <i class="fas fa-pizza-slice text-white text-5xl drop-shadow-lg"></i>
        </span>
        <h1 class="text-3xl font-extrabold text-green-600 tracking-tight mb-1 drop-shadow mt-8">Create Account</h1>
        <p class="text-gray-500 text-base">Register to start ordering your favorite food</p>
      </div>

      <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 animate-shake">
          <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="register.php" class="space-y-4">
        <div>
          <label for="first_name" class="block text-gray-700 font-medium mb-1">First Name</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-green-400">
              <i class="fas fa-user"></i>
            </span>
            <input type="text" id="first_name" name="first_name" required
                   class="w-full pl-10 pr-3 py-2 border border-green-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 bg-green-50/80 transition">
          </div>
        </div>

        <div>
          <label for="last_name" class="block text-gray-700 font-medium mb-1">Last Name</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-green-400">
              <i class="fas fa-user"></i>
            </span>
            <input type="text" id="last_name" name="last_name" required
                   class="w-full pl-10 pr-3 py-2 border border-green-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 bg-green-50/80 transition">
          </div>
        </div>

        <div>
          <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-green-400">
              <i class="fas fa-envelope"></i>
            </span>
            <input type="email" id="email" name="email" required
                   class="w-full pl-10 pr-3 py-2 border border-green-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 bg-green-50/80 transition">
          </div>
        </div>

        <div>
          <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-green-400">
              <i class="fas fa-lock"></i>
            </span>
            <input type="password" id="password" name="password" required
                   class="w-full pl-10 pr-3 py-2 border border-green-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 bg-green-50/80 transition">
          </div>
        </div>

        <div>
          <label for="confirm_password" class="block text-gray-700 font-medium mb-1">Confirm Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-green-400">
              <i class="fas fa-lock"></i>
            </span>
            <input type="password" id="confirm_password" name="confirm_password" required
                   class="w-full pl-10 pr-3 py-2 border border-green-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 bg-green-50/80 transition">
          </div>
        </div>

        <div>
          <label for="mobile" class="block text-gray-700 font-medium mb-1">Mobile</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-green-400">
              <i class="fas fa-phone"></i>
            </span>
            <input type="text" id="mobile" name="mobile" required
                   class="w-full pl-10 pr-3 py-2 border border-green-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 bg-green-50/80 transition">
          </div>
        </div>

        <div>
          <label for="address" class="block text-gray-700 font-medium mb-1">Address</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-green-400">
              <i class="fas fa-map-marker-alt"></i>
            </span>
            <textarea id="address" name="address" required
                      class="w-full pl-10 pr-3 py-2 border border-green-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 bg-green-50/80 transition"></textarea>
          </div>
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-green-500 to-green-400 text-white py-2 px-4 rounded-lg font-bold text-lg shadow-md hover:from-green-600 hover:to-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400 transition flex items-center justify-center gap-2">
          <i class="fas fa-user-plus"></i> Register
        </button>

        <div class="text-center text-sm mt-4">
          <p class="text-gray-600">Already have an account?
            <a href="login.php" class="font-medium text-green-600 hover:text-green-800 transition">Login here</a>
          </p>
        </div>
      </form>

      <div class="text-center mt-6">
        <a href="index.php" class="text-green-500 hover:text-green-700 font-medium transition">
          <i class="fas fa-arrow-left mr-2"></i> Back to Home
        </a>
      </div>
    </div>
  </div>

  <style>
    @keyframes glow {
      0%, 100% { box-shadow: 0 0 0 0 #4ade80, 0 0 0 0 #22c55e; }
      50% { box-shadow: 0 0 32px 8px #4ade80, 0 0 16px 4px #22c55e; }
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
    @keyframes fadein {
      from { opacity: 0; transform: translateY(40px);}
      to { opacity: 1; transform: translateY(0);}
    }
    .animate-fadein {
      animation: fadein 0.8s cubic-bezier(.4,2,.6,1) both;
    }
  </style>
</body>
</html>
