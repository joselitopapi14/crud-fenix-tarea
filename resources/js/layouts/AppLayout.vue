<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-orange-300 via-orange-400 to-red-500">
    <Toaster />
    <main>
      <slot />
    </main>
  </div>
</template>
