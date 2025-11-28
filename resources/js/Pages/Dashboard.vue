<template>
  <MainLayout>
    <template #sidebar>
      <AppSidebar />
    </template>
    <template #header>
      <AppHeader title="Dashboard" subtitle="Welcome back! Here's what's happening with your garage." />
    </template>
    <template #default>
      <div class="space-y-6">
        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="metric in metrics"
            :key="metric.id"
            class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden"
          >
            <div class="p-6">
              <!-- Header with Icon and Menu -->
              <div class="flex items-start justify-between mb-6">
                <div class="flex items-center space-x-3">
                  <div class="relative flex-shrink-0">
                    <!-- Larger fainter background circle -->
                    <div class="absolute -top-1 -left-1 h-14 w-14 rounded-full bg-blue-200 opacity-40"></div>
                    <!-- Main icon circle -->
                    <div class="relative h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center z-10">
                      <HugeiconsIcon :icon="iconMap[metric.icon]" :size="20" color="rgb(37 99 235)" />
                    </div>
                  </div>
                  <div>
                    <h3 class="text-sm font-medium text-gray-900 leading-tight">{{ metric.title }}</h3>
                  </div>
                </div>
                <div class="flex items-center space-x-2.5">
                  <div class="flex -space-x-2">
                    <!-- First avatar -->
                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 border-2 border-white shadow-sm overflow-hidden">
                      <div class="w-full h-full bg-blue-500"></div>
                    </div>
                    <!-- Second avatar -->
                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 border-2 border-white relative shadow-sm overflow-hidden z-10">
                      <div class="w-full h-full bg-pink-500"></div>
                      <!-- 25+ badge -->
                      <div class="absolute -bottom-0.5 -right-0.5 h-4 w-4 bg-blue-500 rounded-full flex items-center justify-center border-2 border-white shadow-sm z-20">
                        <span class="text-[7px] text-white font-bold leading-none">25+</span>
                      </div>
                    </div>
                  </div>
                  <button class="p-1 hover:bg-gray-100 rounded transition-colors">
                    <HugeiconsIcon :icon="More03Icon" :size="16" color="rgb(17 24 39)" />
                  </button>
                </div>
              </div>

              <!-- Value, Trend and Chart Section - Side by Side -->
              <div class="flex items-end justify-between gap-4">
                <!-- Left: Value and Trend -->
                <div class="flex-1">
                  <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ metric.value }}</p>
                  </div>
                  <div class="flex items-center space-x-2">
                    <div class="flex items-center text-blue-400">
                      <HugeiconsIcon
                        :icon="metric.trend.direction === 'up' ? ArrowUp01Icon : ArrowDown01Icon"
                        :size="12"
                        color="currentColor"
                      />
                      <span class="text-sm font-medium ml-1 text-gray-600">{{ Math.abs(metric.trend.value).toFixed(1).replace('.', ',') }}%</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ metric.trend.label }}</span>
                  </div>
                </div>

                <!-- Right: Mini Bar Chart with ApexCharts -->
                <div class="h-12 flex-1 max-w-32 flex-shrink-0">
                  <VueApexCharts
                    type="bar"
                    height="48"
                    :options="getChartOptionsForMetric(metric)"
                    :series="getChartSeriesForMetric(metric)"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import MainLayout from '@/Components/layout/MainLayout.vue';
import AppSidebar from '@/Components/layout/AppSidebar.vue';
import AppHeader from '@/Components/layout/AppHeader.vue';
import { HugeiconsIcon } from '@hugeicons/vue';
import {
  DollarIcon,
  ShoppingBagIcon,
  TagIcon,
  ArrowUp01Icon,
  ArrowDown01Icon,
  More03Icon,
} from '@hugeicons/core-free-icons';
import VueApexCharts from 'vue3-apexcharts';
import dashboardData from '@/data/dashboardData.json';

// Icon mapping
const iconMap = {
  DollarIcon,
  ShoppingBagIcon,
  TagIcon,
};

// Get metrics from JSON data
const metrics = ref(dashboardData.metrics);

// Base chart options function
const getChartOptions = (colors, dataLength) => ({
  chart: {
    type: 'bar',
    height: 48,
    toolbar: { show: false },
    sparkline: { enabled: false },
    animations: { enabled: false },
  },
  plotOptions: {
    bar: {
      borderRadius: 4,
      borderRadiusApplication: 'end',
      columnWidth: dataLength > 4 ? '10%' : '18%',
      distributed: true,
      horizontal: false,
      dataLabels: {
        position: 'top',
      },
    },
  },
  dataLabels: { enabled: false },
  grid: {
    show: false,
    padding: { left: 0, right: 0, top: 0, bottom: 0 },
  },
  xaxis: {
    labels: { show: false },
    axisBorder: { show: false },
    axisTicks: { show: false },
    categories: Array(dataLength).fill(''),
  },
  yaxis: {
    show: false,
    min: 0,
    max: 100,
  },
  colors: colors,
  tooltip: { enabled: false },
  legend: { show: false },
  fill: {
    opacity: 1,
  },
  stroke: {
    show: false,
  },
});

// Get chart options for each metric
const getChartOptionsForMetric = (metric) => {
  return getChartOptions(metric.chart.colors, metric.chart.data.length);
};

// Get chart series for each metric
const getChartSeriesForMetric = (metric) => {
  return [
    {
      name: metric.title,
      data: metric.chart.data,
    },
  ];
};
</script>

<style scoped>
/* Pixel perfect adjustments */
</style>
