<?php
include 'admin/db_connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart - Programmers Guild</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #1e293b;
      --secondary: #475569;
      --accent: #d4af37;
      --success: #059669;
      --danger: #dc2626;
      --light: #f8fafc;
      --border: #e2e8f0;
      --black: #000000;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      min-height: 100vh;
      color: var(--primary);
    }

    h1, h5 {
      font-family: 'Playfair Display', serif;
    }

    .page-title {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--primary);
      letter-spacing: -0.5px;
      margin-bottom: 2rem;
      text-align: center;
    }

    .page-title i {
      color: var(--accent);
      margin-right: 0.75rem;
    }

    .cart-item-card {
      background: white;
      border-radius: 1rem;
      border: 1px solid var(--border);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      margin-bottom: 1.25rem;
      overflow: hidden;
    }

    .cart-item-card:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transform: translateY(-2px);
    }

    .cart-item-card .card-body {
      padding: 1.5rem;
    }

    .product-image {
      max-height: 120px;
      border-radius: 0.75rem;
      object-fit: cover;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .product-name {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--primary);
      margin-bottom: 0.5rem;
    }

    .product-name i {
      color: var(--accent);
      font-size: 1rem;
      margin-right: 0.5rem;
    }

    .product-description {
      font-size: 0.9rem;
      color: var(--secondary);
      margin-bottom: 0.75rem;
      line-height: 1.5;
    }

    .price-label {
      font-size: 0.9rem;
      color: var(--secondary);
      font-weight: 500;
    }

    .price-value {
      font-weight: 600;
      color: var(--black);
      font-size: 1.1rem;
    }

    .qty-controls {
      display: inline-flex;
      border: 1px solid var(--border);
      border-radius: 0.5rem;
      overflow: hidden;
      background: white;
    }

    .qty-controls button {
      border: none;
      background: white;
      color: var(--primary);
      padding: 0.5rem 1rem;
      cursor: pointer;
      transition: all 0.2s ease;
      font-weight: 500;
    }

    .qty-controls button:hover {
      background: var(--light);
      color: var(--success);
    }

    .qty-controls input {
      border: none;
      border-left: 1px solid var(--border);
      border-right: 1px solid var(--border);
      width: 60px;
      text-align: center;
      font-weight: 600;
      color: var(--primary);
    }

    .qty-controls input:focus {
      outline: none;
    }

    .btn-remove {
      background: white;
      border: 1px solid var(--danger);
      color: var(--danger);
      padding: 0.5rem 1.25rem;
      border-radius: 0.5rem;
      font-weight: 500;
      transition: all 0.3s ease;
      font-size: 0.9rem;
    }

    .btn-remove:hover {
      background: var(--danger);
      color: white;
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(220, 38, 38, 0.2);
    }

    .summary-card {
      background: white;
      border-radius: 1rem;
      border: 1px solid var(--border);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
      position: sticky;
      top: 2rem;
    }

    .summary-card .card-body {
      padding: 2rem;
    }

    .summary-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 1.5rem;
    }

    .summary-title i {
      color: var(--accent);
      margin-right: 0.5rem;
    }

    .summary-list {
      list-style: none;
      padding: 0;
      margin: 0;
      font-size: 0.95rem;
      color: var(--secondary);
      line-height: 1.8;
    }

    .summary-list li {
      padding: 0.5rem 0;
      border-bottom: 1px solid var(--border);
    }

    .summary-list li:last-child {
      border-bottom: none;
    }

    .summary-divider {
      border: none;
      border-top: 2px solid var(--border);
      margin: 1.5rem 0;
    }

    .total-amount {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--primary);
      text-align: right;
      margin: 1.5rem 0;
    }

    .total-amount span {
      color: var(--black);
    }

    .btn-checkout {
      background: linear-gradient(135deg, rgba(0, 0, 0, 1) 30%, rgba(139, 69, 19, 0.5) 100%);
      border: none;
      color: white;
      padding: 1rem;
      border-radius: 0.75rem;
      font-weight: 600;
      font-size: 1.1rem;
      width: 100%;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(26, 26, 26, 0.7);
    }

    .btn-checkout:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(26, 26, 26, 0.7);
    }

    .btn-checkout i {
      margin-right: 0.5rem;
    }

    .btn-back {
      background: white;
      border: 2px solid var(--primary);
      color: var(--primary);
      padding: 0.875rem 2rem;
      border-radius: 0.75rem;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn-back:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
    }

    .btn-back i {
      margin-right: 0.5rem;
    }

    .alert-custom {
      background: white;
      border: 1px solid var(--border);
      border-left: 4px solid var(--accent);
      border-radius: 0.75rem;
      padding: 1.5rem;
      text-align: center;
      color: var(--secondary);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .alert-custom a {
      color: var(--success);
      font-weight: 600;
      text-decoration: none;
    }

    .alert-custom a:hover {
      text-decoration: underline;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .page-title {
        font-size: 2rem;
      }

      .cart-item-card .card-body {
        padding: 1rem;
      }

      .product-image {
        max-height: 100px;
        margin-bottom: 1rem;
      }

      .summary-card {
        position: static;
        margin-top: 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h1 class="page-title">
      <i class="fas fa-shopping-cart"></i>Ordering Cart
    </h1>
    <div class="row g-4">
      <div class="col-lg-8">
        <?php
        $total = 0;
        $order_summary = '';
        if (isset($_SESSION['user_id'])) {
          $user_id = $_SESSION['user_id'];
          $cart_items = $conn->query("SELECT c.*, p.name, p.price, p.img_path, p.description FROM cart c JOIN product_list p ON c.product_id = p.id WHERE c.user_id = '$user_id'");
          if ($cart_items->num_rows > 0):
            while ($item = $cart_items->fetch_assoc()):
              $total += $item['qty'] * $item['price'];
              $order_summary .= '<li>' . $item['name'] . ' (' . $item['qty'] . 'x) - ' . $item['description'] . ' <span style="float:right;">₱' . number_format($item['qty'] * $item['price'], 2) . '</span></li>';
        ?>
        <div class="cart-item-card">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-3 text-center">
                <img src="assets/img/<?php echo $item['img_path']; ?>" alt="<?php echo $item['name']; ?>" class="img-fluid product-image">
              </div>
              <div class="col-md-5">
                <h5 class="product-name">
                  <i class="fas fa-utensils"></i><?php echo $item['name']; ?>
                </h5>
                <p class="product-description"><?php echo $item['description']; ?></p>
                <p class="price-label mb-3">Price: <span class="price-value">₱<?php echo number_format($item['price'], 2); ?></span></p>
                <div class="qty-controls">
                  <button class="qty-minus" data-id="<?php echo $item['id']; ?>"><i class="fas fa-minus"></i></button>
                  <input type="number" value="<?php echo $item['qty']; ?>" readonly>
                  <button class="qty-plus" data-id="<?php echo $item['id']; ?>"><i class="fas fa-plus"></i></button>
                </div>
              </div>
              <div class="col-md-4 text-end">
                <p class="price-label mb-3">Total: <span class="price-value" style="font-size: 1.25rem;">₱<?php echo number_format($item['qty'] * $item['price'], 2); ?></span></p>
                <button class="btn-remove remove-item" data-id="<?php echo $item['id']; ?>">
                  <i class="fas fa-trash-alt"></i> Remove
                </button>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; else: ?>
        <div class="alert-custom">Your cart is empty. <a href="index.php">Continue Ordering</a>.</div>
        <?php endif; ?>
        <?php } else { ?>
        <div class="alert-custom">Please <a href="login.php">log in</a> to view your cart.</div>
        <?php } ?>
      </div>
      <div class="col-lg-4">
        <div class="summary-card">
          <div class="card-body">
            <h5 class="summary-title"><i class="fas fa-receipt"></i>Order Summary</h5>
            <hr class="summary-divider">
            <ul class="summary-list">
              <?php echo $order_summary; ?>
              <li style="display: flex; justify-content: space-between; align-items: center;">
                <span><strong>Delivery Fee</strong></span>
                <span>₱5.00</span>
              </li>
            </ul>
            <hr class="summary-divider">
            <?php $grand_total = $total + 5; ?>
            <p class="total-amount">Total: <span>₱<?php echo number_format($grand_total, 2); ?></span></p>
            <button class="btn-checkout" id="checkout">
              <i class="fas fa-credit-card"></i>Proceed to Checkout
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-5">
      <a href="index.php" class="btn-back"><i class="fas fa-store"></i>Back to Store</a>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
  <script>
    $(document).ready(function() {
      updateCartCount();

      $('.qty-minus, .qty-plus').click(function() {
        const id = $(this).data('id');
        const isPlus = $(this).hasClass('qty-plus');
        const input = $(this).siblings('input');
        let qty = parseInt(input.val());

        qty = isPlus ? qty + 1 : qty - 1;
        if (qty < 1) return;

        input.val(qty);
        updateCartQty(id, qty);
      });

      $('.remove-item').click(function () {
        const id = $(this).data('id'); 
        if (confirm('Are you sure you want to remove this item?')) {
          $.ajax({
            url: 'admin/ajax.php?action=delete_cart',
            method: 'POST',
            data: { id },
            success: function (response) {
              if (response == 1) {
                alert('Item removed successfully.');
                updateCartCount();
                location.reload();
              } else {
                alert('Failed to remove the item. Please try again.');
              }
            },
            error: function () {
              alert('An error occurred while removing the item.');
            },
          });
        }
      });

      function updateCartQty(id, qty) {
        $.ajax({
          url: 'admin/ajax.php?action=update_cart_qty',
          method: 'POST',
          data: { id, qty },
          success: function(response) {
            if (response == 1) {
              updateCartCount();
              location.reload();
            } else {
              alert('Failed to update quantity.');
            }
          },
          error: function() {
            alert('An error occurred while updating the quantity.');
          }
        });
      }

      function updateCartCount() {
        $.ajax({
          url: 'admin/ajax.php?action=get_cart_count',
          method: 'GET',
          success: function(response) {
            $('#cartCount').text(response ? response : 0);
          },
          error: function() {
            $('#cartCount').text('0');
          }
        });
      }

      $('#checkout').click(function() {
        <?php if (isset($_SESSION['user_id'])): ?>
          window.location.href = 'checkout.php';
        <?php else: ?>
          alert('You must be logged in to proceed to checkout.');
          window.location.href = 'login.php';
        <?php endif; ?>
      });
    });
  </script>
</body>
</html>
<?php $conn->close();?>
