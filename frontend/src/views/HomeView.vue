<script setup>
import { ref, onMounted } from 'vue'
import TodoForm from "@/components/TodoForm.vue";

// Create a reactive variable to hold our list of todos.
// 'ref' makes it reactive, so Vue updates the page when it changes.
const todos = ref([])
const isLoading = ref(true)
const editingTodoId = ref(null)
const editedTitle = ref('')

const fetchTodos = async () => {
  try{
    const response = await fetch('/api/todos')
    if (!response.ok) {
      throw new Error('Network response was not ok')
    }
    // Update our reactive variable with the data from the API
    todos.value = await response.json()
  } catch (error){
    console.error('Failed to fetch todos:', error)
  } finally {
    isLoading.value = false
  }
}

// 2. Define a function to add a new todo item
const handleNewTodo = async (newTitle) => {
  try {
    const response = await fetch('/api/todos', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ title: newTitle }),
    })

    if (!response.ok) throw new Error('Failed to add todo')

    // The API returns the new todo object, so we can add it
    // directly to our list for an instant UI update!
    const newTodo = await response.json()
    todos.value.push(newTodo)

  } catch (error) {
    console.error('Error adding todo:', error)
  }
}

const startEditing = (todo) => {
  editingTodoId.value = todo.id
  editedTitle.value = todo.title
}
const cancelEditing = () => {
  editingTodoId.value = null
  editedTitle.value = ''
}

const saveEdit = async (todo) => {
  if (editedTitle.value.trim() === '') return
  try{
    const response = await fetch(`/api/todos/${todo.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ title: editedTitle.value }),
    })
    if (!response.ok) throw new Error('Failed to update todo')
    const updatedTodo = await response.json()

    const index = todos.value.findIndex(t => t.id === todo.id)
    if (index !== -1) {
      todos.value[index] = updatedTodo
    }
    cancelEditing()
  } catch (error) {
    console.error('Error updating todo:', error)
  }
}

// 'onMounted' is a lifecycle hook that runs once the component
// is added to the page. It's the perfect place to fetch data.
onMounted(() => {
  fetchTodos()
})
</script>

<template>
  <main>
    <h1>My To-Do List</h1>
    <TodoForm @add-todo="handleNewTodo" />

    <div v-if="isLoading">Loading...</div>
    <ul v-else-if="todos.length > 0">
      <!-- Loop through each 'todo' in our 'todos' array -->
      <li v-for="todo in todos" :key="todo.id">
        <!-- EDITING VIEW (v-if) -->
        <div v-if="editingTodoId === todo.id" class="editing-item">
          <input type="text" v-model="editedTitle" @keyup.enter="saveEdit(todo)" />
          <button @click="saveEdit(todo)" class="btn-save">Save</button>
          <button @click="cancelEditing" class="btn-cancel">Cancel</button>
        </div>

        <!-- NORMAL DISPLAY VIEW (v-else) -->
        <div v-else class="display-item">
          <span>{{ todo.title }} - {{ todo.isCompleted ? 'Done' : 'Pending' }}</span>
          <button @click="startEditing(todo)" class="btn-edit">Edit</button>
        </div>
      </li>
    </ul>
    <div v-else>
      No to-do items yet. Go add some!
    </div>
  </main>
</template>

<style scoped>
main {
  padding: 2rem;
}
ul {
  list-style: none;
  padding: 0;
}
li {
  background-color: #f4f4f4;
  padding: 1rem;
  margin-bottom: 0.5rem;
  border-radius: 5px;
}
.display-item, .editing-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.editing-item input {
  flex-grow: 1;
  margin-right: 1rem;
}
.btn-edit, .btn-save, .btn-cancel {
  border: none;
  padding: 0.5rem;
  border-radius: 4px;
  cursor: pointer;
}
.btn-edit { background-color: #f0ad4e; color: white; }
.btn-save { background-color: #5cb85c; color: white; }
.btn-cancel { background-color: #777; color: white; }
</style>
