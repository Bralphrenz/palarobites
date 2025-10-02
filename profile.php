<?php
session_start();
require 'admin/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM user_info WHERE user_id = $user_id")->fetch_assoc();

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($mobile) || empty($address)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/@cec\.edu\.ph$/', $email)) {
        $errors[] = "Only @cec.edu.ph email addresses are allowed.";
    }

    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $errors[] = "Mobile number must be 10 digits.";
    }

    // Check if email already exists (excluding current user)
    $stmt = $conn->prepare("SELECT * FROM user_info WHERE email = ? AND user_id != ?");
    $stmt->bind_param('si', $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->fetch_assoc()) {
        $errors[] = "Email already exists.";
    }

    if (empty($errors)) {
        $first_name = $conn->real_escape_string($first_name);
        $last_name = $conn->real_escape_string($last_name);
        $email = $conn->real_escape_string($email);
        $mobile = $conn->real_escape_string($mobile);
        $address = $conn->real_escape_string($address);

        $update = $conn->query("UPDATE user_info SET 
            first_name = '$first_name',
            last_name = '$last_name',
            email = '$email',
            mobile = '$mobile',
            address = '$address'
            WHERE user_id = $user_id
        ");

        if ($update) {
            $success = "Profile updated successfully!";
            $user = $conn->query("SELECT * FROM user_info WHERE user_id = $user_id")->fetch_assoc();
        } else {
            $errors[] = "Failed to update profile. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Restaurant</title>
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
        
        .transition-smooth {
            transition: all 0.2s ease;
        }
        
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
    <div class="w-full max-w-sm sm:max-w-md lg:max-w-2xl">
        <div class="bg-white border border-black p-6 sm:p-8 md:p-10 lg:p-12">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-black">
                <i class="fas fa-user-circle text-2xl sm:text-3xl text-black mb-3"></i>
                <h1 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-bold text-black mb-1">My Profile</h1>
                <p class="text-gray-600 text-xs sm:text-sm">Update your personal information</p>
            </div>

            <!-- Success Message -->
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 px-3 py-2 mb-6 text-xs sm:text-sm">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="bg-black text-white px-3 py-2 mb-6 animate-shake text-xs sm:text-sm">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Profile Form -->
            <form method="post" class="space-y-4 sm:space-y-5">
                <!-- Responsive grid for names -->
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
                                value="<?php echo htmlspecialchars($user['first_name']); ?>"
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
                                value="<?php echo htmlspecialchars($user['last_name']); ?>"
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
                            value="<?php echo htmlspecialchars($user['email']); ?>"
                            class="w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border border-black focus:outline-none focus:ring-2 focus:ring-black bg-white text-black transition-smooth text-sm sm:text-base"
                        >
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
                            value="<?php echo htmlspecialchars($user['mobile']); ?>"
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
                        ><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="index.php" class="w-full sm:w-auto bg-gray-200 text-gray-700 px-6 py-3 text-center hover:bg-gray-300 transition-smooth inline-flex items-center justify-center gap-2 text-sm sm:text-base font-medium">
                        <i class="fas fa-arrow-left text-xs"></i>
                        <span>Back to Home</span>
                    </a>
                    <button 
                        type="submit"
                        class="w-full sm:w-auto bg-black text-white py-3 px-6 font-medium text-sm sm:text-base hover:bg-gray-800 transition-smooth"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>