<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Parcel Delivery | Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#f97316',
            accent: '#fff7ed',
          },
          fontFamily: {
            sans: ['Montserrat', 'ui-sans-serif', 'system-ui'],
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

  <!-- Hero Section -->
 <header class="bg-white bg-cover bg-no-repeat bg-center" style="background-image: url('assets/parcel_home_bg.png');">
  <div class="bg-white/10">
    <div class="container mx-auto px-6 py-12 flex flex-col md:flex-row items-center justify-between gap-6">
      
      <!-- Left: Text Content -->
      <div class="space-y-4 md:w-1/2">
        <h1 class="text-4xl font-bold text-gray-900">Smart, Fast & Reliable Parcel Delivery</h1>
        <p class="text-gray-600 text-lg">Connect to a trusted logistics network and track your parcels with confidence.</p>
        <div class="space-x-4">
          <a href="login.php" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-orange-600 transition">Login to Account</a>
          <a href="register.php" class="px-6 py-3 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">Register new Account</a>
        </div>
      </div>

      <!-- Right: Optional spacing or extra content -->
      <div class="md:w-1/2 h-64 md:h-80"></div>

    </div>
  </div>
</header>


  <!-- Track Parcel -->
  <section id="track" class="bg-accent py-12">
  <div class="container mx-auto px-6">
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl mx-auto">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Track Your Parcel</h2>
      <div class="flex">
        <input type="text" placeholder="Enter tracking number" class="flex-grow px-4 py-3 border rounded-l-lg focus:outline-none">
        <button class="px-6 py-3 bg-primary text-white rounded-r-lg hover:bg-orange-600">Track</button>
      </div>
    </div>
  </div>
</section>

  <!-- Services -->
  <section class="w-full px-6 py-16 bg-gray-100">
  <div class="container mx-auto">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-10">Our Services</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      
      <!-- Service 1 -->
      <div class="bg-white p-6 rounded-xl shadow-sm text-center">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR3yf-ZigU47H1qW8DLYCWi7C95rdFjjP9jFQ&s" 
             class="mx-auto mb-4 rounded-full w-20 h-20 object-cover" 
             alt="Local Delivery" />
        <h3 class="font-semibold mb-2 text-lg">Local Delivery</h3>
        <p class="text-gray-600 text-sm">Same-day delivery across your city with live tracking.</p>
      </div>

      <!-- Service 2 -->
      <div class="bg-white p-6 rounded-xl shadow-sm text-center">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRNv17MvUKTrbGERlvAjDosGCO1LMSHeLGFMg&s" 
             class="mx-auto mb-4 rounded-full w-20 h-20 object-cover" 
             alt="Fast Shipping" />
        <h3 class="font-semibold mb-2 text-lg">Fast Shipping</h3>
        <p class="text-gray-600 text-sm">Timely and safe deliveries across the country.</p>
      </div>

      <!-- Service 3 -->
      <div class="bg-white p-6 rounded-xl shadow-sm text-center">
        <img src="data:image/png;base64,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" 
             class="mx-auto mb-4 rounded-full w-20 h-20 object-cover" 
             alt="Secure Packaging" />
        <h3 class="font-semibold mb-2 text-lg">Secure Packaging</h3>
        <p class="text-gray-600 text-sm">Protective packaging to keep your items safe during transit.</p>
      </div>

    </div>
  </div>
</section>



  <!-- Why Choose Us -->
  <section class="bg-white py-16">
  <div class="container mx-auto px-6 flex flex-col md:flex-row items-center gap-8">
    <!-- Image Section -->
    <div 
      class="w-full md:w-3/7 h-64 md:h-80 rounded-xl bg-cover bg-center"
      style="background-image: url('assets/why_us.png');"
    ></div>

    <!-- Text Section -->
    <div class="w-full md:w-4/7 space-y-4">
      <h2 class="text-2xl font-semibold text-gray-900">Why Choose Our Network</h2>
      <ul class="list-disc list-inside text-gray-600 space-y-2">
        <li>Live location-based tracking.</li>
        <li>Integrated logistics partner system.</li>
        <li>Secure delivery with signature proof.</li>
        <li>24/7 customer assistance.</li>
      </ul>
    </div>
  </div>
</section>

  <!-- Testimonials -->
  <section class="bg-accent py-16">
    <div class="container mx-auto px-6">
      <h2 class="text-2xl font-semibold text-center text-gray-900 mb-10">Customer Reviews</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-xl shadow text-gray-700">
          <p class="italic">“Delibox-style delivery changed the way I send packages. Great network and amazing support!”</p>
          <p class="mt-4 font-semibold">— Sarah M.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-gray-700">
          <p class="italic">“I loved the real-time tracking and fast delivery. Highly recommend for businesses.”</p>
          <p class="mt-4 font-semibold">— Michael L.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-white border-t py-10">
    <div class="container mx-auto px-6 text-center text-gray-600 text-sm">
      <p>© 2025 Parcel Network Inc. All rights reserved.</p>
      <div class="mt-4 space-x-4">
        <a href="#" class="hover:text-gray-800">Privacy Policy</a>
        <a href="#" class="hover:text-gray-800">Terms of Service</a>
        <a href="#" class="hover:text-gray-800">Support</a>
      </div>
    </div>
  </footer>
</body>
</html>
