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

    if (!preg_match('/@cec\.edu\.ph$/', $email)) {
        $errors[] = "Only @cec.edu.ph email addresses are allowed. Please try again";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (!preg_match('/^[0-9]{11}$/', $mobile)) {
        $errors[] = "Mobile number must be 11 digits.";
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
    <title>Register - Premium Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-primary: #1a1a1a;
            --color-accent: #c9a961;
            --color-accent-dark: #b8954d;
            --color-cream: #faf8f5;
            --color-warm-gray: #8b6f47;
            --font-heading: 'Cormorant Garamond', serif;
            --font-body: 'Montserrat', sans-serif;
        }
        
        body {
            font-family: var(--font-body);
            background: linear-gradient(135deg, #faf8f5 0%, #f5f3ef 100%);
        }
        
        .font-heading {
            font-family: var(--font-heading);
            letter-spacing: 0.02em;
        }
        
        .premium-card {
            background: linear-gradient(to bottom, #ffffff 0%, #fefefe 100%);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.05),
                0 10px 25px -3px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(201, 169, 97, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .premium-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(201, 169, 97, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(201, 169, 97, 0.02) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .decorative-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
        }
        
        .decorative-divider::before,
        .decorative-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(201, 169, 97, 0.3);
        }
        
        .decorative-divider::before {
            margin-right: 1rem;
        }
        
        .decorative-divider::after {
            margin-left: 1rem;
        }
        
        .decorative-icon {
            color: var(--color-accent);
            font-size: 0.875rem;
        }
        
        .premium-input {
            border: 1.5px solid #e5e5e5;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .premium-input:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.1);
            background: #fefefe;
        }
        
        .premium-input:hover {
            border-color: rgba(201, 169, 97, 0.4);
        }
        
        .premium-button {
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-dark) 100%);
            box-shadow: 0 4px 12px rgba(201, 169, 97, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .premium-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .premium-button:hover::before {
            left: 100%;
        }
        
        .premium-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(201, 169, 97, 0.4);
        }
        
        .premium-button:active {
            transform: translateY(0);
        }
        
        .error-box {
            background: linear-gradient(135deg, #2d1f1f 0%, #1a1a1a 100%);
            border-left: 4px solid var(--color-accent);
            animation: slideIn 0.4s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .input-icon {
            color: var(--color-warm-gray);
            transition: color 0.3s ease;
        }
        
        .premium-input:focus ~ .input-icon,
        .premium-input:focus + .input-icon-wrapper .input-icon {
            color: var(--color-accent);
        }
        
        .premium-link {
            color: var(--color-accent);
            position: relative;
            transition: color 0.3s ease;
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
        
        .premium-link:hover::after {
            width: 100%;
        }
        
        .premium-link:hover {
            color: var(--color-accent-dark);
        }
        
        @media (max-width: 480px) {
            .premium-card {
                margin: 0.75rem;
                padding: 1.5rem !important;
            }
            
            .font-heading {
                font-size: 1.75rem !important;
            }
            
            .premium-input {
                padding: 0.625rem 0.75rem !important;
                padding-left: 2.25rem !important;
                font-size: 0.875rem;
            }
            
            textarea.premium-input {
                padding-left: 2.25rem !important;
            }
            
            .premium-button {
                padding: 0.75rem 1rem !important;
                font-size: 0.875rem;
            }
            
            .input-icon-wrapper {
                padding-left: 0.625rem !important;
            }
            
            .decorative-divider {
                margin: 1rem 0;
            }
        }
        
        @media (min-width: 481px) and (max-width: 640px) {
            .premium-card {
                margin: 1rem;
                padding: 2rem !important;
            }
            
            .font-heading {
                font-size: 2rem !important;
            }
            
            .premium-input {
                padding: 0.75rem 1rem !important;
                padding-left: 2.5rem !important;
            }
            
            textarea.premium-input {
                padding-left: 2.5rem !important;
            }
        }
        
        @media (min-width: 641px) and (max-width: 768px) {
            .premium-card {
                padding: 2.5rem !important;
            }
        }
        
        @media (min-width: 769px) and (max-width: 1024px) {
            .premium-card {
                padding: 3rem !important;
            }
        }
        
        @media (hover: none) and (pointer: coarse) {
            .premium-button,
            .premium-link,
            .premium-input {
                min-height: 44px;
                min-width: 44px;
            }
        }
        
        @media (max-height: 600px) and (orientation: landscape) {
            .premium-card {
                padding: 1.5rem !important;
                margin: 0.5rem;
            }
            
            .decorative-divider {
                margin: 0.75rem 0;
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8">
    <div class="w-full max-w-sm sm:max-w-md lg:max-w-3xl xl:max-w-4xl">
        <div class="premium-card rounded-lg px-6 py-8 sm:px-8 sm:py-10 md:px-10 md:py-12 lg:px-12 lg:py-14">
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-block mb-4">
                    <i class="fas fa-utensils text-3xl sm:text-4xl md:text-5xl" style="color: var(--color-accent);"></i>
                </div>
                <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl font-bold mb-2" style="color: var(--color-primary);">
                    Create Account
                </h1>
                <p class="text-sm sm:text-base" style="color: var(--color-warm-gray);">
                    Join us for an exceptional ordering experience
                </p>
                
                <div class="decorative-divider">
                    <i class="fas fa-leaf decorative-icon"></i>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-box rounded-lg px-4 py-3 mb-6 sm:mb-8">
                    <?php foreach ($errors as $error): ?>
                        <p class="text-white text-sm sm:text-base flex items-start gap-2 mb-1 last:mb-0">
                            <i class="fas fa-exclamation-circle mt-0.5" style="color: var(--color-accent);"></i>
                            <span><?php echo htmlspecialchars($error); ?></span>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php" class="space-y-5 sm:space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
                    <div>
                        <label for="first_name" class="block font-medium mb-2 text-sm sm:text-base" style="color: var(--color-primary);">
                            First Name
                        </label>
                        <div class="relative">
                            <span class="input-icon-wrapper absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user input-icon text-sm sm:text-base"></i>
                            </span>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                required
                                value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"
                                class="premium-input w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 rounded-lg text-sm sm:text-base"
                                style="color: var(--color-primary);"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="last_name" class="block font-medium mb-2 text-sm sm:text-base" style="color: var(--color-primary);">
                            Last Name
                        </label>
                        <div class="relative">
                            <span class="input-icon-wrapper absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user input-icon text-sm sm:text-base"></i>
                            </span>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                required
                                value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>"
                                class="premium-input w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 rounded-lg text-sm sm:text-base"
                                style="color: var(--color-primary);"
                            >
                        </div>
                    </div>
                </div>

                <div>
                    <label for="email" class="block font-medium mb-2 text-sm sm:text-base" style="color: var(--color-primary);">
                        Email Address
                    </label>
                    <div class="relative">
                        <span class="input-icon-wrapper absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope input-icon text-sm sm:text-base"></i>
                        </span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            class="premium-input w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 rounded-lg text-sm sm:text-base"
                            style="color: var(--color-primary);"
                            placeholder="your.name@cec.edu.ph"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
                    <div>
                        <label for="password" class="block font-medium mb-2 text-sm sm:text-base" style="color: var(--color-primary);">
                            Password
                        </label>
                        <div class="relative">
                            <span class="input-icon-wrapper absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock input-icon text-sm sm:text-base"></i>
                            </span>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="premium-input w-full pl-10 sm:pl-12 pr-12 py-3 sm:py-3.5 rounded-lg text-sm sm:text-base"
                                style="color: var(--color-primary);"
                                placeholder="Min. 8 characters"
                            >
                            <button type="button" id="togglePassword" tabindex="-1" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-yellow-700 focus:outline-none" style="font-size:1.1rem;">
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block font-medium mb-2 text-sm sm:text-base" style="color: var(--color-primary);">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <span class="input-icon-wrapper absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock input-icon text-sm sm:text-base"></i>
                            </span>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                required
                                class="premium-input w-full pl-10 sm:pl-12 pr-12 py-3 sm:py-3.5 rounded-lg text-sm sm:text-base"
                                style="color: var(--color-primary);"
                            >
                            <button type="button" id="toggleConfirmPassword" tabindex="-1" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-yellow-700 focus:outline-none" style="font-size:1.1rem;">
                                <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="mobile" class="block font-medium mb-2 text-sm sm:text-base" style="color: var(--color-primary);">
                        Mobile Number
                    </label>
                    <div class="relative">
                        <span class="input-icon-wrapper absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-phone input-icon text-sm sm:text-base"></i>
                        </span>
                        <input 
                            type="text" 
                            id="mobile" 
                            name="mobile" 
                            required
                            value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>"
                            class="premium-input w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 rounded-lg text-sm sm:text-base"
                            style="color: var(--color-primary);"
                            placeholder="11 digits"
                        >
                    </div>
                </div>

                <div>
                    <label for="address" class="block font-medium mb-2 text-sm sm:text-base" style="color: var(--color-primary);">
                        Delivery Address
                    </label>
                    <div class="relative">
                        <span class="input-icon-wrapper absolute top-3 sm:top-3.5 left-0 pl-3 sm:pl-4 flex items-start pointer-events-none">
                            <i class="fas fa-map-marker-alt input-icon text-sm sm:text-base"></i>
                        </span>
                        <textarea 
                            id="address" 
                            name="address" 
                            required
                            rows="3"
                            class="premium-input w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 rounded-lg resize-none text-sm sm:text-base"
                            style="color: var(--color-primary);"
                            placeholder="Enter your complete delivery address"
                        ><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                    </div>
                </div>

                <button 
                    type="submit"
                    class="premium-button w-full text-white py-3.5 sm:py-4 px-6 rounded-lg font-semibold text-sm sm:text-base tracking-wide uppercase"
                >
                    Create Account
                </button>

                <div class="text-center pt-5 sm:pt-6">
                    <div class="decorative-divider">
                        <i class="fas fa-leaf decorative-icon"></i>
                    </div>
                    <p class="text-sm sm:text-base" style="color: var(--color-warm-gray);">
                        Already have an account?
                        <a href="login.php" class="premium-link font-semibold ml-1">Login</a>
                    </p>
                </div>
            </form>
<script>
    // Show/hide password for Password field
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
    // Show/hide password for Confirm Password field
    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
        const pwd = document.getElementById('confirm_password');
        const icon = document.getElementById('toggleConfirmPasswordIcon');
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
            <div class="text-center mt-6 sm:mt-8 pt-6 sm:pt-8 border-t" style="border-color: rgba(201, 169, 97, 0.2);">
                <a href="index.php" class="premium-link inline-flex items-center gap-2 text-sm sm:text-base font-medium">
                    <i class="fas fa-arrow-left text-xs sm:text-sm"></i>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
