<?php
ob_start();
$action = $_GET['action'];
include 'db_connect.php'; 
include 'admin_class.php';
$crud = new Action();

if ($action == 'login') {
    $login = $crud->login();
    if ($login)
        echo $login;
}
if ($action == 'logout') {
    session_start();
    session_unset();
    session_destroy();
    header('Location: ../index.php'); 
    exit();
}
if ($action == 'add_to_cart') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 0, 'message' => 'You must be logged in to add items to the cart.']);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $pid = $_POST['pid'];
    $qty = intval($_POST['qty']);

    // Enforce quantity limits on the backend
    if ($qty < 1) $qty = 1;
    if ($qty > 10) $qty = 10;

    $check = $conn->query("SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$pid'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE cart SET qty = LEAST(qty + $qty, 10) WHERE user_id = '$user_id' AND product_id = '$pid'");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, qty) VALUES ('$user_id', '$pid', '$qty')");
    }

    echo json_encode(['status' => 1, 'message' => 'Product added to cart successfully.']);
    exit();
}

if ($action == 'get_cart_count') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        echo 0; 
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $count = $conn->query("SELECT SUM(qty) AS total FROM cart WHERE user_id = '$user_id'")->fetch_assoc()['total'];
    echo $count ? $count : 0; 
    exit();
}

if($action == "save_user"){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == "signup"){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if ($action == 'save_category') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $img_path = '';

    if (!empty($_FILES['img']['name'])) {
        $img_path = time() . '_' . $_FILES['img']['name'];
        move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/' . $img_path);
    }

    if (empty($id)) {
        $conn->query("INSERT INTO category_list (name, img_path) VALUES ('$name', '$img_path')");
        echo 1;
    } else {
        $img_sql = $img_path ? ", img_path = '$img_path'" : '';
        $conn->query("UPDATE category_list SET name = '$name' $img_sql WHERE id = $id");
        echo 2;
    }
    exit();
}

if ($action == 'delete_category') {
    $id = $_POST['id'];
    $conn->query("DELETE FROM category_list WHERE id = $id");
    echo 1;
    exit();
}

if ($action == 'save_menu') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $img_path = '';

    if (!empty($_FILES['img']['name'])) {
        $img_path = time() . '_' . $_FILES['img']['name'];
        move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/' . $img_path);
    }

    if (empty($id)) {
        $conn->query("INSERT INTO product_list (name, description, price, category_id, img_path) VALUES ('$name', '$description', $price, $category_id, '$img_path')");
        echo 1;
    } else {
        $conn->query("UPDATE product_list SET name = '$name', description = '$description', price = $price, category_id = $category_id, img_path = '$img_path' WHERE id = $id");
        echo 2;
    }
    exit();
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // ✅ Confirm order
    if ($action == 'confirm_order') {
        $id = $_POST['id'];
        $update = $conn->query("UPDATE orders SET status = 1 WHERE id = $id");
        echo $update ? 1 : $conn->error;
        exit;
    }

    // ✅ Set order as In Delivery
    if ($action == 'set_in_delivery') {
        $id = $_POST['id'];
        $update = $conn->query("UPDATE orders SET status = 2 WHERE id = $id");
        echo $update ? 1 : $conn->error;
        exit;
    }

    // ✅ Set order as Delivered
    if ($action == 'set_delivered') {
        $id = $_POST['id'];
        $update = $conn->query("UPDATE orders SET status = 3 WHERE id = $id");
        echo $update ? 1 : $conn->error;
        exit;
    }
}

if ($action == 'delete_menu') {
    $id = $_POST['id'];
    $conn->query("DELETE FROM product_list WHERE id = $id");
    echo 1;
    exit();
}

// if($action == "delete_cart"){
// 	$delete = $crud->delete_cart();
// 	if($delete)
// 		echo $delete;
// }
if($action == "update_cart_qty"){
	$save = $crud->update_cart_qty();
	if($save)
		echo $save;
}
if($action == "save_order"){
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $user_id = $_SESSION['user_id'];
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $name = $first_name . ' ' . $last_name;

    $save = $conn->query("INSERT INTO orders (name, address, mobile, email, status) VALUES ('$name', '$address', '$mobile', '$email', 0)");
    if(!$save){
        echo $conn->error; 
        exit;
    }
    $order_id = $conn->insert_id;

    $cart = $conn->query("SELECT * FROM cart WHERE user_id = '$user_id'");
    while ($item = $cart->fetch_assoc()) {
        $product_id = $item['product_id'];
        $qty = $item['qty'];
        $conn->query("INSERT INTO order_list (order_id, product_id, qty) VALUES ('$order_id', '$product_id', '$qty')");
    }

    $conn->query("DELETE FROM cart WHERE user_id = '$user_id'");

    echo 1;
    exit();
}

