<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Parcel Tracking</title>

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
<body class="bg-gray-100 font-sans text-gray-800 min-h-screen">

  <!-- Page Container -->
  <div class="max-w-5xl mx-auto py-12 px-4 animate-fadeIn">
    <div class="mb-10">
      <h1 class="text-3xl font-bold text-brand">📦 Track Your Parcel</h1>
      <p class="text-gray-600 mt-1">Here's the latest status of your shipment</p>
    </div>

    <!-- Parcel Details Section -->
    <div class="bg-accent rounded-2xl shadow-smooth mb-10 overflow-hidden">
      <div class="bg-gradient-to-r from-primary to-orange-400 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
        <h2 class="text-lg font-semibold flex items-center gap-2">📄 Parcel Details</h2>
        <span class="text-sm font-medium">Last updated: 2025-07-19 11:00 AM</span>
      </div>
      <div class="p-6">
        <div class="grid md:grid-cols-2 gap-4 text-sm">
          <p><span class="font-medium text-gray-700">Tracking ID:</span> ABC123</p>
          <p><span class="font-medium text-gray-700">Sender:</span> Sadi Courier</p>
          <p><span class="font-medium text-gray-700">Recipient:</span> John Doe</p>
          <p><span class="font-medium text-gray-700">Current Location:</span> Dhaka Distribution Center</p>
          <p><span class="font-medium text-gray-700">Expected Delivery:</span> 2025-07-21</p>
          <p><span class="font-medium text-gray-700">Weight:</span> 2.5 kg</p>
        </div>
      </div>
    </div>

    <!-- Tracking Steps Section -->
    <div class="bg-accent rounded-2xl shadow-smooth">
      <div class="bg-gradient-to-r from-primary to-orange-400 text-white px-6 py-4 rounded-t-2xl">
        <h2 class="text-lg font-semibold flex items-center gap-2">🚚 Delivery Progress</h2>
      </div>
      <div class="p-6 relative border-l-4 border-primary pl-6 space-y-10">
        <!-- Step Template -->
        <div class="relative">
          <div class="absolute -left-3 w-6 h-6 bg-primary rounded-full border-4 border-white shadow-md"></div>
          <div class="ml-4">
            <h3 class="text-base font-semibold text-gray-800 mb-1">Pending Pick-up</h3>
            <p class="text-sm text-gray-600">We are processing your request.</p>
            <span class="text-xs text-gray-500">📅 2025-07-17</span>
          </div>
        </div>

        <div class="relative">
          <div class="absolute -left-3 w-6 h-6 bg-primary rounded-full border-4 border-white shadow-md"></div>
          <div class="ml-4">
            <h3 class="text-base font-semibold text-gray-800 mb-1">Picked Up</h3>
            <p class="text-sm text-gray-600">Parcel has been collected from sender.</p>
            <span class="text-xs text-gray-500">📅 2025-07-18</span>
          </div>
        </div>

        <div class="relative">
          <div class="absolute -left-3 w-6 h-6 bg-primary rounded-full border-4 border-white shadow-md"></div>
          <div class="ml-4">
            <h3 class="text-base font-semibold text-gray-800 mb-1">Went to Deliver</h3>
            <p class="text-sm text-gray-600">Courier is on the way to the recipient.</p>
            <span class="text-xs text-gray-500">📅 2025-07-19</span>
          </div>
        </div>

        <div class="relative">
          <div class="absolute -left-3 w-6 h-6 bg-success rounded-full border-4 border-white shadow-md"></div>
          <div class="ml-4">
            <h3 class="text-base font-semibold text-success mb-1">Delivered</h3>
            <p class="text-sm text-gray-600">Package successfully delivered to the recipient.</p>
            <span class="text-xs text-gray-500">📅 2025-07-19</span>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-10 text-center">
      <a href="index.php" class="inline-block px-6 py-2 bg-primary text-white rounded-lg font-medium shadow hover:bg-orange-600 transition">⬅ Back to Home</a>
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

</body>
</html>