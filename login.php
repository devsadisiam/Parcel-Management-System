<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Parcel Dashboard</title>

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

  
  <!-- Login Container -->
<div class="bg-orange-50 rounded-2xl shadow-smooth w-full max-w-3xl grid grid-cols-1 md:grid-cols-[52%_48%] overflow-hidden animate-fadeIn">


    <!-- Left: Login Form -->
    <div class="p-8 sm:p-10 flex flex-col justify-center">
      <div class="border-l-4 border-primary pl-4 mb-6">
        <h1 class="text-2xl font-bold text-brand">Welcome Back</h1>
        <p class="text-gray-600 text-sm mt-1">Log in to access your parcel dashboard</p>
      </div>

      <form action="dashboard.php" method="POST" class="space-y-5">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" id="email" name="email" required
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" id="password" name="password" required
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" />
        </div>

        <button type="submit"
                class="w-full bg-primary text-white py-3 rounded-lg font-semibold text-base hover:bg-orange-600 transition duration-200">
          Login
        </button>
      </form>

      <p class="text-sm text-center text-gray-600 mt-6">
        Don't have an account?
        <a href="register.php" class="text-primary font-semibold hover:underline">Register here</a>
      </p>
    </div>

    <!-- Right: Branding Image -->
    <div class="w-full h-full flex items-center justify-center bg-white p-4">
  <img src="https://img.freepik.com/premium-photo/png-food-delivery-man-riding-bicycle-transportation-motorcycle-cardboard_53876-756064.jpg"
       alt="Parcel Delivery"
       class="max-w-full max-h-full object-contain" />
</div>

  </div>

  <!-- Optional Fade Animation -->
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
