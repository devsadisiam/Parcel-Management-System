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
  </script>
</head>
<body class="bg-accent font-sans text-gray-800 min-h-screen">

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
              <img src="https://via.placeholder.com/80" alt="User Image" class="w-20 h-20 rounded-full border-2 border-primary mb-4" />
              <div class="space-y-2 text-center">
                <div class="flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-primary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2-1h8a1 1 0 011 1v12a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  <span><strong class="text-gray-700">Full Name:</strong> Sadi Ahmed</span>
                </div>
                <div class="flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-primary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2-1h8a1 1 0 011 1v12a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  <span><strong class="text-gray-700">Email:</strong> sadi@example.com</span>
                </div>
                <div class="flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-primary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2-1h8a1 1 0 011 1v12a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  <span><strong class="text-gray-700">Phone:</strong> +880 1234 567890</span>
                </div>
                <div class="flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-primary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2-1h8a1 1 0 011 1v12a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  <span><strong class="text-gray-700">Joined On:</strong> December 1, 2024</span>
                </div>
                <div class="flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-primary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2-1h8a1 1 0 011 1v12a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  <span><strong class="text-gray-700">Address:</strong> 123/B, Dhanmondi</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Add New Address Section -->
        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-primary">Add New Address</h2>
          <div class="bg-white p-6 rounded-xl">
            <form action="#" method="POST" class="space-y-4">
              <div>
                <label for="address_name" class="block text-sm font-medium text-gray-700">Address Name</label>
                <input type="text" id="address_name" name="address_name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Enter the address name (e.g., Home, Office)" required />
              </div>
              <div>
                <label for="address" class="block text-sm font-medium text-gray-700">New Address</label>
                <input type="text" id="address" name="address" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Enter your new address" required />
              </div>
              <div>
                <button type="submit" class="w-full bg-primary text-white py-2 rounded-md hover:bg-orange-600 focus:outline-none">Save Address</button>
              </div>
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
              <p class="text-2xl font-bold text-brand mt-1">24</p>
            </div>
            <div class="bg-white p-5 rounded-xl">
              <h3 class="text-sm text-gray-500">Delivered</h3>
              <p class="text-2xl font-bold text-success mt-1">18</p>
            </div>

            <!-- Row 2 -->
            <div class="bg-white p-5 rounded-xl">
              <h3 class="text-sm text-gray-500">In Transit</h3>
              <p class="text-2xl font-bold text-info mt-1">4</p>
            </div>
            <div class="bg-white p-5 rounded-xl">
              <h3 class="text-sm text-gray-500">Cancelled</h3>
              <p class="text-2xl font-bold text-cancelled mt-1">2</p>
            </div>
          </div>
        </section>

        <!-- Saved Addresses Section -->
        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-primary">Saved Addresses</h2>
          <div class="space-y-4">
            <!-- Address 1 -->
            <div class="bg-white p-5 rounded-xl">
              <div class="flex justify-between items-center">
                <h3 class="font-semibold">Home Address</h3>
                <button class="text-sm text-gray-500 hover:text-primary">Edit</button>
              </div>
              <p class="text-sm text-gray-600">123/B, Dhanmondi, Dhaka</p>
            </div>

            <!-- Address 2 -->
            <div class="bg-white p-5 rounded-xl">
              <div class="flex justify-between items-center">
                <h3 class="font-semibold">Office Address</h3>
                <button class="text-sm text-gray-500 hover:text-primary">Edit</button>
              </div>
              <p class="text-sm text-gray-600">456/A, Banani, Dhaka</p>
            </div>
          </div>
        </section>
      </div>

      <!-- RIGHT COLUMN -->
      <aside class="space-y-6 px-2">
        <!-- Quick Access Section -->
        <div class="space-y-4">
          <h2 class="text-xl font-semibold text-primary">Quick Access</h2>
          <div class="bg-sidebar border border-orange-150 p-6 rounded-xl space-y-6">
            <p class="text-sm text-gray-600">Track your parcel or book a new one with ease.</p>

            <!-- Parcel Tracking Input -->
            <div class="space-y-4">
              <input type="text" placeholder="Enter Parcel ID to Track" class="w-full p-3 rounded-lg focus:ring-2 focus:ring-primary" />
              <a href="tracking.php" class="block bg-primary text-white font-semibold text-center py-3 rounded-lg hover:bg-orange-600 transition">
                Track Parcel
              </a>
            </div>

            <!-- Booking New Parcel -->
            <a href="booking.html" class="block bg-white border border-primary text-primary font-semibold text-center py-3 rounded-lg hover:bg-orange-100 transition">
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
