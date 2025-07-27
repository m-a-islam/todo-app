<script setup>
import { ref, onMounted } from 'vue'

// Create a reactive variable to hold our list of todos.
// 'ref' makes it reactive, so Vue updates the page when it changes.
const todos = ref([])
const isLoading = ref(true)

// 'onMounted' is a lifecycle hook that runs once the component
// is added to the page. It's the perfect place to fetch data.
onMounted(async () => {
  try {
    // Use the browser's fetch API to call our backend.
    // The Vite proxy will forward this to http://webserver:80/todos
    const response = await fetch('/api/todos')
    if (!response.ok) {
      throw new Error('Network response was not ok')
    }
    // Update our reactive variable with the data from the API
    todos.value = await response.json()
  } catch (error) {
    console.error('Failed to fetch todos:', error)
  } finally {
    isLoading.value = false
  }
})
</script>

<template>
  <main>
    <h1>My To-Do List</h1>
    <div v-if="isLoading">Loading...</div>
    <ul v-else-if="todos.length > 0">
      <!-- Loop through each 'todo' in our 'todos' array -->
      <li v-for="todo in todos" :key="todo.id">
        {{ todo.title }} - {{ todo.isCompleted ? 'Done' : 'Pending' }}
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
</style>
