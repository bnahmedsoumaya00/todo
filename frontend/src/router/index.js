import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import AdminDashboard from '@/views/AdminDashboard.vue'

const routes = [
  { path: '/', name: 'Home', component: HomeView, meta: { requiresAuth: true } },
  { path: '/login', name: 'Login', component: LoginView },
  { path: '/register', name: 'Register', component: RegisterView },
  { path:'/admin', name:'Admin', component:AdminDashboard, meta: {requiresAuth: true, requiresAdmin: true}}
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  if (to.meta.requiresAuth && !token) {
    next('/login')
    return
  } 
  if ((to.path === '/login' || to.path === '/register') && token) {
    next('/')
    return
  }
  if (to.meta.requiresAdmin){
    if(user.role ==='admin'){
      next()
    }else{
      alert('Access denied')
      next('/')
      return
    }
  }
  next()
})

export default router