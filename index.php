<?php
session_start();
require 'admin/db_connect.php';

$user_firstname = 'User';
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $result = $conn->query("SELECT first_name FROM user_info WHERE user_id = $uid LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
        $user_firstname = htmlspecialchars($row['first_name']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BENTA BALIJU - Favorite Cuisines</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
      background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
      min-height: 100vh;
    }
    #categoryModal {
      z-index: 50;
      background: rgba(30,41,59,0.45);
      backdrop-filter: blur(4px);
      transition: background 0.3s;
    }
    #categoryModal .swiper-container {
      max-height: 400px;
      overflow: hidden;
    }
    #categoryModal .swiper-slide img {
      max-height: 300px;
      object-fit: contain;
      margin: auto;
    }
    #categoryGrid img {
      max-height: 200px;
      object-fit: cover;
      border-radius: 2.5rem 2.5rem 0 0;
      border-bottom: 4px solid #38bdf8;
      box-shadow: 0 8px 32px 0 rgba(56,189,248,0.15);
      transition: transform 0.4s cubic-bezier(.4,2,.6,1), box-shadow 0.3s, border-radius 0.3s, filter 0.3s;
      background: linear-gradient(120deg, #f0f9ff 60%, #e0e7ef 100%);
      position: relative;
      z-index: 1;
      border: 2px solid #e0e7ef;
      filter: brightness(0.97) saturate(1.1);
    }
    #categoryGrid .bg-white:hover img {
      transform: scale(1.07);
      box-shadow: 0 8px 24px 0 rgba(34,197,94,0.15);
    }
    .fade-in {
      opacity: 0;
      transition: opacity 0.3s ease-in-out;
    }
    .fade-in.show {
      opacity: 1;
    }
    .category-card {
      border-radius: 2rem;
      box-shadow: 0 4px 24px 0 rgba(56,189,248,0.10);
      background: linear-gradient(120deg, #fff 80%, #e0f2fe 100%);
      overflow: hidden;
      position: relative;
      transition: box-shadow 0.3s, transform 0.3s;
    }
    .category-card:hover {
      box-shadow: 0 12px 36px 0 rgba(34,197,94,0.18);
      transform: translateY(-6px) scale(1.04);
      border: 1.5px solid #38bdf8;
    }
    .category-card:hover img {
      transform: scale(1.09) rotate(-2deg);
      box-shadow: 0 16px 48px 0 rgba(34,197,94,0.20);
      border-radius: 3rem 3rem 0 0;
      border-bottom: 4px solid #2563eb;
      filter: brightness(1.03) saturate(1.2);
    }
    .category-card .img-overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      border-radius: 2.5rem 2.5rem 0 0;
      background: linear-gradient(180deg,rgba(56,189,248,0.10) 0%,rgba(34,197,94,0.08) 100%);
      z-index: 2;
      pointer-events: none;
      transition: background 0.3s;
    }
    .category-card:hover .img-overlay {
      background: linear-gradient(180deg,rgba(56,189,248,0.18) 0%,rgba(34,197,94,0.13) 100%);
    }
    .category-card .category-desc {
      font-size: 0.98rem;
      color: #64748b;
      margin-top: 0.5rem;
      min-height: 2.2em;
    }
    .category-card .badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: rgba(56,189,248,0.9);
      color: #fff;
      font-size: 0.85rem;
      font-weight: 600;
      padding: 0.3rem 0.9rem;
      border-radius: 9999px;
      box-shadow: 0 2px 8px 0 rgba(56,189,248,0.08);
      z-index: 3;
      letter-spacing: 0.03em;
    }
    .modal-content {
      border-radius: 1rem;
      box-shadow: 0 8px 32px 0 rgba(34,197,94,0.12);
    }
    .btn-main {
      background: linear-gradient(90deg, #2563eb 0%, #38bdf8 100%);
      color: #fff;
      font-weight: 600;
      border-radius: 8px;
      transition: background 0.2s, box-shadow 0.2s;
      box-shadow: 0 2px 8px 0 rgba(56,189,248,0.08);
    }
    .btn-main:hover {
      background: linear-gradient(90deg, #1d4ed8 0%, #0ea5e9 100%);
      box-shadow: 0 4px 16px 0 rgba(56,189,248,0.16);
    }
    .btn-outline-main {
      border: 2px solid #38bdf8;
      color: #2563eb;
      background: #fff;
      font-weight: 600;
      border-radius: 8px;
      transition: background 0.2s, color 0.2s;
    }
    .btn-outline-main:hover {
      background: #38bdf8;
      color: #fff;
    }
    #categoryModal .swiper-wrapper {
      scrollbar-width: thin;
      scrollbar-color: #38bdf8 #e0e7ef;
    }
    #categoryModal .swiper-wrapper::-webkit-scrollbar {
      width: 8px;
      background: #e0e7ef;
    }
    #categoryModal .swiper-wrapper::-webkit-scrollbar-thumb {
      background: #38bdf8;
      border-radius: 8px;
    }
    #categoryModal .modal-content {
      background: rgba(255,255,255,0.95);
      border-radius: 2rem;
      box-shadow: 0 16px 48px 0 rgba(34,197,94,0.18), 0 2px 8px 0 rgba(56,189,248,0.08);
      border: 1.5px solid #bae6fd;
      animation: modalPop .4s cubic-bezier(.4,2,.6,1) both;
      overflow: hidden;
    }
    @keyframes modalPop {
      0% { transform: scale(0.93) translateY(60px); opacity: 0; }
      100% { transform: scale(1) translateY(0); opacity: 1; }
    }
    #categoryModal .swiper-container {
      padding-bottom: 2.5rem;
    }
    #categoryModal .swiper-slide {
      background: #fff;
      border-radius: 1.5rem;
      box-shadow: 0 2px 12px 0 rgba(56,189,248,0.10);
      padding: 1.5rem;
      margin: 0 0.5rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: box-shadow 0.2s;
    }
    #categoryModal .swiper-slide:hover {
      box-shadow: 0 8px 32px 0 rgba(34,197,94,0.14);
    }
    #categoryModal .swiper-slide img {
      border-radius: 1.5rem;
      margin-bottom: 1rem;
      box-shadow: 0 4px 16px 0 rgba(56,189,248,0.10);
      max-height: 180px;
      object-fit: contain;
      background: #f1f5f9;
      padding: 0.5rem;
      border: 2px solid #bae6fd;
      transition: box-shadow 0.2s, border-color 0.2s;
    }
    #categoryModal .swiper-slide:hover img {
      box-shadow: 0 8px 32px 0 rgba(34,197,94,0.14);
      border-color: #38bdf8;
    }
    #categoryModal .product-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: #2563eb;
      margin-bottom: 0.5rem;
      text-align: center;
    }
    #categoryModal .product-desc {
      font-size: 1rem;
      color: #64748b;
      margin-bottom: 1rem;
      text-align: center;
      min-height: 2.5rem;
    }
    #categoryModal .product-price {
      font-size: 1.1rem;
      font-weight: 600;
      color: #16a34a;
      margin-bottom: 1rem;
      text-align: center;
    }
    #categoryModal .btn-main {
      width: 100%;
      margin-top: 0.5rem;
      font-size: 1rem;
      padding: 0.75rem 0;
    }
    #categoryModal .swiper-button-next,
    #categoryModal .swiper-button-prev {
      color: #2563eb;
      top: 55%;
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      background: #e0e7ef;
      box-shadow: 0 2px 8px 0 rgba(56,189,248,0.08);
      transition: background 0.2s;
    }
    #categoryModal .swiper-button-next:hover,
    #categoryModal .swiper-button-prev:hover {
      background: #bae6fd;
    }
    #categoryModal .swiper-pagination-bullet {
      background: #2563eb;
      opacity: 0.7;
    }
    #categoryModal .swiper-pagination-bullet-active {
      background: #38bdf8;
      opacity: 1;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    .animate-spin {
      display: inline-block;
      animation: spin 1s linear infinite;
    }
  </style>
