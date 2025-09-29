<?php include('db_connect.php'); ?>

<div class="pl-64 pt-16 min-h-screen bg-gray-50">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
    <!-- Category Form -->
    <div class="col-span-1">
      <form action="" id="manage-category" enctype="multipart/form-data">
        <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
          <div class="mb-4 border-b pb-2">
            <h5 class="text-xl font-bold text-green-700">Category Form</h5>
          </div>
          <input type="hidden" name="id">
          <div class="mb-4">
            <label class="block font-medium mb-1">Category</label>
            <input type="text" class="w-full border rounded px-3 py-2" name="name" placeholder="Enter category name (e.g., Pizza)" required>
          </div>
          <div class="mb-4">
            <label class="block font-medium mb-1">Category Image</label>
            <input type="file" class="w-full border rounded px-3 py-2" name="img" id="img" accept="image/*">
          </div>
          <div class="mb-4 text-center">
            <img src="" alt="" id="cimg" class="mx-auto rounded shadow max-h-36 hidden">
          </div>
          <div class="flex gap-2 justify-center">
            <button class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition" type="submit">Save</button>
            <button class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 transition" type="button" id="cancel-btn">Cancel</button>
          </div>
        </div>
      </form>
    </div>

    <!-- Category Table -->
    <div class="col-span-1 lg:col-span-2">
      <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-green-50">
              <tr>
                <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase">#</th>
                <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase">Image</th>
                <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase">Name</th>
                <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <?php 
              $i = 1;
              $cats = $conn->query("SELECT * FROM category_list ORDER BY id ASC");
              while($row=$cats->fetch_assoc()):
              ?>
              <tr>
                <td class="px-4 py-2 text-center align-middle"><?php echo $i++ ?></td>
                <td class="px-4 py-2 text-center align-middle">
                  <img src="<?php echo isset($row['img_path']) ? '../assets/img/' . $row['img_path'] : '' ?>" alt="Category Image" class="mx-auto rounded shadow max-h-24">
                </td>
                <td class="px-4 py-2 text-center align-middle">
                  <?php echo $row['name'] ?>
                </td>
                <td class="px-4 py-2 text-center align-middle">
                  <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition edit_cat" type="button"
                    data-id="<?php echo $row['id'] ?>"
                    data-name="<?php echo $row['name'] ?>"
                    data-img_path="<?php echo $row['img_path'] ?>">Edit</button>
                  <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition delete_cat" type="button"
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
  document.getElementById('img').addEventListener('change', function() {
    const cimg = document.getElementById('cimg');
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        cimg.src = e.target.result;
        cimg.classList.remove('hidden');
      }
      reader.readAsDataURL(this.files[0]);
    } else {
      cimg.src = '';
      cimg.classList.add('hidden');
    }
  });

  document.getElementById('cancel-btn').addEventListener('click', function() {
    document.getElementById('manage-category').reset();
    document.getElementById('cimg').src = '';
    document.getElementById('cimg').classList.add('hidden');
  });

  document.getElementById('manage-category').addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    fetch('ajax.php?action=save_category', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(resp => {
      if (resp == 1) {
        alert('Category successfully added');
        setTimeout(() => location.reload(), 1200);
      } else if (resp == 2) {
        alert('Category successfully updated');
        setTimeout(() => location.reload(), 1200);
      } else {
        alert('Error saving category');
      }
    });
  });

  document.querySelectorAll('.edit_cat').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const cat = document.getElementById('manage-category');
      cat.reset();
      cat.querySelector("[name='id']").value = this.getAttribute('data-id');
      cat.querySelector("[name='name']").value = this.getAttribute('data-name');
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

  document.querySelectorAll('.delete_cat').forEach(function(btn) {
    btn.addEventListener('click', function() {
      if (confirm("Are you sure to delete this category?")) {
        fetch('ajax.php?action=delete_category', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id=' + encodeURIComponent(this.getAttribute('data-id'))
        })
        .then(res => res.text())
        .then(resp => {
          if (resp == 1) {
            alert('Category successfully deleted');
            setTimeout(() => location.reload(), 1200);
          } else {
            alert('Error deleting category');
          }
        });
      }
    });
  });
</script>
<?php $conn->close(); ?>