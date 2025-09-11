<?php
session_start();

// DB connection
require 'db_connect.php';

// --------------------
// Admin Access Check
// --------------------

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to access this page.");
}

// Fetch the user's info
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();

// Restrict access if not admin
if ($is_admin != 1) {
    die("You cannot access this page. Only admins are allowed.");
}

// --------------------
// Handle POST requests
// --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Parcel status update
    if (isset($_POST['parcel_id']) && isset($_POST['new_status'])) {
        $parcel_id = $_POST['parcel_id'];
        $new_status = $_POST['new_status'];
        $stmt = $conn->prepare("UPDATE parcels SET status=? WHERE id=?");
        $stmt->bind_param("si", $new_status, $parcel_id);
        $_SESSION['toast_message'] = $stmt->execute() ? "Parcel status updated!" : "Failed to update status.";
        $stmt->close();
        header("Location: ".$_SERVER['PHP_SELF']."?tab=parcels");
        exit;
    }

    // Parcel delete
    if (isset($_POST['delete_parcel_id'])) {
        $parcel_id = $_POST['delete_parcel_id'];
        $stmt = $conn->prepare("DELETE FROM parcels WHERE id=?");
        $stmt->bind_param("i", $parcel_id);
        $_SESSION['toast_message'] = $stmt->execute() ? "Parcel deleted successfully!" : "Failed to delete parcel.";
        $stmt->close();
        header("Location: ".$_SERVER['PHP_SELF']."?tab=parcels");
        exit;
    }

    // User delete
    if (isset($_POST['delete_user_id'])) {
        $user_id_to_delete = $_POST['delete_user_id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $user_id_to_delete);
        $_SESSION['toast_message'] = $stmt->execute() ? "User removed successfully!" : "Failed to remove user.";
        $stmt->close();
        header("Location: ".$_SERVER['PHP_SELF']."?tab=users");
        exit;
    }
}

// --------------------
// Tabs selection
// --------------------
$tab = $_GET['tab'] ?? 'parcels';

// Users
$users = [];
if ($tab === 'users') {
    $usersResult = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
    while ($row = $usersResult->fetch_assoc()) $users[] = $row;
}

// Parcels
$parcels = [];
$statusCounts = [];
$totalParcels = 0;
if ($tab === 'parcels') {
    $parcelsResult = $conn->query("
        SELECT p.*, u.business_name, u.email 
        FROM parcels p 
        JOIN users u ON u.id = p.user_id 
        ORDER BY p.created_at DESC
    ");
    while ($row = $parcelsResult->fetch_assoc()) $parcels[] = $row;

    $statuses = ['Pending','Picked','Off to Deliver','Delivered','Cancelled','Completed'];
    $totalRes = $conn->query("SELECT COUNT(*) as cnt FROM parcels");
    $totalParcels = $totalRes->fetch_assoc()['cnt'];
    foreach ($statuses as $s) {
        $res = $conn->query("SELECT COUNT(*) as cnt FROM parcels WHERE status='$s'");
        $statusCounts[$s] = $res->fetch_assoc()['cnt'];
    }
}

$conn->close();

// Helper for status colors
function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'pending': return 'bg-gray-400';
        case 'picked': return 'bg-info';
        case 'off to deliver': return 'bg-warning';
        case 'delivered': return 'bg-success';
        case 'cancelled': return 'bg-danger';
        case 'completed': return 'bg-success';
        default: return 'bg-gray-400';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Admin Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: { sans: ['Montserrat','ui-sans-serif','system-ui'] },
      colors: {
        primary: '#f97316',
        info: '#3b82f6',
        success: '#10b981',
        warning: '#f97316',
        danger: '#ef4444',
        accent: '#f5f5f5',
      }
    }
  }
}
</script>
</head>
<body class="bg-accent font-sans text-gray-800 min-h-screen flex flex-col">

<!-- Navbar -->
<header class="bg-primary sticky top-0 z-50">
  <div class="px-10 py-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-white">Admin Panel</h1>
    <a href="logout.php" class="bg-accent text-black px-4 py-2 rounded hover:bg-primary/70 transition">Logout</a>
  </div>
</header>

<div class="flex flex-1">

<!-- Sidebar -->
<aside class="w-1/8 min-w-[180px] bg-white h-screen sticky top-0 flex flex-col">
    <nav class="flex flex-col mt-4">
        <a href="?tab=parcels" class="px-6 py-3 hover:bg-primary/30 <?= $tab==='parcels'?'bg-primary/90 text-white':'' ?>">Parcels</a>
        <a href="?tab=users" class="px-6 py-3 hover:bg-primary/30 <?= $tab==='users'?'bg-primary/90 text-white':'' ?>">Users</a>
    </nav>
