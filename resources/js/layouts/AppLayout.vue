<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-orange-500 via-red-500 to-orange-600">
    <Toaster />
    <main>
      <slot />
    </main>
  </div>
</template>
