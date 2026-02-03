<template>
    <v-container>
        <v-card class="mb-6">
            <v-card-title class="text-h4 font-weight-bold bg-blue-600 text-white">
                <v-icon size="large" class="mr-3">mdi-shield-account</v-icon>
                Admin Dashboard
            </v-card-title>
            <v-card-subtitle class="text-medium-emphasis">
                System overview and user Management
            </v-card-subtitle>
        </v-card>
        <v-progress-linear v-if="loading"  indeterminate  color="blue" class="mb-4"></v-progress-linear>

        <v-alert v-if="error" type="error" variant="tonal" class="mb-4" closable @click:close="error = null">{{error}}</v-alert>
        <v-row v-if="!loading && !error">
            <v-col cols="12" md="3">
                <v-card class="bg-blue-lighten-5" elevation="4">
                    <v-card-text class="text-center py-6">
                        <v-icon size="x-large" color="blue" class="mb-3">mdi-account-group</v-icon>
                        <div class="text-h6 font-weight-medium text-blue-darken-2 mb-1">Total Users</div>
                        <div class="text-h3 font-weight-bold text-blue">
                            {{ stats.total_users || 0 }}
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card class="bg-green-lighten-5" elevation="4">
                    <v-card-text class="text-center py-6">
                        <v-icon size="x-large" color="green" class="mb-3">mdi-format-list-checks</v-icon>
                        <div class="text-h6 font-weight-medium text-green-darken-2 mb-1">Total Todos</div>
                        <div class="text-h3 font-weight-bold text-green">
                            {{ stats.total_todos || 0 }}
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card class="bg-orange-lighten-5" elevation="4">
                    <v-card-text class="text-center py-6">
                        <v-icon size="x-large" color="orange" class="mb-3">mdi-clock-alert</v-icon>
                        <div class="text-h6 font-weight-medium text-orange-darken-2 mb-1">Pending</div>
                        <div class="text-h3 font-weight-bold text-orange">
                            {{ stats.pending_todos || 0 }}
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row class="mt-6">
            <v-col cols="12" md="8">
                <v-card elevation="2">
                    <v-card-title class="text-h5 font-weight-bold">
                        <v-icon class="mr-2">mdi-account-multiple</v-icon>
                        All Users
                    </v-card-title>
                    <v-card-text>
                        <v-data-table :headers="userHeaders" :items="stats.users || []" :items-per-page="10"
                            class="elevation-1">
                            <template #[`item.role`]="{ item }">
                                <v-chip :color="item.role === 'admin' ? 'red' : 'green'" size="small" variant="flat">
                                    {{ item.role }}
                                </v-chip>
                            </template>
                            <template #[`item.todos_count`]="{ item }">
                                <v-chip color="primary" size="small" variant="tonal">
                                    <v-icon size="small" class="mr-1">mdi-format-list-checks</v-icon>
                                    {{ item.todos_count }} todos
                                </v-chip>
                            </template>
                            <template #[`item.created_at`]="{ item }">
                                {{ formatDate(item.created_at) }}
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col><v-col cols="12" md="4">
                <v-card elevation="2">
                    <v-card-title class="text-h5 font-weight-bold">
                        <v-icon class="mr-2">mdi-clock-fast</v-icon>
                        Recent Todos
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <v-list-item v-for="todo in stats.recent_todos || []" :key="todo.id" class="mb-2">
                                <template v-slot:prepend>
                                    <v-icon :color="todo.completed ? 'green' : 'gray'"
                                        :icon="todo.completed ? 'mdi-check-circle' : 'mdi-circle-outline'"></v-icon>
                                </template>
                                <v-list-item-title class="text-wrap">
                                    {{ todo.title }}
                                </v-list-item-title>
                                <v-list-item-subtitle>
                                    by {{ todo.user.name }}
                                </v-list-item-subtitle>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAbility } from '@casl/vue'


const stats = ref({
  total_users: 0,
  total_todos: 0,
  completed_todos: 0,
  pending_todos: 0,
  users: [],
  recent_todos: []
})

const router = useRouter()
const error = ref(null)
const loading = ref(false)

const userHeaders = [
  { title: 'Name', key: 'name' },
  { title: 'Email', key: 'email' },
  { title: 'Role', key: 'role' },
  { title: 'Todos', key: 'todos_count' },
  { title: 'Joined', key: 'created_at' }
]

const formatDate = (date) => {
  if (!date) return ''
  const d = new Date(date)
  return d.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  })
}

const loadDashboard = async () => {
    loading.value=true
    error.value= null
  try {
    const baseUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'
    const response = await axios.get(
      `${baseUrl}/admin/dashboard`,
      {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      }
    )
    
    stats.value = response.data
    
  }catch (err) {
    console.error('Dashboard load error:', err)
    error.value = err.response?.data?.message || 'Failed to load dashboard'
  } finally {
    loading.value = false 
  }
}


onMounted(() => {
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  if (user.role !== 'admin') {
    alert('Access denied. Admin privileges required.')
    router.push('/')
    return
  }
  loadDashboard()
})
</script>
<style scoped>
</style>