</aside>

<!-- Main content -->
<main class="flex-1 p-8 overflow-auto">

<?php if($tab==='parcels'): ?>
    <!-- Parcels Info Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-6 mb-8">
        <div class="bg-white p-5 rounded-[8px] text-center">
            <h3 class="text-sm text-gray-500">Total Parcels</h3>
            <p class="text-2xl font-bold text-primary mt-1"><?= $totalParcels ?></p>
        </div>
        <?php foreach($statusCounts as $status=>$count): 
            $color = match(strtolower($status)) {
                'pending'=>'text-gray-700',
                'picked'=>'text-info',
                'off to deliver'=>'text-warning',
                'delivered'=>'text-success',
                'cancelled'=>'text-danger',
                'completed'=>'text-success',
                default=>'text-gray-700'
            };
        ?>
        <div class="bg-white p-5 rounded-[8px] text-center">
            <h3 class="text-sm text-gray-500"><?= $status ?></h3>
            <p class="text-2xl font-bold <?= $color ?> mt-1"><?= $count ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Parcels Table -->
    <section class="bg-white rounded-[8px] overflow-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-white">
                <tr>
                    <th class="px-4 py-2 text-left">Tracking ID</th>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">Parcel Name</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach($parcels as $p): ?>
                <tr>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['tracking_id']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['business_name']) ?> (<?= htmlspecialchars($p['email']) ?>)</td>
                    <td class="px-4 py-2"><?= htmlspecialchars($p['parcel_name']) ?></td>
                    <td class="px-4 py-2">৳ <?= htmlspecialchars($p['amount']) ?></td>
                    <td class="px-4 py-2">
                        <span class="px-3 py-1 rounded-full text-white <?= getStatusColor($p['status']) ?>">
                            <?= htmlspecialchars($p['status']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-2"><?= date("j M, Y", strtotime($p['created_at'])) ?></td>
                    <td class="px-4 py-2 flex gap-2 items-center">

                        <!-- Delete -->
                        <form method="POST" action="" onsubmit="return confirm('Delete this parcel?');">
                            <input type="hidden" name="delete_parcel_id" value="<?= $p['id'] ?>">
                            <button type="submit" class="bg-danger text-white px-4 py-2 rounded text-sm font-semibold hover:bg-red-600 transition">Delete</button>
                        </form>

                        <!-- Status Dropdown -->
                        <form method="POST" action="" class="flex items-center gap-2">
                            <input type="hidden" name="parcel_id" value="<?= $p['id'] ?>">
                            <select name="new_status" class="border rounded px-2 py-1 text-sm" onchange="this.form.submit()">
                                <?php 
                                $all_statuses = ['Pending','Picked','Off to Deliver','Delivered','Cancelled','Completed'];
                                foreach ($all_statuses as $s): ?>
                                    <option value="<?= $s ?>" <?= $s == $p['status'] ? 'selected' : '' ?>><?= $s ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

<?php elseif($tab==='users'): ?>
    <!-- Users Table -->
    <section class="bg-white rounded-[8px] overflow-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-white">
                <tr>
                    <th class="px-4 py-2 text-left">User Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Phone</th>
                    <th class="px-4 py-2 text-left">Joined On</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach($users as $u): ?>
                <tr>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['business_name']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['email']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['phone']) ?></td>
                    <td class="px-4 py-2"><?= date("j M, Y", strtotime($u['created_at'])) ?></td>
                    <td class="px-4 py-2 flex gap-2">
                        <!-- Remove -->
                        <form method="POST" action="" onsubmit="return confirm('Remove this user?');">
                            <input type="hidden" name="delete_user_id" value="<?= $u['id'] ?>">
                            <button type="submit" class="bg-danger text-white px-4 py-2 rounded text-sm font-semibold hover:bg-red-600 transition">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>

</main>
</div>

<!-- Toast -->
<?php if (isset($_SESSION['toast_message'])): ?>
  <div id="toast" class="fixed bottom-4 right-4 bg-primary text-white px-6 py-3 rounded-lg z-50 opacity-0 translate-y-4 transition-all duration-500">
    <?= htmlspecialchars($_SESSION['toast_message']) ?>
  </div>
  <script>
    window.addEventListener("DOMContentLoaded", () => {
      const toast = document.getElementById("toast");
      if (toast) {
        setTimeout(() => { toast.classList.remove("opacity-0", "translate-y-4"); }, 200);
        setTimeout(() => { toast.classList.add("opacity-0", "translate-y-4"); }, 4000);
      }
    });
  </script>
  <?php unset($_SESSION['toast_message']); ?>
<?php endif; ?>

</body>
</html>
