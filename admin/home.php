<?php require_once('./db_connect.php'); ?>
<style>
    .custom-menu {
        z-index: 1000;
        position: absolute;
        background-color: #ffffff;
        border: 1px solid #0000001c;
        border-radius: 5px;
        padding: 8px;
        min-width: 13vw;
}
a.custom-menu-list {
    width: 100%;
    display: flex;
    color: #4c4b4b;
    font-weight: 600;
    font-size: 1em;
    padding: 1px 11px;
}
    span.card-icon {
    position: absolute;
    font-size: 3em;
    bottom: .2em;
    color: #ffffff80;
}
.file-item{
    cursor: pointer;
}
a.custom-menu-list:hover,.file-item:hover,.file-item.active {
    background: #80808024;
}
table th,td{
    border-left:1px solid gray;
}
a.custom-menu-list span.icon{
        width:1em;
        margin-right: 5px
}
.candidate {
    margin: auto;
    width: 23vw;
    padding: 0 10px;
    border-radius: 20px;
    margin-bottom: 1em;
    display: flex;
    border: 3px solid #00000008;
    background: #8080801a;

}
.candidate_name {
    margin: 8px;
    margin-left: 3.4em;
    margin-right: 3em;
    width: 100%;
}
    .img-field {
        display: flex;
        height: 8vh;
        width: 4.3vw;
        padding: .3em;
        background: #80808047;
        border-radius: 50%;
        position: absolute;
        left: -.7em;
        top: -.7em;
    }
    
    .candidate img {
    height: 100%;
    width: 100%;
    margin: auto;
    border-radius: 50%;
}
.vote-field {
    position: absolute;
    right: 0;
    bottom: -.4em;
}
</style>

<div class="min-h-screen bg-gray-50 md:pl-64 pt-10">
  <div class="mb-8 mt-8 max-w-7xl mx-auto px-4">
    <h3 class="text-2xl font-bold text-gray-700">Welcome back, <?php echo !empty($_SESSION['login_name']) ? $_SESSION['login_name'] : 'Admin'; ?>!</h3>
  </div>

  <?php if (isset($_GET['status'])): ?>
    <div class="mb-4 max-w-7xl mx-auto px-4">
      <span class="inline-block px-4 py-2 rounded bg-green-100 text-green-700 font-semibold">
        Showing <?php echo $_GET['status'] == '1' ? 'Active' : 'Inactive'; ?> Menus
      </span>
      <a href="menu.php" class="ml-4 text-blue-600 underline">Show All</a>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-7xl mx-auto px-4">
    <!-- Total Active Menu Card -->
    <a href="menu.php?status=1" class="block bg-white rounded-2xl shadow p-6 relative overflow-hidden hover:ring-2 hover:ring-green-300 transition">
      <div class="absolute right-4 top-4 text-green-200 text-4xl pointer-events-none"><i class="fas fa-utensils"></i></div>
      <h5 class="text-gray-500 font-semibold mb-2">Total Active Menu</h5>
      <h2 class="text-3xl font-bold text-right text-green-600">
        <?php echo number_format($conn->query("SELECT * FROM `product_list` WHERE `status` = 1")->num_rows); ?>
      </h2>
    </a>

    <!-- Total Inactive Menu Card -->
    <a href="menu.php?status=0" class="block bg-white rounded-2xl shadow p-6 relative overflow-hidden hover:ring-2 hover:ring-gray-400 transition">
      <div class="absolute right-4 top-4 text-gray-300 text-4xl pointer-events-none"><i class="fas fa-utensils"></i></div>
      <h5 class="text-gray-500 font-semibold mb-2">Total Inactive Menu</h5>
      <h2 class="text-3xl font-bold text-right text-gray-500">
        <?php
          $inactive = $conn->query("SELECT COUNT(*) AS total FROM `product_list` WHERE `status` = 0")->fetch_assoc()['total'];
          echo number_format($inactive);
        ?>
      </h2>
    </a>

    <!-- Orders for Verification -->
    <div class="bg-white rounded-2xl shadow p-6 relative overflow-hidden">
      <div class="absolute right-4 top-4 text-yellow-200 text-4xl pointer-events-none"><i class="fas fa-clipboard-list"></i></div>
      <h5 class="text-gray-500 font-semibold mb-2">Orders for Verification</h5>
      <h2 class="text-3xl font-bold text-right text-yellow-600"><?php echo number_format($conn->query("SELECT COUNT(*) AS total FROM `orders` WHERE `status` = 0")->fetch_assoc()['total']); ?></h2>
    </div>

    <!-- Confirmed Orders -->
    <div class="bg-white rounded-2xl shadow p-6 relative overflow-hidden">
      <div class="absolute right-4 top-4 text-blue-200 text-4xl pointer-events-none"><i class="fas fa-check-circle"></i></div>
      <h5 class="text-gray-500 font-semibold mb-2">Confirmed Orders</h5>
      <h2 class="text-3xl font-bold text-right text-blue-600"><?php echo number_format($conn->query("SELECT COUNT(*) AS total FROM `orders` WHERE `status` = 1")->fetch_assoc()['total']); ?></h2>
    </div>

    <!-- In Delivery Orders -->
    <div class="bg-white rounded-2xl shadow p-6 relative overflow-hidden">
      <div class="absolute right-4 top-4 text-indigo-200 text-4xl pointer-events-none"><i class="fas fa-truck"></i></div>
      <h5 class="text-gray-500 font-semibold mb-2">In Delivery</h5>
      <h2 class="text-3xl font-bold text-right text-indigo-600"><?php echo number_format($conn->query("SELECT COUNT(*) AS total FROM `orders` WHERE `status` = 2")->fetch_assoc()['total']); ?></h2>
    </div>

    <!-- Delivered Orders -->
    <div class="bg-white rounded-2xl shadow p-6 relative overflow-hidden">
      <div class="absolute right-4 top-4 text-gray-200 text-4xl pointer-events-none"><i class="fas fa-box"></i></div>
      <h5 class="text-gray-500 font-semibold mb-2">Delivered Orders</h5>
      <h2 class="text-3xl font-bold text-right text-gray-600"><?php echo number_format($conn->query("SELECT COUNT(*) AS total FROM `orders` WHERE `status` = 3")->fetch_assoc()['total']); ?></h2>
    </div>
  </div>
</div>

<?php $conn->close(); ?>
