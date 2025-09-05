<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// DB connection
$host = "localhost";
$username = "root";
$db_password = ""; // your DB password
$dbname = "parcel_delivery"; // change if needed
$conn = new mysqli($host, $username, $db_password, $dbname);
if ($conn->connect_error) {
    die("DB connection failed!");
}

$user_id = $_SESSION['user_id'];

// Count stats
$totalQuery = $conn->query("SELECT COUNT(*) as total FROM parcels WHERE user_id=$user_id");
$total = $totalQuery->fetch_assoc()['total'];

$inTransitQuery = $conn->query("SELECT COUNT(*) as c FROM parcels WHERE user_id=$user_id AND status='Off to Deliver'");
$inTransit = $inTransitQuery->fetch_assoc()['c'];

$deliveredQuery = $conn->query("SELECT COUNT(*) as c FROM parcels WHERE user_id=$user_id AND status='Delivered'");
$delivered = $deliveredQuery->fetch_assoc()['c'];

$cancelledQuery = $conn->query("SELECT COUNT(*) as c FROM parcels WHERE user_id=$user_id AND status='Cancelled'");
$cancelled = $cancelledQuery->fetch_assoc()['c'];

// Fetch parcel list
$parcelsResult = $conn->query("SELECT * FROM parcels WHERE user_id=$user_id ORDER BY created_at DESC");
$parcels = [];
if ($parcelsResult) {
    while ($row = $parcelsResult->fetch_assoc()) {
        $parcels[] = $row;
    }
}

$conn->close();

function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'pending':
            return 'bg-gray-400'; // or another color for pending
        case 'picked':
            return 'bg-info'; // maybe blue-ish
        case 'off to deliver':
            return 'bg-warning'; // maybe yellow/orange
        case 'delivered':
            return 'bg-success'; // green
        case 'cancelled':
            return 'bg-danger'; // red
        case 'completed':
            return 'bg-primary'; // another color for completed
        default:
            return 'bg-gray-400';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Parcel Dashboard</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet" />

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
            cancelled: '#ef4444',
            warning: '#f97316',
            danger: '#ef4444',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-accent font-sans text-gray-800 min-h-screen flex flex-col overflow-hidden">

  <!-- Header -->
  <header class="bg-primary sticky top-0 z-50">
    <div class="px-10 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-white">Parcel Dashboard</h1>
      <nav class="space-x-4 flex">
        <a href="profile.php" class="bg-white text-primary font-bold text-sm px-4 py-2 rounded-lg hover:bg-orange-100 transition">User Profile</a>
        <a href="logout.php" class="bg-white text-primary font-bold text-sm px-4 py-2 rounded-lg hover:bg-orange-100 transition">Logout</a>
      </nav>
    </div>
  </header>

  <!-- Main -->
  <main class="flex-grow overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.4fr_1fr] gap-6 max-w-7xl mx-auto px-10 py-6 h-[calc(100vh-64px)]">
      
      <!-- LEFT COLUMN -->
      <div class="space-y-6 px-2 overflow-hidden">
        <h2 class="text-xl font-semibold text-brand">Infos</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="bg-white p-5 rounded-xl text-center">
            <h3 class="text-sm text-gray-500">Total Parcels</h3>
            <p class="text-2xl font-bold text-brand mt-1"><?= $total ?></p>
          </div>
          <div class="bg-white p-5 rounded-xl text-center">
            <h3 class="text-sm text-gray-500">In Transit</h3>
            <p class="text-2xl font-bold text-info mt-1"><?= $inTransit ?></p>
          </div>
          <div class="bg-white p-5 rounded-xl text-center">
            <h3 class="text-sm text-gray-500">Delivered</h3>
            <p class="text-2xl font-bold text-success mt-1"><?= $delivered ?></p>
          </div>
          <div class="bg-white p-5 rounded-xl text-center">
            <h3 class="text-sm text-gray-500">Cancelled</h3>
            <p class="text-2xl font-bold text-cancelled mt-1"><?= $cancelled ?></p>
          </div>
        </div>
        <div>
          <div class="bg-white p-6 rounded-xl">
            <h2 class="text-lg font-semibold text-brand mb-2">Need Support?</h2>
            <p class="text-sm text-gray-700 leading-relaxed">
              Have any questions or need help with your parcel delivery? Reach out to our support team, and we will assist you.
            </p>
          </div>
        </div>
      </div>

      <!-- MIDDLE COLUMN -->
      <div class="space-y-6 px-2 overflow-y-auto pr-2 h-full">
        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-brand">Booked Parcels</h2>

          <?php if (empty($parcels)): ?>
            <p class="text-gray-500">No parcels booked yet.</p>
          <?php else: ?>
            <?php foreach ($parcels as $parcel): ?>
              <div class="bg-white rounded-xl p-4">
                <div class="flex justify-between items-center">
                  <div class="flex items-center space-x-3">
                    <p class="text-sm text-gray-500"><?= date("j M, Y", strtotime($parcel['created_at'])) ?></p>
                    <div class="inline-block <?= getStatusColor($parcel['status']) ?> text-white text-xs font-medium px-3 py-1 rounded-full">
                      <?= htmlspecialchars($parcel['status']) ?>
                    </div>
                    <p class="text-xs text-orange-500"><?= htmlspecialchars($parcel['tracking_id']) ?></p>
                  </div>
                  <p class="text-sm text-white font-medium px-2 py-1 rounded-full bg-orange-500">
                    ৳ <?= htmlspecialchars($parcel['amount']) ?>
                  </p>
                </div>

                <h3 class="text-base font-semibold text-gray-800 mt-2"><?= htmlspecialchars($parcel['parcel_name']) ?></h3>
                <div class="flex items-center space-x-2 mt-2">
                  
                  <i class="fas fa-map-marker-alt text-gray-500 text-sm"></i>
                  <p class="text-sm text-gray-600"><?= htmlspecialchars($parcel['delivery_address']) ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
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

</body>
</html>
