<?php
include 'db_connect.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id <= 0) {
    echo "<div style='color:#dc2626;'>Invalid order ID.</div>";
    exit;
}

$qry = $conn->query("SELECT * FROM orders WHERE id = $order_id");
if (!$qry || $qry->num_rows == 0) {
    echo "<div style='color:#dc2626;'>Order not found.</div>";
    exit;
}
$order = $qry->fetch_assoc();

// Status mapping
$status_text = "For Verification";
if ($order['status'] == 1) $status_text = "Confirmed";
if ($order['status'] == 2) $status_text = "For Delivery";
if ($order['status'] == 3) $status_text = "Done";
?>
<div style="font-family:Segoe UI,Arial,sans-serif;">
  <h3 style="color:#2563eb; margin-bottom:1rem;">Order #<?php echo $order['id']; ?></h3>
  <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
  <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
  <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
  <p><strong>Mobile:</strong> <?php echo htmlspecialchars($order['mobile']); ?></p>
  <p><strong>Status:</strong> <?php echo $status_text; ?></p>
  
  <hr>
  <h4 style="margin-top:1rem;">Order Items:</h4>
  <ul style="padding-left:1.2em;">
  <?php
  $items = $conn->query("
      SELECT ol.qty, p.name 
      FROM order_list ol 
      JOIN product_list p ON ol.product_id = p.id 
      WHERE ol.order_id = $order_id
  ");
  while ($item = $items->fetch_assoc()):
  ?>
    <li>
      <?php echo htmlspecialchars($item['name']); ?> 
      <span style="color:#2563eb;">(x<?php echo $item['qty']; ?>)</span>
    </li>
  <?php endwhile; ?>
  </ul>
</div>
