<?php
session_start();
require 'db_connect.php';

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? 1; // default for testing

// Fetch user info
$user_sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Handle new address form
$toast = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $address_name = trim($_POST['address_name']);
  $address = trim($_POST['address']);
  $address_id = isset($_POST['address_id']) ? (int)$_POST['address_id'] : 0;

  if (empty($address_name) || empty($address)) {
    $toast = "Please fill in all fields.";
  } else {
    // Prevent duplicate for same user (excluding current one if editing)
    $check_sql = "SELECT id FROM addresses WHERE user_id = ? AND (LOWER(address_name) = LOWER(?) OR LOWER(address) = LOWER(?))";
    if ($address_id > 0) {
      $check_sql .= " AND id != ?";
    }

    $stmt = $conn->prepare($check_sql);
    if ($address_id > 0) {
      $stmt->bind_param("issi", $user_id, $address_name, $address, $address_id);
    } else {
      $stmt->bind_param("iss", $user_id, $address_name, $address);
    }

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $toast = "Address name or address already exists.";
    } else {
      if ($address_id > 0) {
        // Update
        $update_sql = "UPDATE addresses SET address_name = ?, address = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssii", $address_name, $address, $address_id, $user_id);
        if ($stmt->execute()) {
          $toast = "Address updated.";
        } else {
          $toast = "Update failed.";
        }
      } else {
        // Insert
        $insert_sql = "INSERT INTO addresses (user_id, address_name, address) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iss", $user_id, $address_name, $address);
        if ($stmt->execute()) {
          $toast = "Address added successfully.";
      
        } else {
          $toast = "Something went wrong.";
        }
      }
    }
  }
}

if (isset($_POST['upload_image']) && isset($_FILES['profile_image'])) {
    $upload_dir = "uploads/";
    $image_tmp = $_FILES['profile_image']['tmp_name'];
    $image_name = uniqid() . "_" . basename($_FILES['profile_image']['name']);
    $target_path = $upload_dir . $image_name;

    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $file_type = $_FILES['profile_image']['type'];
    $upload_error = $_FILES['profile_image']['error'];

    if ($upload_error !== UPLOAD_ERR_OK) {
        // Handle specific errors
        switch ($upload_error) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $toast = "Image is too large.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $toast = "Image was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $toast = "No image file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $toast = "Missing temporary folder on server.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $toast = "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $toast = "File upload stopped by a PHP extension.";
                break;
            default:
                $toast = "Unknown error occurred during upload.";
                break;
        }
        $toast_type = "error";
    } elseif (!in_array($file_type, $allowed_types)) {
        $toast = "Only JPG and PNG images allowed.";
        $toast_type = "error";
    } elseif (!is_uploaded_file($image_tmp)) {
        $toast = "Invalid upload source.";
        $toast_type = "error";
    } elseif (!move_uploaded_file($image_tmp, $target_path)) {
        $toast = "Failed to move image to destination.";
        $toast_type = "error";
    } else {
        // Upload succeeded — save to DB
        $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $stmt->bind_param("si", $image_name, $user_id);
        if ($stmt->execute()) {
            $toast = "Image uploaded successfully!";
            $toast_type = "success";
        } else {
            $toast = "Database update failed.";
            $toast_type = "error";
        }
    }
}




// Handle address deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_address_id'])) {
    $delete_id = $_POST['delete_address_id'];
    $user_id = $_SESSION['user_id'];

    $delete_stmt = $conn->prepare("DELETE FROM addresses WHERE id = ? AND user_id = ?");
    $delete_stmt->bind_param("ii", $delete_id, $user_id);

    if ($delete_stmt->execute()) {
        $toast = "Address deleted successfully.";
    } else {
        $toast = "Failed to delete address.";
    }

    $delete_stmt->close();
}


// Fetch parcel summary for the logged-in user
$summary_sql = "SELECT 
    COUNT(*) AS total,
    SUM(CASE WHEN status = 'Delivered' THEN 1 ELSE 0 END) AS delivered,
    SUM(CASE WHEN status IN ('Picked','Off to Deliver') THEN 1 ELSE 0 END) AS in_transit,
    SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled
FROM parcels
WHERE user_id = ?";

