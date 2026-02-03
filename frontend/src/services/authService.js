import api from './api'

export const authService = {
  async register(userData) {
    const response = await api.post('/register', userData)
    return response.data
  },
  async login(credentials) {
    const response = await api.post('/login', credentials)
    return response.data
  },
  async logout() {
    await api.post('/logout')
  }
}