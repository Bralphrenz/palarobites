<?php
include 'admin/db_connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders - Premium Restaurant</title>
  
  <!-- Added premium Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* Premium restaurant design system */
    :root {
      --primary-gold: #c9a961;
      --primary-dark: #1a1a1a;
      --secondary-dark: #2d2d2d;
      --cream-bg: #f5f3ef;
      --cream-light: #faf8f5;
      --warm-brown: #8b6f47;
      --white: #ffffff;
      --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
      --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
      --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, var(--cream-bg) 0%, var(--cream-light) 100%);
      font-family: 'Montserrat', sans-serif;
      color: var(--primary-dark);
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    /* Premium background texture */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        radial-gradient(circle at 20% 50%, rgba(201, 169, 97, 0.03) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(201, 169, 97, 0.03) 0%, transparent 50%);
      pointer-events: none;
      z-index: 0;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 3rem 2rem;
      position: relative;
      z-index: 1;
    }

    /* Premium page header */
    .page-header {
      text-align: center;
      margin-bottom: 3rem;
      animation: fadeInDown 0.8s ease-out;
    }

    .page-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 3.5rem;
      font-weight: 600;
      color: var(--primary-dark);
      margin-bottom: 0.5rem;
      letter-spacing: 2px;
    }

    .page-subtitle {
      font-size: 1rem;
      color: var(--warm-brown);
      letter-spacing: 3px;
      text-transform: uppercase;
      font-weight: 400;
    }

    /* Decorative divider */
    .decorative-divider {
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 2rem 0;
      gap: 1rem;
    }

    .decorative-divider::before,
    .decorative-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(to right, transparent, var(--primary-gold), transparent);
      max-width: 200px;
    }

    .decorative-icon {
      color: var(--primary-gold);
      font-size: 1.2rem;
    }

    /* Premium order card */
    .order-card {
      background: var(--white);
      border-radius: 16px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: var(--shadow-md);
      border: 1px solid rgba(201, 169, 97, 0.1);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      animation: fadeInUp 0.6s ease-out backwards;
      position: relative;
      overflow: hidden;
    }

    .order-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: linear-gradient(to bottom, var(--primary-gold), var(--warm-brown));
    }

    .order-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
      border-color: var(--primary-gold);
    }

    /* Order header styling */
    .order-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1.5rem;
      padding-bottom: 1.5rem;
      border-bottom: 1px solid rgba(201, 169, 97, 0.15);
    }

    .order-info h5 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.8rem;
      font-weight: 600;
      color: var(--primary-dark);
      margin-bottom: 0.5rem;
    }

    .order-date {
      font-size: 0.9rem;
      color: var(--warm-brown);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .order-date i {
      color: var(--primary-gold);
    }

    /* Premium status badges */
    .status-badge {
      padding: 0.5rem 1.25rem;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease;
    }

    .status-badge i {
      font-size: 0.9rem;
    }

    .status-pending {
      background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
      color: #856404;
      border: 1px solid #ffeaa7;
    }

    .status-confirmed {
      background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
      color: #155724;
      border: 1px solid #b1dfbb;
    }

    .status-delivery {
      background: linear-gradient(135deg, #cce5ff 0%, #b8daff 100%);
      color: #004085;
      border: 1px solid #9fcdff;
    }

    .status-delivered {
      background: linear-gradient(135deg, var(--primary-gold) 0%, #d4af37 100%);
      color: var(--white);
      border: 1px solid var(--primary-gold);
      box-shadow: 0 4px 12px rgba(201, 169, 97, 0.3);
    }

    /* Premium order item styling */
    .order-item {
      display: grid;
      grid-template-columns: 120px 1fr auto;
      gap: 1.5rem;
      padding: 1.5rem;
      margin-bottom: 1rem;
      background: var(--cream-light);
      border-radius: 12px;
      border: 1px solid rgba(201, 169, 97, 0.1);
      transition: all 0.3s ease;
    }

    .order-item:hover {
      background: var(--white);
      border-color: var(--primary-gold);
      transform: translateX(4px);
    }

    .item-image {
      width: 120px;
      height: 120px;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow-sm);
      border: 2px solid var(--white);
    }

    .item-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .order-item:hover .item-image img {
      transform: scale(1.1);
    }

    .item-details h6 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.4rem;
      font-weight: 600;
      color: var(--primary-dark);
      margin-bottom: 0.5rem;
    }

    .item-description {
      color: var(--warm-brown);
      font-size: 0.9rem;
      margin-bottom: 0.75rem;
      line-height: 1.6;
    }

    .item-quantity {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.95rem;
      color: var(--secondary-dark);
    }

    .item-quantity .price {
      color: var(--primary-gold);
      font-weight: 600;
    }

    .item-subtotal {
      text-align: right;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .item-subtotal .amount {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.6rem;
      font-weight: 600;
      color: var(--primary-dark);
    }

    /* Premium order summary */
    .order-summary {
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 2px solid rgba(201, 169, 97, 0.2);
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 0;
      font-size: 1rem;
    }

    .summary-row.total {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--primary-dark);
      padding-top: 1rem;
      border-top: 2px solid var(--primary-gold);
    }

    .summary-row.total .amount {
      color: var(--primary-gold);
    }

    /* Premium alert messages */
    .alert {
      padding: 1.5rem 2rem;
      border-radius: 12px;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      animation: fadeIn 0.5s ease-out;
    }

    .alert i {
      font-size: 1.5rem;
    }

    .alert-info {
      background: linear-gradient(135deg, #cce5ff 0%, #b8daff 100%);
      color: #004085;
      border: 1px solid #9fcdff;
    }

    .alert-warning {
      background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
      color: #856404;
      border: 1px solid #ffeaa7;
    }

    .alert a {
      color: var(--primary-gold);
      font-weight: 600;
      text-decoration: none;
      border-bottom: 2px solid var(--primary-gold);
      transition: all 0.3s ease;
    }

    .alert a:hover {
      color: var(--warm-brown);
      border-color: var(--warm-brown);
    }

    /* Premium back button */
    .back-to-store {
      text-align: center;
      margin-top: 3rem;
      padding-top: 3rem;
      border-top: 1px solid rgba(201, 169, 97, 0.2);
    }

    .btn-premium {
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      padding: 1rem 2.5rem;
      background: linear-gradient(135deg, var(--primary-gold) 0%, #d4af37 100%);
      color: var(--white);
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      text-decoration: none;
      border-radius: 50px;
      border: 2px solid var(--primary-gold);
      box-shadow: 0 4px 16px rgba(201, 169, 97, 0.3);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .btn-premium::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .btn-premium:hover::before {
      width: 300px;
      height: 300px;
    }

    .btn-premium:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(201, 169, 97, 0.4);
      background: linear-gradient(135deg, #d4af37 0%, var(--primary-gold) 100%);
    }

    .btn-premium i {
      font-size: 1.2rem;
      transition: transform 0.3s ease;
    }

    .btn-premium:hover i {
      transform: translateX(-4px);
    }

    /* Empty state styling */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      animation: fadeIn 0.8s ease-out;
    }

    .empty-state i {
      font-size: 4rem;
      color: var(--primary-gold);
      margin-bottom: 1.5rem;
      opacity: 0.6;
    }

    .empty-state h4 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 2rem;
      color: var(--primary-dark);
      margin-bottom: 1rem;
    }

    .empty-state p {
      color: var(--warm-brown);
      font-size: 1rem;
    }

    /* Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Comprehensive responsive breakpoints */
    
    /* Extra Large Desktops (1400px+) */
    @media (min-width: 1400px) {
      .container {
        max-width: 1320px;
      }
    }

    /* Large Desktops (1200px - 1399px) */
    @media (max-width: 1399px) {
      .page-title {
        font-size: 3rem;
      }
    }

    /* Medium Desktops & Tablets (992px - 1199px) */
    @media (max-width: 1199px) {
      .container {
        padding: 2.5rem 1.5rem;
      }

      .page-title {
        font-size: 2.8rem;
      }

      .order-card {
        padding: 1.75rem;
      }
    }

    /* Tablets (768px - 991px) */
    @media (max-width: 991px) {
      .page-title {
        font-size: 2.5rem;
      }

      .order-item {
        grid-template-columns: 100px 1fr auto;
        gap: 1rem;
        padding: 1.25rem;
      }

      .item-image {
        width: 100px;
        height: 100px;
      }

      .item-details h6 {
        font-size: 1.2rem;
      }

      .item-subtotal .amount {
        font-size: 1.4rem;
      }
    }

    /* Mobile Landscape & Small Tablets (641px - 767px) */
    @media (max-width: 767px) {
      .container {
        padding: 2rem 1rem;
      }

      .page-header {
        margin-bottom: 2rem;
      }

      .page-title {
        font-size: 2.2rem;
        letter-spacing: 1px;
      }

      .page-subtitle {
        font-size: 0.85rem;
        letter-spacing: 2px;
      }

      .order-card {
        padding: 1.5rem;
        border-radius: 12px;
      }

      .order-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
      }

      .order-info h5 {
        font-size: 1.5rem;
      }

      .order-date {
        font-size: 0.85rem;
      }

      .status-badge {
        padding: 0.4rem 1rem;
        font-size: 0.75rem;
      }

      .order-item {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
      }

      .item-image {
        width: 100%;
        height: 200px;
        margin: 0 auto;
      }

      .item-details {
        text-align: center;
      }

      .item-details h6 {
        font-size: 1.3rem;
      }

      .item-description {
        font-size: 0.85rem;
      }

      .item-quantity {
        justify-content: center;
      }

      .item-subtotal {
        text-align: center;
      }

      .item-subtotal .amount {
        font-size: 1.5rem;
      }

      .summary-row {
        font-size: 0.95rem;
      }

      .summary-row.total {
        font-size: 1.6rem;
      }

      .btn-premium {
        padding: 0.9rem 2rem;
        font-size: 0.95rem;
        width: 100%;
        justify-content: center;
      }

      .decorative-divider::before,
      .decorative-divider::after {
        max-width: 100px;
      }
    }

    /* Mobile Portrait (481px - 640px) */
    @media (max-width: 640px) {
      .page-title {
        font-size: 2rem;
      }

      .order-info h5 {
        font-size: 1.4rem;
      }

      .item-details h6 {
        font-size: 1.2rem;
      }

      .summary-row.total {
        font-size: 1.5rem;
      }
    }

    /* Small Mobile (320px - 480px) */
    @media (max-width: 480px) {
      .container {
        padding: 1.5rem 0.75rem;
      }

      .page-title {
        font-size: 1.8rem;
      }

      .page-subtitle {
        font-size: 0.75rem;
        letter-spacing: 1.5px;
      }

      .order-card {
        padding: 1.25rem;
        margin-bottom: 1.5rem;
      }

      .order-info h5 {
        font-size: 1.3rem;
      }

      .order-date {
        font-size: 0.8rem;
      }

      .status-badge {
        padding: 0.35rem 0.85rem;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
      }

      .order-item {
        padding: 0.85rem;
      }

      .item-image {
        height: 180px;
      }

      .item-details h6 {
        font-size: 1.1rem;
      }

      .item-description {
        font-size: 0.8rem;
      }

      .item-quantity {
        font-size: 0.85rem;
      }

      .item-subtotal .amount {
        font-size: 1.3rem;
      }

      .summary-row {
        font-size: 0.9rem;
        padding: 0.6rem 0;
      }

      .summary-row.total {
        font-size: 1.4rem;
      }

      .btn-premium {
        padding: 0.85rem 1.5rem;
        font-size: 0.85rem;
        gap: 0.5rem;
      }

      .btn-premium i {
        font-size: 1rem;
      }

      .alert {
        padding: 1.25rem 1.5rem;
        font-size: 0.9rem;
      }

      .alert i {
        font-size: 1.2rem;
      }

      .empty-state {
        padding: 3rem 1rem;
      }

      .empty-state i {
        font-size: 3rem;
      }

      .empty-state h4 {
        font-size: 1.6rem;
      }

      .empty-state p {
        font-size: 0.9rem;
      }
    }

    /* Extra Small Mobile (below 375px) */
    @media (max-width: 374px) {
      .page-title {
        font-size: 1.6rem;
      }

      .order-card {
        padding: 1rem;
      }

      .order-info h5 {
        font-size: 1.2rem;
      }

      .item-details h6 {
        font-size: 1rem;
      }

      .summary-row.total {
        font-size: 1.3rem;
      }
    }

    /* Landscape orientation adjustments for mobile */
    @media (max-height: 500px) and (orientation: landscape) {
      .page-header {
        margin-bottom: 1.5rem;
      }

      .page-title {
        font-size: 1.8rem;
      }

      .order-card {
        padding: 1rem;
      }

      .item-image {
        height: 120px;
      }

      .back-to-store {
        margin-top: 2rem;
        padding-top: 2rem;
      }
    }

    /* Touch device optimizations */
    @media (hover: none) and (pointer: coarse) {
      .order-item,
      .btn-premium,
      .alert a {
        -webkit-tap-highlight-color: rgba(201, 169, 97, 0.2);
      }

      /* Ensure minimum 44px touch targets */
      .status-badge {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
      }

      .btn-premium {
        min-height: 48px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Premium page header -->
    <div class="page-header">
      <h1 class="page-title">My Orders</h1>
      <p class="page-subtitle">Order History</p>
      <div class="decorative-divider">
        <i class="fas fa-leaf decorative-icon"></i>
      </div>
    </div>

    <?php
    if (isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];

      // fetch orders for this user
      $orders = $conn->query("SELECT * FROM orders WHERE email IN (SELECT email FROM user_info WHERE user_id = '$user_id') ORDER BY id DESC");

      if ($orders && $orders->num_rows > 0):
        $counter = 1;
        $animation_delay = 0;
        while ($order = $orders->fetch_assoc()):
          $status_class = "status-pending";
          $status_text  = "For Verification";
          $status_icon = "fa-clock";
          
          if ($order['status'] == 1) { 
            $status_class = "status-confirmed"; 
            $status_text = "Confirmed"; 
            $status_icon = "fa-check-circle";
          }
          if ($order['status'] == 2) { 
            $status_class = "status-delivery"; 
            $status_text = "For Delivery"; 
            $status_icon = "fa-truck";
          }
          if ($order['status'] == 3) { 
            $status_class = "status-delivered"; 
            $status_text = "Delivered"; 
            $status_icon = "fa-check-double";
          }

          $order_id = (int)$order['id'];
          $items = $conn->query("
            SELECT ol.*, p.name, p.description, p.price, p.img_path 
            FROM order_list ol
            JOIN product_list p ON ol.product_id = p.id
            WHERE ol.order_id = $order_id
          ");

          $total = 0;
          $animation_delay += 0.1;
    ?>
      <!-- Premium order card with animation -->
      <div class="order-card" style="animation-delay: <?php echo $animation_delay; ?>s;">
        <div class="order-header">
          <div class="order-info">
            <h5>Order #<?php echo $counter; ?></h5>
            <div class="order-date">
              <i class="far fa-calendar-alt"></i>
              <span><?php echo date("M d, Y h:i A", strtotime($order['date_created'])); ?></span>
            </div>
          </div>
          <div>
            <span class="status-badge <?php echo $status_class; ?>">
              <i class="fas <?php echo $status_icon; ?>"></i>
              <?php echo $status_text; ?>
            </span>
          </div>
        </div>

        <?php if ($items && $items->num_rows > 0): ?>
          <?php while ($item = $items->fetch_assoc()):
            $subtotal = $item['qty'] * $item['price'];
            $total += $subtotal;
          ?>
          <!-- Premium order item -->
          <div class="order-item">
            <div class="item-image">
              <img src="assets/img/<?php echo $item['img_path']; ?>" 
                   alt="<?php echo htmlspecialchars($item['name']); ?>">
            </div>
            <div class="item-details">
              <h6><?php echo htmlspecialchars($item['name']); ?></h6>
              <p class="item-description"><?php echo htmlspecialchars($item['description']); ?></p>
              <div class="item-quantity">
                <span class="price">₱<?php echo number_format($item['price'], 2); ?></span>
                <span>×</span>
                <span><?php echo $item['qty']; ?></span>
              </div>
            </div>
            <div class="item-subtotal">
              <span class="amount">₱<?php echo number_format($subtotal, 2); ?></span>
            </div>
          </div>
          <?php endwhile; ?>
          
          <!-- Premium order summary -->
          <div class="order-summary">
            <div class="summary-row">
              <span>Subtotal</span>
              <span>₱<?php echo number_format($total, 2); ?></span>
            </div>
            <div class="summary-row">
              <span>Delivery Fee</span>
              <span>₱5.00</span>
            </div>
            <div class="summary-row total">
              <span>Total</span>
              <span class="amount">₱<?php echo number_format($total + 5, 2); ?></span>
            </div>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h4>No Items Found</h4>
            <p>This order doesn't contain any items.</p>
          </div>
        <?php endif; ?>
      </div>
    <?php 
        $counter++;
        endwhile; 
      else: ?>
      <!-- Premium empty state -->
      <div class="empty-state">
        <i class="fas fa-shopping-bag"></i>
        <h4>No Orders Yet</h4>
        <p>You haven't placed any orders. Start exploring our menu!</p>
      </div>
    <?php endif; ?>
    <?php } else { ?>
      <!-- Premium alert for login -->
      <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Please <a href="login.php">login</a> to view your orders.</span>
      </div>
    <?php } ?>

    <!-- Premium back to store button -->
    <div class="back-to-store">
      <a href="index.php" class="btn-premium">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Store</span>
      </a>
    </div>
  </div>
</body>
</html>

<?php $conn->close(); ?>
