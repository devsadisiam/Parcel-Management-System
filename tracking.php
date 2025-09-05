<?php
// tracking.php
$host = "localhost";
$username = "root";
$db_password = ""; // your DB password
$dbname = "parcel_delivery"; // change if needed

$conn = new mysqli($host, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed!");
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$tracking_id = $_GET['tracking_id'] ?? '';
$parcel = null;
$senderName = '';

if ($tracking_id) {
    $stmt = $conn->prepare("SELECT * FROM parcels WHERE tracking_id = ?");
    $stmt->bind_param("s", $tracking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $parcel = $result->fetch_assoc();
    $stmt->close();

    if ($parcel && isset($parcel['user_id'])) {
        $stmt2 = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt2->bind_param("i", $parcel['user_id']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $user = $result2->fetch_assoc();
        $senderName = $user['business_name'] ?? 'Unknown Sender';
        $stmt2->close();
    }
}

// Define ordered steps per status
function getSteps($status) {
    $steps = [
        "pending" => ["Pending Pick-up"],
        "picked" => ["Pending Pick-up", "Picked Up"],
        "off to deliver" => ["Pending Pick-up", "Picked Up", "Off to Deliver"],
        "delivered" => ["Pending Pick-up", "Picked Up", "Off to Deliver", "Delivered"],
        "cancelled" => ["Pending Pick-up", "Picked Up", "Off to Deliver", "Cancelled"],
        "completed" => ["Pending Pick-up", "Picked Up", "Off to Deliver", "Delivered", "Completed"]
    ];
    return $steps[strtolower($status)] ?? ["Unknown Status"];
}

// Assign each step its own color
function getStepColor($step) {
    switch (strtolower($step)) {
        case "pending pick-up": return "bg-gray-400 text-gray-600";   // gray
        case "picked up": return "bg-info text-info";                // blue
        case "off to deliver": return "bg-warning text-warning";     // orange
        case "delivered": return "bg-success text-success";          // green
        case "cancelled": return "bg-danger text-danger";            // red
        case "completed": return "bg-primary text-primary";          // brand orange
        default: return "bg-gray-400 text-gray-600";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Parcel Tracking</title>
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
          },
          boxShadow: {
            smooth: '0 10px 30px rgba(0,0,0,0.08)',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-100 font-sans text-gray-800 min-h-screen">

<div class="max-w-5xl mx-auto py-12 px-4 animate-fadeIn">
  <div class="mb-10">
    <h1 class="text-3xl font-bold text-brand">📦 Track Your Parcel</h1>
    <p class="text-gray-600 mt-1">Here's the latest status of your shipment</p>
  </div>

  <?php if ($parcel): ?>
    <!-- Parcel Details -->
    <div class="bg-accent rounded-2xl shadow mb-10 overflow-hidden">
      <div class="bg-gradient-to-r from-primary to-orange-400 text-white px-6 py-4 rounded-t-2xl flex justify-between">
        <h2 class="text-lg font-semibold">📄 Parcel Details</h2>
        <span class="text-sm font-medium">Booked On: <?= date("Y-m-d H:i", strtotime($parcel['created_at'])) ?></span>
      </div>
      <div class="p-6 grid md:grid-cols-2 gap-4 text-sm">
        <p><span class="font-medium text-gray-700">Tracking ID:</span> <?= htmlspecialchars($parcel['tracking_id']) ?></p>
        <p><span class="font-medium text-gray-700">Sender:</span> <?= htmlspecialchars($senderName) ?></p>
        <p><span class="font-medium text-gray-700">Recipient:</span> <?= htmlspecialchars($parcel['recipient_name']) ?></p>
        <p><span class="font-medium text-gray-700">Weight:</span> <?= htmlspecialchars($parcel['weight']) ?> kg</p>
      </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-accent rounded-2xl shadow">
      <div class="bg-gradient-to-r from-primary to-orange-400 text-white px-6 py-4 rounded-t-2xl">
        <h2 class="text-lg font-semibold">🚚 Delivery Progress</h2>
      </div>
      <div class="p-6 relative border-l-4 border-primary pl-6 space-y-10">
        <?php 
        $steps = getSteps($parcel['status']); 
        foreach ($steps as $step): 
          $color = getStepColor($step);
        ?>
        <div class="relative">
          <div class="absolute -left-3 w-6 h-6 <?= explode(" ", $color)[0] ?> rounded-full border-4 border-white shadow-md"></div>
          <div class="ml-4">
            <h3 class="text-base font-semibold <?= explode(" ", $color)[1] ?> mb-1"><?= $step ?></h3>
            <p class="text-sm text-gray-600">
              <?php if ($step=="Pending Pick-up"): ?>We are processing your request.
              <?php elseif ($step=="Picked Up"): ?>Parcel has been collected from sender.
              <?php elseif ($step=="Off to Deliver"): ?>Courier is on the way to the recipient.
              <?php elseif ($step=="Delivered"): ?>Package successfully delivered.
              <?php elseif ($step=="Cancelled"): ?>Parcel delivery has been cancelled.
              <?php elseif ($step=="Completed"): ?>The order process is completed.
              <?php endif; ?>
            </p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php else: ?>
    <div class="bg-red-100 text-red-600 p-6 rounded-lg">
      ❌ No parcel found with Tracking ID: <strong><?= htmlspecialchars($tracking_id) ?></strong>
    </div>
  <?php endif; ?>

  <div class="mt-10 text-center">
    <a href="dashboard.php" class="px-6 py-2 bg-primary text-white rounded-lg font-medium shadow hover:bg-orange-600 transition">⬅ Back to Dashboard</a>
  </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
  animation: fadeIn 0.6s ease-out;
}
</style>
</body>
</html>
