<script setup>
// Features data with enhanced descriptions and emojis
const features = [
  {
    title: 'Smart Dashboard',
    description: 'AI-powered analytics dashboard with real-time insights, performance metrics, and predictive maintenance alerts.',
    icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    emoji: '📊',
    gradient: 'from-blue-500 to-cyan-500'
  },
  {
    title: 'Customer Portal',
    description: 'Complete customer relationship management with service history, preferences, and automated communications.',
    icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    emoji: '👥',
    gradient: 'from-purple-500 to-pink-500'
  },
  {
    title: 'Digital Records',
    description: 'Blockchain-secured vehicle histories with detailed maintenance logs, warranty tracking, and compliance reports.',
    icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    emoji: '📋',
    gradient: 'from-green-500 to-teal-500'
  },
  {
    title: 'Smart Inventory',
    description: 'IoT-enabled inventory management with automatic reordering, supplier integration, and cost optimization.',
    icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
    emoji: '📦',
    gradient: 'from-orange-500 to-red-500'
  },
  {
    title: 'AI Scheduling',
    description: 'Machine learning-powered appointment system with dynamic pricing, resource optimization, and customer preferences.',
    icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    emoji: '🗓️',
    gradient: 'from-indigo-500 to-purple-500'
  },
  {
    title: 'Mobile Diagnostics',
    description: 'Advanced OBD-II integration with real-time vehicle diagnostics, error code analysis, and repair recommendations.',
    icon: 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
    emoji: '🔧',
    gradient: 'from-yellow-500 to-orange-500'
  }
];
</script>

<template>
  <section class="py-24 px-4 bg-gradient-to-br from-gray-50 via-white to-blue-50 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
      <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(59,130,246,0.3) 1px, transparent 0); background-size: 50px 50px;"></div>
    </div>
    
    <div class="max-w-7xl mx-auto relative z-10">
      <!-- Header -->
      <div class="text-center mb-20">
        <span class="inline-block px-4 py-2 bg-blue-100 text-blue-600 rounded-full text-sm font-medium mb-4">
          ⚡ Next-Gen Technology
        </span>
        <h2 class="text-5xl md:text-6xl font-black mb-6">
          <span class="bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent">
            Revolutionary Features
          </span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Experience the future of garage management with cutting-edge AI, IoT integration, and seamless automation.
        </p>
      </div>
      
      <!-- Features Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="(feature, index) in features" 
          :key="index" 
          class="group relative bg-white/80 backdrop-blur-sm p-8 rounded-2xl hover:bg-white transition-all duration-500 transform hover:-translate-y-3 animate-fade-in-up shadow-lg hover:shadow-2xl border border-gray-100 hover:border-gray-200 overflow-hidden" 
          :style="{ animationDelay: `${index * 150}ms` }"
        >
          <!-- Gradient Background -->
          <div :class="['absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-500 bg-gradient-to-br', feature.gradient]"></div>
          
          <!-- Content -->
          <div class="relative z-10">
            <!-- Icon Container -->
            <div class="flex items-center mb-6">
              <div :class="['w-16 h-16 rounded-2xl flex items-center justify-center mr-4 bg-gradient-to-br', feature.gradient, 'shadow-lg']">
                <span class="text-2xl">{{ feature.emoji }}</span>
              </div>
              <div :class="['w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br', feature.gradient, 'opacity-20']">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon"></path>
                </svg>
              </div>
            </div>
            
            <h3 class="text-2xl font-bold mb-4 text-gray-900 group-hover:text-gray-800 transition-colors">
              {{ feature.title }}
            </h3>
            <p class="text-gray-600 leading-relaxed group-hover:text-gray-700 transition-colors">
              {{ feature.description }}
            </p>
            
            <!-- Hover Arrow -->
            <div class="mt-6 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-0 group-hover:translate-x-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
      
      <!-- CTA Section -->
      <div class="text-center mt-20">
        <button class="group bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-10 rounded-full text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
          <span class="flex items-center justify-center gap-2">
            Explore All Features
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
          </span>
        </button>
      </div>
    </div>
  </section>
</template>

<style scoped>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out forwards;
  opacity: 0;
}
</style>
