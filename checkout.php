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
  <title>Checkout - Premium Restaurant</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --gold: #c9a961;
      --gold-dark: #b8954d;
      --gold-light: #d4af37;
      --charcoal: #1a1a1a;
      --charcoal-light: #2d2d2d;
      --cream: #f5f3ef;
      --cream-light: #faf8f5;
      --brown: #8b6f47;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(135deg, var(--cream) 0%, var(--cream-light) 100%);
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: 
        radial-gradient(circle at 20% 50%, rgba(201, 169, 97, 0.03) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(201, 169, 97, 0.03) 0%, transparent 50%);
      pointer-events: none;
      z-index: 0;
    }

    h1, h2, h3 {
      font-family: 'Cormorant Garamond', serif;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .checkout-container {
      position: relative;
      z-index: 1;
      padding: 2rem 1rem;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .checkout-card {
      background: white;
      border-radius: 1rem;
      box-shadow: 
        0 10px 40px rgba(0, 0, 0, 0.08),
        0 2px 8px rgba(0, 0, 0, 0.04);
      max-width: 600px;
      width: 100%;
      overflow: hidden;
      border: 1px solid rgba(201, 169, 97, 0.1);
      transition: all 0.3s ease;
    }

    .checkout-card:hover {
      box-shadow: 
        0 15px 50px rgba(0, 0, 0, 0.12),
        0 5px 15px rgba(0, 0, 0, 0.06);
    }

    .checkout-header {
      background: linear-gradient(135deg, var(--charcoal) 0%, var(--charcoal-light) 100%);
      color: white;
      padding: 3rem 2rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .checkout-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, var(--gold), transparent);
    }

    .checkout-header h1 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
      color: white;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .checkout-header p {
      color: var(--gold);
      font-size: 1rem;
      letter-spacing: 1px;
    }

    .decorative-divider {
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 1.5rem 0;
      gap: 1rem;
    }

    .decorative-divider::before,
    .decorative-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(to right, transparent, var(--gold), transparent);
    }

    .decorative-divider svg {
      width: 24px;
      height: 24px;
      fill: var(--gold);
      opacity: 0.6;
    }

    .checkout-body {
      padding: 2.5rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      display: block;
      font-weight: 600;
      color: var(--charcoal);
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .form-input,
    .form-textarea {
      width: 100%;
      padding: 0.875rem 1rem;
      border: 2px solid #e5e7eb;
      border-radius: 0.5rem;
      font-family: 'Montserrat', sans-serif;
      font-size: 1rem;
      color: var(--charcoal);
      background: white;
      transition: all 0.3s ease;
    }

    .form-input:focus,
    .form-textarea:focus {
      outline: none;
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.1);
    }

    .form-input[readonly] {
      background: var(--cream-light);
      cursor: not-allowed;
    }

    .form-textarea {
      resize: vertical;
      min-height: 100px;
    }

    .btn-primary {
      width: 100%;
      padding: 1rem;
      background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
      color: white;
      font-weight: 600;
      font-size: 1rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      border: none;
      border-radius: 0.5rem;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(201, 169, 97, 0.3);
      position: relative;
      overflow: hidden;
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s ease;
    }

    .btn-primary:hover::before {
      left: 100%;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(201, 169, 97, 0.4);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .btn-primary:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .btn-secondary {
      display: block;
      width: 100%;
      padding: 1rem;
      margin-top: 1rem;
      background: white;
      color: var(--charcoal);
      font-weight: 600;
      font-size: 0.9rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      text-align: center;
      text-decoration: none;
      border: 2px solid var(--gold);
      border-radius: 0.5rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-secondary:hover {
      background: var(--gold);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(201, 169, 97, 0.3);
    }

    /* Modal Styles */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(4px);
      z-index: 1000;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .modal-overlay.show {
      opacity: 1;
    }

    .modal-content {
      background: white;
      border-radius: 1rem;
      padding: 2.5rem;
      max-width: 450px;
      width: 100%;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      text-align: center;
      border: 2px solid var(--gold);
      transform: scale(0.9);
      transition: transform 0.3s ease;
    }

    .modal-overlay.show .modal-content {
      transform: scale(1);
    }

    .modal-content h2 {
      font-size: 2rem;
      color: var(--charcoal);
      margin-bottom: 1rem;
    }

    .modal-content p {
      color: #6b7280;
      font-size: 1rem;
      margin-bottom: 2rem;
      line-height: 1.6;
    }

    .modal-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
    }

    .modal-btn {
      padding: 0.875rem 2rem;
      border: none;
      border-radius: 0.5rem;
      font-weight: 600;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      cursor: pointer;
      transition: all 0.3s ease;
      min-width: 120px;
    }

    .modal-btn-confirm {
      background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(201, 169, 97, 0.3);
    }

    .modal-btn-confirm:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(201, 169, 97, 0.4);
    }

    .modal-btn-cancel {
      background: #e5e7eb;
      color: var(--charcoal);
    }

    .modal-btn-cancel:hover {
      background: #d1d5db;
      transform: translateY(-2px);
    }

    /* Responsive Styles */
    @media (max-width: 640px) {
      .checkout-header h1 {
        font-size: 2rem;
      }

      .checkout-header p {
        font-size: 0.9rem;
      }

      .checkout-body {
        padding: 1.5rem;
      }

      .form-group {
        margin-bottom: 1.25rem;
      }

      .modal-content {
        padding: 2rem 1.5rem;
      }

      .modal-content h2 {
        font-size: 1.5rem;
      }

      .modal-buttons {
        flex-direction: column;
      }

      .modal-btn {
        width: 100%;
      }
    }

    @media (max-width: 480px) {
      .checkout-container {
        padding: 1rem 0.5rem;
      }

      .checkout-header {
        padding: 2rem 1.5rem;
      }

      .checkout-header h1 {
        font-size: 1.75rem;
      }

      .checkout-body {
        padding: 1.25rem;
      }

      .form-input,
      .form-textarea {
        padding: 0.75rem;
        font-size: 0.95rem;
      }

      .btn-primary,
      .btn-secondary {
        padding: 0.875rem;
        font-size: 0.9rem;
      }
    }

    @media (min-width: 641px) and (max-width: 768px) {
      .checkout-header {
        padding: 2.5rem 2rem;
      }

      .checkout-body {
        padding: 2rem;
      }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
      .checkout-card {
        max-width: 650px;
      }
    }

    /* Landscape orientation for mobile */
    @media (max-height: 600px) and (orientation: landscape) {
      .checkout-container {
        padding: 1rem;
      }

      .checkout-header {
        padding: 1.5rem;
      }

      .checkout-header h1 {
        font-size: 1.75rem;
      }

      .checkout-body {
        padding: 1.5rem;
      }

      .form-group {
        margin-bottom: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="checkout-container">
    <div class="checkout-card">
      <div class="checkout-header">
        <h1>Checkout</h1>
        <p>Confirm Your Delivery Information</p>
      </div>

      <div class="checkout-body">
        <div class="decorative-divider">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C11.5 2 11 2.19 10.59 2.59L2.59 10.59C1.8 11.37 1.8 12.63 2.59 13.41L10.59 21.41C11.37 22.2 12.63 22.2 13.41 21.41L21.41 13.41C22.2 12.63 22.2 11.37 21.41 10.59L13.41 2.59C13 2.19 12.5 2 12 2M12 4L20 12L12 20L4 12L12 4M12 7C9.24 7 7 9.24 7 12S9.24 17 12 17 17 14.76 17 12 14.76 7 12 7M12 9C13.66 9 15 10.34 15 12S13.66 15 12 15 9 13.66 9 12 10.34 9 12 9Z"/>
          </svg>
        </div>

        <form action="" id="checkout-frm">
          <div class="form-group">
            <label class="form-label">Firstname</label>
            <input type="text" name="first_name" required readonly
              class="form-input"
              value="<?php echo htmlspecialchars($first_name); ?>">
          </div>

          <div class="form-group">
            <label class="form-label">Lastname</label>
            <input type="text" name="last_name" required readonly
              class="form-input"
              value="<?php echo htmlspecialchars($last_name); ?>">
          </div>

          <div class="form-group">
            <label class="form-label">Contact</label>
            <input type="text" name="mobile" required readonly
              class="form-input"
              value="<?php echo htmlspecialchars($mobile); ?>">
          </div>

          <div class="form-group">
            <label class="form-label">Delivery Address</label>
            <textarea name="address" required class="form-textarea"><?php echo htmlspecialchars($address); ?></textarea>
          </div>

          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" required readonly
              class="form-input"
              value="<?php echo htmlspecialchars($email); ?>">
          </div>

          <div class="decorative-divider">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2C11.5 2 11 2.19 10.59 2.59L2.59 10.59C1.8 11.37 1.8 12.63 2.59 13.41L10.59 21.41C11.37 22.2 12.63 22.2 13.41 21.41L21.41 13.41C22.2 12.63 22.2 11.37 21.41 10.59L13.41 2.59C13 2.19 12.5 2 12 2M12 4L20 12L12 20L4 12L12 4M12 7C9.24 7 7 9.24 7 12S9.24 17 12 17 17 14.76 17 12 14.76 7 12 7M12 9C13.66 9 15 10.34 15 12S13.66 15 12 15 9 13.66 9 12 10.34 9 12 9Z"/>
            </svg>
          </div>

          <button class="btn-primary" type="submit">
            Place Order
          </button>

          <a href="cart_list.php" class="btn-secondary">
            Back to Cart
          </a>
        </form>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div id="orderConfirmModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
      <h2>Confirm Order</h2>
      <p>Are you sure you want to place this order? Please review your delivery information before confirming.</p>
      <div class="modal-buttons">
        <button id="confirmOrderBtn" class="modal-btn modal-btn-confirm">Yes, Place Order</button>
        <button id="cancelOrderBtn" class="modal-btn modal-btn-cancel">Cancel</button>
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
        const modal = $('#orderConfirmModal');
        modal.css('display', 'flex');
        setTimeout(() => modal.addClass('show'), 10);
      });

      $('#cancelOrderBtn').on('click', function(){
        const modal = $('#orderConfirmModal');
        modal.removeClass('show');
        setTimeout(() => modal.css('display', 'none'), 300);
        formToSubmit = null;
      });

      $('#confirmOrderBtn').on('click', function(){
        if(!formToSubmit) return;
        var $btn = $(formToSubmit).find('button[type="submit"]');
        $btn.prop('disabled', true).text('Processing...');
        const modal = $('#orderConfirmModal');
        modal.removeClass('show');
        setTimeout(() => modal.css('display', 'none'), 300);
        
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
