import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from '@/App.vue'
import vuetify from '@/plugins/vuetify'
import router from '@/router'
import { ability } from '@/ability'
import { abilitiesPlugin } from '@casl/vue'
import '@/assets/main.css'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(vuetify)
app.use(router)
app.use(abilitiesPlugin, ability, {
  useGlobalProperties: true
})
app.mount('#app')