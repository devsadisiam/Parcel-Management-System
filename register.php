<?php
$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $business_name = trim($_POST['business_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    if (empty($business_name) || empty($email) || empty($phone) || empty($password)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        $host = "localhost";
        $username = "root";
        $db_password = ""; // your DB password
        $dbname = "parcel_delivery"; // change this

        $conn = new mysqli($host, $username, $db_password, $dbname);

        if ($conn->connect_error) {
            $error = "Database connection failed!";
        } else {
            // Check if email exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Email already exists!";
            } else {
                $stmt->close();

                // Check if phone exists
                $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
                $stmt->bind_param("s", $phone);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $error = "Phone number already exists!";
                } else {
                    $stmt->close();

                    // Insert new user
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $conn->prepare("INSERT INTO users (business_name, email, phone, password) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $business_name, $email, $phone, $hashedPassword);

                    if ($stmt->execute()) {
                        $success = "Registration successful! Redirecting to login...";
                        echo "<script>setTimeout(() => { window.location.href = 'login.php'; }, 2000);</script>";
                    } else {
                        $error = "Registration failed. Try again.";
                    }
                }
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Parcel Dashboard</title>
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
          },
          boxShadow: {
            smooth: '0 10px 30px rgba(0,0,0,0.08)',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-100 font-sans text-gray-800 min-h-screen flex items-center justify-center px-4">

  <!-- Toast -->
  <?php if ($error): ?>
    <div id="toast" class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow z-50"><?= $error ?></div>
  <?php elseif ($success): ?>
    <div id="toast" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow z-50"><?= $success ?></div>
  <?php endif; ?>

  <!-- Register Container -->
  <div class="bg-orange-50 rounded-2xl shadow-smooth w-full max-w-3xl grid grid-cols-1 md:grid-cols-[55%_45%] overflow-hidden animate-fadeIn">

    <!-- Form Section -->
    <div class="p-8 sm:p-10 flex flex-col justify-center">
      <div class="border-l-4 border-primary pl-4 mb-6">
        <h1 class="text-2xl font-bold text-brand">Create an Account</h1>
        <p class="text-gray-600 text-sm mt-1">Register to get started with your parcel dashboard</p>
      </div>

      <form id="registerForm" method="POST" class="space-y-5" onsubmit="return validateForm()">
        <div>
          <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
          <input type="text" id="business_name" name="business_name"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" />
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" id="email" name="email"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" />
        </div>

        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
          <input type="text" id="phone" name="phone"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" id="password" name="password"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" />
        </div>

        <button type="submit"
                class="w-full bg-primary text-white py-3 rounded-lg font-semibold text-base hover:bg-orange-600 transition duration-200">
          Register
        </button>
      </form>

      <p class="text-sm text-center text-gray-600 mt-6">
        Already have an account?
        <a href="login.php" class="text-primary font-semibold hover:underline">Login here</a>
      </p>
    </div>

    <!-- Image -->
    <div class="w-full h-full flex items-center justify-center bg-white p-4">
      <img src="https://img.freepik.com/premium-photo/png-food-delivery-man-riding-bicycle-transportation-motorcycle-cardboard_53876-756064.jpg"
           alt="Parcel Delivery"
           class="max-w-full max-h-full object-contain" />
    </div>
  </div>

  <!-- Animation -->
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
      animation: fadeIn 0.6s ease-out;
    }
  </style>

  <!-- JS Toast & Form Validation -->
  <script>
    function showToast(message, color = 'bg-red-500') {
      const toast = document.getElementById('toast');
      toast.textContent = message;
      toast.className = `fixed top-5 right-5 text-white px-4 py-2 rounded shadow z-50 ${color}`;
      toast.classList.remove('hidden');

      setTimeout(() => {
        toast.classList.add('hidden');
      }, 3000);
    }

    function validateForm() {
      const businessName = document.getElementById('business_name').value.trim();
      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const password = document.getElementById('password').value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!businessName || !email || !phone || !password) {
        showToast("All fields are required!");
        return false;
      }

      if (!emailRegex.test(email)) {
        showToast("Please enter a valid email address!");
        return false;
      }

      return true;
    }
  </script>
</body>
</html>
