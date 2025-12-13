<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-white from-40% via-orange-200 to-red-600">
    <Toaster />
    <main>
      <slot />
    </main>
  </div>
</template>
