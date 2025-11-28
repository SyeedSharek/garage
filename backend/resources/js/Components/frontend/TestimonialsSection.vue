<script setup>
// Testimonials data
const testimonials = [
  {
    name: 'John Doe',
    role: 'Car Owner',
    content: 'Excellent service! My car has never run better. The technicians were professional and explained everything clearly. Highly recommend this garage for anyone looking for quality auto service.',
    rating: 5,
    date: '2 weeks ago',
    image: 'https://picsum.photos/100/100?random=1'
  },
  {
    name: 'Jane Smith',
    role: 'Business Owner',
    content: 'Professional staff and top-notch maintenance. They keep my entire fleet in perfect condition. Their scheduling system makes it easy to book services, and I always get updates on the work progress.',
    rating: 5,
    date: '1 month ago',
    image: 'https://picsum.photos/100/100?random=2'
  },
  {
    name: 'Mike Johnson',
    role: 'Mechanic',
    content: 'As a fellow mechanic, I can appreciate the quality of work here. Great tools, organized workspace, and this management system makes everything so much easier. The inventory tracking is a game-changer.',
    rating: 5,
    date: '3 weeks ago',
    image: 'https://picsum.photos/100/100?random=3'
  },
  {
    name: 'Sarah Wilson',
    role: 'Customer',
    content: 'Fast, reliable, and transparent service. I can track everything online from the initial booking to completion. The app notifications keep me updated. Fantastic experience overall!',
    rating: 5,
    date: '1 week ago',
    image: 'https://picsum.photos/100/100?random=4'
  },
  {
    name: 'David Brown',
    role: 'Fleet Manager',
    content: 'Managing 50+ vehicles has never been easier. Their system helps me track maintenance schedules, costs, and vehicle history. The reporting features are excellent for our business needs.',
    rating: 5,
    date: '2 months ago',
    image: 'https://picsum.photos/100/100?random=5'
  },
  {
    name: 'Lisa Garcia',
    role: 'Regular Customer',
    content: 'I\'ve been bringing my car here for 3 years now. Every service is done professionally and on time. The customer portal lets me see my service history and upcoming appointments. Very satisfied!',
    rating: 5,
    date: '4 days ago',
    image: 'https://picsum.photos/100/100?random=6'
  },
  {
    name: 'Robert Chen',
    role: 'Small Business Owner',
    content: 'Their brake service saved me from a potential accident. The diagnostic was thorough and they explained exactly what needed to be done. Fair pricing and excellent workmanship.',
    rating: 5,
    date: '1 month ago',
    image: 'https://picsum.photos/100/100?random=7'
  },
  {
    name: 'Maria Rodriguez',
    role: 'Working Professional',
    content: 'Love the convenience of their online booking system. I can schedule services during lunch breaks and they always have my car ready when promised. The loaner car service is a great bonus!',
    rating: 5,
    date: '3 weeks ago',
    image: 'https://picsum.photos/100/100?random=8'
  }
];
</script>

<template>
  <section class="py-20 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-4xl md:text-5xl font-bold text-center mb-16 text-gray-900">What Our Customers Say</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div v-for="(testimonial, index) in testimonials.slice(0, 8)" :key="index" class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 animate-fade-in-up border border-gray-200" :style="{ animationDelay: `${index * 100}ms` }">
          <div class="flex items-center mb-4">
            <img :src="testimonial.image" :alt="testimonial.name" class="w-12 h-12 rounded-full mr-4 object-cover border-2 border-gray-200">
            <div class="flex">
              <div v-for="star in testimonial.rating" :key="star" class="text-yellow-400">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
              </div>
            </div>
          </div>
          <p class="text-gray-700 mb-4 italic text-sm leading-relaxed">"{{ testimonial.content }}"</p>
          <div class="flex justify-between items-end">
            <div>
              <p class="font-semibold text-gray-900">{{ testimonial.name }}</p>
              <p class="text-blue-600 text-sm">{{ testimonial.role }}</p>
            </div>
            <p class="text-gray-500 text-xs">{{ testimonial.date }}</p>
          </div>
        </div>
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
