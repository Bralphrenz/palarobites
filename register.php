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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Lora', serif;
        }
        
        body {
            font-family: var(--font-body);
        }
        
        .font-heading {
            font-family: var(--font-heading);
        }
        
        /* Simplified transition for minimal design */
        .transition-smooth {
            transition: all 0.2s ease;
        }
        
        /* Removed complex animations, kept only shake for errors */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }
        
        .animate-shake {
            animation: shake 0.4s ease-in-out;
        }
    </style>
</head>
<body class="min-h-screen bg-white flex items-center justify-center p-4 sm:p-6 md:p-8">
    <!-- Added responsive width constraints and padding -->
    <div class="w-full max-w-sm sm:max-w-md lg:max-w-2xl">
        <div class="bg-white border border-black p-6 sm:p-8 md:p-10 lg:p-12">
            <!-- Simplified header with responsive text sizes -->
            <div class="text-center mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-black">
                <i class="fas fa-utensils text-2xl sm:text-3xl text-black mb-3"></i>
                <h1 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-bold text-black mb-1">Create Account</h1>
                <p class="text-gray-600 text-xs sm:text-sm">Register to start ordering</p>
            </div>

            <!-- Minimal error styling -->
            <?php if (!empty($errors)): ?>
                <div class="bg-black text-white px-3 py-2 mb-6 animate-shake text-xs sm:text-sm">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Improved responsive form with better mobile spacing -->
            <form method="POST" action="register.php" class="space-y-4 sm:space-y-5">
                <!-- Responsive grid that stacks on mobile -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label for="first_name" class="block text-black font-medium mb-2 text-xs sm:text-sm">First Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-user text-xs sm:text-sm"></i>
                            </span>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                required
                                value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"
                                class="w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border border-black focus:outline-none focus:ring-2 focus:ring-black bg-white text-black transition-smooth text-sm sm:text-base"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="last_name" class="block text-black font-medium mb-2 text-xs sm:text-sm">Last Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-user text-xs sm:text-sm"></i>
                            </span>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                required
                                value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>"
                                class="w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border border-black focus:outline-none focus:ring-2 focus:ring-black bg-white text-black transition-smooth text-sm sm:text-base"
                            >
                        </div>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-black font-medium mb-2 text-xs sm:text-sm">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-envelope text-xs sm:text-sm"></i>
                        </span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            class="w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border border-black focus:outline-none focus:ring-2 focus:ring-black bg-white text-black transition-smooth text-sm sm:text-base"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label for="password" class="block text-black font-medium mb-2 text-xs sm:text-sm">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-lock text-xs sm:text-sm"></i>
                            </span>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border border-black focus:outline-none focus:ring-2 focus:ring-black bg-white text-black transition-smooth text-sm sm:text-base"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-black font-medium mb-2 text-xs sm:text-sm">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-lock text-xs sm:text-sm"></i>
                            </span>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                required
                                class="w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border border-black focus:outline-none focus:ring-2 focus:ring-black bg-white text-black transition-smooth text-sm sm:text-base"
                            >
                        </div>
                    </div>
                </div>

                <div>
                    <label for="mobile" class="block text-black font-medium mb-2 text-xs sm:text-sm">Mobile Number</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-phone text-xs sm:text-sm"></i>
                        </span>
                        <input 
                            type="text" 
                            id="mobile" 
                            name="mobile" 
                            required
                            value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>"
                            class="w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border border-black focus:outline-none focus:ring-2 focus:ring-black bg-white text-black transition-smooth text-sm sm:text-base"
                            placeholder="10 digits"
                        >
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-black font-medium mb-2 text-xs sm:text-sm">Delivery Address</label>
                    <div class="relative">
                        <span class="absolute top-3 left-0 pl-3 flex items-start text-gray-400">
                            <i class="fas fa-map-marker-alt text-xs sm:text-sm"></i>
                        </span>
                        <textarea 
                            id="address" 
                            name="address" 
                            required
                            rows="3"
                            class="w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border border-black focus:outline-none focus:ring-2 focus:ring-black bg-white text-black transition-smooth resize-none text-sm sm:text-base"
                        ><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                    </div>
                </div>

                <!-- Minimal button with better mobile touch target -->
                <button 
                    type="submit"
                    class="w-full bg-black text-white py-3 sm:py-3.5 px-6 font-medium text-sm sm:text-base hover:bg-gray-800 transition-smooth"
                >
                    Create Account
                </button>

                <!-- Simplified footer with responsive text -->
                <div class="text-center pt-4 border-t border-gray-300">
                    <p class="text-gray-600 text-xs sm:text-sm">
                        Already have an account?
                        <a href="login.php" class="text-black font-medium hover:underline transition-smooth">Login</a>
                    </p>
                </div>
            </form>

            <div class="text-center mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-300">
                <a href="index.php" class="text-black hover:underline transition-smooth inline-flex items-center gap-2 text-xs sm:text-sm font-medium">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
