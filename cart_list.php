<?php
include 'admin/db_connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body style="background: linear-gradient(135deg, #e0f7fa 0%, #f1f8e9 100%); min-height: 100vh;">
  <div class="container py-5">
    <h1 class="text-center mb-4 fw-bold text-success" style="letter-spacing:2px;">
      <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
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
              $order_summary .= '<li>' . $item['name'] . ' (' . $item['qty'] . 'x) - ' . $item['description'] . '</li>';
        ?>
        <div class="card mb-3 shadow-lg border-0" style="background:rgba(255,255,255,0.85); border-radius:1.5rem;">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-3 text-center">
                <img src="assets/img/<?php echo $item['img_path']; ?>" alt="<?php echo $item['name']; ?>" class="img-fluid rounded shadow" style="max-height: 120px; border-radius:1rem;">
              </div>
              <div class="col-md-5">
                <h5 class="fw-bold text-success mb-1">
                  <i class="fas fa-utensils me-1"></i><?php echo $item['name']; ?>
                </h5>
                <p class="mb-1 text-muted" style="font-size:0.95em;"><?php echo $item['description']; ?></p>
                <p class="mb-2">Price: <span class="fw-semibold text-primary">₱<?php echo number_format($item['price'], 2); ?></span></p>
                <div class="input-group input-group-sm w-75">
                  <button class="btn btn-outline-success qty-minus" data-id="<?php echo $item['id']; ?>"><i class="fas fa-minus"></i></button>
                  <input type="number" class="form-control text-center bg-white" value="<?php echo $item['qty']; ?>" readonly>
                  <button class="btn btn-outline-success qty-plus" data-id="<?php echo $item['id']; ?>"><i class="fas fa-plus"></i></button>
                </div>
              </div>
              <div class="col-md-4 text-end">
                <p class="mb-2">Total: <span class="fw-bold text-success">₱<?php echo number_format($item['qty'] * $item['price'], 2); ?></span></p>
                <button class="btn btn-danger btn-sm remove-item" data-id="<?php echo $item['id']; ?>">
                  <i class="fas fa-trash-alt"></i> Remove
                </button>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; else: ?>
        <div class="alert alert-warning text-center shadow-sm rounded-3">Your cart is empty. <a href="index.php" class="text-success fw-bold">Continue shopping</a>.</div>
        <?php endif; ?>
        <?php } else { ?>
        <div class="alert alert-warning text-center shadow-sm rounded-3">Please <a href="login.php" class="text-success fw-bold">log in</a> to view your cart.</div>
        <?php } ?>
      </div>
      <div class="col-lg-4">
        <div class="card shadow-lg border-0" style="background:rgba(255,255,255,0.93); border-radius:1.5rem;">
          <div class="card-body">
            <h5 class="fw-bold text-success mb-3"><i class="fas fa-receipt me-2"></i>Order Summary</h5>
            <hr>
            <ul class="mb-2" style="font-size:0.97em;">
              <?php echo $order_summary; ?>
            </ul>
            <hr>
            <p class="text-end fs-5 fw-bold">Total: <span class="text-success">₱<?php echo number_format($total, 2); ?></span></p>
            <button class="btn btn-success btn-lg w-100 shadow" id="checkout">
              <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <a href="index.php" class="btn btn-primary btn-lg shadow"><i class="fas fa-store me-2"></i>Back to Store</a>
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