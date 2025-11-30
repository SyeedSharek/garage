<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <Head title="Welcome" />
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="flex min-h-screen flex-col">
            <!-- Header -->
            <header class="border-b border-gray-200 bg-white/80 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/80">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center">
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                                Garage Management
                            </h1>
                        </div>
                        <nav v-if="canLogin" class="flex items-center space-x-4">
                            <Link
                                v-if="$page.props.auth?.user"
                                :href="route('dashboard')"
                                class="rounded-md px-4 py-2 text-sm font-medium text-gray-700 transition hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                            >
                                Dashboard
                            </Link>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="rounded-md px-4 py-2 text-sm font-medium text-gray-700 transition hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                                >
                                    Log in
                                </Link>
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                                >
                                    Register
                                </Link>
                            </template>
                        </nav>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex flex-1 items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-4xl text-center">
                    <h2 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl md:text-6xl dark:text-white">
                        Professional Garage
                        <span class="text-blue-600">Management System</span>
                    </h2>
                    <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-gray-600 dark:text-gray-300">
                        Streamline your garage operations with our comprehensive management solution.
                        Manage orders, customers, services, and invoices all in one place.
                    </p>
                    <div v-if="canLogin && !$page.props.auth?.user" class="mt-10 flex items-center justify-center gap-x-6">
                        <Link
                            :href="route('login')"
                            class="rounded-md bg-blue-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                        >
                            Get started
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100"
                        >
                            Learn more <span aria-hidden="true">→</span>
                        </Link>
                    </div>
                    <div v-else-if="$page.props.auth?.user" class="mt-10">
                        <Link
                            :href="route('dashboard')"
                            class="rounded-md bg-blue-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                        >
                            Go to Dashboard
                        </Link>
                    </div>
                </div>
            </main>

            <!-- Features Section -->
            <section class="border-t border-gray-200 bg-white py-12 dark:border-gray-700 dark:bg-gray-900">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                        <div class="text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                <svg
                                    class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-3.87a3.37 3.37 0 00-.93-2.608L3.5 5.128a3.445 3.445 0 112.06-1.28l4.5 4.128a3.445 3.445 0 003.327 3.128H18"
                                    />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Order Management</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                Create and track orders efficiently with detailed service management.
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                <svg
                                    class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.645-5.963-1.75A1 1 0 017 18.72M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM4.19 15a2.25 2.25 0 103.75 2.25A2.25 2.25 0 004.19 15z"
                                    />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Customer Management</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                Maintain comprehensive customer records and history.
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                <svg
                                    class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375h-8.25A3.375 3.375 0 002.25 4.125v15.75m0 0h12m0 0h3.75m-12 0v-5.25m0 5.25h3.75V19.5"
                                    />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Invoice Generation</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                Generate professional invoices and track payments seamlessly.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

