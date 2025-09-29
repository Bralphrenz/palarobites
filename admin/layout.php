<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
  <?php include 'navbar.php'; ?>
  <div class="pl-64"> <!-- Sidebar width -->
    <?php include 'topbar.php'; ?>
    <main class="pt-20 px-6">
      <?php
        if ($page === 'orders') {
          include 'orders.php';
        } else {
          include 'home.php';
        }
      ?>
    </main>
  </div>
</body>
</html>