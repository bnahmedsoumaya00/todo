import api from './api'

export const todoService = {
  async getTodos() {
    const response = await api.get('/todos')
    return response.data
  },
  async createTodo(todoData) {
    const response = await api.post('/todos', todoData)
    return response.data
  },
  async updateTodo(id, todoData) {
    const response = await api.put(`/todos/${id}`, todoData)
    return response.data
  },
  async deleteTodo(id) {
    await api.delete(`/todos/${id}`)
  }
}