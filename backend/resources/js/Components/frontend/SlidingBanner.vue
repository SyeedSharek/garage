<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import car1 from '@/assets/image/upload/car1.jpg';

// Sliding images with promotional messages
const slides = [
  {
    image: car1,
    title: "Professional Garage Management System",
    subtitle: "Streamline Your Operations"
  },
  {
    image: 'https://picsum.photos/1200/400?random=11',
    title: "5-Star Rated Service",
    subtitle: "Join 1000+ Satisfied Customers"
  },
  {
    image: 'https://picsum.photos/1200/400?random=12',
    title: "Fast & Reliable Service",
    subtitle: "Same Day Service Available"
  },
  {
    image: 'https://picsum.photos/1200/400?random=13',
    title: "Transparent Pricing",
    subtitle: "No Hidden Fees"
  },
  {
    image: 'https://picsum.photos/1200/400?random=14',
    title: "Online Booking",
    subtitle: "Schedule 24/7"
  },
  {
    image: 'https://picsum.photos/1200/400?random=15',
    title: "Certified Technicians",
    subtitle: "Expert Auto Care"
  }
];

const currentIndex = ref(0);
let interval = null;

const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % messages.length;
};

const prevSlide = () => {
  currentIndex.value = currentIndex.value === 0 ? messages.length - 1 : currentIndex.value - 1;
};

onMounted(() => {
  interval = setInterval(nextSlide, 4000); // Auto slide every 4 seconds
});

onUnmounted(() => {
  if (interval) {
    clearInterval(interval);
  }
});
</script>

<template>
  <div class="relative h-96 overflow-hidden">
    <!-- Sliding images -->
    <div class="relative h-full">
      <div
        class="flex transition-transform duration-700 ease-in-out h-full"
        :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
      >
        <div
          v-for="(slide, index) in slides"
          :key="index"
          class="flex-shrink-0 w-full h-full relative"
        >
          <img
            :src="slide.image"
            :alt="slide.title"
            class="w-full h-full object-cover"
          >
          <!-- Overlay -->
          <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
            <div class="text-center text-white">
              <h3 class="text-3xl md:text-4xl font-bold mb-2">{{ slide.title }}</h3>
              <p class="text-xl md:text-2xl">{{ slide.subtitle }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation arrows -->
    <button
      @click="prevSlide"
      class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition-all duration-200 text-white"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
    </button>

    <button
      @click="nextSlide"
      class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition-all duration-200 text-white"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </button>

    <!-- Indicators -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
      <button
        v-for="(slide, index) in slides"
        :key="index"
        @click="currentIndex = index"
        class="w-3 h-3 rounded-full transition-all duration-200"
        :class="currentIndex === index ? 'bg-white' : 'bg-white bg-opacity-50'"
      ></button>
    </div>
  </div>
</template>
