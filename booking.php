<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$host = "localhost";
$username = "root";
$db_password = ""; // your DB password
$dbname = "parcel_delivery"; // change if needed

$conn = new mysqli($host, $username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed!");
}

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // Collect & sanitize inputs
    $recipient_name   = trim($_POST['recipient_name']);
    $recipient_phone  = trim($_POST['recipient_phone']);
    $parcelName       = trim($_POST['parcelName']);
    $weight           = trim($_POST['weight']);
    $amount           = trim($_POST['amount']);
    $pickup_address   = trim($_POST['pickup_address']);
    $delivery_address = trim($_POST['delivery_address']);

    // Validation
    if (empty($recipient_name) || strlen($recipient_name) < 3) {
        $errors[] = "Recipient name must be at least 3 characters.";
    }
    if (!preg_match("/^[0-9]{10,15}$/", $recipient_phone)) {
        $errors[] = "Invalid phone number (must be 10-15 digits).";
    }
    if (empty($parcelName)) {
        $errors[] = "Parcel name is required.";
    }
    if (!is_numeric($weight) || $weight <= 0) {
        $errors[] = "Weight must be a positive number.";
    }
    if (!is_numeric($amount) || $amount < 0) {
        $errors[] = "Amount must be a non-negative number.";
    }
    if (empty($pickup_address)) {
        $errors[] = "Pickup address is required.";
    }
    if (empty($delivery_address)) {
        $errors[] = "Delivery address is required.";
    }

    if (count($errors) === 0) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO parcels 
            (user_id, recipient_name, recipient_phone, parcel_name, weight, amount, pickup_address, delivery_address, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())");
        $stmt->bind_param("isssdsss", 
            $user_id, 
            $recipient_name, 
            $recipient_phone, 
            $parcelName, 
            $weight, 
            $amount, 
            $pickup_address, 
            $delivery_address
        );

        if ($stmt->execute()) {
            $success = true;
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Error booking parcel. Please try again.";
        }

        $stmt->close();
    }
}

// Fetch pickup addresses **only for logged-in user**
$pickup_addresses = [];
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, address_name FROM addresses WHERE user_id = ? ORDER BY address_name ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $pickup_addresses[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Book Parcel - Parcel Dashboard</title>

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
            brand: '#f97316',
            success: '#10b981',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-accent font-sans text-gray-800 min-h-screen">

  <!-- Main -->
  <main class="max-w-3xl mx-auto px-6 py-12">
    <div class="mb-10 text-center">
      <h1 class="text-3xl font-bold text-brand">📦 Book a New Parcel</h1>
      <p class="text-gray-600 mt-2">Fill in the details below to schedule a delivery</p>
    </div>

    <!-- Success Message -->
    <?php if ($success): ?>
      <div class="bg-success text-white p-4 rounded-lg mb-6">
        ✅ Parcel booked successfully!
      </div> 
    <?php endif; ?>

    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
      <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
        <ul class="list-disc list-inside">
          <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="" method="POST" class="bg-white p-8 rounded-2xl shadow-smooth space-y-6">
    
      <!-- Recipient Info -->
      <div>
        <h2 class="text-xl font-semibold text-primary mb-4">Recipient Information</h2>
        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label for="recipient_name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" id="recipient_name" name="recipient_name" value="<?= htmlspecialchars($_POST['recipient_name'] ?? '') ?>" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required />
          </div>
          <div>
            <label for="recipient_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" id="recipient_phone" name="recipient_phone" value="<?= htmlspecialchars($_POST['recipient_phone'] ?? '') ?>" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required />
          </div>
        </div>
      </div>

      <!-- Parcel Info -->
      <div>
        <h2 class="text-xl font-semibold text-primary mb-4">Parcel Details</h2>
        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label for="parcelName" class="block text-sm font-medium text-gray-700">Parcel Name</label>
            <input type="text" id="parcelName" name="parcelName" value="<?= htmlspecialchars($_POST['parcelName'] ?? '') ?>" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required />
          </div>
          <div>
            <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
            <input type="number" step="0.1" id="weight" name="weight" value="<?= htmlspecialchars($_POST['weight'] ?? '') ?>" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required />
          </div>
          <div>
            <label for="amount" class="block text-sm font-medium text-gray-700">Amount to be Collected</label>
            <input type="number" step="0.01" id="amount" name="amount" value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required />
          </div>
        </div>
      </div>

      <!-- Address Info -->
      <div>
        <h2 class="text-xl font-semibold text-primary mb-4">Delivery Information</h2>
        <div class="grid gap-4">
          <label for="pickup_address" class="block text-sm font-medium text-gray-700">Pickup Address</label>
          <select id="pickup_address" name="pickup_address" class="block w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-primary focus:outline-none appearance-none" required>
            <option value="" disabled <?= empty($_POST['pickup_address']) ? 'selected' : '' ?>>Select Pickup Location</option>
            <?php foreach ($pickup_addresses as $address): ?>
              <option value="<?= htmlspecialchars($address['address_name']) ?>" <?= ($_POST['pickup_address'] ?? '') === $address['address_name'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($address['address_name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="delivery_address" class="block pt-4 text-sm font-medium text-gray-700">Delivery Address</label>
          <input type="text" id="delivery_address" name="delivery_address" value="<?= htmlspecialchars($_POST['delivery_address'] ?? '') ?>" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" required />
        </div>
      </div>

      <!-- Submit Button -->
      <div>
        <button type="submit" class="w-full bg-primary text-white font-semibold py-3 rounded-lg hover:bg-orange-600 transition">
          📦 Book Parcel
        </button>
      </div>
    </form>

    <div class="mt-6 text-center">
      <a href="dashboard.php" class="text-primary text-sm hover:underline">← Back to Dashboard</a>
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
