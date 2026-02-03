<template>
  <v-container class="fill-height">
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card>
          <v-card-title class="text-center">Login</v-card-title>
          <v-card-text>
            <v-form @submit.prevent="login">
              <v-text-field
                v-model="form.email"
                label="Email"
                type="email"
                required
                variant="outlined"
                :error-messages="errors.email"
              ></v-text-field>
              <v-text-field
                v-model="form.password"
                label="Password"
                type="password"
                required
                variant="outlined"
                :error-messages="errors.password"
              ></v-text-field>
              <v-btn type="submit" color="primary" block :loading="loading">
                Login
              </v-btn>
            </v-form>
          </v-card-text>
          <v-card-actions class="justify-center">
            <router-link to="/register">Don't have an account? Register</router-link>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores'
import { authService } from '@/services/authService'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({ email: '', password: '' })
const errors = ref({})
const loading = ref(false)

const login = async () => {
  loading.value = true
  errors.value = {}
  try {
    const data = await authService.login(form.value)
    authStore.setAuth(data.user, data.token)
    router.push('/')
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      alert('Login failed. Please check your credentials.')
    }
  } finally {
    loading.value = false
  }
}
</script>