$stmt = $conn->prepare($summary_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$summary = $result->fetch_assoc();

// Provide defaults in case of null
$summary = array_merge([
    'total' => 0,
    'delivered' => 0,
    'in_transit' => 0,
    'cancelled' => 0
], $summary);




// Fetch saved addresses
$address_sql = "SELECT id, address_name, address FROM addresses WHERE user_id = ?";
$stmt = $conn->prepare($address_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$addresses_result = $stmt->get_result();
$addresses = $addresses_result->fetch_all(MYSQLI_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Profile - Parcel Dashboard</title>

  <!-- Montserrat font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Montserrat', 'ui-sans-serif', 'system-ui'],
          },
          colors: {
            primary: '#f97316',
            accent: '#f5f5f5',
            sidebar: '#fff3e0',
            brand: '#f97316',
            info: '#3b82f6',
            success: '#10b981',
            cancelled: '#ef4444', // Red color for cancelled status
          }
        }
      }
    }

    // JavaScript to validate the form before submission
    function validateForm(event) {
        const addressName = document.getElementById('address_name').value.trim();
        const address = document.getElementById('address').value.trim();

        // Check if both fields are empty
        if (addressName === '' || address === '') {
            alert('Please fill in both the Address Name and Address fields!');
            event.preventDefault(); // Prevent form submission
        }
    }

  function editAddress(id, name, address) {
    // Fill the form with clicked address data
    document.getElementById('address_id').value = id;
    document.getElementById('address_name').value = name;
    document.getElementById('address').value = address;

    // Change the button text to indicate it's updating
    document.getElementById('submitBtn').innerText = "Update Address";
  }

  // Optional reset if you want to clear the form after update
  function resetAddressForm() {
    document.getElementById('address_id').value = '';
    document.getElementById('address_name').value = '';
    document.getElementById('address').value = '';
    document.getElementById('submitBtn').innerText = "Add Address";
  }

  function openImageModal() {
      document.getElementById("imageModal").classList.remove("hidden");
    }
    function closeImageModal() {
      document.getElementById("imageModal").classList.add("hidden");
    }

  </script>
