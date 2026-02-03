import { defineStore } from 'pinia'
import { defineAbilitiesFor } from '@/permissions/defineAbilities'
import { ability } from '@/ability'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user
  },
  actions: {
    setAuth(user, token) {
      this.user = user
      this.token = token
      localStorage.setItem('token', token)
      localStorage.setItem('user', JSON.stringify(user))


      const newAbility = defineAbilitiesFor(user)
      ability.update(newAbility.rules)
    },
    logout() {
      this.user = null
      this.token = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')

      ability.update([])
      
    }
  }
})

export const useTodoStore = defineStore('todos', {
  state: () => ({
    todos: [],
    loading: false
  }),
  actions: {
    setTodos(todos) {
      this.todos = todos
    },
    addTodo(todo) {
      this.todos.unshift(todo)
    },
    updateTodo(updatedTodo) {
      const index = this.todos.findIndex(t => t.id === updatedTodo.id)
      if (index !== -1) {
        this.todos[index] = updatedTodo
      }
    },
    deleteTodo(id) {
      this.todos = this.todos.filter(t => t.id !== id)
    }
  }
})