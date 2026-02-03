<template>
  <v-container>
    <v-card>
      <v-card-title>
        <v-icon left>mdi-format-list-checks</v-icon>
        My Todos
        <v-spacer></v-spacer>
        <v-btn 
          color="primary"
          @click="dialog = true"
        >
          Add Todo
        </v-btn>
      </v-card-title>

      <v-card-text>
        <v-alert v-if="todoStore.todos.length === 0" type="info" variant="tonal">
          No todos yet. Click "Add Todo" to create one!
        </v-alert>
        <v-list v-else>
          <v-list-item
            v-for="todo in todoStore.todos"
            :key="todo.id"
            :class="{ 'bg-grey-lighten-4': todo.completed }"
          >
            <template v-slot:prepend>
              <v-checkbox-btn
                :model-value="todo.completed"
                @click="toggleComplete(todo)"
              ></v-checkbox-btn>
            </template>

            <v-list-item-title :class="{ 'text-decoration-line-through': todo.completed }">
              {{ todo.title }}
            </v-list-item-title>

            <v-list-item-subtitle v-if="todo.description">
              {{ todo.description }}
            </v-list-item-subtitle>

            <template v-slot:append>
              <v-btn
                icon="mdi-pencil"
                variant="text"
                @click="editTodo(todo)"
              ></v-btn>
              <v-btn 
                icon="mdi-delete" 
                variant="text" 
                color="error" 
                @click="deleteTodo(todo.id)"
              ></v-btn>
            </template>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>

    <v-dialog v-model="dialog" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">{{ editingTodo ? 'Edit Todo' : 'Add Todo' }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-form ref="formRef">
              <v-text-field
                v-model="todoForm.title"
                label="Title"
                required
                :rules="[v => !!v || 'Title is required']"
              ></v-text-field>
              <v-textarea
                v-model="todoForm.description"
                label="Description"
                clearable
              ></v-textarea>
              <v-checkbox v-model="todoForm.completed" label="Completed"></v-checkbox>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue-darken-1" variant="text" @click="dialog = false">Cancel</v-btn>
          <v-btn color="blue-darken-1" variant="text" @click="saveTodo">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useTodoStore } from '@/stores'
import { todoService } from '@/services/todoService'

const todoStore = useTodoStore()

const dialog = ref(false)
const editingTodo = ref(null)
const todoForm = ref({ title: '', description: '', completed: false })
const formRef = ref()

const loadTodos = async () => {
  try {
    console.log('Loading todos...')
    const data = await todoService.getTodos()
    console.log('Todos loaded:', data)
    todoStore.setTodos(data)
  } catch (error) {
    console.error('Failed to load todos:', error)
    if (error.response?.status === 401) {
      // Token expired or invalid
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      window.location.href = '/login'
    }
  }
}

const toggleComplete = async (todo) => {
  try {
    const updated = await todoService.updateTodo(todo.id, { completed: !todo.completed })
    todoStore.updateTodo(updated)
  } catch (error) {
    console.error('Failed to update todo:', error)
    alert('Failed to update todo')
  }
}

const editTodo = (todo) => {
  editingTodo.value = todo
  todoForm.value = { ...todo }
  dialog.value = true
}

const deleteTodo = async (id) => {
  if (!confirm('Are you sure you want to delete this todo?')) return
  try {
    await todoService.deleteTodo(id)
    todoStore.deleteTodo(id)
  } catch (error) {
    console.error('Failed to delete todo:', error)
    alert('Failed to delete todo')
  }
}

const saveTodo = async () => {
  try {
    if (editingTodo.value) {
      const updated = await todoService.updateTodo(editingTodo.value.id, todoForm.value)
      todoStore.updateTodo(updated)
    } else {
      const newTodo = await todoService.createTodo(todoForm.value)
      todoStore.addTodo(newTodo)
    }
    dialog.value = false
    resetForm()
    await loadTodos()
  } catch (error) {
    console.error('Failed to save todo:', error)
    alert('Failed to save todo. Please check console for details.')
  }
}

const resetForm = () => {
  editingTodo.value = null
  todoForm.value = { title: '', description: '', completed: false }
}

onMounted(() => {
  loadTodos()
})
</script>