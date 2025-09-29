<?php
session_start();
include 'admin/db_connect.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    echo "<script>alert('You must be logged in to checkout.'); location.replace('login.php')</script>";
    exit;
}

$chk = $conn->query("SELECT * FROM cart WHERE user_id = $user_id")->num_rows;
if($chk <= 0){
    echo "<script>alert('You don\'t have an Item in your cart yet.'); location.replace('./')</script>";
    exit;
}

$user_info = $conn->query("SELECT * FROM user_info WHERE user_id = $user_id")->fetch_assoc();
$first_name = isset($user_info['first_name']) ? $user_info['first_name'] : '';
$last_name = isset($user_info['last_name']) ? $user_info['last_name'] : '';
$mobile = isset($user_info['mobile']) ? $user_info['mobile'] : '';
$address = isset($user_info['address']) ? $user_info['address'] : '';
$email = isset($user_info['email']) ? $user_info['email'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
      min-height: 100vh;
    }
    .checkout-card {
      box-shadow: 0 10px 25px 0 rgba(0,0,0,0.08);
      border-radius: 1rem;
    }
    .checkout-header {
      background: linear-gradient(90deg, #2563eb 0%, #38bdf8 100%);
      color: #fff;
      border-radius: 1rem 1rem 0 0;
    }
    .form-label {
      font-weight: 600;
      color: #334155;
    }
  </style>
</head>
<body>
  <div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-xl checkout-card bg-white">
      <div class="checkout-header p-6 text-center">
        <h1 class="text-3xl font-bold mb-2">Checkout</h1>
        <p class="text-lg">Confirm your delivery information</p>
      </div>
      <div class="p-8">
        <form action="" id="checkout-frm" class="space-y-6">
          <div>
            <label class="form-label block mb-1">Firstname</label>
            <input type="text" name="first_name" required readonly
              class="form-input w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
              value="<?php echo htmlspecialchars($first_name); ?>">
          </div>
          <div>
            <label class="form-label block mb-1">Lastname</label>
            <input type="text" name="last_name" required readonly
              class="form-input w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
              value="<?php echo htmlspecialchars($last_name); ?>">
          </div>
          <div>
            <label class="form-label block mb-1">Contact</label>
            <input type="text" name="mobile" required readonly
              class="form-input w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
              value="<?php echo htmlspecialchars($mobile); ?>">
          </div>
          <div>
            <label class="form-label block mb-1">Address</label>
            <textarea cols="30" rows="3" name="address" required
              class="form-input w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"><?php echo htmlspecialchars($address); ?></textarea>
          </div>
          <div>
            <label class="form-label block mb-1">Email</label>
            <input type="email" name="email" required readonly
              class="form-input w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
              value="<?php echo htmlspecialchars($email); ?>">
          </div>
          <div class="text-center pt-4">
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded transition duration-200 shadow" type="submit">
              Place Order
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div id="orderConfirmModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl p-8 max-w-sm w-full shadow-2xl text-center">
      <h2 class="text-xl font-bold mb-4 text-gray-700">Confirm Order</h2>
      <p class="mb-6 text-gray-600">Are you sure you want to place this order?</p>
      <div class="flex justify-center gap-4">
        <button id="confirmOrderBtn" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Yes, Place Order</button>
        <button id="cancelOrderBtn" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 transition">Cancel</button>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function(){
      let formToSubmit = null;

      $('#checkout-frm').submit(function(e){
        e.preventDefault();
        formToSubmit = this;
        $('#orderConfirmModal').removeClass('hidden').addClass('flex');
      });

      $('#cancelOrderBtn').on('click', function(){
        $('#orderConfirmModal').addClass('hidden').removeClass('flex');
        formToSubmit = null;
      });

      $('#confirmOrderBtn').on('click', function(){
        if(!formToSubmit) return;
        var $btn = $(formToSubmit).find('button[type="submit"]');
        $btn.prop('disabled', true).text('Processing...');
        $('#orderConfirmModal').addClass('hidden').removeClass('flex');
        $.ajax({
          url: "admin/ajax.php?action=save_order",
          method: 'POST',
          data: $(formToSubmit).serialize(),
          success: function(resp){
            if(resp == 1){
              alert("Order successfully placed.");
              setTimeout(function(){
                window.location.href = 'index.php';
              }, 1500);
            } else {
              alert("There was a problem placing your order. Please try again.");
              $btn.prop('disabled', false).text('Place Order');
            }
          },
          error: function() {
            alert("A server error occurred. Please try again.");
            $btn.prop('disabled', false).text('Place Order');
          }
        });
      });
    });
  </script>
</body>
</html>