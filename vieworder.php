<?php
include 'admin/db_connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body { background: #fafafa; font-family: "Segoe UI", sans-serif; }
    .order-card { border-radius: 1rem; background: #fff; border: 1px solid #eee; box-shadow: 0 3px 8px rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
    .status-badge { font-size: 0.85rem; padding: 5px 10px; border-radius: 20px; font-weight: 500; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-confirmed { background: #d4edda; color: #155724; }
    .status-delivery { background: #cce5ff; color: #004085; }
    .status-delivered { background: #e2f0cb; color: #155724; }
  </style>
</head>
<body>
  <div class="container py-5">
    <h3 class="fw-bold mb-4">Orders :</h3>

    <?php
    if (isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];

      // fetch orders for this user
      $orders = $conn->query("SELECT * FROM orders WHERE email IN (SELECT email FROM user_info WHERE user_id = '$user_id') ORDER BY id DESC");

      if ($orders && $orders->num_rows > 0):
        $counter = 1; // Order number counter
        while ($order = $orders->fetch_assoc()):
          $status_class = "status-pending";
          $status_text  = "For Verification";
          if ($order['status'] == 1) { $status_class = "status-confirmed"; $status_text = "Confirmed"; }
          if ($order['status'] == 2) { $status_class = "status-delivery"; $status_text = "For Delivery"; }
          if ($order['status'] == 3) { $status_class = "status-delivered"; $status_text = "Delivered"; }

          // fetch items in this order
          $order_id = (int)$order['id'];
          $items = $conn->query("
            SELECT ol.*, p.name, p.description, p.price, p.img_path 
            FROM order_list ol
            JOIN product_list p ON ol.product_id = p.id
            WHERE ol.order_id = $order_id
          ");

          $total = 0;
    ?>
      <div class="order-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h5 class="mb-1">Order #<?php echo $counter; ?></h5>
            <small class="text-muted">Placed on: <?php echo date("M d, Y h:i A", strtotime($order['date_created'])); ?></small>
          </div>
          <div>
            <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
          </div>
        </div>

        <?php if ($items && $items->num_rows > 0): ?>
          <?php while ($item = $items->fetch_assoc()):
            $subtotal = $item['qty'] * $item['price'];
            $total += $subtotal;
          ?>
          <div class="row mb-3 border rounded p-2">
            <div class="col-md-2 text-center">
              <img src="assets/img/<?php echo $item['img_path']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" 
                   class="img-fluid rounded" style="max-height:100px;">
            </div>
            <div class="col-md-6">
              <h6><?php echo htmlspecialchars($item['name']); ?></h6>
              <p class="text-muted mb-1" style="font-size:0.9rem;"><?php echo htmlspecialchars($item['description']); ?></p>
              <p class="fw-semibold">₱<?php echo number_format($item['price'], 2); ?> × <?php echo $item['qty']; ?></p>
            </div>
            <div class="col-md-4 text-end">
              <p class="fw-bold">₱<?php echo number_format($subtotal, 2); ?></p>
            </div>
          </div>
          <?php endwhile; ?>
          <div class="text-end">Delivery Fee: <strong>₱5.00</strong></div>
          <div class="text-end fw-bold">Total: ₱<?php echo number_format($total + 5, 2); ?></div>
        <?php else: ?>
          <p class="text-muted">No items found in this order.</p>
        <?php endif; ?>
      </div>
    <?php 
        $counter++; // increment for next order
        endwhile; 
      else: ?>
      <div class="alert alert-info">You have no orders yet.</div>
    <?php endif; ?>
    <?php } else { ?>
      <div class="alert alert-warning">Please <a href="login.php">login</a> to view your orders.</div>
    <?php } ?>
  </div>

  <div class="text-center mt-4">
    <a href="index.php" class="btn btn-primary btn-lg shadow"><i class="fas fa-store me-2"></i>Back to Store</a>
  </div>
</body>
</html>

<?php $conn->close(); ?>
