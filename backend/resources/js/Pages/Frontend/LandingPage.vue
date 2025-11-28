<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import SlidingBanner from '@/Components/frontend/SlidingBanner.vue';
import HeroSection from '@/Components/frontend/HeroSection.vue';
import FeaturesSection from '@/Components/frontend/FeaturesSection.vue';
import AboutSection from '@/Components/frontend/AboutSection.vue';
import LocationSection from '@/Components/frontend/LocationSection.vue';
import TestimonialsSection from '@/Components/frontend/TestimonialsSection.vue';
import CallToActionSection from '@/Components/frontend/CallToActionSection.vue';
import Footer from '@/Components/frontend/Footer.vue';

const isLoading = ref(true);
const showScrollTop = ref(false);

onMounted(() => {
  setTimeout(() => {
    isLoading.value = false;
  }, 1500);
  
  const handleScroll = () => {
    showScrollTop.value = window.scrollY > 300;
  };
  
  window.addEventListener('scroll', handleScroll);
  
  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
  });
});

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

<template>
  <Head title="Professional Garage Management System - Premium Auto Care" />

  <div class="min-h-screen bg-white text-gray-900 overflow-x-hidden">
    <div v-if="isLoading" class="fixed inset-0 bg-gradient-to-br from-blue-900 to-purple-900 flex items-center justify-center z-50">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mb-4 mx-auto"></div>
        <p class="text-white text-lg font-medium">Loading Premium Experience...</p>
      </div>
    </div>
    
    <div v-else class="animate-fade-in">
      <SlidingBanner />
      <HeroSection />
      <FeaturesSection />
      <AboutSection />
      <LocationSection />
      <TestimonialsSection />
      <CallToActionSection />
      <Footer />
    </div>
    
    <button 
      v-show="showScrollTop"
      @click="scrollToTop"
      class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 z-40 flex items-center justify-center"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
      </svg>
    </button>
  </div>
</template>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.animate-fade-in {
  animation: fadeIn 0.5s ease-out forwards;
}

::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
  border-radius: 4px;
}
</style>
