<nav id="sidebar" class="fixed top-0 left-0 h-screen w-64 bg-gradient-to-b from-gray-900 to-gray-800 border-r border-gray-700 shadow-2xl z-40 flex flex-col">
  <div class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-600 text-center py-6 border-b border-gray-700 tracking-wide" style="font-family: 'Cormorant Garamond', serif;">
    Admin Panel
  </div>
  
  <div class="flex-1 flex flex-col gap-2 mt-6 px-3">
    <a href="index.php?page=home" class="nav-item nav-home flex items-center px-4 py-3 text-gray-300 font-medium rounded-lg hover:bg-gray-700/50 hover:text-yellow-400 transition-all duration-300 group">
      <span class="mr-3 text-lg text-gray-500 group-hover:text-yellow-400 transition-colors duration-300"><i class="fa fa-home"></i></span> 
      <span style="font-family: 'Montserrat', sans-serif;">Home</span>
    </a>
    
    <a href="index.php?page=orders" class="nav-item nav-orders flex items-center px-4 py-3 text-gray-300 font-medium rounded-lg hover:bg-gray-700/50 hover:text-yellow-400 transition-all duration-300 group">
      <span class="mr-3 text-lg text-gray-500 group-hover:text-yellow-400 transition-colors duration-300"><i class="fa fa-list"></i></span> 
      <span style="font-family: 'Montserrat', sans-serif;">Orders</span>
    </a>
    
    <a href="index.php?page=messages" class="nav-item nav-messages flex items-center px-4 py-3 text-gray-300 font-medium rounded-lg hover:bg-gray-700/50 hover:text-yellow-400 transition-all duration-300 group">
      <span class="mr-3 text-lg text-gray-500 group-hover:text-yellow-400 transition-colors duration-300"><i class="fa fa-comments"></i></span> 
      <span style="font-family: 'Montserrat', sans-serif;">Messages</span>
    </a>
    
    <a href="index.php?page=menu" class="nav-item nav-menu flex items-center px-4 py-3 text-gray-300 font-medium rounded-lg hover:bg-gray-700/50 hover:text-yellow-400 transition-all duration-300 group">
      <span class="mr-3 text-lg text-gray-500 group-hover:text-yellow-400 transition-colors duration-300"><i class="fa fa-list"></i></span> 
      <span style="font-family: 'Montserrat', sans-serif;">Menu</span>
    </a>
    
    <a href="index.php?page=categories" class="nav-item nav-categories flex items-center px-4 py-3 text-gray-300 font-medium rounded-lg hover:bg-gray-700/50 hover:text-yellow-400 transition-all duration-300 group">
      <span class="mr-3 text-lg text-gray-500 group-hover:text-yellow-400 transition-colors duration-300"><i class="fa fa-list"></i></span> 
      <span style="font-family: 'Montserrat', sans-serif;">Category List</span>
    </a>
    
    <?php if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == 1): ?>
      <a href="index.php?page=users" class="nav-item nav-users flex items-center px-4 py-3 text-gray-300 font-medium rounded-lg hover:bg-gray-700/50 hover:text-yellow-400 transition-all duration-300 group">
        <span class="mr-3 text-lg text-gray-500 group-hover:text-yellow-400 transition-colors duration-300"><i class="fa fa-users"></i></span> 
        <span style="font-family: 'Montserrat', sans-serif;">Users</span>
      </a>
      
      <a href="index.php?page=site_settings" class="nav-item nav-site_settings flex items-center px-4 py-3 text-gray-300 font-medium rounded-lg hover:bg-gray-700/50 hover:text-yellow-400 transition-all duration-300 group">
        <span class="mr-3 text-lg text-gray-500 group-hover:text-yellow-400 transition-colors duration-300"><i class="fa fa-cogs"></i></span> 
        <span style="font-family: 'Montserrat', sans-serif;">Site Settings</span>
      </a>
    <?php endif; ?>
  </div>
  <div class="mb-6 px-4">
    <a href="ajax.php?action=logout"
       class="w-full flex items-center justify-center px-4 py-3 bg-gray-700/50 border border-yellow-600/50 text-yellow-400 rounded-lg hover:bg-yellow-600 hover:text-gray-900 hover:border-yellow-600 transition-all duration-300 text-sm font-semibold shadow-lg"
       style="font-family: 'Montserrat', sans-serif;">
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
        // <CHANGE> Updated active state with premium gold styling
        active.classList.add('bg-yellow-600/20', 'text-yellow-400', 'border-l-4', 'border-yellow-500', 'shadow-lg');
        active.classList.remove('text-gray-300');
      }
    }
  });
</script>