if ($action == 'save_order') {
    // 1. Insert into orders table
    // (collect user info from $_POST, etc)
    $conn->query("INSERT INTO orders (name, address, email, mobile, status) VALUES (...)");
    $order_id = $conn->insert_id;

    // 2. Get all cart items for this user
    $user_id = $_SESSION['user_id'];
    $cart = $conn->query("SELECT * FROM cart WHERE user_id = '$user_id'");
    while ($row = $cart->fetch_assoc()) {
        // 3. Insert each cart item into order_list
        $conn->query("INSERT INTO order_list (order_id, product_id, qty, price) VALUES (
            '$order_id',
            '{$row['product_id']}',
            '{$row['qty']}',
            '{$row['price']}'
        )");
    }

    // 4. Clear the cart
    $conn->query("DELETE FROM cart WHERE user_id = '$user_id'");

    echo 1;
    exit;
}

if($action == "confirm_order"){
	$save = $crud->confirm_order();
	if($save)
		echo $save;
}

if ($action == 'get_product') {
    $id = $_POST['id'];
    $qry = $conn->query("SELECT p.*, c.name as category_name 
                         FROM product_list p 
                         LEFT JOIN category_list c ON p.category_id = c.id 
                         WHERE p.id = $id")->fetch_assoc();
    if ($qry) {
        echo '
        <div class="md:w-1/2">
          <img src="assets/img/' . $qry['img_path'] . '" alt="' . $qry['name'] . '" class="w-full h-auto rounded-lg shadow">
        </div>
        <div class="md:w-1/2 md:pl-8">
          <h1 class="text-3xl font-bold text-gray-800 mb-4">' . $qry['name'] . '</h1>
          <p class="text-gray-600 mb-4">' . $qry['description'] . '</p>
          <p class="text-green-600 font-bold text-2xl mb-4">₱' . number_format($qry['price'], 2) . '</p>
          <p class="text-gray-500 text-sm mb-4"><strong>Category:</strong> ' . $qry['category_name'] . '</p>
        </div>';
    } else {
        echo '<p class="text-red-600">Product not found.</p>';
    }
    exit();
}

if ($action == 'filter_products') {
    $category_id = $_POST['category_id'];
    $where = $category_id !== 'all' ? "WHERE category_id = $category_id AND status = 1" : "WHERE status = 1";
    $qry = $conn->query("SELECT * FROM product_list $where");
    while ($row = $qry->fetch_assoc()) {
        echo '
        <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105 overflow-hidden text-center">
          <img src="assets/img/' . $row['img_path'] . '" alt="' . $row['name'] . '" class="w-full h-48 object-cover">
          <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-800 hover:text-green-600 transition-colors">' . $row['name'] . '</h3>
            <p class="text-gray-600">' . $row['description'] . '</p>
            <p class="text-green-600 font-bold">₱' . number_format($row['price'], 2) . '</p>
            <button onclick="addToCart(' . $row['id'] . ', this)" class="btn-main w-full py-2 text-lg flex items-center justify-center gap-2 transition-all duration-150 mt-4">
              <svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-5 w-5 inline\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.6 8M17 13l1.6 8M9 21h6\' /></svg>
              Add to Cart
            </button>
          </div>
        </div>';
    }
    exit();
}

if ($action == 'get_related_products') {
    $category_id = $_POST['category_id'];
    $qry = $conn->query("SELECT * FROM product_list WHERE category_id = $category_id AND status = 1");
    $output = '';
    while ($row = $qry->fetch_assoc()) {
        $output .= '
        <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105 overflow-hidden text-center">
          <img src="assets/img/' . $row['img_path'] . '" alt="' . $row['name'] . '" class="w-full h-48 object-cover">
          <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-800 hover:text-green-600 transition-colors">' . $row['name'] . '</h3>
            <p class="text-gray-600">' . $row['description'] . '</p>
            <p class="text-green-600 font-bold">₱' . number_format($row['price'], 2) . '</p>
          </div>
        </div>';
    }
    echo $output;
    exit();
}

if ($action == 'get_products_by_category') {
    $category_id = $_POST['category_id'];
    $qry = $conn->query("SELECT * FROM product_list WHERE category_id = $category_id AND status = 1");
    $output = '';
    while ($row = $qry->fetch_assoc()) {
        $output .= '
        <div class="swiper-slide text-center">
          <img src="assets/img/' . $row['img_path'] . '" alt="' . $row['name'] . '" class="w-full h-48 object-cover mb-4">
          <h4 class="text-lg font-bold">' . $row['name'] . '</h4>
          <p class="text-gray-600">' . $row['description'] . '</p>
          <p class="text-green-600 font-bold">₱' . number_format($row['price'], 2) . '</p>
          <div class="flex justify-center items-center mt-4 gap-2">
            <label for="qty_' . $row['id'] . '" class="font-medium text-gray-700">Quantity</label>
            <button type="button" class="qty-btn px-2 py-1 rounded border" onclick="changeQty(\'qty_' . $row['id'] . '\', -1)">−</button>
            <input type="number" id="qty_' . $row['id'] . '" class="border border-gray-300 rounded px-2 py-1 w-16 text-center" value="1" min="1" max="10">
            <button type="button" class="qty-btn px-2 py-1 rounded border" onclick="changeQty(\'qty_' . $row['id'] . '\', 1)">+</button>
          </div>
          <button onclick="addToCart(' . $row['id'] . ', this)" class="btn-main w-full py-2 text-lg flex items-center justify-center gap-2 transition-all duration-150 mt-4">
            <svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-5 w-5 inline\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.6 8M17 13l1.6 8M9 21h6\' /></svg>
            Add to Cart 
          </button>
        </div>';
    }
    echo $output;
    exit();
}

if ($action == 'delete_cart') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        echo 0; 
        exit();
    }

    $id = $_POST['id']; 
    $user_id = $_SESSION['user_id'];

    
    $delete = $conn->query("DELETE FROM cart WHERE id = '$id' AND user_id = '$user_id'");
    if ($delete) {
        echo 1; 
    } else {
        echo 0; 
    }
    exit();
}

