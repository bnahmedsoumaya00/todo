<template>
  <v-app>
    <NavBar v-if="isAuthenticated || $route.path !== '/'" />
    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores'
import NavBar from '@/components/NavBar.vue'

const route = useRoute()
const authStore = useAuthStore()
const isAuthenticated = computed(() => authStore.isAuthenticated)
</script>