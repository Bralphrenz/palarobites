<style>
  #sidebar {
    background-color: #f8f9fa; 
    color: #343a40; 
    height: 100vh;
    position: fixed;
    width: 250px;
    padding-top: 20px;
    transition: all 0.3s ease;
    border-right: 1px solid #dee2e6; 
  }

  #sidebar .sidebar-list a {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #495057; 
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  #sidebar .sidebar-list a:hover,
  #sidebar .sidebar-list a.active {
    background-color: #e9ecef;
    color: #212529; 
    border-left: 4px solid #0d6efd; 
  }

  #sidebar .icon-field {
    margin-right: 10px;
    font-size: 18px;
    color: #6c757d; 
  }

  #sidebar .sidebar-header {
    font-size: 20px;
    font-weight: bold;
    color: #343a40; 
    text-align: center;
    margin-bottom: 20px;
    padding: 10px 0;
    border-bottom: 1px solid #dee2e6; 
  }
</style>

<nav id="sidebar" class="fixed top-0 left-0 h-screen w-64 bg-white border-r border-gray-200 shadow-lg z-40 flex flex-col">
  <div class="text-2xl font-bold text-green-700 text-center py-6 border-b border-gray-100 tracking-wide" style="font-family: 'Dancing Script', cursive;">
    Admin Panel
  </div>
  <div class="flex-1 flex flex-col gap-1 mt-4">
    <a href="index.php?page=home" class="nav-item nav-home flex items-center px-6 py-3 text-gray-700 font-medium rounded-l-full hover:bg-green-50 hover:text-green-700 transition group">
      <span class="mr-4 text-lg text-gray-400 group-hover:text-green-500"><i class="fa fa-home"></i></span> Home
    </a>
    <a href="index.php?page=orders" class="nav-item nav-orders flex items-center px-6 py-3 text-gray-700 font-medium rounded-l-full hover:bg-green-50 hover:text-green-700 transition group">
      <span class="mr-4 text-lg text-gray-400 group-hover:text-green-500"><i class="fa fa-list"></i></span> Orders
    </a>
    <a href="index.php?page=menu" class="nav-item nav-menu flex items-center px-6 py-3 text-gray-700 font-medium rounded-l-full hover:bg-green-50 hover:text-green-700 transition group">
      <span class="mr-4 text-lg text-gray-400 group-hover:text-green-500"><i class="fa fa-list"></i></span> Menu
    </a>
    <a href="index.php?page=categories" class="nav-item nav-categories flex items-center px-6 py-3 text-gray-700 font-medium rounded-l-full hover:bg-green-50 hover:text-green-700 transition group">
      <span class="mr-4 text-lg text-gray-400 group-hover:text-green-500"><i class="fa fa-list"></i></span> Category List
    </a>
    <?php if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == 1): ?>
      <a href="index.php?page=users" class="nav-item nav-users flex items-center px-6 py-3 text-gray-700 font-medium rounded-l-full hover:bg-green-50 hover:text-green-700 transition group">
        <span class="mr-4 text-lg text-gray-400 group-hover:text-green-500"><i class="fa fa-users"></i></span> Users
      </a>
      <a href="index.php?page=site_settings" class="nav-item nav-site_settings flex items-center px-6 py-3 text-gray-700 font-medium rounded-l-full hover:bg-green-50 hover:text-green-700 transition group">
        <span class="mr-4 text-lg text-gray-400 group-hover:text-green-500"><i class="fa fa-cogs"></i></span> Site Settings
      </a>
    <?php endif; ?>
  </div>
  <div class="mb-6 px-6">
    <a href="ajax.php?action=logout"
       class="w-full flex items-center justify-center px-3 py-2 border border-red-500 text-red-500 rounded hover:bg-red-500 hover:text-white transition text-sm font-semibold">
      <?php echo isset($_SESSION['login_name']) ? $_SESSION['login_name'] : 'Admin'; ?>
      <i class="fa fa-power-off ml-2"></i>
    </a>
  </div>
</nav>

<script>
  // Highlight the active nav item
  document.addEventListener('DOMContentLoaded', function() {
    var page = "<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>";
    if(page) {
      var active = document.querySelector('.nav-' + page);
      if(active) {
        active.classList.add('bg-green-100', 'text-green-700', 'border-l-4', 'border-green-500');
      }
    }
  });
</script>