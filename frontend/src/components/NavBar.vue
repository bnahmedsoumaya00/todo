<template>
  <v-app-bar color="primary" dark>
    <v-app-bar-title>
      <v-icon left>mdi-format-list-checks</v-icon>
      Todo App
    </v-app-bar-title>
    <v-spacer></v-spacer>
    <v-btn v-if="isAuthenticated && currentUser?.role === 'admin'" to="/admin" variant="text" class="mx-2">
      <v-icon left>mdi-shield-account</v-icon>
      Admin
    </v-btn>

    <v-btn v-if="!isAuthenticated" to="/login" variant="text">Login</v-btn>
    <v-btn v-if="!isAuthenticated" to="/register" variant="text">Register</v-btn>
    <v-btn v-if="isAuthenticated" @click="logout" variant="text">Logout</v-btn>
  </v-app-bar>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores'

const router = useRouter()
const authStore = useAuthStore()

const isAuthenticated = computed(() => authStore.isAuthenticated)
const currentUser = computed(() => authStore.currentUser)

const logout = async () => {
  try {
    if (authStore.token) {
      await fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${authStore.token}`,
          'Content-Type': 'application/json'
        }
      })
    }
  } catch (error) {
    console.error('Logout error:', error)
  } finally {
    authStore.logout()
    router.push('/login')
  }
}
</script>