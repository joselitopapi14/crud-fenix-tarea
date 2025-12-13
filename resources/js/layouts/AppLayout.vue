<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
// Layout simple sin autenticación
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <Toaster />
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-bold text-gray-900">CRUD Fenix</h1>
          </div>
          <div class="flex items-center">
            <a href="/productos" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Productos
            </a>
          </div>
        </div>
      </div>
    </nav>

    <main>
      <slot />
    </main>
  </div>
</template>
