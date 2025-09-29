<?php include('db_connect.php'); ?>

<?php
$status_filter = '';
if (isset($_GET['status']) && ($_GET['status'] === '0' || $_GET['status'] === '1')) {
    $status = intval($_GET['status']);
    $status_filter = "WHERE p.status = $status";
}
?>

<div class="pl-64 pt-16 min-h-screen bg-gray-50">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
    <!-- Menu Form -->
    <div class="col-span-1">
      <form action="" id="manage-menu" enctype="multipart/form-data">
        <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
          <div class="mb-4 border-b pb-2">
            <h5 class="text-xl font-bold text-green-700">Menu Form</h5>
          </div>
          <input type="hidden" name="id">
          <div class="mb-4">
            <label for="menuName" class="block font-medium mb-1">Menu Name</label>
            <input type="text" class="w-full border rounded px-3 py-2" id="menuName" name="name" placeholder="Enter food name" required>
          </div>
          <div class="mb-4">
            <label for="menuDescription" class="block font-medium mb-1">Menu Description</label>
            <textarea class="w-full border rounded px-3 py-2" id="menuDescription" name="description" rows="3" placeholder="Enter food description" required></textarea>
          </div>
          <div class="flex items-center mb-4">
            <input class="mr-2" type="checkbox" id="availability" name="status" value="1" checked>
            <label for="availability" class="font-medium">Available</label>
          </div>
          <div class="mb-4">
            <label for="category" class="block font-medium mb-1">Category</label>
            <select id="category" name="category_id" class="w-full border rounded px-3 py-2" required>
              <?php
              $cat = $conn->query("SELECT * FROM category_list ORDER BY name ASC");
              while ($row = $cat->fetch_assoc()):
              ?>
              <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-4">
            <label for="price" class="block font-medium mb-1">Price</label>
            <input type="number" class="w-full border rounded px-3 py-2" id="price" name="price" step="any" placeholder="Enter price" required>
          </div>
          <div class="mb-4">
            <label for="img" class="block font-medium mb-1">Image</label>
            <input type="file" class="w-full border rounded px-3 py-2" id="img" name="img" accept="image/*">
          </div>
          <div class="mb-4 text-center">
            <img src="" alt="" id="cimg" class="mx-auto rounded shadow max-h-36 hidden">
          </div>
          <div class="flex gap-2 justify-center">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Save</button>
            <button type="button" id="cancel-btn" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 transition">Cancel</button>
          </div>
        </div>
      </form>
    </div>

    <!-- Menu Table -->
    <div class="col-span-1 lg:col-span-2">
      <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-green-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase">#</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Image</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Details</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <?php 
              $i = 1;
              $query = "SELECT p.*, c.name as cat FROM product_list p INNER JOIN category_list c ON c.id = p.category_id $status_filter ORDER BY p.id ASC";
              $cats = $conn->query($query);
              while ($row = $cats->fetch_assoc()):
              ?>
              <tr>
                <td class="px-4 py-2 align-middle"><?php echo $i++ ?></td>
                <td class="px-4 py-2 align-middle text-center">
                  <img src="<?php echo isset($row['img_path']) ? '../assets/img/'.$row['img_path'] : '' ?>" alt="" class="mx-auto rounded shadow max-h-24">
                </td>
                <td class="px-4 py-2 align-middle">
                  <p><strong>Name:</strong> <?php echo $row['name'] ?></p>
                  <p><strong>Category:</strong> <?php echo $row['cat'] ?></p>
                  <p><strong>Description:</strong> <?php echo $row['description'] ?></p>
                  <p><strong>Price:</strong> ₱<?php echo number_format($row['price'], 2) ?></p>
                </td>
                <td class="px-4 py-2 align-middle text-center">
                  <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition edit_menu" type="button"
                    data-id="<?php echo $row['id'] ?>"
                    data-name="<?php echo $row['name'] ?>"
                    data-status="<?php echo $row['status'] ?>"
                    data-description="<?php echo $row['description'] ?>"
                    data-price="<?php echo $row['price'] ?>"
                    data-category_id="<?php echo $row['category_id'] ?>"
                    data-img_path="<?php echo $row['img_path'] ?>">Edit</button>
                  <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition delete_menu" type="button"
                    data-id="<?php echo $row['id'] ?>">Delete</button>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function displayImg(input) {
    const cimg = document.getElementById('cimg');
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        cimg.src = e.target.result;
        cimg.classList.remove('hidden');
      }
      reader.readAsDataURL(input.files[0]);
    } else {
      cimg.src = '';
      cimg.classList.add('hidden');
    }
  }
  document.getElementById('img').addEventListener('change', function() {
    displayImg(this);
  });

  document.getElementById('cancel-btn').addEventListener('click', function() {
    document.getElementById('manage-menu').reset();
    document.getElementById('cimg').src = '';
    document.getElementById('cimg').classList.add('hidden');
  });

  document.getElementById('manage-menu').addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    fetch('ajax.php?action=save_menu', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(resp => {
      if (resp == 1) {
        alert('Menu successfully added');
        setTimeout(() => location.reload(), 1200);
      } else if (resp == 2) {
        alert('Menu successfully updated');
        setTimeout(() => location.reload(), 1200);
      } else {
        alert('Error saving menu');
      }
    });
  });

  document.querySelectorAll('.edit_menu').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const menu = document.getElementById('manage-menu');
      menu.reset();
      menu.querySelector("[name='id']").value = this.getAttribute('data-id');
      menu.querySelector("[name='name']").value = this.getAttribute('data-name');
      menu.querySelector("[name='description']").value = this.getAttribute('data-description');
      menu.querySelector("[name='price']").value = this.getAttribute('data-price');
      menu.querySelector("[name='category_id']").value = this.getAttribute('data-category_id');
      menu.querySelector("#availability").checked = this.getAttribute('data-status') == 1;
      const imgPath = this.getAttribute('data-img_path');
      const cimg = document.getElementById('cimg');
      if (imgPath) {
        cimg.src = '../assets/img/' + imgPath;
        cimg.classList.remove('hidden');
      } else {
        cimg.src = '';
        cimg.classList.add('hidden');
      }
    });
  });

  document.querySelectorAll('.delete_menu').forEach(function(btn) {
    btn.addEventListener('click', function() {
      if (confirm("Are you sure to delete this menu?")) {
        fetch('ajax.php?action=delete_menu', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id=' + encodeURIComponent(this.getAttribute('data-id'))
        })
        .then(res => res.text())
        .then(resp => {
          if (resp == 1) {
            alert('Menu successfully deleted');
            setTimeout(() => location.reload(), 1200);
          } else {
            alert('Error deleting menu');
          }
        });
      }
    });
  });
</script>
<?php $conn->close(); ?>