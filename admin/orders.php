<?php include 'db_connect.php'; ?>
<div class="pl-64 pt-16 min-h-screen bg-gray-50">
  <div class="container mx-auto mt-8 px-4">
    <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
      <h5 class="text-3xl font-extrabold mb-8 text-green-700 tracking-tight flex items-center gap-3">
        <i class="fa fa-clipboard-list text-green-400"></i> Orders
      </h5>
      <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl">
          <thead class="bg-green-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-bold text-green-700 uppercase tracking-wider">#</th>
              <th class="px-4 py-3 text-left text-xs font-bold text-green-700 uppercase tracking-wider">Name</th>
              <th class="px-4 py-3 text-left text-xs font-bold text-green-700 uppercase tracking-wider">Address</th>
              <th class="px-4 py-3 text-left text-xs font-bold text-green-700 uppercase tracking-wider">Email</th>
              <th class="px-4 py-3 text-left text-xs font-bold text-green-700 uppercase tracking-wider">Mobile</th>
              <th class="px-4 py-3 text-center text-xs font-bold text-green-700 uppercase tracking-wider">Status</th>
              <th class="px-4 py-3 text-center text-xs font-bold text-green-700 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <?php 
            $i = 1;
            if ($conn->connect_error) {
                echo "<tr><td colspan='7' class='text-center text-red-600'>Database connection failed: " . $conn->connect_error . "</td></tr>";
            } else {
              $qry = $conn->query("SELECT * FROM orders");
              if (!$qry) {
                  echo "<tr><td colspan='7' class='text-center text-red-600'>Error fetching orders: " . $conn->error . "</td></tr>";
              } else {
                  while ($row = $qry->fetch_assoc()):
            ?>
            <tr class="hover:bg-green-50 transition">
              <td class="px-4 py-3 font-semibold text-gray-700"><?php echo $i++ ?></td>
              <td class="px-4 py-3"><?php echo htmlspecialchars($row['name']) ?></td>
              <td class="px-4 py-3"><?php echo htmlspecialchars($row['address']) ?></td>
              <td class="px-4 py-3"><?php echo htmlspecialchars($row['email']) ?></td>
              <td class="px-4 py-3"><?php echo htmlspecialchars($row['mobile']) ?></td>
              <td class="px-4 py-3 text-center">
                <?php if ($row['status'] == 1): ?>
                  <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold shadow">Confirmed</span>
                <?php else: ?>
                  <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold shadow">For Verification</span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3 text-center space-x-2">
                <button class="view_order inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded shadow transition" data-id="<?php echo $row['id'] ?>">
                  <i class="fa fa-eye mr-1"></i> View
                </button>
                <?php if ($row['status'] == 0): ?>
                  <button class="confirm-order-btn inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded shadow transition" data-id="<?php echo $row['id'] ?>">
                    <i class="fa fa-check mr-1"></i> Confirm
                  </button>
                <?php endif; ?>
              </td>
            </tr>
            <?php 
                  endwhile;
              }
            }
            ?>
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

  // Close modal on close button
  document.getElementById('closeOrderModal').onclick = function() {
    document.getElementById('orderModal').classList.add('hidden');
  };
  // Close modal when clicking outside the modal content
  document.getElementById('orderModal').onclick = function(e) {
    if (e.target === this) {
      this.classList.add('hidden');
    }
  };

  // Confirm order button handler
  document.querySelectorAll('.confirm-order-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var orderId = this.getAttribute('data-id');
      if (confirm('Are you sure you want to confirm this order?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'confirm_order.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
          if (xhr.status === 200 && xhr.responseText.trim() === 'success') {
            alert('Order confirmed!');
            location.reload();
          } else {
            alert('Failed to confirm order.');
          }
        };
        xhr.send('id=' + encodeURIComponent(orderId));
      }
    });
  });
</script>
<?php $conn->close(); ?>