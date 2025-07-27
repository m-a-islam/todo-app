<script setup>
import { ref } from 'vue'

const newTodoTitle = ref('')
const emit = defineEmits(['add-todo'])
const handleSubmit = () => {
  if (newTodoTitle.value.trim() === ''){
    return
  }

  emit('add-todo', newTodoTitle.value)

  newTodoTitle.value = ''
}
</script>

<template>
  <!-- The @submit.prevent modifier stops the browser's default page reload -->
  <form @submit.prevent="handleSubmit" class="todo-form">
    <input type="text"
    v-model="newTodoTitle"
    placeholder="Add a new to-do item"/>
    <button type="submit">Add Todo</button>
  </form>
</template>

<style scoped>
.todo-form {
  display: flex;
  margin-bottom: 1rem;
}
.todo-form input {
  flex-grow: 1;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.todo-form button {
  padding: 0.5rem 1rem;
  margin-left: 0.5rem;
  background-color: #2c3e50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.todo-form button:hover {
  background-color: #42b983;
}
</style>
