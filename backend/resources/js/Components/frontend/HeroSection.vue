<template>
  <section class="relative h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Slideshow -->
    <div class="absolute inset-0">
      <div 
        v-for="(image, index) in backgroundImages" 
        :key="index"
        :class="['absolute inset-0 bg-cover bg-center transition-opacity duration-1000', currentImageIndex === index ? 'opacity-100' : 'opacity-0']"
        :style="`background-image: url('${image}')`"
      >
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-black/30"></div>
      </div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 text-center px-4 max-w-6xl mx-auto">
      <div class="mb-8">
        <span class="inline-block px-4 py-2 bg-blue-600/20 backdrop-blur-sm border border-blue-400/30 rounded-full text-blue-300 text-sm font-medium mb-6 animate-fade-in-up">
          🚗 Premium Auto Care Services
        </span>
      </div>
      
      <h1 class="text-6xl md:text-8xl font-black mb-8 animate-fade-in-up text-white leading-tight">
        Your Car's
        <span class="bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 bg-clip-text text-transparent">
          Best Friend
        </span>
      </h1>
      
      <p class="text-xl md:text-2xl mb-12 text-gray-200 max-w-3xl mx-auto leading-relaxed animate-fade-in-up animation-delay-300">
        Experience premium garage management with cutting-edge technology. 
        From diagnostics to repairs, we've got your vehicle covered.
      </p>
      
      <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-600">
        <button class="group bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-10 rounded-full text-lg transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-blue-500/25">
          <span class="flex items-center justify-center gap-2">
            Book Service Now
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
          </span>
        </button>
        <button class="bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 text-white font-bold py-4 px-10 rounded-full text-lg transition-all duration-300 transform hover:scale-105">
          Watch Demo
        </button>
      </div>
      
      <!-- Stats -->
      <div class="grid grid-cols-3 gap-8 mt-16 animate-fade-in-up animation-delay-900">
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-white mb-2">500+</div>
          <div class="text-gray-300 text-sm">Happy Customers</div>
        </div>
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-white mb-2">24/7</div>
          <div class="text-gray-300 text-sm">Support Available</div>
        </div>
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-white mb-2">99%</div>
          <div class="text-gray-300 text-sm">Satisfaction Rate</div>
        </div>
      </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
      <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
      </svg>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

// Import local car images
const backgroundImages = [
  '/resources/js/assets/image/upload/car1.jpg',
  '/resources/js/assets/image/upload/car2.png', 
  '/resources/js/assets/image/upload/car3.jpg',
  '/resources/js/assets/image/upload/car5.jpg'
];

const currentImageIndex = ref(0);
let intervalId = null;

onMounted(() => {
  // Auto-slide background images
  intervalId = setInterval(() => {
    currentImageIndex.value = (currentImageIndex.value + 1) % backgroundImages.length;
  }, 4000);
});

onUnmounted(() => {
  if (intervalId) {
    clearInterval(intervalId);
  }
});
</script>

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

.animation-delay-300 {
  animation-delay: 0.3s;
}

.animation-delay-600 {
  animation-delay: 0.6s;
}

.animation-delay-900 {
  animation-delay: 0.9s;
}
</style>
