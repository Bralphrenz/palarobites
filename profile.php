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
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $address = $conn->real_escape_string($_POST['address']);

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
        $error = "Failed to update profile. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-lg">
      <h2 class="text-3xl font-bold text-blue-700 mb-6 text-center">My Profile</h2>
      <?php if ($success): ?>
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-center"><?php echo $success; ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-center"><?php echo $error; ?></div>
      <?php endif; ?>
      <form method="post" class="space-y-4">
        <div>
          <label class="block text-gray-600 font-semibold mb-1">First Name</label>
          <input type="text" name="first_name" class="w-full px-4 py-2 border rounded" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>
        <div>
          <label class="block text-gray-600 font-semibold mb-1">Last Name</label>
          <input type="text" name="last_name" class="w-full px-4 py-2 border rounded" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>
        <div>
          <label class="block text-gray-600 font-semibold mb-1">Email</label>
          <input type="email" name="email" class="w-full px-4 py-2 border rounded" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div>
          <label class="block text-gray-600 font-semibold mb-1">Mobile</label>
          <input type="text" name="mobile" class="w-full px-4 py-2 border rounded" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
        </div>
        <div>
          <label class="block text-gray-600 font-semibold mb-1">Address</label>
          <textarea name="address" class="w-full px-4 py-2 border rounded" rows="2" required><?php echo htmlspecialchars($user['address']); ?></textarea>
        </div>
        <div class="pt-4 flex justify-between items-center">
          <a href="index.php" class="inline-block bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 transition">Back to Home</a>
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>