// Always start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_GET['action'])) {
    $action = $_GET['action'];
    switch($action) {
        // ✅ send message
        case 'send_message':
            if(!isset($_SESSION['user_id'])){
                echo json_encode(["success"=>false,"error"=>"Not logged in"]);
                exit;
            }
            $user_id = $_SESSION['user_id'];
            $message = trim($_POST['message'] ?? '');
            if($message === ''){
                echo json_encode(["success"=>false,"error"=>"Empty message"]);
                exit;
            }

            // check role
            $roleQ = $conn->prepare("SELECT role FROM user_info WHERE user_id=?");
            $roleQ->bind_param("i", $user_id);
            $roleQ->execute();
            $roleRes = $roleQ->get_result()->fetch_assoc();
            $role = $roleRes['role'] ?? 'user';

            if($role === 'admin'){
                $receiver_id = intval($_POST['receiver_id'] ?? 0);
                if($receiver_id <= 0){
                    echo json_encode(["success"=>false,"error"=>"No user selected"]);
                    exit;
                }
            } else {
                $receiver_id = 3; // admin id
            }

            $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $user_id, $receiver_id, $message);
            $stmt->execute();

            echo json_encode(["success"=>true]);
            exit;

        // ✅ fetch messages
        case 'fetch_messages':
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(["success" => false, "error" => "No session"]);
                exit;
            }
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT role FROM user_info WHERE user_id=?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $roleRes = $stmt->get_result()->fetch_assoc();
            $role = $roleRes['role'] ?? 'user';

            if ($role === 'admin') {
                $partner_id = intval($_GET['user_id'] ?? 0);
                if ($partner_id <= 0) {
                    echo json_encode(["success" => false, "error" => "No user selected"]);
                    exit;
                }
            } else {
                $partner_id = 3; // admin id
            }

            $q = $conn->prepare("SELECT * FROM messages 
                                 WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) 
                                 ORDER BY created_at ASC");
            $q->bind_param("iiii", $user_id, $partner_id, $partner_id, $user_id);
            $q->execute();
            $res = $q->get_result();

            $messages = [];
            while ($row = $res->fetch_assoc()) {
                $messages[] = $row;
            }

            echo json_encode([
                "success" => true,
                "messages" => $messages
            ]);
            exit;
    }
}

$conn->close();
?>

