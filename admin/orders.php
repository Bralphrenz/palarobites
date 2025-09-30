<?php 
include 'db_connect.php'; 
?>

<div class="pl-64 pt-16 min-h-screen bg-gray-50">
  <div class="container mx-auto mt-8 px-4">
    <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
      <h5 class="text-3xl font-extrabold mb-8 text-green-600 tracking-tight border-b pb-4">
        Orders
      </h5>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
          <thead class="bg-green-600 text-white text-xs uppercase tracking-wider">
            <tr>
              <th class="px-4 py-3 text-left">Order ID</th>
              <th class="px-4 py-3 text-left">Customer</th>
              <th class="px-4 py-3 text-left">Address</th>
              <th class="px-4 py-3 text-left">Mobile</th>
              <th class="px-4 py-3 text-left">Email</th>
              <th class="px-4 py-3 text-center">Status</th>
              <th class="px-4 py-3 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <?php
              $orders = $conn->query("SELECT * FROM orders ORDER BY id DESC");
              while($row = $orders->fetch_assoc()):
            ?>
              <tr class="hover:bg-gray-50 transition duration-150">
                <td class="px-4 py-3 font-bold text-gray-900">#<?php echo $row['id'] ?></td>
                <td class="px-4 py-3"><?php echo $row['name'] ?></td>
                <td class="px-4 py-3"><?php echo $row['address'] ?></td>
                <td class="px-4 py-3"><?php echo $row['mobile'] ?></td>
                <td class="px-4 py-3"><?php echo $row['email'] ?></td>
                <td class="px-4 py-3 text-center">
                  <?php if ($row['status'] == 1): ?>
                    <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold shadow">
                      Confirmed
                    </span>
                  <?php elseif($row['status'] == 2): ?>
                    <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold shadow">
                      In Delivery
                    </span>  
                  <?php elseif($row['status'] == 3): ?>
                    <span class="inline-block px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-xs font-bold shadow">
                      Delivered
                    </span>
                  <?php else: ?>
                    <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold shadow">
                      For Verification
                    </span>
                  <?php endif; ?>
                </td>
                <td class="px-4 py-3 text-center space-x-2">
                  <button class="view_order px-3 py-1 bg-indigo-500 text-white rounded-xl text-xs font-bold shadow hover:bg-indigo-600" 
                          data-id="<?php echo $row['id'] ?>">
                    View
                  </button>

                  <?php if ($row['status'] == 0): ?>
                    <button onclick="updateStatus(<?php echo $row['id'] ?>, 'confirm_order')" 
                      class="px-3 py-1 bg-green-500 text-white rounded-xl text-xs font-bold shadow hover:bg-green-600">
                      Confirm
                    </button>
                  <?php elseif ($row['status'] == 1): ?>
                    <button onclick="updateStatus(<?php echo $row['id'] ?>, 'set_in_delivery')" 
                      class="px-3 py-1 bg-blue-500 text-white rounded-xl text-xs font-bold shadow hover:bg-blue-600">
                      Mark as for Delivery
                    </button>
                  <?php elseif ($row['status'] == 2): ?>
                    <button onclick="updateStatus(<?php echo $row['id'] ?>, 'set_delivered')" 
                      class="px-3 py-1 bg-gray-500 text-white rounded-xl text-xs font-bold shadow hover:bg-gray-600">
                      Mark as Delivered
                    </button>
                  <?php else: ?>
                    <span class="text-gray-400 text-xs">No Actions</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center">
  <div id="orderModalContent" class="bg-white rounded-2xl max-w-lg w-full mx-4 p-8 relative shadow-2xl border border-green-100 max-h-[80vh] overflow-y-auto animate-fadein">
    <button id="closeOrderModal" type="button" class="absolute top-3 right-4 text-3xl text-green-600 hover:text-red-600 transition">&times;</button>
    <div id="orderModalBody" class="text-center text-gray-500">Loading...</div>
  </div>
</div>

<style>
  @keyframes fadein {
    from { opacity: 0; transform: translateY(40px);}
    to { opacity: 1; transform: translateY(0);}
  }
  .animate-fadein { animation: fadein 0.25s; }
</style>

<script>
  // Update order status
  function updateStatus(id, action) {
    if (!confirm("Are you sure you want to update this order?")) return;

    fetch('ajax.php?action=' + action, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'id=' + id
    })
    .then(res => res.text())
    .then(resp => {
      if (resp == 1) {
        alert("Order updated successfully!");
        location.reload();
      } else {
        alert("Error: " + resp);
      }
    })
    .catch(err => alert("Request failed: " + err));
  }

  // View Order Modal
  document.querySelectorAll('.view_order').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var orderId = this.getAttribute('data-id');
      var modal = document.getElementById('orderModal');
      modal.classList.remove('hidden');
      document.getElementById('orderModalBody').innerHTML = 'Loading...';
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'view_orders.php?id=' + orderId, true);
      xhr.onload = function() {
        if (xhr.status === 200) {
          document.getElementById('orderModalBody').innerHTML = xhr.responseText;
        } else {
          document.getElementById('orderModalBody').innerHTML = 'Failed to load order details.';
        }
      };
      xhr.send();
    });
  });

  // Close modal
  document.getElementById('closeOrderModal').onclick = function() {
    document.getElementById('orderModal').classList.add('hidden');
  };
  document.getElementById('orderModal').onclick = function(e) {
    if (e.target === this) {
      this.classList.add('hidden');
    }
  };
</script>

<?php $conn->close(); ?>