</head>
<body class="bg-accent font-sans text-gray-800 min-h-screen">

  <?php if ($toast): ?>
  <div id="toast" class="fixed top-4 right-4 bg-primary text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-500">
    <?= htmlspecialchars($toast) ?>
  </div>
  <script>
    setTimeout(() => {
      document.getElementById('toast').style.opacity = '0';
    }, 3000);
  </script>
  <?php endif; ?>

  <!-- Image Upload Modal -->
  <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg w-80">
      <h3 class="text-lg font-semibold mb-4 text-center">Upload Profile Image</h3>
      <form method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_image" accept="image/*" required class="block w-full mb-4" />
        <div class="flex justify-end gap-2">
                    <button type="button" class="bg-gray-300 px-4 py-2 rounded" onclick="document.getElementById('imageModal').classList.add('hidden')">Cancel</button>
                    <button type="submit" name="upload_image" class="bg-primary text-white px-4 py-2 rounded hover:bg-orange-600">Upload</button>
                </div>
      </form>
    </div>
  </div>
  
  
  <!-- Main -->
  <main class="px-10 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.4fr_1fr] gap-6 max-w-7xl mx-auto items-start">

      <!-- LEFT COLUMN -->
      <div class="space-y-6 px-2">
        <!-- User Info Section -->
        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-primary">User Information</h2>
          <div class="bg-white p-6 rounded-xl flex flex-col items-center space-y-4">
            <div class="flex flex-col items-center">
              <!-- Smaller Image -->
              <img
      src="<?= $user['profile_image'] ? 'uploads/' . $user['profile_image'] : 'https://via.placeholder.com/80' ?>"
      alt="User"
      class="w-24 h-24 rounded-full border-2 border-orange-500 mx-auto mb-4 cursor-pointer"
      onclick="openImageModal()"
    />
              <div class="space-y-2 text-center">
                <div class="flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-primary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2-1h8a1 1 0 011 1v12a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  <span><strong class="text-gray-700">Full Name:</strong> <?= htmlspecialchars($user['business_name']) ?></span>
                </div>
                <div class="flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-primary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2-1h8a1 1 0 011 1v12a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  <span><strong class="text-gray-700">Email:</strong> <?= htmlspecialchars($user['email']) ?></span>
                </div>
                <div class="flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-primary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2-1h8a1 1 0 011 1v12a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  <span><strong class="text-gray-700">Phone:</strong> <?= htmlspecialchars($user['phone']) ?></span>
                </div>
                <div class="flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-primary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2-1h8a1 1 0 011 1v12a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  <span><strong class="text-gray-700">Joined On:</strong> <?= date('F j, Y', strtotime($user['created_at'])) ?></span>
                </div>
                
              </div>
            </div>
          </div>
        </section>

        <!-- Add New Address Section -->
        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-primary">Add New Address</h2>
          <div class="bg-white p-6 rounded-xl">
            <form method="POST" id="addressForm" class="space-y-4">
              <!-- Hidden input to store address ID if editing -->
              <input type="hidden" name="address_id" id="address_id" value="">

  <div>
    <label class="text-sm font-medium text-gray-700">Address Name</label>
    <input
      type="text"
      name="address_name"
      id="address_name"
      placeholder="e.g. Home, Office"
      class="w-full px-4 py-2 border rounded-lg"
    />
  </div>

  <div>
    <label class="text-sm font-medium text-gray-700">Full Address</label>
    <input
      type="text"
      name="address"
      id="address"
      placeholder="e.g. 123 Street, City, ZIP"
      class="w-full px-4 py-2 border rounded-lg"
    />
  </div>

  <button
    type="submit"
    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition duration-200"
    id="submitBtn"
  >
    Add Address
  </button>
</form>

          </div>
        </section>
      </div>

      <!-- MIDDLE COLUMN -->
      <div class="space-y-6 px-2">
        <!-- Parcel Summary Section -->
        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-primary">Parcel Summary</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Row 1 -->
            <div class="bg-white p-5 rounded-xl">
              <h3 class="text-sm text-gray-500">Total Parcels</h3>
              <p class="text-2xl font-bold text-brand mt-1"><?= $summary['total'] ?></p>
            </div>
            <div class="bg-white p-5 rounded-xl">
              <h3 class="text-sm text-gray-500">Delivered</h3>
              <p class="text-2xl font-bold text-brand mt-1"><?= $summary['delivered'] ?></p>
            </div>

            <!-- Row 2 -->
            <div class="bg-white p-5 rounded-xl">
              <h3 class="text-sm text-gray-500">In Transit</h3>
              <p class="text-2xl font-bold text-brand mt-1"><?= $summary['in_transit'] ?></p>
            </div>
            <div class="bg-white p-5 rounded-xl">
              <h3 class="text-sm text-gray-500">Cancelled</h3>
              <p class="text-2xl font-bold text-brand mt-1"><?= $summary['cancelled'] ?></p>
            </div>
          </div>
        </section>

        <!-- Saved Addresses Section -->
        <section class="space-y-4">
        <h2 class="text-xl font-semibold text-primary">Saved Addresses</h2>
        <div class="space-y-4">
          <?php if (count($addresses) > 0): ?>
            <?php foreach ($addresses as $a): ?>
              <div class="bg-white p-5 rounded-xl">
    <div class="flex justify-between items-center">
      <div>
        <h3 class="font-semibold"><?= htmlspecialchars($a['address_name']) ?></h3>
        <p class="text-sm text-gray-600"><?= htmlspecialchars($a['address']) ?></p>
      </div>
      <div class="flex space-x-2">
        <!-- Edit Button -->
        <button
          onclick='editAddress(<?= $a["id"] ?>, <?= json_encode($a["address_name"]) ?>, <?= json_encode($a["address"]) ?>)'
          class="text-sm text-blue-600 hover:underline">
          Edit
        </button>

        <!-- Delete Button -->
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
          <input type="hidden" name="delete_address_id" value="<?= $a['id'] ?>">
          <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
        </form>
      </div>
    </div>
  </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-sm text-gray-500">No addresses saved yet.</p>
          <?php endif; ?>
        </div>
      </section>
      </div>

      <!-- RIGHT COLUMN -->
      <aside class="space-y-6 px-2 overflow-hidden">
        <div class="space-y-4">
          <h2 class="text-lg font-semibold text-brand">Quick Access</h2>
          <div class="bg-sidebar border border-orange-150 p-6 rounded-xl space-y-6">
            <p class="text-sm text-gray-600">Track your parcel or book a new one with ease.</p>
            <div class="space-y-4">
              <form action="tracking.php" method="get">
                <input type="text" name="tracking_id" placeholder="Enter Parcel ID to Track" class="w-full p-3 rounded-lg focus:ring-2 focus:ring-primary" required />
                <button type="submit" class="w-full mt-4 bg-primary text-white font-semibold py-3 rounded-lg hover:bg-orange-600 transition">Track Parcel</button>
              </form>
            </div>
            <a href="booking.php" class="block bg-white border border-primary text-primary font-semibold text-center py-3 rounded-lg hover:bg-orange-100 transition">
              Book New Parcel
            </a>
          </div>
        </div>
      </aside>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-white border-t mt-10">
    <div class="px-10 py-4 text-center text-sm text-gray-500">
      &copy; 2025 Parcel Dashboard. All rights reserved.
    </div>
  </footer>

</body>
</html>