</head>
<body class="bg-gray-100">
  <section class="relative h-[400px] w-full overflow-hidden">
    <img src="assets/img/Main.jpg" alt="Delicious Dish" class="w-full h-full object-cover">
    <header class="absolute top-0 left-0 w-full z-20 p-6 flex justify-between items-center bg-gradient-to-b from-black/60 to-transparent rounded-b-lg shadow-lg">
      <div class="text-white text-3xl font-bold drop-shadow-lg">
        <span class="font-semibold">Programmers Guild</span>
      </div>
      <div class="flex items-center space-x-6">
        <a href="cart_list.php" class="relative">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white hover:text-blue-200 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.6 8M17 13l1.6 8M9 21h6" />
          </svg>
          <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1 shadow">0</span>
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="relative group">
            <button id="userDropdownBtn" type="button" class="flex items-center gap-2 text-white font-semibold px-4 py-2 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 shadow-lg hover:from-blue-700 hover:to-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
              <svg class="w-8 h-8 rounded-full bg-white text-blue-600 p-1 shadow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="#e0e7ef"/>
                <text x="12" y="16" text-anchor="middle" font-size="10" fill="#2563eb" font-family="Arial" dy=".3em"><?php echo strtoupper(substr($user_firstname,0,1)); ?></text>
              </svg>
              <span>Hi, <?php echo htmlspecialchars($user_firstname); ?></span>
              <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div id="userDropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 z-30 border border-blue-100 hidden transition-all duration-200">
              <div class="px-4 py-2 border-b border-gray-100 text-gray-700 font-semibold text-sm">
                <span class="block">Signed in as</span>
                <span class="block truncate text-blue-700"><?php echo htmlspecialchars($user_firstname); ?></span>
              </div>
              <a href="profile.php" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-blue-50 transition">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Profile
              </a>
              <a href="logout.php" class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 transition">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
                </svg>
                Logout
              </a>
            </div>
          </div>
        <?php else: ?>
          <a href="login.php"
             class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-400 text-white px-6 py-2 rounded-full font-semibold shadow-lg hover:from-blue-600 hover:to-blue-500 hover:scale-105 transition-all duration-200 border-2 border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
            </svg>
            Log in
          </a>
          <a href="register.php"
             class="flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-400 text-white px-6 py-2 rounded-full font-semibold shadow-lg hover:from-green-600 hover:to-green-500 hover:scale-105 transition-all duration-200 border-2 border-green-400 focus:outline-none focus:ring-2 focus:ring-green-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Sign up
          </a>
        <?php endif; ?>
      </div>
    </header>
  </section>

  <main class="container mx-auto px-4 py-8">
    <div class="container-box p-6 mx-auto bg-white rounded-lg shadow-md">
      <section class="mb-8">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">Favorite Cuisines</h2>
        </div>
        <div id="categoryGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-8">
          <?php
          $categories = $conn->query("SELECT * FROM category_list");
          while ($category = $categories->fetch_assoc()):
            $cat_id = $category['id'];
            $prod_count = $conn->query("SELECT COUNT(*) FROM product_list WHERE category_id = $cat_id")->fetch_row()[0];
          ?>
          <div class="category-card bg-white hover:shadow-2xl transition-transform transform hover:scale-105 overflow-hidden text-center relative">
            <div class="relative">
              <img src="assets/img/<?php echo $category['img_path']; ?>" alt="<?php echo $category['name']; ?>" class="w-full h-48 object-cover">
              <div class="img-overlay"></div>
              <span class="badge"><?php echo $prod_count; ?> items</span>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-semibold text-gray-800 hover:text-blue-600 transition-colors"><?php echo $category['name']; ?></h3>
              <div class="category-desc">
                <?php echo isset($category['description']) ? htmlspecialchars($category['description']) : 'Explore delicious options!'; ?>
              </div>
              <button onclick="viewCategory(<?php echo $category['id']; ?>)" class="btn-main px-4 py-2 mt-4">View</button>
            </div>
          </div>
          <?php endwhile; ?>
        </div>
      </section>
    </div>
  </main>

  <!-- Modal -->
  <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden fade-in transition-all duration-300">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-0 relative overflow-hidden border border-blue-200">
      <div class="flex justify-between items-center p-6 border-b border-blue-100 bg-gradient-to-r from-blue-50 to-white">
        <h3 class="text-2xl font-bold text-blue-700 tracking-tight">Options</h3>
        <button id="closeCategoryModal" class="text-gray-400 hover:text-blue-600 transition-colors rounded-full focus:outline-none focus:ring-2 focus:ring-blue-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <circle cx="12" cy="12" r="11" stroke="#e0e7ef" stroke-width="2" fill="#f8fafc"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" stroke="#2563eb"/>
          </svg>
        </button>
      </div>
      <div id="modalCategoryContent" class="flex flex-col px-8 pb-8">
        <h3 class="text-3xl font-extrabold text-blue-700 mb-6 text-center tracking-tight drop-shadow">Available Foods</h3>
        <div class="swiper-container">
          <div class="swiper-wrapper" id="categoryProducts">
            <!-- Products will be dynamically loaded here -->
          </div>
          <div class="swiper-pagination mt-4"></div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function viewCategory(categoryId) {
      $.ajax({
        url: 'admin/ajax.php?action=get_products_by_category',
        method: 'POST',
        data: { category_id: categoryId },
        success: function(response) {
          $('#categoryProducts').html(response); 
          $('#categoryModal').removeClass('hidden').addClass('show');  
          
          new Swiper('.swiper-container', {
            loop: true,
            navigation: {
              nextEl: '.swiper-button-next',
              prevEl: '.swiper-button-prev',
            },
            pagination: {
              el: '.swiper-pagination',
              clickable: true,
            },
          });
        },
        error: function() {
          alert('An error occurred while loading the products.');
        }
      });
    }

    document.getElementById('closeCategoryModal').addEventListener('click', function() {
      $('#categoryModal').removeClass('show').addClass('hidden');  
    });

    function updateCartCount() {
      $.ajax({
        url: 'admin/ajax.php?action=get_cart_count',
        method: 'GET',
        success: function (response) {
          $('#cartCount').text(response); 
        },
        error: function () {
          alert('An error occurred while updating the cart count.');
        },
      });
    }

    function addToCart(productId, btn = null) {
      let qtyInput = document.getElementById(`qty_${productId}`);
      const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

      if (btn) {
        btn.disabled = true;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="animate-spin mr-2">&#9696;</span>Adding...';

        $.ajax({
          url: 'admin/ajax.php?action=add_to_cart',
          method: 'POST',
          data: { pid: productId, qty: qty },
          success: function (response) {
            const res = JSON.parse(response);
            if (res.status === 1) {
              btn.innerHTML = '<span class="text-green-600">&#10003;</span> Added!';
              updateCartCount();
              setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
              }, 1200);
            } else {
              btn.innerHTML = originalText;
              btn.disabled = false;
              alert(res.message);
              window.location.href = 'login.php';
            }
          },
          error: function () {
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('An error occurred while adding the product to the cart.');
          },
        });
      } else {
        // fallback if no button passed
        $.ajax({
          url: 'admin/ajax.php?action=add_to_cart',
          method: 'POST',
          data: { pid: productId, qty: qty },
          success: function (response) {
            const res = JSON.parse(response);
            if (res.status === 1) {
              alert(res.message);
              updateCartCount();
            } else {
              alert(res.message);
              window.location.href = 'login.php';
            }
          },
          error: function () {
            alert('An error occurred while adding the product to the cart.');
          },
        });
      }
    }

    $(document).ready(function () {
      updateCartCount();
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const btn = document.getElementById('userDropdownBtn');
      const menu = document.getElementById('userDropdownMenu');
      if(btn && menu) {
        btn.addEventListener('click', function(e) {
          e.stopPropagation();
          menu.classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
          menu.classList.add('hidden');
        });
      }
    });
  </script>
</body>
</html>
