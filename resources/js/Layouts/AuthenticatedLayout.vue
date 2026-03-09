<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-white z-30">
      <div class="flex items-center justify-center h-16 border-b border-slate-700">
        <span class="text-xl font-bold">Witwan Brain</span>
      </div>

      <nav class="mt-6 px-4 space-y-1">
        <Link
          :href="route('dashboard')"
          class="flex items-center px-4 py-3 text-gray-300 rounded-lg transition-colors hover:bg-slate-800 hover:text-white"
          :class="{ 'bg-blue-600 text-white': route().current('dashboard') }"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          Dashboard
        </Link>

        <Link
          :href="route('licencias.index')"
          class="flex items-center px-4 py-3 text-gray-300 rounded-lg transition-colors hover:bg-slate-800 hover:text-white"
          :class="{ 'bg-blue-600 text-white': route().current('licencias.*') }"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          Licencias
        </Link>

        <Link
          :href="route('secciones.index')"
          class="flex items-center px-4 py-3 text-gray-300 rounded-lg transition-colors hover:bg-slate-800 hover:text-white"
          :class="{ 'bg-blue-600 text-white': route().current('secciones.*') }"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
          </svg>
          Secciones
        </Link>
      </nav>
    </aside>

    <!-- Main content -->
    <div class="ml-64">
      <!-- Header -->
      <header class="bg-white shadow-sm border-b h-16 flex items-center justify-between px-8 sticky top-0 z-20">
        <h1 class="text-lg font-semibold text-gray-900">
          <slot name="header" />
        </h1>

        <div class="flex items-center gap-4">
          <span class="text-sm text-gray-600">{{ $page.props.auth.user?.name }}</span>
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="text-sm text-gray-500 hover:text-gray-700"
          >
            Salir
          </Link>
        </div>
      </header>

      <!-- Flash messages -->
      <div v-if="$page.props.flash?.success" class="mx-8 mt-4">
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
          {{ $page.props.flash.success }}
        </div>
      </div>
      <div v-if="$page.props.flash?.error" class="mx-8 mt-4">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
          {{ $page.props.flash.error }}
        </div>
      </div>

      <!-- Page content -->
      <main class="p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
