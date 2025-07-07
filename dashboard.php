<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Parcel Dashboard</title>

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
<body class="bg-accent font-sans text-gray-800 min-h-screen flex flex-col">

  <!-- Header -->
  <header class="bg-primary">
    <div class="px-10 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-white">Parcel Dashboard</h1>
      <nav class="space-x-4 flex">
        <a href="#"
           class="bg-white text-primary font-bold text-sm px-4 py-2 rounded-lg hover:bg-orange-100 transition">
          User Profile
        </a>
        <a href="#"
           class="bg-white text-primary font-bold text-sm px-4 py-2 rounded-lg hover:bg-orange-100 transition">
          Logout
        </a>
      </nav>
    </div>
  </header>

  <!-- Main -->
  <main class="flex-grow px-10 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.4fr_1fr] gap-6 max-w-7xl mx-auto items-start">

      <!-- LEFT COLUMN -->
      <div class="space-y-6 px-2">
        <!-- Support Section with Title OUTSIDE -->
        

        <!-- Summary Cards - 4 Cards in 2 Rows -->
         <h2 class="text-xl font-semibold text-brand">Infos</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="bg-white p-5 rounded-xl text-center">
            <h3 class="text-sm text-gray-500">Total Parcels</h3>
            <p class="text-2xl font-bold text-brand mt-1">14</p>
          </div>
          
          <div class="bg-white p-5 rounded-xl text-center">
            <h3 class="text-sm text-gray-500">In Transit</h3>
            <p class="text-2xl font-bold text-info mt-1">5</p>
        
          </div>

          <div class="bg-white p-5 rounded-xl text-center">
            <h3 class="text-sm text-gray-500">Delivered</h3>
            <p class="text-2xl font-bold text-success mt-1">9</p>
           
          </div>

          <div class="bg-white p-5 rounded-xl text-center">
            <h3 class="text-sm text-gray-500">Returned</h3>
            <p class="text-2xl font-bold text-gray-500 mt-1">2</p>
            
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

      <!-- MIDDLE COLUMN (Booked Parcels) -->
      <div class="space-y-6 px-2">
        <!-- Booked Parcels -->
        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-brand">Booked Parcels</h2>
          <div class="space-y-4">
            <div class="bg-white rounded-xl p-5">
              <div class="flex justify-between items-center">
                <h3 class="font-semibold">Parcel #12345</h3>
                <span class="text-success font-medium">$40</span>
              </div>
              <p class="text-sm text-gray-600 mt-1">Status: <span class="text-brand font-medium">In Transit</span></p>
              <p class="text-sm text-gray-600">Destination: New York</p>
            </div>
            <div class="bg-white rounded-xl p-5">
              <div class="flex justify-between items-center">
                <h3 class="font-semibold">Parcel #12346</h3>
                <span class="text-success font-medium">$25</span>
              </div>
              <p class="text-sm text-gray-600 mt-1">Status: <span class="text-info font-medium">Dispatched</span></p>
              <p class="text-sm text-gray-600">Destination: Los Angeles</p>
            </div>
          </div>
        </section>

        <!-- Payments -->
        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-brand">Payments</h2>
          <div class="space-y-4">
            <div class="bg-white rounded-xl p-5">
              <div class="flex justify-between items-center">
                <h3 class="font-semibold">Parcel #12345</h3>
                <span class="text-success font-medium">$40</span>
              </div>
              <p class="text-sm text-gray-600 mt-1">Status: Paid</p>
              <p class="text-sm text-gray-600">Date: Jul 1, 2025</p>
            </div>
            <div class="bg-white rounded-xl p-5">
              <div class="flex justify-between items-center">
                <h3 class="font-semibold">Parcel #12346</h3>
                <span class="text-success font-medium">$25</span>
              </div>
              <p class="text-sm text-gray-600 mt-1">Status: Paid</p>
              <p class="text-sm text-gray-600">Date: Jul 3, 2025</p>
            </div>
          </div>
        </section>
      </div>

      <!-- RIGHT COLUMN -->
      <aside class="space-y-6 px-2">
        <!-- Quick Access Section -->
        <div class="space-y-4">
          <h2 class="text-lg font-semibold text-brand">Quick Access</h2>
          <div class="bg-sidebar border border-orange-150 p-6 rounded-xl space-y-6">
            <p class="text-sm text-gray-600">Track your parcel or book a new one with ease.</p>

            <!-- Parcel Tracking Input -->
            <div class="space-y-4">
              <input type="text" placeholder="Enter Parcel ID to Track" class="w-full p-3 rounded-lg focus:ring-2 focus:ring-primary" />
              <a href="track.html" class="block bg-primary text-white font-semibold text-center py-3 rounded-lg hover:bg-orange-600 transition">
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
